<?php

namespace App\Http\Controllers\Sales;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Party;
use App\Models\SaleBatch;
use App\Models\Ingredient;
use App\Models\PartyLedger;
use App\Models\ProductType;
use App\Models\Table\Table;
use Illuminate\Support\Str;
use App\Models\Sales\bility;
use Illuminate\Http\Request;
use App\Models\Product_stock;
use App\Models\IngredientSale;
use App\Models\Product_ledger;
use App\Models\Account\Account;
use App\Models\Deals\DealTable;
use App\Models\IngredientsItem;
use App\Models\WalkingCustomer;
use App\Models\Product_category;
use App\Models\Sales\ProductHold;
use App\Models\Sales\SaleInvoice;
use App\Models\Sales\SaleProduct;
use App\Models\Account\PosClosing;
use App\Models\IngredientSaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PartyDiscountProduct;
use App\Models\Sales\KitchenInvoice;
use Illuminate\Support\Facades\Auth;
use App\Models\Account\AccountLedger;
use Illuminate\Support\Facades\Cache;
use App\Models\Sales\quotationInvoice;
use App\Models\Account\ReceivedPayment;
use App\Models\Models\Employee\Employee;
use App\Models\Sales\ProductHoldInvoice;
use App\Models\Sales\KitchenInvoiceDetail;
use App\Models\Sales\quotationInvoiceDetail;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\Product\Variant\ProductVariantLedger;

