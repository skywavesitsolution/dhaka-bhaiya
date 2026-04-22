<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountLedger;
use App\Models\Account\CashDeposit;
use App\Models\Account\expense;
use App\Models\Account\MakePayment;
use App\Models\Account\PosClosing;
use App\Models\Account\ReceivedPayment;
use App\Models\Purchase;
use App\Models\Sales\SaleInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayBookController extends Controller
{

    public function showDayBookForm()
    {
        // $getSaleData = sal::with('account')->whereNotNull('sale_id') // Ensures sale_id is not null
        //     ->whereDate('created_at', Carbon::today()) // Filters records for today's date
        //     ->get();
        // $totalSale = $getSaleData->sum('received');
        $openingBalance = PosClosing::whereDate('created_at', Carbon::yesterday())->orderBy('created_at', 'desc')->value('total_cash');
        $paymentData = MakePayment::with('account')->whereDate('created_at', Carbon::today())->get();
        $deposite = CashDeposit::whereDate('created_at', now()->toDateString())->get();
        $depositeAmount = $deposite->sum('deposit_amount');
        $getSaleData = SaleInvoice::whereDate('created_at', Carbon::today())->get();
        $totalSale = $getSaleData->sum('net_payable');
        $creditSale = SaleInvoice::where('payment_type', 'credit')->whereDate('created_at', Carbon::today())->get();
        $creditSaleTotal = $creditSale->sum('net_payable');
        $cashSale = SaleInvoice::where('payment_type', 'cash')->whereDate('created_at', Carbon::today())->get();
        $cashSaleTotal = $cashSale->sum('net_payable');
        $cashPurchases = Purchase::where('payment_type', 'cash')->whereDate('created_at', Carbon::today())->get();
        $cashPurchaseTotal = $cashPurchases->sum('net_payable');
        $expences = expense::with('account')->whereDate('created_at', Carbon::today())->get();
        $expencestotal = $cashPurchases->sum('total_amount');
        $paymentsRecevingData = ReceivedPayment::with('account')->whereDate('created_at', Carbon::today())->get();
        $paymentsRecevingtotal = $cashPurchases->sum('total_amount');
        return view('adminPanel.orders.reports.day_books', compact('depositeAmount', 'getSaleData', 'totalSale', 'creditSaleTotal', 'cashSaleTotal', 'paymentData', 'cashPurchases', 'cashPurchaseTotal', 'expences', 'expencestotal', 'paymentsRecevingData', 'paymentsRecevingtotal', 'openingBalance'));
    }


    public function showDayBookdatewiseForm(Request $request)
    {
        // Get the start and end dates from the request, if not provided, default to today's date.
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Fetch payment data within the date range
        $paymentData = MakePayment::with('account')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Fetch deposit data within the date range
        $deposite = CashDeposit::whereBetween('created_at', [$startDate, $endDate])->get();
        $depositeAmount = $deposite->sum('deposit_amount');

        // Fetch sale data within the date range
        $getSaleData = SaleInvoice::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalSale = $getSaleData->sum('net_payable'); // Total Sale for the specified date range

        // Fetch credit sale data within the date range
        $creditSale = SaleInvoice::where('payment_type', 'credit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $creditSaleTotal = $creditSale->sum('net_payable'); // Total Credit Sale for the specified date range

        // Fetch cash sale data within the date range
        $cashSale = SaleInvoice::where('payment_type', 'cash')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $cashSaleTotal = $cashSale->sum('net_payable'); // Total Cash Sale for the specified date range


        // Pass the fetched data and the date range to the view
        return view('adminPanel.orders.reports.datewise_daybook', compact(
            'depositeAmount',
            'getSaleData',
            'totalSale',
            'creditSaleTotal',
            'cashSaleTotal',
            'paymentData',
            'startDate',
            'endDate'
        ));
    }



    public function todayInvoices()
    {

        $todayInvoices = SaleInvoice::whereDate('bill_date', Carbon::today())->get();

        return view('adminPanel.orders.reports.today_invoices', compact('todayInvoices'));
    }
}
