<?php

namespace App\Http\Controllers;

use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLedger;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\Product\Variant\ProductVariantRate;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\Product_ledger;
use App\Models\Product_rate;
use App\Models\Product_stock;
use App\Models\ProductType;
use App\Models\Purchase;
use App\Models\Purchase_detail;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function show_purchase_page()
    {
        $productVarients = ProductVariant::get();
        $suppliers = Party::where('type', 'Supplier')->get();
        $accounts = Account::get();

        return view('adminPanel/purchase/purchase', compact('productVarients', 'suppliers', 'accounts'));
    }


    public function getsupplierbalance($id)
    {
        $supplierBalance = Party::find($id);

        return response()->json([
            'data' => [
                'supplierBalance' => [
                    'id' => $supplierBalance->id,
                    'balance' => $supplierBalance->balance,
                ]
            ]
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->query('query');
        $cacheKey = 'products_' . md5($query);

        // Cache query results for 60 seconds
        $products = Cache::remember($cacheKey, 60, function () use ($query) {
            return ProductVariant::where('service_item', 0) // Add the condition
                ->where(function ($q) use ($query) {
                    $q->where('code', 'LIKE', "$query%")
                        ->orWhere(
                            'product_variant_name',
                            'LIKE',
                            "$query%"
                        );
                })
                ->select('id', 'code', 'product_variant_name') // Only select required columns
                ->limit(20) // Return limited results
                ->get();
        });

        return response()->json($products);
    }

    public function fetchProductDetails($id)
    {

        $product = ProductVariant::with('productVariantLocation')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $stock = ProductVariantStock::where('product_variant_id', $id)->first();
        $cost_price = ProductVariantRate::where('product_variant_id', $id)->first();

        if ($product && $cost_price) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->product_variant_name,
                    'color' => $product->color->name ?? 'Default Color', // Use a default value if color is null
                    'location' => $product->productVariantLocation->first()->ProductLocation->name ?? null, // Ensure this exists in your `rates` table
                    'stock' => $stock->stock ?? 'null',
                    'cost_price' => $cost_price->cost_price,
                    'retail' => $cost_price->retail_price,
                    'wholesale' => $cost_price->wholesale_price,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product, stock, or cost price not found.'
        ]);
    }


    // public function save_purchase(Request $request)
    // {
    //     dd($request->all());
    //     $purchase = $request->validate([
    //         'supplier_name' => 'required',
    //         'total_bill' => 'required|numeric',
    //     ]);

    //     if ($purchase) {
    //         try {
    //             DB::transaction(function () use ($request) {
    //                 // Create the Purchase
    //                 $purchase = Purchase::create([
    //                     'received_date' => $request->received_date,
    //                     'due_date' => $request->date,
    //                     'supplier_id' => $request->supplier_name,
    //                     'supplier_balance' => $request->supplier_balance,
    //                     'payment_type' => $request->payment_type,
    //                     'payment_amount' => $request->payment_amount,
    //                     'account_id' => $request->account_name,
    //                     'total_bill' => $request->total_bill,
    //                     'adjustment' => $request->adjustment,
    //                     'net_payable' => $request->net_payable,
    //                     'status' => 'purchase',
    //                 ]);



    //                 // Loop through products and save purchase details and ledger entries
    //                 for ($i = 0; $i < count($request->pro_id); $i++) {
    //                     if (isset($request->pro_id[$i]) && isset($request->stock[$i]) && isset($request->qty[$i])) {
    //                         // Create Purchase_detail
    //                         Purchase_detail::create([
    //                             'purchase_id' => $purchase->id,
    //                             'product_variant_id' => $request->pro_id[$i],
    //                             'stock' => (int)$request->stock[$i],
    //                             'cost_price' => (float)$request->cost_price[$i],
    //                             'retail_price' => (float)$request->retail[$i],
    //                             'wholesale_price' => (float)$request->wholesale[$i],
    //                             'qty' => (float)$request->qty[$i],
    //                             'total' => (float)$request->total[$i],
    //                             'location' => $request->location[$i],
    //                             'discount_type' => $request->product_discount_type[$i],
    //                             'discount_value' => $request->product_discount_value[$i],
    //                             'actual_discount_value' => $request->product_discount_actual_value[$i],
    //                         ]);
    //                     }
    //                 }
    //             });

    //             return redirect()->back()->with('success', 'Purchase added successfully!');
    //         } catch (\Exception $e) {
    //             Log::error('Error saving purchase: ' . $e->getMessage());
    //             return redirect()->back()->withInput()->with('error', $e->getMessage());
    //         }
    //     }
    // }

    public function save_purchase(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required',
            'total_bill' => 'required|numeric',
            'pro_id' => 'required|array|min:1',
            'pro_id.*' => 'required',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|numeric|min:0.01',
            'cost_price' => 'required|array|min:1',
            'cost_price.*' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $purchase = Purchase::create([
                    'received_date' => $request->received_date,
                    'due_date' => $request->date,
                    'supplier_id' => $request->supplier_name,
                    'supplier_balance' => $request->supplier_balance,
                    'payment_type' => $request->payment_type,
                    'payment_amount' => $request->payment_amount ?? 0,
                    'account_id' => $request->account_name,
                    'total_bill' => $request->total_bill,
                    'adjustment' => $request->adjustment ?? 0,
                    'net_payable' => $request->net_payable,
                    'status' => 'purchase',
                ]);

                for ($i = 0; $i < count($request->pro_id); $i++) {
                    Log::info('Processing product: ' . $request->pro_id[$i]);
                    Log::info('Purchase ID: ' . $purchase->id);
                    $detail = Purchase_detail::create([
                        'purchase_id' => $purchase->id,
                        'product_variant_id' => $request->pro_id[$i] ?? null,
                        'stock' => (int)($request->stock[$i] ?? 0),
                        'cost_price' => round((float)($request->cost_price[$i] ?? 0), 2),
                        'retail_price' => round((float)($request->retail[$i] ?? 0), 2),
                        'wholesale_price' => round((float)($request->wholesale[$i] ?? 0), 2),
                        'qty' => (float)($request->qty[$i] ?? 0),
                        'total' => (float)($request->total[$i] ?? 0),
                        'location' => $request->location[$i] ?? 'Default',
                        'discount_type' => $request->product_discount_type[$i] ?? null,
                        'discount_value' => $request->product_discount_value[$i] ?? 0,
                        'actual_discount_value' => $request->product_discount_actual_value[$i] ?? 0,
                    ]);

                    Log::info('Detail created: ' . $detail->id);
                }
            });

            return redirect()->back()->with('success', 'Purchase added successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving purchase: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            throw $e;
            return redirect()->back()->withInput()->with('error', 'Error saving purchase: ' . $e->getMessage());
        }
    }

    public function received($id)
    {
        $purchase = Purchase::find($id);

        if ($purchase && $purchase->order_status === 'pending') {
            try {
                DB::transaction(function () use ($purchase) {
                    $purchase->order_status = 'received';
                    $purchase->order_received_date = now();
                    $purchase->save();

                    if ($purchase->payment_type == 'cash') {
                        $payment_account = Account::find($purchase->account_id);

                        if ($payment_account->balance < $purchase->payment_amount) {
                            throw new \Exception('Insufficient funds in account. Payment could not be processed.');
                        }

                        $payment_account->balance -= $purchase->payment_amount;
                        $payment_account->save();

                        AccountLedger::create([
                            'date' => $purchase->received_date,
                            'purchase_id' => $purchase->id,
                            'payment' => $purchase->payment_amount,
                            'balance' => $payment_account->balance,
                            'account_id' => $payment_account->id,
                            'user_id' => Auth::id(),
                        ]);
                    } elseif ($purchase->payment_type == 'credit') {
                        $supplier_balance = Party::find($purchase->supplier_id);
                        if ($supplier_balance) {
                            $supplier_balance->balance += $purchase->net_payable;
                            $supplier_balance->save();
                        }

                        PartyLedger::create([
                            'date' => $purchase->received_date,
                            'party_id' => $purchase->supplier_id,
                            'party_type' => 'Supplier',
                            'purchase_id' => $purchase->id,
                            'price' => $purchase->net_payable,
                            'balance' => $supplier_balance->balance,
                            'user_id' => Auth::id(),
                        ]);
                    } elseif ($purchase->payment_type == 'cash+credit') {
                        $payment_amount = $purchase->payment_amount;
                        $remaining_amount = $purchase->net_payable - $payment_amount;

                        $payment_account = Account::find($purchase->account_id);
                        if ($payment_account->balance < $payment_amount) {
                            throw new \Exception('Insufficient funds in account. Payment could not be processed.');
                        }

                        $payment_account->balance -= $payment_amount;
                        $payment_account->save();

                        AccountLedger::create([
                            'date' => $purchase->received_date,
                            'purchase_id' => $purchase->id,
                            'payment' => $payment_amount,
                            'balance' => $payment_account->balance,
                            'account_id' => $payment_account->id,
                            'user_id' => Auth::id(),
                        ]);

                        $supplier_balance = Party::find($purchase->supplier_id);
                        if ($supplier_balance) {
                            $supplier_balance->balance += $remaining_amount;
                            $supplier_balance->save();
                        }

                        PartyLedger::create([
                            'date' => $purchase->received_date,
                            'party_id' => $purchase->supplier_id,
                            'party_type' => 'Supplier',
                            'purchase_id' => $purchase->id,
                            'price' => $remaining_amount,
                            'balance' => $supplier_balance->balance,
                            'user_id' => Auth::id(),
                        ]);
                    }

                    foreach ($purchase->purchase_details as $detail) {
                        $variantPrice = ProductVariantRate::where('product_variant_id', $detail->product_variant_id)->first();
                        if ($variantPrice) {
                            if ($variantPrice->retail_price != $detail->retail_price) {
                                $variantPrice->retail_price = $detail->retail_price;
                            }

                            if ($variantPrice->wholesale_price != $detail->wholesale_price) {
                                $variantPrice->wholesale_price = $detail->wholesale_price;
                            }

                            if ($variantPrice->isDirty()) {
                                $variantPrice->save();
                            }
                        } else {
                            $variantPrice = ProductVariantRate::create([
                                'product_variant_id' => $detail->product_variant_id,
                                'retail_price' => $detail->retail_price,
                                'wholesale_price' => $detail->wholesale_price,
                                'cost_price' => $detail->cost_price,
                                'user_id' => Auth::id(),
                            ]);
                        }

                        $productStock = ProductVariantStock::firstOrCreate(
                            ['product_variant_id' => $detail->product_variant_id],
                            ['stock' => 0, 'user_id' => Auth::id()]
                        );

                        $originalStock = $productStock->stock;

                        $productStock->stock = $originalStock + $detail->qty;
                        $productStock->user_id = Auth::id();
                        $productStock->save();

                        $locationId = ProductLocation::where('name', $detail->location)->pluck('id')->first();

                        if ($locationId) {
                            $productLocationStock = ProductVariantLocation::firstOrCreate(
                                [
                                    'product_variant_id' => $detail->product_variant_id,
                                    'location_id' => $locationId
                                ],
                                ['stock_qty' => 0]
                            );

                            $originalLocationStock = $productLocationStock->stock_qty;
                            $productLocationStock->stock_qty = $originalLocationStock + $detail->qty;
                            $productLocationStock->save();
                        }

                        if ($variantPrice) {
                            $previousStock = $originalStock;
                            $previousCost = $variantPrice->cost_price;
                            $totalAmount = ($previousStock * $previousCost) + ($detail->cost_price * $detail->qty);
                            $totalStock = $previousStock + $detail->qty;

                            if ($totalStock > 0) {
                                $newCostPrice = $totalAmount / $totalStock;
                                $variantPrice->cost_price = $newCostPrice;
                                $variantPrice->save();
                            }
                        }

                        ProductVariantLedger::create([
                            'product_variant_id' => $detail->product_variant_id,
                            'product_variant_purchase_id' => $purchase->id,
                            'product_variant_purchase_stock' => $detail->qty,
                            'user_id' => Auth::id(),
                        ]);
                    }
                });

                return redirect()->back()->with('success', 'Purchase approved and entries updated!');
            } catch (\Exception $e) {
                Log::error('Error approving purchase: ' . $e->getMessage());
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Purchase not found or already processed.');
    }

    public function purchase_list()
    {
        // $allParties = $this->getPurchaseWithPagination(5);
        $purchases = Purchase::with('supplier')->where('status', 'purchase')->paginate(1000);
        // dd($purchases);

        return view('adminPanel/purchase/purchase_list', compact('purchases'));
    }



    public function getPurchase($id)
    {
        $purchase = Purchase::with('purchase_details.productVarient', 'supplier', 'account')->find($id);
        $products = ProductVariant::get();
        // dd($products);
        $suppliers = Party::where('type', 'Supplier')->get();
        $accounts = Account::get();


        return view('adminPanel/purchase/purchase_edit', compact('purchase', 'products', 'suppliers', 'accounts'));
    }


    public function printPurchaseDetails($id)
    {
        $purchase = Purchase::with(['supplier'])->findOrFail($id);        // dd($purchase);
        $purchaseDetails = Purchase_detail::with('productVarient.rates')->where('purchase_id', $id)->get();
        // dd($purchaseDetails);

        $totalInvoiceAmount = $purchaseDetails->sum('total'); // Sum of the total field from purchase details
        $totalLoadingExpense = 0; // Set actual value if available
        $totalOtherExpense = 0; // Set actual value if available
        $totalFreightExpense = 0; // Set actual value if available

        // Pass data to the view
        return view('adminPanel/purchase/purchase_print', compact('purchase', 'purchaseDetails', 'totalInvoiceAmount', 'totalLoadingExpense', 'totalOtherExpense', 'totalFreightExpense'));
    }


    public function deletePurchaseInvoice($id)
    {
        $del = Purchase::find($id)->delete();
        if ($del) {
            return redirect()->back()->with(['success' => 'Purchase invoice delete successfully']);
        }
    }
}