class SaleInvoiceController extends Controller
{
    public function getSaleInvoice()
    {
        // if (!Session::where('user_id', auth()->id())->where('status', 'active')->exists()) {
        //     return redirect()->route('session.manage')->with('error', 'You must create a session to access POS!');
        // }

        $getProduct = ProductVariant::with('rates')->where('raw_material', 0)->get();
        $getParties = Party::whereIn('type', ['Customer', 'Both'])->get();
        $accounts = Account::get();
        $categories = Product_category::get();
        $getSaleHold = ProductHoldInvoice::get();
        $getQuotation = quotationInvoice::with('table.location')->get();
        $employees = Employee::get();
        $tables = Table::with('location')->get();

        return view('adminPanel.SaleAndPurchase.sale_product', [
            'products' => $getProduct,
            'parties' => $getParties,
            'accounts' => $accounts,
            'categories' => $categories,
            'holdInvoices' => $getSaleHold,
            'quotationInvoives' => $getQuotation,
            'employees' => $employees,
            'tables' => $tables,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->query('query');
        $cacheKey = 'products_' . md5($query);

        $products = Cache::remember($cacheKey, 60, function () use ($query) {
            return ProductVariant::where('raw_material', 0)
                ->where(function ($q) use ($query) {
                    $q->where('code', 'LIKE', "$query%")
                        ->orWhereRaw('LOWER(product_variant_name) LIKE LOWER(?)', ["%$query%"]);
                })
                ->select('id', 'code', 'product_variant_name')
                ->limit(20)
                ->get();
        });

        return response()->json($products);
    }


    public function filterProducts(Request $request)
    {
        // Start a query from the ProductVariant model
        $query = ProductVariant::query()
            ->with('rates') // Eager load the rates relationship
            ->where('raw_material', 0)
            ->whereHas('product', function ($productQuery) use ($request) {
                // Filter by category if provided
                if ($request->filled('category')) {
                    $productQuery->where('category_id', $request->category);
                }
            });

        // Get the filtered products
        $filterproducts = $query->get();

        // Check if any products were fetched
        if ($filterproducts->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        // Prepare the response data, including the media URL for each product
        $filterproductsData = $filterproducts->map(function ($product) {
            // Get the first media image for the product variant (if it exists)
            $mediaUrl = $product->getFirstMediaUrl('pro_var_images', 'thumb'); // You can specify the desired media conversion here

            // Modify the URL to match the required public storage URL format
            // $mediaUrl = str_replace('storage/', 'public/storage/', $mediaUrl);

            return [
                'id' => $product->id,
                'product_variant_name' => $product->product_variant_name,
                'rates' => $product->rates, // Assuming 'rates' relationship contains price data
                'media' => $mediaUrl ?: "https://via.placeholder.com/100", // Fallback to placeholder image if no media is found
            ];
        });

        // Return the filtered products as JSON with media URLs
        return response()->json(['filterproducts' => $filterproductsData]);
    }

    public function getProductById($id)
    {
        $product = ProductVariant::with('rates', 'stock', 'productVariantLocation.ProductLocation')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $deal = DealTable::where('product_variant_deal_id', $product->id)->first();
        $dealTotal = $deal ? $deal->deal_total : null;

        return response()->json([
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'product_id' => $product->product_id,
                    'name' => $product->product_variant_name,
                    'retail_price' => $product->rates->retail_price ?? null,
                    'cost_price' => $product->rates->cost_price ?? null,
                    'wholesale_price' => $product->rates->wholesale_price ?? null,
                    'location' => $product->productVariantLocation->first()->ProductLocation->name ?? null,
                    'deal_total' => $dealTotal, // Add deal total if the product has a deal
                ]
            ]
        ]);
    }

    public function getcustomerbalance($id)
    {
        $customerBalance = Party::with('customerDiscount')->find($id);
        // $customerDiscount = PartyDiscount::find($id);
        // dd($customerBalance);
        return response()->json([
            'data' => [
                'customerBalance' => [
                    'id' => $customerBalance->id,
                    'balance' => $customerBalance->balance,
                    'discount_type' => $customerBalance->customerDiscount->discount_type ?? 'null',
                    'discount_value' => $customerBalance->customerDiscount->discount_value ?? 'null',
                ]
            ]
        ]);
    }

    public function storeSaleInvoice(Request $request)
    {
        $request->validate([
            'total_bill' => ['required'],
            'payment_type' => ['required'],
            'net_payable' => ['required'],
            'service_charges' => ['nullable', 'numeric', 'min:0'], // Add validation
            'product_id.*' => ['required'],
            'retail_price.*' => ['required'],
            'qty.*' => ['required'],
            'total.*' => ['required'],
        ]);

        try {
            $saleInvoice = null;

            DB::transaction(function () use ($request, &$saleInvoice) {
                // Calculate net payable with service charges
                $totalBill = floatval($request->total_bill);
                $discountActualValue = floatval($request->discount_actual_value ?? 0);
                $serviceCharges = floatval($request->service_charges ?? 0);
                $netPayable = ($totalBill - $discountActualValue) + $serviceCharges;
                $activeBatch = SaleBatch::where('status', 'active')->first();

                if (!$activeBatch) {
                    $activeBatch = SaleBatch::create([
                        'id' => Str::ulid(),
                        'status' => 'active'
                    ]);
                }

                $ulid = $activeBatch->id;

                // Function to decode ULID timestamp (first 10 characters) from base32
                function decodeUlidTimestamp($ulid)
                {
                    $timestampChars = substr($ulid, 0, 10);
                    $base32Chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

                    $timestamp = 0;
                    for ($i = 0; $i < 10; $i++) {
                        $timestamp = $timestamp * 32 + strpos($base32Chars, $timestampChars[$i]);
                    }

                    return $timestamp;
                }

                $timestampMs = decodeUlidTimestamp($ulid);
                $batchDate = Carbon::createFromTimestampMs($timestampMs);

                $saleInvoice = SaleInvoice::create([
                    'user_id' => Auth::user()->id,
                    'party_id' => $request->party_id,
                    'customer_reciveable' => $request->customer_reciveable,
                    'bill_date' => $batchDate, // Store extracted batch date
                    'sale_batch_id' => $activeBatch->id,
                    'due_date' => $request->due_date,
                    'payment_type' => $request->payment_type,
                    'received_amount' => $request->payment_amount,
                    'total_bill' => $request->total_bill,
                    'discount_type' => $request->discount_type,
                    'discount_value' => $request->discount_value,
                    'discount_actual_value' => $request->discount_actual_value,
                    'service_charges' => $serviceCharges, // Store service charges
                    'net_payable' => $netPayable, // Use calculated value
                    'employee_id' => $request->employee,
                    'status' => 'sale',
                    'ip_address' => $request->ip(),
                ]);

                if ($request->payment_type == 'cash') {
                    $paymentAccount = Account::findOrFail($request->account_name);
                    $paymentAccount->balance += $request->net_payable;
                    $paymentAccount->save();

                    AccountLedger::create([
                        'date' => now(),
                        'sale_id' => $saleInvoice->id,
                        'received' => $request->net_payable,
                        'balance' => $paymentAccount->balance,
                        'account_id' => $paymentAccount->id,
                        'user_id' => Auth::user()->id,
                    ]);
                } elseif ($request->payment_type == 'credit') {
                    $customer = Party::findOrFail($request->party_id);
                    $customer->balance += $request->net_payable;
                    $customer->save();

                    PartyLedger::create([
                        'date' => now(),
                        'party_id' => $request->party_id,
                        'party_type' => 'Customer',
                        'sale_id' => $saleInvoice->id,
                        'price' => $request->net_payable,
                        'balance' => $customer->balance,
                        'user_id' => Auth::user()->id,
                    ]);
                } elseif ($request->payment_type == 'cash+credit') {
                    $paymentAmount = $request->payment_amount;
                    $remainingAmount = $request->net_payable - $paymentAmount;

                    $paymentAccount = Account::findOrFail($request->account_name);
                    $paymentAccount->balance += $paymentAmount;
                    $paymentAccount->save();

                    AccountLedger::create([
                        'date' => now(),
                        'sale_id' => $saleInvoice->id,
                        'received' => $paymentAmount,
                        'balance' => $paymentAccount->balance,
                        'account_id' => $paymentAccount->id,
                        'user_id' => Auth::user()->id,
                    ]);

                    $customer = Party::findOrFail($request->party_id);
                    $customer->balance += $remainingAmount;
                    $customer->save();

                    PartyLedger::create([
                        'date' => now(),
                        'party_id' => $request->party_id,
                        'party_type' => 'Customer',
                        'sale_id' => $saleInvoice->id,
                        'price' => $remainingAmount,
                        'balance' => $customer->balance,
                        'user_id' => Auth::user()->id,
                    ]);
                }

                foreach ($request->product_id as $index => $productId) {
                    if (isset($request->check[$productId]) && $request->check[$productId] === 'on') {
                        $deal = DealTable::where('product_variant_deal_id', $productId)->first();

                        if ($deal) {
                            $saleProduct = SaleProduct::create([
                                'user_id' => Auth::user()->id,
                                'invoice_id' => $saleInvoice->id,
                                'product_id' => $productId,
                                'retail_price' => $request->retail_price[$index],
                                'sale_qty' => $request->qty[$index],
                                'product_discount_type' => $request->product_discount_type[$index],
                                'product_discount_value' => $request->product_discount_value[$index],
                                'product_discount_actual_value' => $request->product_discount_actual_value[$index],
                                'sale_amount' => $request->total[$index],
                                'location' => $request->location[$index],
                                'remarks' => $request->remarks[$index],
                                'ip_address' => $request->ip(),
                            ]);

                            foreach ($deal->deal_item as $dealItem) {
                                $dealItemStock = ProductVariantStock::where('product_variant_id', $dealItem->product_variant_id)->first();
                                $dealItemUsedQty = $saleProduct->sale_qty * $dealItem->product_variant_qty;

                                if ($dealItemStock) {
                                    $dealItemStock->stock -= ($dealItem->product_variant_qty * $request->qty[$index]);
                                    $dealItemStock->save();
                                }

                                ProductVariantLedger::create([
                                    'product_variant_id' => $dealItem->product_variant_id,
                                    'product_variant_sale_id' => $saleInvoice->id,
                                    'product_variant_sale_stock' => $dealItem->product_variant_qty * $request->qty[$index],
                                    'user_id' => Auth::user()->id,
                                ]);

                                $recipe = Ingredient::where('product_variant_id', $dealItem->product_variant_id)->first();
                                if ($recipe) {
                                    $ingredients = IngredientsItem::where('ingredients_id', $recipe->id)->get();
                                    foreach ($ingredients as $ingredient) {
                                        $usedQty = $ingredient->qty * $dealItemUsedQty;
                                        $ingredientStock = ProductVariantStock::where('product_variant_id', $ingredient->product_variant_id)->first();
                                        if ($ingredientStock) {
                                            $ingredientStock->stock -= $usedQty;
                                            $ingredientStock->save();
                                        }
                                    }

                                    $ingredientSale = IngredientSale::create([
                                        'sale_id' => $saleInvoice->id,
                                        'product_id' => $dealItem->product_variant_id,
                                    ]);

                                    foreach ($ingredients as $ingredient) {
                                        $usedQty = $ingredient->qty * $dealItemUsedQty;
                                        IngredientSaleItem::create([
                                            'ingredient_sale_id' => $ingredientSale->id,
                                            'ingredient_id' => $ingredient->product_variant_id,
                                            'qty' => $usedQty,
                                        ]);
                                    }
                                }
                            }
                        } else {
                            $saleProduct = SaleProduct::create([
                                'user_id' => Auth::user()->id,
                                'invoice_id' => $saleInvoice->id,
                                'product_id' => $productId,
                                'retail_price' => $request->retail_price[$index],
                                'sale_qty' => $request->qty[$index],
                                'sale_dicount' => $request->product_discount_actual_value[$index] ?? 0,
                                'sale_amount' => $request->total[$index],
                                // 'location' => $request->location[$index],
                                'remarks' => $request->remarks[$index],
                                'ip_address' => $request->ip(),
                            ]);

                            $productStock = ProductVariantStock::where('product_variant_id', $productId)->first();
                            if ($productStock) {
                                $productStock->stock -= $request->qty[$index];
                                $productStock->save();
                            }

                            ProductVariantLedger::create([
                                'product_variant_id' => $productId,
                                'product_variant_sale_id' => $saleInvoice->id,
                                'product_variant_sale_stock' => $request->qty[$index],
                                'user_id' => Auth::user()->id,
                            ]);

                            $recipe = Ingredient::where('product_variant_id', $productId)->first();
                            if ($recipe) {
                                $ingredients = IngredientsItem::where('ingredients_id', $recipe->id)->get();
                                foreach ($ingredients as $ingredient) {
                                    $usedQty = $ingredient->qty * $saleProduct->sale_qty;
                                    $ingredientStock = ProductVariantStock::where('product_variant_id', $ingredient->product_variant_id)->first();
                                    if ($ingredientStock) {
                                        $ingredientStock->stock -= $usedQty;
                                        $ingredientStock->save();
                                    }
                                }

                                $ingredientSale = IngredientSale::create([
                                    'sale_id' => $saleInvoice->id,
                                    'product_id' => $productId,
                                ]);

                                foreach ($ingredients as $ingredient) {
                                    $usedQty = $ingredient->qty * $saleProduct->sale_qty;
                                    IngredientSaleItem::create([
                                        'ingredient_sale_id' => $ingredientSale->id,
                                        'ingredient_id' => $ingredient->product_variant_id,
                                        'qty' => $usedQty,
                                    ]);
                                }
                            }
                        }
                    }
                }
            });

            if ($request->has('print_sale') && $request->print_sale === 'on') {
                return redirect()->route('printInvoice', ['invoiceId' => $saleInvoice->id]);
            }

            return redirect()->back()->with('success', 'Sale invoice added successfully');
        } catch (\Exception $e) {
            Log::error('Error saving sale invoice: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function SaleInvoiceList()
    {
        $getSaleInvoice = SaleInvoice::with('party', 'bilty')
            ->orderBy('id', 'desc')->get();

        return view('adminPanel.SaleAndPurchase.sale_product_list', compact('getSaleInvoice'));
    }

    public function todaySaleInvoiceList()
    {

        $activeBatch = SaleBatch::where('status', 'active')->first();

        $batchDate = null;
        if ($activeBatch) {
            $timestampBase32 = substr($activeBatch->id, 0, 10);
            $timestamp = 0;
            $base32chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
            for ($i = 0; $i < 10; $i++) {
                $timestamp *= 32;
                $timestamp += strpos($base32chars, $timestampBase32[$i]);
            }
            $timestampSeconds = $timestamp / 1000;
            $batchDate = \Carbon\Carbon::createFromTimestamp($timestampSeconds)->toDateString();
        }

        $getSaleInvoice = SaleInvoice::with('party', 'bilty')
            ->whereDate('bill_date', $batchDate)
            ->orderBy('id', 'desc')
            ->get();

        return view('adminPanel.SaleAndPurchase.today_sale_list', compact('getSaleInvoice'));
    }

    public function editSaleInvoice($id)
    {
        $getProduct = ProductType::get();
        $getParties = Party::where('type', 'Customer')->get();
        $editSaleInvoice = SaleInvoice::with('party', 'getProduct')->find($id);
        $saleProducts = SaleProduct::with('getProduct')->where('invoice_id', $id)->get();

        return view('adminPanel.SaleAndPurchase.update_sale_product', [
            'editSaleInvoice' => $editSaleInvoice,
            'saleProducts' => $saleProducts,
            'parties' => $getParties,
            'products' => $getProduct,
        ]);
    }

    public function SaleInvoicePrint($id)
    {
        $getProduct = ProductVariant::get();
        $getParties = Party::get();
        $editSaleInvoice = SaleInvoice::with('party', 'getProduct', 'user', 'bilty')->find($id);
        $saleProducts = SaleProduct::with('getProduct')->where('invoice_id', $id)->get();
        // dd($editSaleInvoice);

        return view('adminPanel.SaleAndPurchase.sale_product_print', [
            'editSaleInvoice' => $editSaleInvoice,
            'saleProducts' => $saleProducts,
            'parties' => $getParties,
            'products' => $getProduct,
        ]);
    }

    public function updateSaleProduct(Request $request, $id)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'bill_date' => 'required|date',
            'payment_type' => 'required',
            'total_bill' => 'required|numeric',
            'product_id.*' => 'required|exists:product_types,id',
            'qty.*' => 'required|numeric|min:1',
            'retail_price.*' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $saleInvoice = SaleInvoice::findOrFail($id);
                $res = $saleInvoice->update([
                    'user_id' => Auth::user()->id,
                    'party_id' => $request->party_id,
                    'bill_date' => $request->bill_date,
                    'payment_type' => $request->payment_type,
                    'total_bill' => $request->total_bill,
                    'discount_type' => $request->discount_type,
                    'discount_value' => $request->discount_value,
                    'discount_actual_value' => $request->discount_actual_value,
                    'adjustment' => $request->adjustment,
                    'net_payable' => $request->net_payable,
                    'ip_address' => $request->ip(),
                ]);

                foreach ($request->product_id as $index => $productId) {
                    $saleProduct = SaleProduct::where('invoice_id', $saleInvoice->id)
                        ->where('product_id', $productId)
                        ->first();
                    if ($saleProduct) {
                        $saleProduct->update([
                            'user_id' => Auth::user()->id,
                            'product_name' => $request->product_name[$index],
                            'retail_price' => $request->retail_price[$index],
                            'qty' => $request->qty[$index],
                            'product_discount_type' => $request->product_discount_type[$index],
                            'product_discount_value' => $request->product_discount_value[$index],
                            'product_discount_actual_value' => $request->product_discount_actual_value[$index],
                            'total' => $request->total[$index],
                            'ip_address' => $request->ip(),
                        ]);
                    } else {
                        SaleProduct::create([
                            'invoice_id' => $saleInvoice->id,
                            'product_id' => $productId,
                            'product_name' => $request->product_name[$index],
                            'retail_price' => $request->retail_price[$index],
                            'qty' => $request->qty[$index],
                            'product_discount_type' => $request->product_discount_type[$index],
                            'product_discount_value' => $request->product_discount_value[$index],
                            'product_discount_actual_value' => $request->product_discount_actual_value[$index],
                            'total' => $request->total[$index],
                            'user_id' => Auth::user()->id,
                            'ip_address' => $request->ip(),
                        ]);
                    }

                    $product = ProductType::with('category')->find($productId);
                    $productStock = Product_stock::where('product_id', $productId)->first();

                    if ($productStock && $product->category_id == 1) {
                        $productStock->stock -= $request->qty[$index];
                        $productStock->save();

                        Product_ledger::create([
                            'product_id' => $productId,
                            'sale_id' => $saleInvoice->id,
                            'sale_stock' => $request->qty[$index],
                        ]);
                    }
                }

                if ($request->payment_type == 'Credit') {
                    $partyLedger = PartyLedger::where('party_id', $request->party_id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $currentBalance = $partyLedger ? $partyLedger->balance : 0;
                    $newBalance = $currentBalance + $request->total[$index];

                    PartyLedger::create([
                        'user_id' => Auth::user()->id,
                        'date' => $request->bill_date,
                        'party_id' => $request->party_id,
                        'party_type' => 'Customer',
                        'sale_id' => $saleInvoice->id,
                        'price' => $request->total[$index],
                        'balance' => $newBalance,
                        'ip_address' => $request->ip(),
                    ]);

                    $customer = Party::find($request->party_id);
                    if ($customer) {
                        $customer->balance = $newBalance;
                        $customer->save();
                    }
                }
            });

            return redirect()->back()->with(['success' => 'Sale invoice updated successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function storeHoldInvoice(Request $request)
    {
        // Validate the request
        // dd($request);
        $request->validate([
            'total_bill' => ['required'],
            'net_payable' => ['required'],
            'product_id.*' => ['required'],
            'product_name.*' => ['required'],
            'retail_price.*' => ['required'],
            'qty.*' => ['required'],
            'total.*' => ['required'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $holdInvoice = null;

                // Check if this is an update to an existing hold invoice
                if (!empty($request->hold_invoice_id)) {
                    // Find the existing hold invoice
                    $holdInvoice = ProductHoldInvoice::find($request->hold_invoice_id);

                    if ($holdInvoice) {
                        // Update the existing hold invoice
                        $holdInvoice->update([
                            'user_id' => Auth::user()->id,
                            'party_id' => $request->party_id,
                            'customer_reciveable' => $request->customer_reciveable,
                            'bill_date' => $request->bill_date,
                            'payment_type' => $request->payment_type,
                            'total_bill' => $request->total_bill,
                            'discount_type' => $request->discount_type,
                            'discount_value' => $request->discount_value,
                            'discount_actual_value' => $request->discount_actual_value,
                            'adjustment' => $request->adjustment,
                            'net_payable' => $request->net_payable,
                            'employee_id' => $request->employee,
                            'ip_address' => $request->ip(),
                        ]);

                        // Remove old products associated with this hold invoice
                        ProductHold::where('invoice_hold_id', $holdInvoice->id)->delete();
                    }
                }

                // If no existing hold invoice, create a new one
                if (!$holdInvoice) {
                    $holdInvoice = ProductHoldInvoice::create([
                        'user_id' => Auth::user()->id,
                        'party_id' => $request->party_id,
                        'customer_reciveable' => $request->customer_reciveable,
                        'bill_date' => $request->bill_date,
                        'payment_type' => $request->payment_type,
                        'total_bill' => $request->total_bill,
                        'discount_type' => $request->discount_type,
                        'discount_value' => $request->discount_value,
                        'discount_actual_value' => $request->discount_actual_value,
                        'adjustment' => $request->adjustment,
                        'net_payable' => $request->net_payable,
                        'ip_address' => $request->ip(),
                    ]);
                }

                // Add or update products in the hold invoice
                foreach ($request->product_id as $index => $productId) {
                    if (isset($request->check[$productId]) && $request->check[$productId] === 'on') {

                        ProductHold::create([
                            'user_id' => Auth::user()->id,
                            'invoice_hold_id' => $holdInvoice->id,
                            'product_id' => (int) $productId,
                            'retail_price' => $request->retail_price[$index],
                            'sale_qty' => $request->qty[$index],
                            'dicount_type' => $request->product_discount_type[$index],
                            'dicount_value' => $request->product_discount_value[$index],
                            'sale_amount' => $request->total[$index],
                            'location' => $request->location[$index],
                            'remarks' => $request->remarks[$index],
                            'ip_address' => $request->ip(),
                        ]);
                    }
                }
            });

            return redirect()->back()->with(['success' => 'Sale invoice held successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function storeQuotationInvoice(Request $request)
    {
        // Validate the request
        $request->validate([
            'total_bill' => ['required'],
            'product_id.*' => ['required'],
            'product_name.*' => ['required'],
            'retail_price.*' => ['required'],
            'qty.*' => ['required'],
            'total.*' => ['required'],
        ]);

        try {
            $quotationInv = null;
            DB::transaction(function () use ($request, &$quotationInv) {
                // Handle existing quotation invoice
                if (!empty($request->quotation_invoice_id)) {
                    $quotationInv = QuotationInvoice::find($request->quotation_invoice_id);
                    // dd($quotationInv);

                    if ($quotationInv) {
                        $quotationInv->update([
                            'user_id' => Auth::user()->id,
                            'party_id' => $request->party_id,
                            'customer_reciveable' => $request->customer_reciveable,
                            'bill_date' => $request->bill_date,
                            'payment_type' => $request->payment_type,
                            'total_bill' => $request->total_bill,
                            'discount_type' => $request->discount_type,
                            'discount_value' => $request->discount_value,
                            'discount_actual_value' => $request->discount_actual_value,
                            'adjustment' => $request->adjustment,
                            'net_payable' => $request->net_payable,
                            'ip_address' => $request->ip(),
                        ]);

                        QuotationInvoiceDetail::where('invoice_quotation_id', $quotationInv->id)->delete();
                    }
                }

                // Create new quotation invoice if none exists
                if (!$quotationInv) {
                    $quotationInv = QuotationInvoice::create([
                        'user_id' => Auth::user()->id,
                        'party_id' => $request->party_id,
                        'customer_reciveable' => $request->customer_reciveable,
                        'customer_name' => $request->customer_name,
                        'customer_number' => $request->customer_number,
                        'customer_address' => $request->customer_address,
                        'table_number' => $request->table_number,
                        'employee' => $request->employee,
                        'order_type' => $request->order_type,
                        'bill_date' => $request->bill_date,
                        'payment_type' => $request->payment_type,
                        'total_bill' => $request->total_bill,
                        'discount_type' => $request->discount_type,
                        'discount_value' => $request->discount_value,
                        'discount_actual_value' => $request->discount_actual_value,
                        'adjustment' => $request->adjustment,
                        'net_payable' => $request->net_payable,
                        'ip_address' => $request->ip(),
                    ]);

                    if ($request->table_number) {
                        $table = Table::findOrFail($request->table_number);
                        $table->status = 'Reserve';
                        $table->save();
                    }
                }

                // Create quotation invoice details
                $newItems = [];
                foreach ($request->product_id as $index => $productId) {
                    if (isset($request->check[$productId]) && $request->check[$productId] === 'on') {
                        $quotationDetail = QuotationInvoiceDetail::create([
                            'user_id' => Auth::user()->id,
                            'invoice_quotation_id' => $quotationInv->id,
                            'product_id' => (int) $productId,
                            'retail_price' => $request->retail_price[$index],
                            'sale_qty' => $request->qty[$index],
                            'dicount_type' => $request->product_discount_type[$index],
                            'dicount_value' => $request->product_discount_value[$index],
                            'sale_amount' => $request->total[$index],
                            'location' => $request->location[$index],
                            'remarks' => $request->location[$index],
                            'ip_address' => $request->ip(),
                        ]);

                        $newItems[] = $quotationDetail;
                    }
                }

                // Handle kitchen invoice
                if (!empty($newItems)) {
                    $kitchenInvoice = null;
                    $itemsToSend = [];

                    // Check if kitchen_invoice_id is provided and exists
                    if ($request->has('kitchen_invoice_id') && !empty($request->kitchen_invoice_id)) {
                        $kitchenInvoice = KitchenInvoice::find($request->kitchen_invoice_id);
                    }

                    if ($kitchenInvoice) {
                        // Update existing kitchen invoice
                        $totalBill = array_sum(array_column($newItems, 'sale_amount'));
                        $netPayable = $totalBill - ($request->discount_actual_value ?? 0);

                        $kitchenInvoice->update([
                            'user_id' => Auth::user()->id,
                            'quotation_invoice_id' => $quotationInv->id,
                            'party_id' => $request->party_id,
                            'customer_reciveable' => $request->customer_reciveable,
                            'customer_name' => $request->customer_name,
                            'customer_number' => $request->customer_number,
                            'customer_address' => $request->customer_address,
                            'bill_date' => $request->bill_date ?? now(),
                            'total_bill' => $totalBill,
                            'payment_type' => $request->payment_type,
                            'discount_type' => $request->discount_type,
                            'discount_value' => $request->discount_value,
                            'discount_actual_value' => $request->discount_actual_value,
                            'table_number' => $request->table_number,
                            'employee' => $request->employee,
                            'order_type' => $request->order_type,
                            'adjustment' => $request->adjustment,
                            'net_payable' => $netPayable,
                            'ip_address' => $request->ip(),
                            'updated_by' => Auth::user()->id,
                            'status' => 'in_kitchen',
                        ]);

                        // Fetch existing kitchen invoice details
                        $existingKitchenItems = KitchenInvoiceDetail::where('kitchen_invoice_id', $kitchenInvoice->id)
                            ->where('sent_to_kitchen', true)
                            ->pluck('sale_qty', 'product_id')
                            ->toArray();

                        // Compare new items with existing ones
                        foreach ($newItems as $item) {
                            $key = $item->product_id . '-' . $item->sale_qty;
                            if (!isset($existingKitchenItems[$item->product_id]) || $existingKitchenItems[$item->product_id] != $item->sale_qty) {
                                $itemsToSend[] = $item;
                            }
                        }
                    } else {
                        // Create new kitchen invoice
                        $itemsToSend = $newItems; // All items are new
                        $totalBill = array_sum(array_column($itemsToSend, 'sale_amount'));
                        $netPayable = $totalBill - ($request->discount_actual_value ?? 0);

                        $kitchenInvoice = KitchenInvoice::create([
                            'user_id' => Auth::user()->id,
                            'quotation_invoice_id' => $quotationInv->id,
                            'party_id' => $request->party_id,
                            'customer_reciveable' => $request->customer_reciveable,
                            'customer_name' => $request->customer_name,
                            'customer_number' => $request->customer_number,
                            'customer_address' => $request->customer_address,
                            'bill_date' => $request->bill_date ?? now(),
                            'total_bill' => $totalBill,
                            'payment_type' => $request->payment_type,
                            'discount_type' => $request->discount_type,
                            'discount_value' => $request->discount_value,
                            'discount_actual_value' => $request->discount_actual_value,
                            'table_number' => $request->table_number,
                            'employee' => $request->employee,
                            'order_type' => $request->order_type,
                            'adjustment' => $request->adjustment,
                            'net_payable' => $netPayable,
                            'ip_address' => $request->ip(),
                            'updated_by' => Auth::user()->id,
                            'status' => 'in_kitchen',
                        ]);
                    }

                    // Add new items to kitchen invoice details
                    if (!empty($itemsToSend)) {
                        foreach ($itemsToSend as $item) {
                            KitchenInvoiceDetail::create([
                                'user_id' => Auth::user()->id,
                                'kitchen_invoice_id' => $kitchenInvoice->id,
                                'quotation_invoice_detail_id' => $quotationDetail->id,
                                'product_id' => $item->product_id,
                                'retail_price' => $item->retail_price,
                                'sale_qty' => $item->sale_qty,
                                'dicount_type' => $item->dicount_type,
                                'dicount_value' => $item->dicount_value,
                                'sale_amount' => $item->sale_amount,
                                'ip_address' => $request->ip(),
                                'location' => $item->location,
                                'remarks' => $item->remarks,
                                'sent_to_kitchen' => false, // New items are not sent to kitchen
                            ]);
                        }
                    }
                }
            });

            if ($request->has('print_quotation') && $request->print_quotation === 'on') {
                return redirect()->route('printquotation', ['quotationId' => $quotationInv->id]);
            }

            return redirect()->back()->with(['success' => 'Quotation invoice saved successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $invoice = QuotationInvoice::findOrFail($id);
            $invoice->status = $request->status;
            $invoice->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status.']);
        }
    }

    public function reloadInvoice($invoiceId)
    {
        // Fetch the invoice by ID
        $invoice = ProductHoldInvoice::find($invoiceId);

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found'
            ], 404);
        }

        // Fetch the items of the invoice
        $items = ProductHold::with('variant.productVariantLocation')->where('invoice_hold_id', $invoiceId)->get();

        // Prepare the response data
        $invoiceData = [
            'status' => 'success',
            'data' => [
                'invoice' => $invoice,
                'items' => $items
            ]
        ];

        // Delete the items and the invoice
        try {
            ProductHold::where('invoice_hold_id', $invoiceId)->delete(); // Delete related items
            $invoice->delete(); // Delete the invoice itself
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete invoice or items',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json($invoiceData);
    }

    public function reloadQuotation($quotationId)
    {
        $invoice = QuotationInvoice::find($quotationId);

        if (!$invoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quotation not found'
            ], 404);
        }

        $items = QuotationInvoiceDetail::with('variant.productVariantLocation')
            ->where('invoice_quotation_id', $quotationId)
            ->get();

        $kitchenInvoice = KitchenInvoice::where('quotation_invoice_id', $invoice->id)
            ->where('status', 'in_kitchen')
            ->first();

        $kitchenInvoiceDetails = [];
        if ($kitchenInvoice) {
            $kitchenInvoiceDetails = KitchenInvoiceDetail::where('kitchen_invoice_id', $kitchenInvoice->id)
                ->get();
        }

        $walkingCustomer = WalkingCustomer::where('phone_number', $invoice->customer_number)->first();

        if ($walkingCustomer) {
            $walkingCustomer->update([
                'name' => $invoice->customer_name ?? 'Walking Customer',
                'address' => $invoice->customer_address,
            ]);
        } else {
            $walkingCustomer = WalkingCustomer::create([
                'name' => $invoice->customer_name ?? 'Walking Customer',
                'phone_number' => $invoice->customer_number,
                'address' => $invoice->customer_address,
            ]);
        }

        $invoiceData = [
            'status' => 'success',
            'data' => [
                'invoice' => $invoice,
                'items' => $items,
                'walking_customer' => $walkingCustomer,
                'kitchen_invoice' => $kitchenInvoice,
                'kitchen_invoice_details' => $kitchenInvoiceDetails
            ]
        ];

        try {
            if ($invoice->order_type === 'dine-in' && $invoice->table_number) {
                $table = Table::where('table_number', $invoice->table_number)->first();
                if ($table && $table->status === 'Reserve') {
                    $table->status = 'Free';
                    $table->save();
                }
            }
            QuotationInvoiceDetail::where('invoice_quotation_id', $quotationId)->delete();
            $invoice->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete quotation or details',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json($invoiceData);
    }

    public function deleteSaleInvoice($id)
    {
        $del = SaleInvoice::find($id)->delete();
        if ($del) {
            return redirect()->back()->with(['success' => 'Sale invoice delete successfully']);
        }
    }
    public function deleteHoldInvoice($id)
    {
        $del = ProductHoldInvoice::find($id)->delete();
        if ($del) {
            return redirect()->back()->with(['success' => 'Hold invoice delete successfully']);
        }
    }

    public function deleteQuotationInvoice($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $quotation = QuotationInvoice::findOrFail($id);

                if ($quotation->table_number) {
                    $table = Table::where('table_number', $quotation->table_number)->first();
                    if ($table && $table->status === 'Reserve') {
                        $table->status = 'Free';
                        $table->save();
                    }
                }

                $quotation->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Kitchen invoice deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete: ' . $e->getMessage()], 500);
        }
    }


    public function printInvoice($invoiceId)
    {
        $invoice = SaleInvoice::with([
            'saleProduct.getProduct', // Load product variant
            'saleProduct.getProduct.deal' // Load deal if applicable
        ])->findOrFail($invoiceId);

        return view('adminPanel.SaleAndPurchase.invoice', compact('invoice'));
    }

    public function printQuotation($quotationId)
    {
        $quotation = QuotationInvoice::findOrFail($quotationId);
        // dd($quotation);

        $invoice = KitchenInvoice::with([
            'kitchenInvoiceDetails' => function ($query) {
                $query->where('sent_to_kitchen', 0);
            },
            'kitchenInvoiceDetails.product' // Eager load associated product
        ])
            ->where('quotation_invoice_id', $quotation->id)
            ->where('status', 'in_kitchen')
            ->firstOrFail();
        // dd($invoice);

        $productsWithDeals = DB::transaction(function () use ($invoice) {
            $productsWithDeals = $invoice->kitchenInvoiceDetails
                ->where('sent_to_kitchen', 0) // Only include items not sent to kitchen
                ->map(function ($product) {
                    $deals = DealTable::with(['deal_item.products'])
                        ->where('product_variant_deal_id', $product->product_id) // Match with product_id
                        ->get();

                    return [
                        'product' => $product,
                        'deals' => $deals,
                    ];
                });

            KitchenInvoiceDetail::where('kitchen_invoice_id', $invoice->id)
                ->where('sent_to_kitchen', 0)
                ->update(['sent_to_kitchen' => 1]);

            // dd($productsWithDeals);

            return $productsWithDeals;
        });
        // dd($productsWithDeals);

        return view('adminPanel.SaleAndPurchase.quotation_print', compact('invoice', 'productsWithDeals'));
    }



    public function bestSellingProducts()
    {
        // Get the best-selling products with their rates and associated product data
        $bestSellingProducts = ProductVariant::with(['rates', 'product'])
            ->whereHas('product', function ($query) {
                $query->where('best_selling_product', true); // Filter best-selling products
            })
            ->get();

        // Prepare the response data, including the media URL for each best-selling product
        $bestSellingProductsData = $bestSellingProducts->map(function ($productVariant) {
            // Get the first media image for the product variant (if it exists)
            $mediaUrl = $productVariant->getFirstMediaUrl('pro_var_images', 'thumb'); // Use 'thumb' conversion or other

            // Modify the URL to match the required public storage URL format
            // $mediaUrl = str_replace('storage/', 'public/storage/', $mediaUrl);

            return [
                'id' => $productVariant->id,
                'product_variant_name' => $productVariant->product_variant_name,
                'rates' => $productVariant->rates, // Assuming 'rates' relationship contains price data
                'media' => $mediaUrl ?: "https://via.placeholder.com/100", // Fallback to placeholder image if no media is found
                'product_name' => $productVariant->product->product_name, // Optionally include the associated product name
                'retail_price' => $productVariant->rates->retail_price, // Example: retail price from rates
            ];
        });

        // Return the best-selling products as JSON with media URLs
        return response()->json($bestSellingProductsData);
    }

    public function getHoldCount()
    {
        $holdCount = ProductHoldInvoice::count(); // Replace 'Hold' with your model name
        return response()->json(['count' => $holdCount]);
    }
    public function getquotationCount()
    {
        $quotationCount = quotationInvoice::count(); // Replace 'Hold' with your model name
        return response()->json(['count' => $quotationCount]);
    }



    public function posClosing()
    {
        $employees = User::all();

        $activeBatch = SaleBatch::where('status', 'active')->first();

        $batchDate = null;
        if ($activeBatch) {
            $timestampBase32 = substr($activeBatch->id, 0, 10);
            $timestamp = 0;
            $base32chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
            for ($i = 0; $i < 10; $i++) {
                $timestamp *= 32;
                $timestamp += strpos($base32chars, $timestampBase32[$i]);
            }
            $timestampSeconds = $timestamp / 1000;
            $batchDate = \Carbon\Carbon::createFromTimestamp($timestampSeconds)->toDateString();
        }

        return view('adminPanel.pos_closing', compact('employees', 'batchDate', 'activeBatch'));
    }

    public function fetchSaleData(Request $request)
    {
        $operatorId = $request->input('Id');
        $date = $request->input('date');
        $batch_id = $request->input('batch_id');

        $systemCash = SaleInvoice::where('user_id', $operatorId)
            ->where('sale_batch_id', $batch_id)
            ->whereDate('bill_date', $date)
            ->where('payment_type', 'cash')
            ->sum('net_payable');

        $customerPayments = ReceivedPayment::whereDate('date', $date)
            ->sum('total_payments');

        $pastClosings = PosClosing::where('user_id', $operatorId)
            ->whereDate('report_date', $date)
            ->get(['id', 'phisical_cash', 'system_cash', 'differ_cash', 'report_date'])
            ->toArray();

        return response()->json([
            'system_cash' => $systemCash,
            'customer_payments' => $customerPayments,
            'tbl_data' => $pastClosings,
        ]);
    }

    public function FetchSaleOfEmployee(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'Id' => 'required|integer',      // Ensure Id is provided and is an integer
            'date' => 'required|date',      // Ensure date is provided and is a valid date
        ]);

        // Get input data from the request
        $id = $request->input('Id');
        $date = $request->input('date');

        // Fetch sales data based on user_id and date from SaleInvoice
        $saleAmount = SaleInvoice::where('payment_type', 'cash')
            ->where('employee_id', $id)
            ->whereDate('bill_date', $date)
            ->get();

        // Calculate total net payable (system cash)
        $systemCash = $saleAmount->sum('net_payable');

        // Fetch payments from the payments table
        $payments = ReceivedPayment::where('user_id', $id)
            ->whereDate('date', $date)
            ->sum('total_payments');

        // Fetch the data from PosClosing based on user_id
        $posClosingData = PosClosing::where('user_id', $id) // Assuming 'closing_date' is the date column in PosClosing
            ->get();



        // Prepare the response data
        $responseData = [
            'tbl_data' => $posClosingData,        // All PosClosing data for the user
            'system_cash' => $systemCash,         // Total of net_payable
            'payments' => $payments,              // Total payments from payments table
        ];

        // Return the response as JSON
        return response()->json($responseData);
    }



    public function saveBiltyData(Request $request)
    {
        try {
            // Validate the incoming data
            $validated = $request->validate([
                'date' => 'required|date',
                'bilty_number' => 'required|numeric',
                'number_of_corton' => 'required|numeric',
                'cargo_name' => 'required|string',
                // 'vehicle_number' => 'required|string',
                'sale_id' => 'required|numeric', // Ensure sale_id is required for the new record
            ]);

            // Create a new Bilty record using the validated data
            $bilty = bility::create([
                'sale_id' => $request->sale_id, // Associate it with the sale_id
                'bilty_number' => $request->bilty_number,
                'number_of_corton' => $request->number_of_corton,
                'cargo_name' => $request->cargo_name, // Make sure cargo_name is included
                'vahical_number' => $request->vahical_number,
                'bilty_date' => $request->date,
                'remarks' => $request->remarks ?? null, // Optional, can be null if not provided
                'user_id' => Auth::user()->id,
            ]);

            // Return a response
            return redirect()->back()->with('success', 'bilty added successfully');
        } catch (\Exception $e) {
            // If an error occurs, catch it and return the error message
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function getCustomerDiscount($customer_id, $product_id)
    {
        $discount = PartyDiscountProduct::where('party_id', $customer_id)
            ->where('product_variant_id', $product_id)
            ->first();

        if ($discount) {
            return response()->json([
                'discount' => $discount->product_discount,
                'discount_type' => $discount->product_discount_type ?? 'Fixed',
            ]);
        }

        return response()->json([
            'discount' => 0,
            'discount_type' => 'Fixed', // Default to fixed if no discount
        ]);
    }

    public function printCustomerInvoice($quotationId)
    {
        $invoice = quotationInvoice::with([
            'qutaionProduct.variant', // Load product variants
            'table.location',         // Load table and location
            // 'employee',               // Load employee (if applicable)
            // 'party'                   // Load party (customer) if linked
        ])->findOrFail($quotationId);

        $productsWithDeals = $invoice->qutaionProduct->map(function ($product) {
            $deals = DealTable::with(['deal_item.products'])
                ->where('product_variant_deal_id', $product->variant->id)
                ->get();

            return [
                'product' => $product,
                'deals' => $deals,
            ];
        });

        return view('adminPanel.SaleAndPurchase.customer_invoice_print', compact('invoice', 'productsWithDeals'));
    }

    public function checkTableStatus($tableId)
    {
        try {
            $table = Table::findOrFail($tableId);

            return response()->json([
                'status' => $table->status === 'Reserve' ? 'reserved' : 'free',
                'table_number' => $table->table_number,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table not found or an error occurred',
            ], 404);
        }
    }

    public function updateQuotationTable($quotationId, Request $request)
    {
        try {
            $data = $request->validate([
                'table_id' => 'required|exists:tables,id',
                'old_table_id' => 'nullable|exists:tables,id'
            ]);

            $newTableId = $data['table_id'];
            $oldTableId = $data['old_table_id'];


            $response = DB::transaction(function () use ($quotationId, $newTableId, $oldTableId) {
                $quotation = QuotationInvoice::findOrFail($quotationId);

                $newTable = Table::findOrFail($newTableId);
                if ($newTable->status !== 'Free') {
                    throw new \Exception('Selected table is already reserved.');
                }

                if ($oldTableId) {
                    $oldTable = Table::findOrFail($oldTableId);
                    $oldTable->status = 'Free';
                    $oldTable->save();
                }

                $newTable->status = 'Reserve';
                $newTable->save();

                $newTableNumber = $newTable->table_number;

                $quotation->table_number = $newTableNumber;
                $quotation->save();

                return [
                    'success' => true,
                    'message' => 'Table updated successfully.'
                ];
            });

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }


    public function getTables()
    {
        try {
            $tables = Table::with('location')->get()->map(function ($table) {
                return [
                    'id' => $table->id,
                    'location_name' => $table->location->name,
                    'table_number' => $table->table_number,
                    'status' => $table->status,
                ];
            });

            return response()->json([
                'success' => true,
                'tables' => $tables
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching tables: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tables.'
            ], 500);
        }
    }
}
