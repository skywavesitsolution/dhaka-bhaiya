<?php

namespace App\Http\Controllers\Account;

use Carbon\Carbon;
use App\Models\SaleBatch;
use Illuminate\Http\Request;
use App\Models\Account\dayClosing;
use App\Models\Account\PosClosing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosClosingController extends Controller
{
    public function SavePosClosing(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|integer',
            'date' => 'required|date',
            'system_cash' => 'required|numeric',
            'customer_payments' => 'required|numeric',
            'total_cash' => 'required|numeric',
            'phisical_cash' => 'required|numeric',
            'differ_cash' => 'required|numeric',
            'cash_submited' => 'nullable|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                $activeBatch = SaleBatch::where('status', 'active')->first();
                if ($activeBatch) {
                    $rowsAffected = SaleBatch::where('id', $activeBatch->id)
                        ->where('status', 'active')
                        ->update(['status' => 'inactive']);

                    if ($rowsAffected === 0) {
                        throw new \Exception('Failed to deactivate SaleBatch.');
                    }
                }

                PosClosing::create([
                    'user_id' => $request->operator_id,
                    'report_date' => $request->date,
                    'cash_submited' => $request->cash_submited ?? null,
                    'system_cash' => $request->system_cash,
                    'customer_payments' => $request->customer_payments,
                    'total_cash' => $request->total_cash,
                    'phisical_cash' => $request->phisical_cash,
                    'differ_cash' => $request->differ_cash,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'POS closing saved successfully.'
                ]);
            });
        } catch (\Exception $e) {
            logger($e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function dayClosing()
    {
        // Get the physical cash for today
        $physicalCash = PosClosing::whereDate('created_at', Carbon::today())->value('phisical_cash');

        // Get the last closing balance (latest day closing record)
        $lastDayClosing = dayClosing::latest()->first();  // This fetches the most recent record

        // Optionally, you can get the closing_balance value from the last day closing
        $lastClosingBalance = $lastDayClosing ? $lastDayClosing->closing_balance : null;

        // Fetch all day closing data
        $dayClosingData = dayClosing::get();

        // Pass data to the view
        return view('adminPanel.day_closing', compact('physicalCash', 'dayClosingData', 'lastClosingBalance'));
    }


    public function dayClosingSave(Request $request)
    {
        // Check if a day closing record already exists for today
        $existingDayClosing = dayClosing::whereDate('created_at', now()->toDateString())->first();

        if ($existingDayClosing) {
            // Return an error message if a record already exists
            return redirect()->back()->with(['error' => 'Day Closing has already been done for today.']);
        }

        // Create a new day closing record
        $dayClosing = dayClosing::create([
            'user_id' => Auth::user()->id,
            'date' => now(),
            'opening_balance' => $request->opening_balance,
            'physical_balance' => $request->physical_balance,
            'expence' => $request->expence,
            'closing_balance' => $request->closing_balance
        ]);

        return redirect()->back()->with(['success' => 'POS Closing successfully']);
    }
}
