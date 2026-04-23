<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\expense;
use App\Models\Sales\SaleProduct;
use Illuminate\Support\Facades\DB;
use App\Models\Account\MakePayment;
use App\Models\Account\AccountLedger;
use App\Models\Account\ReceivedPayment;
use App\Models\Nozzle\NozzleOpeningAndClosing;
use App\Models\Purchase_detail;

class SummaryReportController extends Controller
{
    public function summaryReports()
    {
        $suppliers = Supplier::all();
        $accounts = Account::all();
        return view('adminPanel.Summary.summaryReports', compact('suppliers', 'accounts'));
    }

    public function dateWiseIncomeStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);


        $expense = Expense::join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id')
            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
            ->get();


        $generalSales = SaleProduct::join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'sale_products.product_id', '=', 'product_variants.id')
            // ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'sale_products.product_id')
            ->whereBetween('sale_invoices.bill_date', [$request->start_date, $request->end_date])
            // ->where('product_categories.category_name', 'General items')
            ->select(
                'product_variants.product_variant_name as name',
                DB::raw('SUM(sale_products.sale_qty) as total_quantity'),
                DB::raw('SUM(sale_products.sale_qty * product_variant_rates.cost_price) as total_cost'),
                DB::raw('SUM(sale_products.sale_qty * product_variant_rates.retail_price) as total_retail')
            )
            ->groupBy('product_variants.id')
            ->get();



        $start_date = $request->start_date;
        $end_date =  $request->end_date;
        return view('adminPanel.Summary.incomestatement', compact(
            'expense',
            'generalSales',
            // 'nozzleSales',
            'start_date',
            'end_date',
        ));
    }

    public function daySummary(Request $request)
    {
        // dd($request);
        $expense = Expense::join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id')
            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')
            ->where('date', $request->date)
            ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
            ->get();
        $makePayment = MakePayment::with('paymentItems')->where('date', $request->date)->get();
        $reaceivedPayment = ReceivedPayment::with('paymentItems')->where('date', $request->date)->get();

        $cashSalesRaw = SaleProduct::join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'sale_products.product_id', '=', 'product_variants.id')
            ->where('sale_invoices.bill_date', $request->date)
            ->where('sale_invoices.payment_type', 'cash')
            ->select(
                'sale_products.*',
                'product_variants.product_variant_name as name',
                'product_variants.manage_deal_items'
            )
            ->get();

        $cashSaleProducts = [];
        foreach ($cashSalesRaw as $sale) {
            if ($sale->manage_deal_items > 0) {
                // Deal Expansion logic
                $deal = \App\Models\Deals\DealTable::where('product_variant_deal_id', $sale->product_id)
                    ->with('deal_item.products')
                    ->first();

                if ($deal && $deal->deal_item->count() > 0) {
                    $isFirst = true;
                    foreach ($deal->deal_item as $item) {
                        $cashSaleProducts[] = (object)[
                            'name' => $item->products->product_variant_name ?? 'Unknown',
                            'quantity' => floatval($sale->sale_qty) * $item->product_variant_qty,
                            'price' => $isFirst ? floatval($sale->retail_price) : 0,
                            'amount' => $isFirst ? floatval($sale->sale_amount) : 0,
                            'sale_type' => 'General Sales (cash)'
                        ];
                        $isFirst = false;
                    }
                } else {
                    $cashSaleProducts[] = (object)[
                        'name' => $sale->name,
                        'quantity' => floatval($sale->sale_qty),
                        'price' => floatval($sale->retail_price),
                        'amount' => floatval($sale->sale_amount),
                        'sale_type' => 'General Sales (cash)'
                    ];
                }
            } else {
                $cashSaleProducts[] = (object)[
                    'name' => $sale->name,
                    'quantity' => floatval($sale->sale_qty),
                    'price' => floatval($sale->retail_price),
                    'amount' => floatval($sale->sale_amount),
                    'sale_type' => 'General Sales (cash)'
                ];
            }
        }

        // dd($cashSaleProducts);

        // $nozzleSales = NozzleOpeningAndClosing::join('nozzles', 'nozzle_id', '=', 'nozzles.id')
        //     ->join('product_types', 'nozzles.product_id', '=', 'product_types.id')
        //     ->where('closing_date', $request->date)
        //     ->select(
        //         'product_types.name as name',
        //         'consumption_qty as quantity',
        //         'rate as price',
        //         'amount',
        //         DB::raw('"Nozzle Sales (Cash)" as sale_type')
        //     )
        //     ->get()
        //     ->toArray();
        $allSales = $cashSaleProducts;

        $creditSalesRaw = SaleProduct::join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'sale_products.product_id', '=', 'product_variants.id')
            ->where('sale_invoices.bill_date', $request->date)
            ->where('sale_invoices.payment_type', 'credit')
            ->select(
                'sale_products.*',
                'product_variants.product_variant_name as name',
                'product_variants.manage_deal_items'
            )
            ->get();

        $creditSaleProducts = [];
        foreach ($creditSalesRaw as $sale) {
            if ($sale->manage_deal_items > 0) {
                // Deal Expansion logic
                $deal = \App\Models\Deals\DealTable::where('product_variant_deal_id', $sale->product_id)
                    ->with('deal_item.products')
                    ->first();

                if ($deal && $deal->deal_item->count() > 0) {
                    $isFirst = true;
                    foreach ($deal->deal_item as $item) {
                        $creditSaleProducts[] = (object)[
                            'name' => $item->products->product_variant_name ?? 'Unknown',
                            'quantity' => floatval($sale->sale_qty) * $item->product_variant_qty,
                            'price' => $isFirst ? floatval($sale->retail_price) : 0,
                            'amount' => $isFirst ? floatval($sale->sale_amount) : 0,
                            'sale_type' => 'General Sales (Credit)'
                        ];
                        $isFirst = false;
                    }
                } else {
                    $creditSaleProducts[] = (object)[
                        'name' => $sale->name,
                        'quantity' => floatval($sale->sale_qty),
                        'price' => floatval($sale->retail_price),
                        'amount' => floatval($sale->sale_amount),
                        'sale_type' => 'General Sales (Credit)'
                    ];
                }
            } else {
                $creditSaleProducts[] = (object)[
                    'name' => $sale->name,
                    'quantity' => floatval($sale->sale_qty),
                    'price' => floatval($sale->retail_price),
                    'amount' => floatval($sale->sale_amount),
                    'sale_type' => 'General Sales (Credit)'
                ];
            }
        }

        $date = $request->date;
        return view('adminPanel.Summary.daySummary', compact(
            'expense',
            'makePayment',
            'allSales',
            'reaceivedPayment',
            'creditSaleProducts',
            'date'
        ));
    }

    public function supplierWiseSummary(Request $request)
    {
        $supplier = $request->supplier;
        $date = $request->date;
        $account = $request->account;

        $orders = Order::where('supplier_id', $supplier)
            ->where('date', $date)
            ->get();
        // Fetch account ledger summaries
        $accountSums = AccountLedger::where('account_id', $account)
            ->whereDate('date', $date)
            ->selectRaw('SUM(payment) as total_payment, SUM(received) as total_received')
            ->first();

        // Handle case where no account ledger records are found
        $accountPayment = $accountSums ? $accountSums->total_payment : 0;
        $accountReceived = $accountSums ? $accountSums->total_received : 0;

        // Get the last transaction record based on date
        $lastTransaction = AccountLedger::where('account_id', $account)
            ->whereDate('date', $date)
            ->orderBy('created_at', 'desc')
            ->first();

        // Calculate the balance
        $balance = 0;
        if ($lastTransaction) {
            $balance = $lastTransaction->balance; // Assuming balance is stored directly in the record
        }
        $accountPayment = $accountSums->total_payment;
        $accountReceived = $accountSums->total_received;
        $makePayment = MakePayment::with('paymentItems')
            ->where('date', $date)
            ->where('account_id', $account)
            ->get();

        $receivedPayment = ReceivedPayment::with('paymentItems')
            ->where('date', $date)
            ->where('account_id', $account)
            ->get();


        return view('adminPanel.Summary.supplierWiseSummary', compact('orders', 'accountPayment', 'accountReceived', 'makePayment', 'receivedPayment', 'date', 'supplier', 'balance'));
    }
}
