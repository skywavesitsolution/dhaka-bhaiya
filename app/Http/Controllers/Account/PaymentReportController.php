<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\MakePayment;
use App\Models\Account\ReceivedPayment;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function paymentsReports()
    {
        $CashAccountsdata = AccountController::getAllAccounts();
        return view('adminPanel.accounts.accountReports.paymentsReports', compact('CashAccountsdata'));
    }

    public function dateWisePayment(Request $request)
    {
        $paymentsData = MakePayment::whereBetween('date', [$request->start_date, $request->end_date])
            ->get();
        return view('adminPanel.accounts.accountReports.dateWisePayments', ['paymentsData' => $paymentsData, 'request' => $request->all()]);
    }

    public function dateWiseReceivedPayment(Request $request)
    {
        $paymentsReceivedData = ReceivedPayment::whereBetween('date', [$request->start_date, $request->end_date])
            ->get();
        return view('adminPanel.accounts.accountReports.dateWiseReceivedPayments', ['paymentsReceivedData' => $paymentsReceivedData, 'request' => $request->all()]);
    }
}
