<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Party;
use App\Models\Purchase;
use App\Models\SaleBatch;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use App\Models\Account\expense;
use App\Models\Product\Product;
use App\Models\Sales\SaleInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\Account\MakePayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\Sales\quotationInvoice;
use App\Models\Account\expenseCategory;
use App\Models\Account\ReceivedPayment;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantStock;

class DashboardController extends Controller

{
    public function dashboard(): View
    {


        return view('adminPanel/dashboard');
    }

    public function getDashboardCard(Request $request)
    {
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

        // Find the active SaleBatch for today
        $currentBatch = SaleBatch::where('status', 'active')
            ->first();

        // Initialize sales variables
        $todaySaleAmount = 0;
        $todayCashSale = 0;
        $todayCreditSale = 0;

        if ($currentBatch) {
            // Extract the date from the ULID
            $timestampMs = decodeUlidTimestamp($currentBatch->id);
            $batchDate = Carbon::createFromTimestampMs($timestampMs)->toDateString();

            // Filter SaleInvoice records by the extracted batch date
            $todaySaleAmount = SaleInvoice::whereDate('bill_date', $batchDate)
                ->sum('net_payable') ?? 0;

            $todayCashSale = SaleInvoice::whereDate('bill_date', $batchDate)
                ->where('payment_type', 'cash')
                ->sum('net_payable') ?? 0;

            $todayCreditSale = SaleInvoice::whereDate('bill_date', $batchDate)
                ->where('payment_type', 'credit')
                ->sum('net_payable') ?? 0;
        }

        $todayPurchaseAmount = Purchase::where('received_date', date('Y-m-d'))->sum('net_payable');
        $todayCashPurchaseAmount = Purchase::where('received_date', date('Y-m-d'))->where('payment_type', 'cash')->sum('net_payable');
        $todayCreditPurchaseAmount = Purchase::where('received_date', date('Y-m-d'))->where('payment_type', 'credit')->sum('net_payable');
        $totalProductsCount = ProductVariant::count();

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $todayCost = SaleInvoice::where('bill_date', date('Y-m-d'))
            ->join('sale_products', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'sale_products.product_id')
            ->sum(DB::raw('sale_products.sale_qty * product_variant_rates.cost_price')) ?? 0;

        $todayExpense = expense::whereYear('date', $request->year)
            ->sum('total_amount') ?? 0;
        $todayGrossProfit = $todaySaleAmount - $todayCost;
        $todayNetProfit = $todayGrossProfit - $todayExpense;

        $monthlySaleAmount = SaleInvoice::whereYear('bill_date', $year)
            ->whereMonth('bill_date', $month)
            ->sum('net_payable');

        $monthlyCost = SaleInvoice::whereYear('bill_date', $year)
            ->whereMonth('bill_date', $month)
            ->join('sale_products', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'sale_products.product_id')
            ->sum(DB::raw('sale_products.sale_qty * product_variant_rates.cost_price')) ?? 0;

        $monthlyExpense = expense::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_amount');

        $monthlyGrossProfit = $monthlySaleAmount - $monthlyCost;
        $monthlyNetProfit = $monthlyGrossProfit - $monthlyExpense;

        $yearlySaleAmount = SaleInvoice::whereYear('bill_date', $year)->sum('net_payable');
        $yearlyCost = SaleInvoice::whereYear('bill_date', $year)
            ->join('sale_products', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'sale_products.product_id')
            ->sum(DB::raw('sale_products.sale_qty * product_variant_rates.cost_price')) ?? 0;
        $yearlyExpense = expense::whereYear('date', $year)->sum('total_amount');

        $YearlyGrossProfit = $yearlySaleAmount - $yearlyCost;
        $yearlyProfit = $YearlyGrossProfit - $yearlyExpense;

        $totalSaleAmount = SaleInvoice::sum('net_payable');
        $totalCost = SaleInvoice::join('sale_products', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'sale_products.product_id')
            ->sum(DB::raw('sale_products.sale_qty * product_variant_rates.cost_price')) ?? 0;
        $totalExpense = expense::sum('total_amount');

        $totalGrossProfit = $totalSaleAmount - $totalCost;
        $totalProfit = $totalGrossProfit - $totalExpense;

        $todayPayments = MakePayment::where('date', date('Y-m-d'))->sum('total_payments');
        $todayReceivedPayments = ReceivedPayment::where('date', date('Y-m-d'))->sum('total_payments');
        $todaypaymentandreceiving = $todayPayments + $todayReceivedPayments;

        $stockStatus = ProductVariantStock::get()->pluck('stock')->sum();
        $lowStock = ProductVariantStock::where('stock', '<', 'low_stock')->get()->count();

        $supplierReceivable = Party::where('type', 'Supplier')
            ->where('balance', '<', 0)->sum('balance');

        $supplierPayable = Party::where('type', 'Supplier')
            ->where('balance', '>', 0)->sum('balance');

        $customerReceivable = Party::where('type', 'Customer')
            ->where('balance', '>', 0)->sum('balance');

        $customerPayable = Party::where('type', 'Customer')
            ->where('balance', '<', 0)->sum('balance');

        $accountPayable = Account::where('balance', '<', 0)->sum('balance');
        $accountReceivable = Account::where('balance', '>', 0)->sum('balance');

        try {
            // Fix the runningOrders query to count records for today where status is not 'completed'
            $runningOrders = quotationInvoice::where('status', '!=', 'completed')
                ->count();

            // Log for debugging
            Log::info('Running Orders Count: ' . $runningOrders);
            Log::info('Records for today: ', quotationInvoice::whereDate('bill_date', now()->toDateString())->get()->toArray());

            $completedOrders = 0;
            if ($currentBatch) {
                $completedOrders = SaleInvoice::where('sale_batch_id', $currentBatch->id)
                    ->whereDate('bill_date', $batchDate) // Use the extracted batch date
                    ->count();
            }

            $todayOrders = $runningOrders + $completedOrders;
        } catch (\Exception $e) {
            Log::error('Error calculating orders: ' . $e->getMessage());
            $runningOrders = 0;
            $completedOrders = 0;
        }

        return response()->json([
            'error' => false,
            'data' => [
                'monthlyGrossProfit' => number_format($monthlyGrossProfit),
                'todayNetProfit' => number_format($todayNetProfit),
                'todayGrossProfit' => number_format($todayGrossProfit),
                'todayCashPurchaseAmount' => number_format($todayCashPurchaseAmount),
                'todayCreditPurchaseAmount' => number_format($todayCreditPurchaseAmount),
                'todayCashSale' => number_format($todayCashSale),
                'todayCreditSale' => number_format($todayCreditSale),
                'todaypaymentandreceiving' => number_format($todaypaymentandreceiving),
                'lowStock' => number_format($lowStock),
                'stockStatus' => number_format($stockStatus),
                'todayPurchaseAmount' => number_format($todayPurchaseAmount),
                'todaySaleAmount' => number_format($todaySaleAmount),
                'totalProductsCount' => number_format($totalProductsCount),
                'todayPayments' => number_format($todayPayments),
                'todayReceivedPayments' => number_format($todayReceivedPayments),
                'todayExpense' => number_format($todayExpense),
                'markaReceivable' => number_format($supplierReceivable),
                'markaPayable' => number_format($supplierPayable),
                'driverReceivable' => number_format($customerReceivable),
                'driverPayable' => number_format($customerPayable),
                'accountPayable' => number_format($accountPayable),
                'accountReceivable' => number_format($accountReceivable),
                'yearlyProfit' => number_format($yearlyProfit),
                'YearlyGrossProfit' => number_format($YearlyGrossProfit),
                'yearlyexpense' => number_format($yearlyExpense),
                'monthlyGrossProfit' => number_format($monthlyGrossProfit),
                'monthlyexpense' => number_format($monthlyExpense),
                'monthlyProfit' => number_format($monthlyNetProfit),
                'totalGrossProfit' => number_format($totalGrossProfit),
                'totalExpense' => number_format($totalExpense),
                'totalProfit' => number_format($totalProfit),
                'runningOrders' => $runningOrders,
                'completedOrders' => $completedOrders,
                'todayOrders' => $todayOrders,
                'expenseGraph' => $this->categoryWiseExpenseGraph($year),
                'expenseMonthyGraph' => $this->monthWiseExpense($year),
                'monthWiseOrders' => $this->monthWiseOrders($year),
                'monthWisepurchases' => $this->monthWisepurchases($year),
                'monthWiseProfit' => $this->monthWiseProfit($year),
            ]
        ]);
    }





