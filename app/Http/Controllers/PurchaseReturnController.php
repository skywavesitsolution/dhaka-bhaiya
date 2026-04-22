<?php

namespace App\Http\Controllers;

use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLedger;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\Purchase;
use App\Models\Purchase_detail;
use Database\Factories\Product\Variant\ProductVariantFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseReturnController extends Controller
{
    public function list()
    {
        // $allParties = $this->getPurchaseWithPagination(5);
        $purchases = Purchase::with('supplier')->where('status', 'Return')->paginate(10);
        // dd($purchases);

        return view('adminPanel/purchase/purchase_return_list', compact('purchases'));
    }
    public function showpurchaseReturnpage()
    {
        $productVarients = ProductVariant::get();
        $suppliers = Party::where('type', 'Supplier')->get();
        $accounts = Account::get();

        return view('adminPanel/purchase/purchase_return', compact('productVarients', 'suppliers', 'accounts'));
    }

    public function savePurchaseReturn(Request $request)
    {
        $purchase = $request->validate([
            'supplier_name' => 'required',
            'total_bill' => 'required|numeric',
        ]);

        if ($purchase) {
            try {
                DB::transaction(function () use ($request) {
                    // Create the Purchase Return
                    $purchase = Purchase::create([
                        'received_date' => $request->received_date,
                        'due_date' => $request->date,
                        'status' => "Return",
                        'supplier_id' => $request->supplier_name,
                        'supplier_balance' => $request->supplier_balance,
                        'payment_type' => $request->payment_type,
                        'payment_amount' => $request->payment_amount,
                        'account_id' => $request->account_name,
                        'total_bill' => $request->total_bill,
                        'adjustment' => $request->adjustment,
                        'net_payable' => $request->net_payable,
                    ]);

                    if ($request->payment_type == 'cash') {
                        $payment_account = Account::find($request->account_name);
                        // if ($payment_account->balance < $request->payment_amount) {
                        //     throw new \Exception('Insufficient funds in account. Payment could not be processed.');
                        // }
                        $payment_account->balance += $request->payment_amount; // Reverse cash
                        $payment_account->save();

                        AccountLedger::create([
                            'date' => $request->received_date,
                            'purchase_id' => $purchase->id,
                            'payment' => -$request->payment_amount, // Reverse transaction
                            'balance' => $payment_account->balance,
                            'account_id' => $payment_account->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    } elseif ($request->payment_type == 'credit') {
                        $supplier_balance = Party::find($request->supplier_name);
                        if ($supplier_balance) {
                            $supplier_balance->balance -= $request->net_payable; // Reverse supplier balance
                            $supplier_balance->save();
                        }

                        PartyLedger::create([
                            'date' => $request->received_date,
                            'party_id' => $request->supplier_name,
                            'party_type' => "Supplier",
                            'purchase_id' => $purchase->id,
                            'price' => -$request->net_payable, // Reverse transaction
                            'balance' => $supplier_balance->balance,
                            'user_id' => Auth::user()->id,
                        ]);
                    } elseif ($request->payment_type == 'cash+credit') {
                        $payment_amount = $request->payment_amount; // Amount paid in cash
                        $remaining_amount = $request->net_payable - $payment_amount;

                        $payment_account = Account::find($request->account_name);
                        if ($payment_account->balance < $request->payment_amount) {
                            throw new \Exception('Insufficient funds in account. Payment could not be processed.');
                        }
                        $payment_account->balance += $payment_amount; // Reverse cash payment
                        $payment_account->save();

                        AccountLedger::create([
                            'date' => $request->received_date,
                            'purchase_id' => $purchase->id,
                            'payment' => -$payment_amount, // Reverse transaction
                            'balance' => $payment_account->balance,
                            'account_id' => $payment_account->id,
                            'user_id' => Auth::user()->id,
                        ]);

                        $supplier_balance = Party::find($request->supplier_name);
                        if ($supplier_balance) {
                            $supplier_balance->balance -= $remaining_amount; // Reverse credit payment
                            $supplier_balance->save();
                        }

                        PartyLedger::create([
                            'date' => $request->received_date,
                            'party_id' => $request->supplier_name,
                            'party_type' => "Supplier",
                            'purchase_id' => $purchase->id,
                            'price' => -$remaining_amount, // Reverse transaction
                            'balance' => $supplier_balance->balance,
                            'user_id' => Auth::user()->id,
                        ]);
                    }

                    // Loop through products and handle stock adjustments
                    for ($i = 0; $i < count($request->pro_id); $i++) {
                        if (isset($request->pro_id[$i]) && isset($request->stock[$i]) && isset($request->qty[$i])) {
                            $product = ProductVariant::with('rates')->findorfail($request->pro_id[$i]);
                            Purchase_detail::create([
                                'purchase_id' => $purchase->id,
                                'product_variant_id' => $request->pro_id[$i],
                                'stock' => -(int)$request->stock[$i], // Subtract stock
                                'cost_price' => (float)$request->cost_price[$i],
                                'retail_price' => (float)$product->rates->retail_price,
                                'qty' => -(int)$request->qty[$i], // Negative quantity for return
                                'total' => -(float)$request->total[$i], // Negative total for return
                                'discount_type' => $request->product_discount_type[$i], // Negative total for return
                                'discount_value' => $request->product_discount_value[$i], // Negative total for return
                                'actual_discount_value' => $request->product_discount_actual_value[$i], // Negative total for return
                            ]);

                            $productStock = ProductVariantStock::where('product_variant_id', $request->pro_id[$i])->first();
                            if ($productStock) {
                                $previousStock = $productStock->stock;
                                $updateStock = $previousStock - $request->qty[$i]; // Subtract stock
                                $productStock->stock = $updateStock;
                                $productStock->save();
                            }

                            ProductVariantLedger::create([
                                'product_variant_id' => $request->pro_id[$i],
                                'product_variant_purchase_id' => $purchase->id,
                                'product_variant_purchase_stock' => -$request->qty[$i], // Negative stock for return
                                'user_id' => Auth::user()->id,
                            ]);
                        }
                    }
                });

                return redirect()->back()->with('success', 'Purchase return processed successfully!');
            } catch (\Exception $e) {
                Log::error('Error saving purchase return: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }
    }

    public function purchaseReturnlist()
    {
        // $allParties = $this->getPurchaseWithPagination(5);
        $Returnpurchases = Purchase::with('supplier')->where('status', 'Return')->paginate(10);

        return view('adminPanel/purchase/purchase_return_list', compact('Returnpurchases'));
    }

    public function printPurchasereturnDetails($id)
    {
        $purchase = Purchase::with(['supplier'])->findOrFail($id);        // dd($purchase);
        $purchaseDetails = Purchase_detail::with('productVarient.rates')->where('purchase_id', $id)->get();
        // dd($purchaseDetails);

        $totalInvoiceAmount = $purchaseDetails->sum('total'); // Sum of the total field from purchase details
        $totalLoadingExpense = 0; // Set actual value if available
        $totalOtherExpense = 0; // Set actual value if available
        $totalFreightExpense = 0; // Set actual value if available

        // Pass data to the view
        return view('adminPanel/purchase/purchase_return_print', compact('purchase', 'purchaseDetails', 'totalInvoiceAmount', 'totalLoadingExpense', 'totalOtherExpense', 'totalFreightExpense'));
    }
}
