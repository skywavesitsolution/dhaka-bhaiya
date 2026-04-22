<?php

namespace App\Http\Controllers\Account;

use App\Actions\Account\SaveAccountLedger;
use App\Actions\Account\UpdateAccountBalance;
use App\Actions\SavePartyLedger;
use App\Actions\UpdatePartyBalance;
use App\Http\Controllers\Controller;
use App\Models\Account\ReceivedPayment;
use App\Models\Account\ReceivedPaymentItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceivedPaymentController extends Controller
{
    public function printPaymentVoucher(ReceivedPayment $receivedPayment)
    {
        $receivedPayment->load('paymentItems');
        return view('adminPanel.accounts.receivedPaymentPrint', ['payment' => $receivedPayment]);
    }

    public function receivePaymentsList()
    {
        $accounts = AccountController::getAllAccounts();
        $receivedPayments = ReceivedPayment::orderBy('id', 'desc')->paginate(10);
        return view('adminPanel.accounts.receivePaymentsList', ['receivedPayments' => $receivedPayments, 'accounts' => $accounts]);
    }

    public function getPaymentReceivedItem(ReceivedPaymentItems $receivedPaymentItem)
    {
        return response()->json([
            'error' => false,
            'data' => [
                'paymentItem' => $receivedPaymentItem
            ]
        ]);
    }

    public function updatePaymentReceivedItem(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        $request->validate([
            'payment_received_id' => ['required', 'exists:received_payment_items,id'],
            'party_id' => ['required', 'integer'],
            'total_payment' => ['required', 'numeric']
        ]);

        $receivedPaymentItem = ReceivedPaymentItems::find($request->payment_received_id);

        try {
            DB::transaction(function () use ($request, $receivedPaymentItem, $updateAccountBalance, $saveAccountLedger) {

                if ($receivedPaymentItem->particular == 'Party') {
                    $this->updatePartyData($request->all(), $receivedPaymentItem);
                }

                if ($receivedPaymentItem->particular == 'Account') {
                    $this->updateAccountData($request->all(), $receivedPaymentItem);
                }

                $paymentDifference = $request->total_payment - $receivedPaymentItem->payment;

                $receivedPaymentItem->update([
                    'payment' => $request->total_payment,
                    'remarks' => $request->remarks
                ]);

                $receivedPayment = ReceivedPayment::find($receivedPaymentItem->received_payment_id);
                // Update Account Balance
                $updateAccountBalance->execute($receivedPayment->account_id, $paymentDifference, 'increment');

                // Insert Account Ledger
                $saveAccountLedger->execute($receivedPayment->account_id, $paymentDifference, 'received', 'received_id', $receivedPayment->id, 'Payment Received Update', date: date('Y-m-d'));

                $result = $receivedPayment->update([
                    'total_payments' => $receivedPayment->total_payments + $paymentDifference
                ]);
            });

            return redirect()->back()->with(['success' => 'Payment Received Item Updated Successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    private function updatePartyData(array $requestData, ReceivedPaymentItems $receivedPaymentItem)
    {
        $paymentDifference = $requestData['total_payment'] - $receivedPaymentItem->payment;
        // Update Party Balance
        $updatePartyBalance = app(UpdatePartyBalance::class);
        $updatePartyBalance->execute($receivedPaymentItem->particular_id, $paymentDifference, 'increment');

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($receivedPaymentItem->particular_id, $paymentDifference, 'recevied_id', 'payment', $receivedPaymentItem->id, $requestData['remarks'] . ". Payment Received Update", date: date('Y-m-d'));
    }

    public function deletePaymentItem(ReceivedPaymentItems $paymentItem)
    {
        try {
            DB::transaction(function () use ($paymentItem) {
                if ($paymentItem->particular == 'Account') {
                    $this->adjustAccountItemBalance($paymentItem);
                } else {
                    $this->adjustPartyBalance($paymentItem);
                }
                $this->adjustAccountBalance($paymentItem);

                $makePayment = ReceivedPayment::find($paymentItem->received_payment_id);
                $makePayment->update([
                    'total_payments' => $makePayment->total_payments - $paymentItem->payment
                ]);

                $paymentItem->delete();
            });

            return redirect()->back()->with(['success' => 'Received Payment Item deleted Successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function adjustAccountItemBalance(ReceivedPaymentItems $paymentItem)
    {
        // Update Party Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($paymentItem->particular_id, $paymentItem->payment, 'increment');

        // Insert Party Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($paymentItem->particular_id, $paymentItem->payment, 'received', 'received_id', $paymentItem->id, 'Payment Recevied Deleted');
    }

    public function adjustPartyBalance(ReceivedPaymentItems $paymentItem)
    {
        // Update Party Balance
        $updatePartyBalance = app(UpdatePartyBalance::class);
        $updatePartyBalance->execute($paymentItem->particular_id, $paymentItem->payment, 'decrement');

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($paymentItem->particular_id, $paymentItem->payment, 'received_id', 'received', $paymentItem->id, 'Payment Recevied Deleted');
    }

    public function adjustAccountBalance(ReceivedPaymentItems $paymentItem)
    {
        $makePayment = ReceivedPayment::find($paymentItem->received_payment_id);
        // Update Account Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($makePayment->account_id, $paymentItem->payment, 'decrement');

        // Insert Account Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($makePayment->account_id, $paymentItem->payment, 'payment', 'sub_recevied_payment_id', $paymentItem->id, 'Received payment Item Deleted');
    }

    private function updateAccountData(array $requestData, ReceivedPaymentItems $receivedPaymentItem)
    {
        $paymentDifference = $requestData['total_payment'] - $receivedPaymentItem->payment;
        // Update Account Balance
        $updateAccountBalance = app(UpdateAccountBalance::class);
        $updateAccountBalance->execute($receivedPaymentItem->particular_id, $paymentDifference, 'decrement');

        // Insert Account Ledger
        $saveAccountLedger = app(SaveAccountLedger::class);
        $saveAccountLedger->execute($receivedPaymentItem->particular_id, $paymentDifference, 'payment', 'sub_recevied_payment_id', $receivedPaymentItem->id, $requestData['remarks'] . ". Payment Received Update", date: date('Y-m-d'));
    }

    public function fetchReceivedPayment(ReceivedPayment $payment)
    {
        return response()->json([
            'error' => false,
            'data' => [
                'received_payment' => $payment,
                'payment_date' => date('m-d-Y', strtotime($payment->date))
            ]
        ]);
    }

    public function viewReceivePaymentDetails($receivedId)
    {
        $receivedPaymentItems = ReceivedPaymentItems::where('received_payment_id', $receivedId)->get();
        return view('adminPanel.accounts.receivePaymentsListDetails', ['receivedPaymentItems' => $receivedPaymentItems]);
    }

    public function updatePayment(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        $request->validate([
            'payment_id' => ['required', 'exists:received_payments,id'],
            'accountId' => ['required', 'exists:received_payments,id'],
            'date' => ['required', 'date']
        ]);

        try {
            DB::transaction(function () use ($request, $updateAccountBalance, $saveAccountLedger) {
                $receivedPayment = ReceivedPayment::find($request->payment_id);

                if ($receivedPayment->account_id != $request->accountId) {
                    // Remove Amount From  Prvious Account
                    $updateAccountBalance->execute($receivedPayment->account_id, $receivedPayment->total_payments, 'decrement');

                    // Insert Account Ledger
                    $saveAccountLedger->execute($receivedPayment->account_id, $receivedPayment->total_payments, 'payment', 'received_id', $receivedPayment->id, remarks: 'Payment Updated', date: $request->date);

                    // add Amount to New Account
                    $updateAccountBalance->execute($request->accountId, $receivedPayment->total_payments, 'increment');

                    // Insert Account Ledger
                    $saveAccountLedger->execute($request->accountId, $receivedPayment->total_payments, 'received', 'received_id', $receivedPayment->id, remarks: 'Payment Updated', date: $request->date);
                }

                $receivedPayment->update([
                    'account_id' => $request->accountId,
                    'date' => $request->date,
                ]);
            });

            return redirect()->back()->with(['success' => 'Payment Received Updated Successfully']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function addReceivedPayment(
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
                $receivedPayment = ReceivedPayment::create([
                    'date' => $request->date,
                    'prev_balance' => $request->previousBalance,
                    'updated_balance' => $request->updatedBalance,
                    'total_payments' => $totalPayments,
                    'account_id' => $request->accountId,
                    'user_id' => Auth::user()->id,
                ]);
                // Update Account Balance
                $updateAccountBalance->execute($request->accountId, $totalPayments, 'increment');

                // Insert Account Ledger
                $saveAccountLedger->execute($request->accountId, $totalPayments, 'received', 'received_id', $receivedPayment->id, date: $request->date);

                // Save Payments Items
                $this->savePaymentsItems($request->all(), $receivedPayment->id);
            });

            return redirect()->back()->with(['success' => 'Payment Received Successfully']);
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
        $receivedPayment = ReceivedPaymentItems::create([
            'received_payment_id' => $paymentId,
            'particular' => $requestData['particular'][$index],
            'particular_id' => $requestData['particularId'][$index],
            'particular_name' => $requestData['particularName'][$index],
            'payment' => $requestData['payment'][$index],
            'remarks' => $requestData['remarks'][$index],
        ]);

        // Update Party Balance
        if ($requestData['party_name'] == 'Customer') {
            $updatePartyBalance = app(UpdatePartyBalance::class);
            $updatePartyBalance->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'decrement');
        } else {
            $updatePartyBalance = app(UpdatePartyBalance::class);
            $updatePartyBalance->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'increment');
        }

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'recevied_id', 'payment', $receivedPayment->id, $requestData['remarks'][$index], date: $requestData['date']);
    }

    private function saveAccountData(array $requestData, $index, int $paymentId)
    {
        $makePayment = ReceivedPaymentItems::create([
            'received_payment_id' => $paymentId,
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
        $saveAccountLedger->execute($requestData['particularId'][$index], $requestData['payment'][$index], 'payment', 'sub_recevied_payment_id', $makePayment->id, $requestData['remarks'][$index], date: $requestData['date']);
    }
}