    private function categoryWiseExpenseGraph($year)
    {
        $expenseCategory = expenseCategory::all();
        $expenseLables = [];
        $expenseValues = [];
        foreach ($expenseCategory as $category) {
            $expenseLables[] = $category->exp_category_name;
            $totalExpense = expense::where('category_id', $category->id)->whereYear('date', $year)->sum('total_amount');
            $expenseValues[] = ["name" => $category->exp_category_name, "data" => [$totalExpense]];
        }

        return [$expenseLables, $expenseValues];
    }

    private function monthWiseExpense($year)
    {
        // Month Wise Graph

        $monthLabels = [];
        $expenseMonthlyValues = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $monthLabels[] = $date->format('F');
            $expenseMonthlyValues[] = 0;
        }

        $expenses = Expense::selectRaw('MONTH(date) as month, SUM(total_amount) as total_expense')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        foreach ($expenses as $expense) {
            $monthIndex = $expense->month - 1;
            $expenseMonthlyValues[$monthIndex] = $expense->total_expense;
        }

        return [$monthLabels, $expenseMonthlyValues];
    }

    private function monthWiseOrders($year)
    {
        // Month Wise Graph

        $monthLabels = [];
        $orderMonthlyValues = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $monthLabels[] = $date->format('F');
            $orderMonthlyValues[] = 0;  // Initialize the month values to 0
        }

        // Modify the query to sum the 'net_payable' field instead of counting 'id'
        $orders = SaleInvoice::selectRaw('MONTH(bill_date) as month, SUM(net_payable) as total_amount')
            ->whereYear('bill_date', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Loop through the result and fill the orderMonthlyValues array with the calculated sum for each month
        foreach ($orders as $order) {
            $monthIndex = $order->month - 1;
            $orderMonthlyValues[$monthIndex] = $order->total_amount;
        }

        return [$monthLabels, $orderMonthlyValues];
    }
    private function monthWisepurchases($year)
    {
        // Month Wise Graph

        $monthLabels = [];
        $purchaseMonthlyValues = [];  // Correct array to store monthly purchase amounts

        // Initialize the month values to 0
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $monthLabels[] = $date->format('F');
            $purchaseMonthlyValues[] = 0;  // This should be $purchaseMonthlyValues, not $orderMonthlyValues
        }

        // Modify the query to sum the 'net_payable' field instead of counting 'id'
        $orders = Purchase::selectRaw('MONTH(received_date) as month, SUM(net_payable) as total_amount')
            ->whereYear('received_date', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Loop through the result and fill the purchaseMonthlyValues array with the calculated sum for each month
        foreach ($orders as $order) {
            $monthIndex = $order->month - 1;
            $purchaseMonthlyValues[$monthIndex] = $order->total_amount;
        }

        // Optionally debug the values if needed
        // dd($purchaseMonthlyValues);

        return [$monthLabels, $purchaseMonthlyValues];
    }



    private function monthWiseProfit($year)
    {
        // Month Wise Graph

        $monthLabels = [];
        $MonthyProfitValues = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $monthLabels[] = $date->format('F');
            $MonthyProfitValues[] = 0;
        }

        // $ordersProfit = Order::selectRaw('MONTH(date) as month, Sum(profit) as total_profit')
        //     ->whereYear('date', $year)
        //     ->groupBy('month')
        //     ->orderBy('month', 'asc')
        //     ->get();

        $expenses = Expense::selectRaw('MONTH(date) as month, SUM(total_amount) as total_expense')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // foreach ($ordersProfit as $index => $order) {
        //     $monthIndex = $order->month - 1;
        //     $MonthyProfitValues[$monthIndex] = $order->total_profit - $expenses[$index]->total_expense;
        // }

        return [$monthLabels, $MonthyProfitValues];
    }
}
