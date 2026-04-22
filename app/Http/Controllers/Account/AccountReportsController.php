<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountReportsController extends Controller
{
    public function ledgersReports()
    {
        $accounts = AccountController::getAllAccounts();

        $suppliers = Party::all();
        $allProducts = ProductVariant::with('measuringUnit')->get();
        // dd($suppliers);
        return view('adminPanel.accounts.accountReports.ledegersReports', compact('accounts', 'suppliers', 'allProducts'));
    }

    public function cashAccountLedeger(Request $request)
    {
        $cashAccountsdata = Account::find($request->account_id);
        $CashAccountsLedeger = AccountLedger::where('account_id', $request->account_id)->get();

        return view('adminPanel.accounts.accountReports.cashAccountLedeger', compact('CashAccountsLedeger', 'cashAccountsdata'));
    }

    public function dateWiseCashAccountLedeger(Request $request)
    {
        $cashAccountsdata = Account::find($request->account_id);
        $cashAccountsLedeger = AccountLedger::whereBetween('date', [$request->start_date, $request->end_date])
            ->where('account_id', $request->account_id)
            ->get();

        return view('adminPanel.accounts.accountReports.dateWiseCashAccLedeger', ['cashAccountsLedeger' => $cashAccountsLedeger, 'cashAccountsdata' => $cashAccountsdata, 'request' => $request->all()]);
    }

    public function partyLedger(Request $request)
    {
        $party = Party::find($request->partyId);
        $partyLedeger = PartyLedger::where('party_id', $request->partyId)->with('sale', 'purchase')->get();
        // dd($partyLedeger);
        return view('adminPanel.party.partyReports.partyLedger', compact('party', 'partyLedeger'));
    }

    public function dateWisePartyledeger(Request $request)
    {
        $party = Party::find($request->partyId);
        $partyLedeger = PartyLedger::whereBetween('date', [$request->start_date, $request->end_date])
            ->where('party_id', $request->partyId)
            ->get();

        return view('adminPanel.party.partyReports.partyLedgerDateWise', ['party' => $party, 'partyLedeger' => $partyLedeger, 'request' => $request->all()]);
    }

    public function supplierCustomerList(Request $request)
    {
        $parties = Party::where('supplier_id', $request->supplier_id)
            ->orderBy('name', 'asc')
            ->get();

        $supplier = Supplier::find($request->supplier_id);

        return view('adminPanel.party.partyReports.supplierCustomerList', ['parties' => $parties, 'supplier' => $supplier]);
    }

    // public function payableAndReceivableReport()
    // {

    //     $supplierReceivable = Party::where('type', 'Supplier')
    //         ->where('balance', '<', 0)
    //         ->select('supplier_id', DB::raw('SUM(balance) as total_balance'))
    //         ->groupBy('supplier_id')
    //         ->get();





    //         $supplierPayable = Party::where('type', 'Supplier')
    //         ->where('balance', '>', 0)
    //         ->select('supplier_id', DB::raw('SUM(balance) as total_balance'))
    //         ->groupBy('supplier_id')
    //         ->get();



    //         $customerReceivable = Party::where('type', 'Customer')
    //             ->where('balance', '>', 0)
    //             ->select('supplier_id', DB::raw('SUM(balance) as total_balance'))
    //             ->groupBy('supplier_id')
    //             ->get();


    //             $customerPayable = Party::where('type', 'Customer')
    //             ->where('balance', '<', 0)
    //             ->select('supplier_id', DB::raw('SUM(balance) as total_balance'))
    //             ->groupBy('supplier_id')
    //             ->get();

    //         $supplierAccounts = [];



    //     foreach ($supplierReceivable as $receivable) {
    //         $supplierAccounts[$receivable->supplier_id]['receivable'] = $receivable->total_balance;
    //     }

    //     foreach ($supplierPayable as $payable) {
    //         dd($payable);

    //         $supplierAccounts[$payable->supplier_id]['payable'] = $payable->total_balance;
    //     }

    //     foreach ($supplierAccounts as $index => $account) {
    //         $supplier = Party::find($index);
    //         $supplierAccounts[$index]['name'] = $supplier->name;
    //         if (!array_key_exists('receivable', $account)) {
    //             $supplierAccounts[$index]['receivable'] = 0;
    //         }
    //         if (!array_key_exists('payable', $account)) {
    //             $supplierAccounts[$index]['payable'] = 0;
    //         }
    //     }

    //     $supplierAccountsCollection = collect($supplierAccounts)->sortByDesc('name');

    //     // If you need it back as an array
    //     $supplierAccounts = $supplierAccountsCollection->values()->all();

    //     dd($supplierAccounts);

    //     $cashAccounts = Account::orderBy('account_name', 'asc')
    //         ->get();

    //     return view(
    //         'adminPanel.accounts.accountReports.payableAndReceivable',
    //         compact(
    //             // 'markaPayable',
    //             // 'markaReceivable',
    //             // 'driverPayable',
    //             // 'driverReceivable',
    //             'supplierAccounts',
    //             'cashAccounts'
    //         )
    //     );



    // }


    public function payableAndReceivableReport()
    {
        // Get all suppliers (with their payables and receivables)
        $suppliers = Party::where('type', 'Supplier')->get(['name', 'balance']);

        // Get all customers (with their payables and receivables)
        $customers = Party::where('type', 'Customer')->get(['name', 'balance']);

        // Create a total summary array for payables and receivables (summed up values)
        $totalSummary = [
            'total_supplier_receivable' => $suppliers->where('balance', '<', 0)->sum('balance'),
            'total_supplier_payable' => $suppliers->where('balance', '>', 0)->sum('balance'),
            'total_customer_receivable' => $customers->where('balance', '>', 0)->sum('balance'),
            'total_customer_payable' => $customers->where('balance', '<', 0)->sum('balance'),
        ];

        // dd($totalSummary);
        // Return the data to the view
        return view('adminPanel.accounts.accountReports.payableAndReceivable', compact('totalSummary', 'suppliers', 'customers'));
    }
}
