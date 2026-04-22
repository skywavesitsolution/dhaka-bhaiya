<?php

namespace App\Http\Controllers\Account;

use App\Actions\Account\SaveAccountLedger;
use App\Actions\Account\UpdateAccountBalance;
use App\Http\Controllers\Controller;
use App\Models\Account\CashDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashDepositController extends Controller
{

    public function addCashDesposit(
        Request $request,
        UpdateAccountBalance $updateAccountBalance,
        SaveAccountLedger $saveAccountLedger
    ) {
        $request->validate([
            'depositAmount' => 'required|numeric',
            'depositBy' => 'required|max:250',
            'accountId' => 'required|integer',
        ]);

        try {
            DB::transaction(function () use ($request, $updateAccountBalance, $saveAccountLedger) {
                // Save Purchase Data
                $cashDeposit = CashDeposit::create([
                    'deposit_amount' => $request->depositAmount,
                    'deposit_by' => $request->depositBy,
                    'account_id' => $request->accountId,
                    'user_id' => Auth::user()->id,
                ]);

                // Update Account Balance
                $updateAccountBalance->execute($request->accountId, $request->depositAmount, 'increment');

                // Insert Account Ledger
                $saveAccountLedger->execute($request->accountId, $request->depositAmount, 'received', 'deposit_id', $cashDeposit->id);
            });

            return redirect()->back()->with(['success' => 'Ingredient Purchased Successfully']);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }
}
