<?php

namespace App\Http\Controllers\Account;

use App\Actions\Account\SaveAccountLedger;
use App\Actions\Account\UpdateAccountBalance;
use App\Actions\SavePartyLedger;
use App\Actions\UpdatePartyBalance;
use App\Http\Controllers\Controller;
use App\Models\Account\MakePayment;
use App\Models\Account\MakePaymentItems;
use App\Models\Purchase;
use App\Models\Sales\SaleInvoice;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MakePaymentController extends Controller
{
    public function printPaymentVoucher(MakePayment $makePayment)
    {
        $makePayment->load('paymentItems');
        return view('adminPanel.accounts.paymentListPrint', ['payment' => $makePayment]);
    }

    public function getPaymentAddPage()
    {
        $accounts = AccountController::getAllAccounts();
        $suppliers = Supplier::all();
        // dd($suppliers);
        return view('adminPanel.accounts.payments', ['accounts' => $accounts, 'suppliers' => $suppliers]);
    }

    public function updatePaymentItem(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        $request->validate([
            'payment_id' => ['required', 'exists:make_payment_items,id'],
            'party_id' => ['required', 'integer'],
            'total_payment' => ['required', 'numeric']
        ]);

        $makePaymentItem = MakePaymentItems::find($request->payment_id);

        try {
            DB::transaction(function () use ($request, $makePaymentItem, $updateAccountBalance, $saveAccountLedger) {

                if ($makePaymentItem->particular == 'Party') {
                    $this->updatePartyData($request->all(), $makePaymentItem);
                }

                if ($makePaymentItem->particular == 'Account') {
                    $this->updateAccountData($request->all(), $makePaymentItem);
                }

                $paymentDifference = $request->total_payment - $makePaymentItem->payment;

                $makePaymentItem->update([
                    'payment' => $request->total_payment,
                    'remarks' => $request->remarks
                ]);

                $makePayment = MakePayment::find($makePaymentItem->make_payment_id);
                // Update Account Balance
                $updateAccountBalance->execute($makePayment->account_id, $paymentDifference, 'decrement');

                // Insert Account Ledger
                $saveAccountLedger->execute($makePayment->account_id, $paymentDifference, 'payment', 'payment_id', $makePayment->id, 'Payment Update', date: date('Y-m-d'));


                $result = $makePayment->update([
                    'total_payments' => $makePayment->total_payments + $paymentDifference
                ]);
            });

            return redirect()->back()->with(['success' => 'Payment Item Updated Successfully']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    private function updatePartyData(array $requestData, MakePaymentItems $MakePaymentItem)
    {
        $paymentDifference = $requestData['total_payment'] - $MakePaymentItem->payment;
        // Update Party Balance
        $updatePartyBalance = app(UpdatePartyBalance::class);
        $updatePartyBalance->execute($MakePaymentItem->particular_id, $paymentDifference, 'decrement');

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($MakePaymentItem->particular_id, $paymentDifference, 'payment_id', 'received', $MakePaymentItem->id, $requestData['remarks'] . ". Payment Update", date: date('Y-m-d'));
    }

    private function updateAccountData(array $requestData, MakePaymentItems $MakePaymentItem)
    {
        $paymentDifference = $requestData['total_payment'] - $MakePaymentItem->payment;
        // Update Account Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($MakePaymentItem->particular_id, $paymentDifference, 'increment');

        // Insert Account Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($MakePaymentItem->particular_id, $paymentDifference, 'received', 'sub_payment_id', $MakePaymentItem->id, $requestData['remarks'] . ". Payment Update", date: date('Y-m-d'));
    }

    public function updatePayment(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        $request->validate([
            'payment_id' => ['required', 'exists:make_payments,id'],
            'accountId' => ['required', 'exists:make_payments,id'],
            'date' => ['required', 'date']
        ]);

        try {
            DB::transaction(function () use ($request, $updateAccountBalance, $saveAccountLedger) {
                $makePayment = MakePayment::find($request->payment_id);

                if ($makePayment->account_id != $request->accountId) {
                    // Add Amount to Prvious Account
                    $updateAccountBalance->execute($makePayment->account_id, $makePayment->total_payments, 'increment');

                    // Insert Account Ledger
                    $saveAccountLedger->execute($makePayment->account_id, $makePayment->total_payments, 'received', 'payment_id', $makePayment->id, remarks: 'Payment Updated', date: $request->date);

                    // Remove Amount From New Account
                    $updateAccountBalance->execute($request->accountId, $makePayment->total_payments, 'decrement');

                    // Insert Account Ledger
                    $saveAccountLedger->execute($request->accountId, $makePayment->total_payments, 'payment', 'payment_id', $makePayment->id, remarks: 'Payment Updated', date: $request->date);
                }

                $makePayment->update([
                    'account_id' => $request->accountId,
                    'date' => $request->date,
                ]);
            });

            return redirect()->back()->with(['success' => 'Payment Make Updated Successfully']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function deletePaymentItem(MakePaymentItems $paymentItem)
    {
        try {
            DB::transaction(function () use ($paymentItem) {
                if ($paymentItem->particular == 'Account') {
                    $this->adjustAccountItemBalance($paymentItem);
                } else {
                    $this->adjustPartyBalance($paymentItem);
                }
                $this->adjustAccountBalance($paymentItem);

                $makePayment = MakePayment::find($paymentItem->make_payment_id);
                $makePayment->update([
                    'total_payments' => $makePayment->total_payments - $paymentItem->payment
                ]);

                $paymentItem->delete();
            });

            return redirect()->back()->with(['success' => 'Payment Item deleted Successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function adjustAccountItemBalance(MakePaymentItems $paymentItem)
    {
        // Update Party Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($paymentItem->particular_id, $paymentItem->payment, 'decrement');

        // Insert Party Ledger
        $SaveAccountLedger = app(SaveAccountLedger::class);
        $SaveAccountLedger->execute($paymentItem->particular_id, $paymentItem->payment, 'payment', 'payment_id',  $paymentItem->id, 'Payment Deleted');
    }

    public function adjustPartyBalance(MakePaymentItems $paymentItem)
    {
        // Update Party Balance
        $updatePartyBalance = app(UpdatePartyBalance::class);
        $updatePartyBalance->execute($paymentItem->particular_id, $paymentItem->payment, 'increment');

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($paymentItem->particular_id, $paymentItem->payment, 'payment_id', 'payment', $paymentItem->id, 'Payment Deleted');
    }

    public function adjustAccountBalance(MakePaymentItems $paymentItem)
    {
        $makePayment = MakePayment::find($paymentItem->make_payment_id);
        // Update Account Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($makePayment->account_id, $paymentItem->payment, 'increment');

        // Insert Account Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($makePayment->account_id, $paymentItem->payment, 'received', 'sub_payment_id', $paymentItem->id, 'payment Item Deleted');
    }

    public function fetchMakePayment(MakePayment $payment)
    {
        return response()->json([
            'error' => false,
            'data' => [
                'payment' => $payment,
                'payment_date' => date('m-d-Y', strtotime($payment->date))
            ]
        ]);
    }

    public function paymentsList()
    {
        $accounts = AccountController::getAllAccounts();
        $makePayments = MakePayment::orderBy('id', 'desc')->paginate(10);
        return view('adminPanel.accounts.paymentsList', ['accounts' => $accounts, 'makePayments' => $makePayments]);
    }

    public function viewPaymentDetails($paymentId)
    {
        $makePaymentItems = MakePaymentItems::where('make_payment_id', $paymentId)->get();
        return view('adminPanel.accounts.paymentsListDetails', ['makePaymentItems' => $makePaymentItems]);
    }

    public function getPaymentItem(MakePaymentItems $makePaymentItem)
    {
        return response()->json([
            'error' => false,
            'data' => [
                'paymentItem' => $makePaymentItem
            ]
        ]);
    }





    public function addMakePayment(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        // dd($request);
        $request->validate([
            'date' => ['required', 'date'],
            'previousBalance' => ['required', 'numeric'],
            'updatedBalance' => ['required', 'numeric'],
            'totalPayments' => ['required', 'numeric'],
            'accountId' => ['integer'],
            'particular' => ['required', 'array'],
            'particularId' => ['required', 'array'],
            'particularName' => ['required', 'array'],
            'payment' => ['required', 'array'],
            'remarks' => ['required', 'array'],
        ]);

        try {
            DB::transaction(function () use ($request, $updateAccountBalance, $saveAccountLedger) {

                $totalPayments = $this->calculateTotalPayments($request->payment);

                // Save Make Payment
                $makePayment = MakePayment::create([
                    'date' => $request->date,
                    'prev_balance' => $request->previousBalance,
                    'updated_balance' => $request->updatedBalance,
                    'total_payments' => $totalPayments,
                    'account_id' => $request->accountId,
                    'user_id' => Auth::user()->id,
                ]);

                // Update Account Balance
                $updateAccountBalance->execute($request->accountId, $totalPayments, 'decrement');

                // Insert Account Ledger
                $saveAccountLedger->execute($request->accountId, $totalPayments, 'payment', 'payment_id', $makePayment->id, date: $request->date);

                // Save Payments Items
                $this->savePaymentsItems($request->all(), $makePayment->id);
            });

            return redirect()->back()->with(['success' => 'Payment Make Successfully']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    private function calculateTotalPayments(array $payments)
    {
        $totalPayment = 0;
        foreach ($payments as $payment) {
            $totalPayment += $payment;
        }

        return $totalPayment;
    }

    private function savePaymentsItems(array $requestData, int $paymentId)
    {
        foreach ($requestData['particular'] as $index => $particular) {
            if ($particular == 'Party') {
                $this->savePartyData($requestData, $index, $paymentId);
            }

            if ($particular == 'Account') {
                $this->saveAccountData($requestData, $index, $paymentId);
            }
        }
    }

    private function savePartyData(array $requestData, $index, int $paymentId)
    {
        $makePayment = MakePaymentItems::create([
            'make_payment_id' => $paymentId,
            'particular' => $requestData['particular'][$index],
            'particular_id' => $requestData['particularId'][$index],
            'particular_name' => $requestData['particularName'][$index],
            'payment' => $requestData['payment'][$index],
            'remarks' => $requestData['remarks'][$index],
        ]);

        if ($requestData['party_name'] == 'Customer') {
            // Update Party Balance
            $updatePartyBalance = app(UpdatePartyBalance::class);
            $updatePartyBalance->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'increment');
        } else {

            $updatePartyBalance = app(UpdatePartyBalance::class);
            $updatePartyBalance->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'decrement');
        }

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'payment_id', 'received', $makePayment->id, $requestData['remarks'][$index], date: $requestData['date']);
    }

    private function saveAccountData(array $requestData, $index, int $paymentId)
    {
        $makePayment = MakePaymentItems::create([
            'make_payment_id' => $paymentId,
            'particular' => $requestData['particular'][$index],
            'particular_id' => $requestData['particularId'][$index],
            'particular_name' => $requestData['particularName'][$index],
            'payment' => $requestData['payment'][$index],
            'remarks' => $requestData['remarks'][$index],
        ]);

        // Update Account Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'increment');

        // Insert Account Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'received', 'sub_payment_id', $makePayment->id, $requestData['remarks'][$index], date: $requestData['date']);
    }


    public function alert()
    {
        // Fetch the purchases and their count
        $purchases = Purchase::with('supplier')
            ->where('payment_type', 'credit')
            ->where('due_date', '>', Carbon::today()->addDays(2))
            ->whereHas('supplier', function ($query) {
                $query->where('balance', '>', DB::raw('purchases.supplier_balance'));
            })
            ->get();

        $purchaseCount = $purchases->count();

        // Share the variable globally
        // view()->share('purchaseCount', $purchaseCount);

        return view('adminPanel.accounts.payment_alert', compact('purchases', 'purchaseCount'));
    }


    public function alertReceving()
    {
        // Fetch the purchases and their count
        $getSaleInvoice = SaleInvoice::with('party')
            ->where('payment_type', 'credit')
            ->where('due_date', '>', Carbon::today()->addDays(2)) // Due date must be greater than today + 2 days
            ->whereHas('party', function ($query) {
                $query->where('balance', '>', DB::raw('sale_invoices.customer_reciveable')); // Balance condition
            })
            ->get();
        // dd($getSaleInvoice);

        $saleCount = $getSaleInvoice->count();

        // Share the variable globally
        // view()->share('purchaseCount', $purchaseCount);

        return view('adminPanel.accounts.receving_alert', compact('getSaleInvoice', 'saleCount'));
    }
}
