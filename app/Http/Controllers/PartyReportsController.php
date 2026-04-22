<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Account\AccountController;
use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use App\Models\Account\CashDeposit;
use App\Models\Account\MakePayment;
use App\Models\Account\MakePaymentItems;
use App\Models\Account\ReceivedPayment;
use App\Models\Account\ReceivedPaymentItems;
use App\Models\Order;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Purchase;
use App\Models\Sales\SaleInvoice;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PartyReportsController extends Controller
{
    public function partyReports()
    {
        $parties = Party::get();
        $suppliers = Party::where('type', 'Supplier')->get();
        // dd($suppliers);

        return view('adminPanel.party.partyReports.partyReports', ['suppliers' => $suppliers, 'parties' => $parties]);
    }

    public function partyWiseBalanceList(Request $request)
    {
        // dd($request);
        $id = $request->partyId;
        $type = $request->particularType;
        // dd($type);

        if ($id = 'Select all') {
            $parties = Party::where('type', $type)
                ->orderBy('name', 'asc')
                ->get();
            // dd($parties);
        } else {
            $parties = Party::where('id', $id)
                ->orderBy('name', 'asc')
                ->get();
        }



        return view('adminPanel.party.partyReports.partyBalanceList', ['parties' => $parties, 'type' => $type, 'request' => $request->all()]);
    }

    public function allCustomerBalanceList()
    {
        $parties = Party::where('type', 'Customer')
            ->orderBy('name', 'asc')
            ->get();

        return view('adminPanel.party.partyReports.customersBalanceList', ['parties' => $parties]);
    }

    public function partyLastTranscation(Request $request)
    {
        $parties = Party::where('type', 'Customer')->get();

        $lastEntries = PartyLedger::selectRaw('MAX(id) as id')
            ->where('date', '<', $request->date)
            ->where('party_type', $request->particular)
            ->groupBy('party_id')
            ->get();

        // dd($lastEntries);
        // Now fetch the complete records based on the last entry IDs
        $partiesTranscations = PartyLedger::whereIn('id', $lastEntries->pluck('id'))->get();
        // dd($partiesTranscations);
        $partyLastTransArr = [];
        foreach ($partiesTranscations as $index => $partyTranscation) {
            $party = Party::find($partyTranscation->party_id);
            $partyLastTransArr[] = (object) [
                'id' => $party->id,
                'name' => $party->name,
                'balance' => $party->balance,
                'lastTranscation' => $partyTranscation->date,
                'supplier' => $party->supplier->name ?? '',
            ];
        }


        $partyLastTransCollection = collect($partyLastTransArr)->sortByDesc('name');

        // If you need it back as an array
        $partyLastTransArr = $partyLastTransCollection->values()->all();

        return view('adminPanel.party.partyReports.partyLastTransaction', ['partyLastTransArr' => $partyLastTransArr, 'type' => $request->particular, 'from_date' => $request->date]);
    }

    public function partyStatements()
    {
        $parties = Party::get();
        $suppliers = Supplier::all();
        $accounts = AccountController::getAllAccounts();

        return view('adminPanel.party.partyReports.partyStatements', ['suppliers' => $suppliers, 'parties' => $parties, 'accounts' => $accounts]);
    }

    public function generatePartyStatement(Request $request)
    {
        // dd($request)

        $party = Party::find($request->partyId);
        if ($party->type == 'Supplier') {
            $orders = PartyLedger::with('purchase.purchase_details')->where('party_id', $request->partyId)->wherenotnull('purchase_id')->get();
            // dd($orders);
        }

        // if ($party->type == 'Driver') {
        //     $orders = Order::where('driver_id', $request->partyId)->get();
        // }

        if ($party->type == 'Customer') {
            $orders = PartyLedger::with('sale')->where('party_id', $request->partyId)->wherenotnull('sale_id')->get();
            // dd($orders);
        }

        $makePayments = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
            ->where('make_payment_items.particular_id', $request->partyId)
            ->where('make_payment_items.particular', 'Party')
            ->get(['make_payment_items.*', 'make_payments.*', 'make_payments.id as payment_id']);

        $receivedPayments = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
            ->where('received_payment_items.particular_id', $request->partyId)
            ->where('received_payment_items.particular', 'Party')
            ->get(['received_payment_items.*', 'received_payments.*', 'received_payments.id as received_id']);

        $ordersCollection = collect($orders);
        $makePaymentsCollection = collect($makePayments);
        $receivedPaymentsCollection = collect($receivedPayments);

        $mergedCollection = $ordersCollection->merge($makePaymentsCollection)->merge($receivedPaymentsCollection);

        // Sort the merged collection by the date column in ascending order
        $sortedCollection = $mergedCollection->sortBy('date')->values();

        // Find the next party ID
        $nextParty = Party::where('id', '>', $request->partyId)
            ->where('type', $party->type)
            ->orderBy('id')
            ->first();
        $nextPartyId = $nextParty ? $nextParty->id : null;

        // Find the previous party ID of the same type
        $previousParty = Party::where('id', '<', $request->partyId)
            ->where('type', $party->type)
            ->orderBy('id', 'desc')
            ->first();
        $previousPartyId = $previousParty ? $previousParty->id : null;

        // Convert the sorted collection to an array if needed
        $sortedArray = $sortedCollection;
        // dd($sortedArray);
        return view('adminPanel.party.partyReports.partyStatementPrint', compact('party', 'sortedArray', 'nextPartyId', 'previousPartyId'));
    }


    // public function generatePartyStatement(Request $request)
    // {
    //     // Retrieve party data
    //     $party = Party::find($request->partyId);

    //     // Initialize the necessary collections based on party type
    //     $orders = collect();
    //     if ($party->type == 'Supplier') {
    //         $orders = PartyLedger::with('purchase.purchase_details')->where('party_id', $request->partyId)->get();
    //     } elseif ($party->type == 'Customer') {
    //         $orders = PartyLedger::with('sale')->where('party_id', $request->partyId)->get();
    //     }

    //     // Payments and receipts
    //     $makePayments = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
    //     ->where('make_payment_items.particular_id', $request->partyId)
    //         ->where('make_payment_items.particular', 'Party')
    //         ->get();

    //     $receivedPayments = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
    //     ->where('received_payment_items.particular_id', $request->partyId)
    //         ->where('received_payment_items.particular', 'Party')
    //         ->get();

    //         // dd($receivedPayments);

    //     // Merge all data into a single collection and sort by date
    //     $mergedCollection = $orders->merge($makePayments)->merge($receivedPayments)->sortBy('date')->values();

    //     // Fetch the next and previous party IDs
    //     $nextPartyId = Party::where('id', '>', $request->partyId)
    //         ->where('type', $party->type)
    //         ->orderBy('id')
    //         ->first()->id ?? null;

    //     $previousPartyId = Party::where('id', '<', $request->partyId)
    //         ->where('type', $party->type)
    //         ->orderBy('id', 'desc')
    //         ->first()->id ?? null;

    //     return view('adminPanel.party.partyReports.partyStatementPrint', compact('party', 'mergedCollection', 'nextPartyId', 'previousPartyId'));
    // }


    // public function generatePartyStatement(Request $request)
    // {
    //     // Retrieve party data
    //     $party = Party::find($request->partyId);
    //     // dd($party);

    //     // Initialize the necessary collections based on party type
    //     // $orders = collect();  // Empty collection to store orders (for debugging)

    //     // Debug the empty collection
    //     dd($orders);  // Check if it's empty at the start

    //     // Fetch Orders based on Party Type
    //     if ($party->type == 'Supplier') {
    //         // Fetch purchases for the Supplier from the purchase table
    //         $orders = Purchase::with('purchase_details')
    //         ->where('supplier_id', $request->partyId)  // Assuming supplier_id is used in purchase table
    //         ->get();

    //         // Debug: check if the orders are fetched correctly
    //         dd($orders);  // Check if the query returns data
    //     } elseif ($party->type == 'Customer') {
    //         // Fetch sales for the Customer from the sales table
    //         $orders = SaleInvoice::with('saleProduct')
    //         ->where('party_id', $request->partyId)  // Assuming party_id is used in sale table
    //         ->get();

    //         // Debug: check if the orders are fetched correctly
    //         dd($orders);  // Check if the query returns data
    //     }

    //     // Fetch Payments made to the party (MakePayments)
    //     $makePayments = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
    //     ->where('make_payment_items.particular_id', $request->partyId)
    //         ->where('make_payment_items.particular', 'Party')
    //         ->get();

    //     // Fetch Payments received from the party (ReceivedPayments)
    //     $receivedPayments = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
    //     ->where('received_payment_items.particular_id', $request->partyId)
    //         ->where('received_payment_items.particular', 'Party')
    //         ->get();

    //     // Merge all data into a single collection and sort by date
    //     $mergedCollection = $orders->merge($makePayments)->merge($receivedPayments);

    //     // Sort the merged collection by date
    //     $sortedCollection = $mergedCollection->sortBy('date')->values();

    //     // Fetch the next and previous party IDs
    //     $nextPartyId = Party::where('id', '>', $request->partyId)
    //         ->where('type', $party->type)
    //         ->orderBy('id')
    //         ->first()->id ?? null;

    //     $previousPartyId = Party::where('id', '<', $request->partyId)
    //         ->where('type', $party->type)
    //         ->orderBy('id', 'desc')
    //         ->first()->id ?? null;

    //     // Pass the sorted collection to the view
    //     return view('adminPanel.party.partyReports.partyStatementPrint', compact('party', 'sortedCollection', 'nextPartyId', 'previousPartyId'));
    // }



    public function generateAccountStatement(Request $request)
    {
        $account = Account::find($request->account_id);
        // dd($account);

        $makePayments = MakePayment::where('account_id', $request->account_id)
            ->select('make_payments.*', 'make_payments.id as main_make_payment_id')
            ->get();
        $receivedPayments = ReceivedPayment::where('account_id', $request->account_id)
            ->select('received_payments.*', 'received_payments.id as main_received_payment_id')
            ->get();

        $makePaymentItems = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
            ->where('make_payment_items.particular_id', $request->account_id)
            ->where('make_payment_items.particular', 'Account')
            ->get(['make_payment_items.*', 'make_payment_items.id as payment_item_id', 'make_payments.*', 'make_payments.id as payment_id']);

        $receivedPaymentsItems = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
            ->where('received_payment_items.particular_id', $request->account_id)
            ->where('received_payment_items.particular', 'Account')
            ->get(['received_payment_items.*', 'received_payment_items.id as received_item_id', 'received_payments.*', 'received_payments.id as received_id']);

        $cashDeposit = CashDeposit::where('account_id', $request->account_id)
            ->select('cash_deposits.*', 'cash_deposits.created_at as date')
            ->get();

        $sales = AccountLedger::where('account_id', $request->account_id)
            ->whereNotNull('sale_id')  // Filter records where sale_id is present
            ->select('account_ledgers.*', 'account_ledgers.created_at as date')
            ->get();

        $purchases = AccountLedger::where('account_id', $request->account_id)
            ->whereNotNull('purchase_id')  // Filter records where sale_id is present
            ->select('account_ledgers.*', 'account_ledgers.created_at as date')
            ->get();

        $makePaymentsCollection = collect($makePayments);
        $receivedPaymentsCollection = collect($receivedPayments);
        $makePaymentItemsCollection = collect($makePaymentItems);
        $receivedPaymentsItemsCollection = collect($receivedPaymentsItems);
        $cashDepositCollection = collect($cashDeposit);
        $saleCollection = collect($sales);
        $purchaseCollection = collect($purchases);

        $mergedCollection = $makePaymentsCollection->merge($receivedPaymentsCollection)
            ->merge($makePaymentItemsCollection)
            ->merge($receivedPaymentsItemsCollection)
            ->merge($cashDepositCollection)
            ->merge($saleCollection)
            ->merge($purchaseCollection);

        // Sort the merged collection by the date column in ascending order
        $sortedCollection = $mergedCollection->sortBy('date')->values();
        // Find the next party ID
        $nextParty = Account::where('id', '>', $request->account_id)
            ->orderBy('id')
            ->first();
        $nextPartyId = $nextParty ? $nextParty->id : null;

        // Find the previous party ID of the same type
        $previousParty = Account::where('id', '<', $request->account_id)
            ->orderBy('id', 'desc')
            ->first();
        $previousPartyId = $previousParty ? $previousParty->id : null;

        // Convert the sorted collection to an array if needed
        $sortedArray = $sortedCollection;
        // dd($sortedArray);

        return view('adminPanel.accounts.accountReports.accountStatementPrint', compact('account', 'sortedArray', 'previousPartyId', 'nextPartyId'));
    }

    public function generateAccountStatementDateWise(Request $request)
    {
        $account = Account::find($request->account_id);
        $ledgerLastTranscation = AccountLedger::where('account_id', $request->account_id)
            ->whereDate('date', '<', $request->start_date)
            ->latest()->first();

        $makePayments = MakePayment::where('account_id', $request->account_id)
            ->whereBetween('make_payments.date', [$request->start_date, $request->end_date])
            ->select('make_payments.*', 'make_payments.id as main_make_payment_id')
            ->get();
        $receivedPayments = ReceivedPayment::where('account_id', $request->account_id)
            ->whereBetween('received_payments.date', [$request->start_date, $request->end_date])
            ->select('received_payments.*', 'received_payments.id as main_received_payment_id')
            ->get();

        $makePaymentItems = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
            ->where('make_payment_items.particular_id', $request->account_id)
            ->where('make_payment_items.particular', 'Account')
            ->whereBetween('make_payments.date', [$request->start_date, $request->end_date])
            ->get(['make_payment_items.*', 'make_payment_items.id as payment_item_id', 'make_payments.*', 'make_payments.id as payment_id']);

        $receivedPaymentsItems = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
            ->where('received_payment_items.particular_id', $request->account_id)
            ->where('received_payment_items.particular', 'Account')
            ->whereBetween('received_payments.date', [$request->start_date, $request->end_date])
            ->get(['received_payment_items.*', 'received_payment_items.id as received_item_id', 'received_payments.*', 'received_payments.id as received_id']);

        $cashDeposit = CashDeposit::where('account_id', $request->account_id)
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->select('cash_deposits.*', 'cash_deposits.created_at as date')
            ->get();

        $sales = AccountLedger::where('account_id', $request->account_id)
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->whereNotNull('sale_id')  // Filter records where sale_id is present
            ->select('account_ledgers.*', 'account_ledgers.created_at as date')
            ->get();

        $purchases = AccountLedger::where('account_id', $request->account_id)
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->whereNotNull('purchase_id')  // Filter records where sale_id is present
            ->select('account_ledgers.*', 'account_ledgers.created_at as date')
            ->get();

        $makePaymentsCollection = collect($makePayments);
        $receivedPaymentsCollection = collect($receivedPayments);
        $makePaymentItemsCollection = collect($makePaymentItems);
        $receivedPaymentsItemsCollection = collect($receivedPaymentsItems);
        $cashDepositCollection = collect($cashDeposit);
        $saleCollection = collect($sales);
        $purchaseCollection = collect($purchases);

        $mergedCollection = $makePaymentsCollection->merge($receivedPaymentsCollection)
            ->merge($makePaymentItemsCollection)
            ->merge($receivedPaymentsItemsCollection)
            ->merge($cashDepositCollection)
            ->merge($saleCollection)
            ->merge($purchaseCollection);

        // Sort the merged collection by the date column in ascending order
        $sortedCollection = $mergedCollection->sortBy('date')->values();

        // Convert the sorted collection to an array if needed
        $sortedArray = $sortedCollection;
        $request_data = $request->all();

        // Find the previous party based on the given criteria
        $previousParty = Account::where('id', '<', $request->account_id)
            ->whereBetween('created_at', [$request->start_date, $request->end_date]) // Assuming created_at is the date column
            ->orderBy('id', 'desc')
            ->first();

        // Find the next party based on the given criteria
        $nextParty = Account::where('id', '>', $request->account_id)
            ->whereBetween('created_at', [$request->start_date, $request->end_date]) // Assuming created_at is the date column
            ->orderBy('id')
            ->first();

        // Get the previous and next party IDs
        $previousPartyId = $previousParty ? $previousParty->id : null;
        $nextPartyId = $nextParty ? $nextParty->id : null;

        return view('adminPanel.accounts.accountReports.accountStatementDateWise', compact('account', 'sortedArray', 'request_data', 'ledgerLastTranscation', 'previousPartyId', 'nextPartyId'));
    }

    public function partyStatementDateWise(Request $request)
    {
        // dd($request);
        $party = Party::find($request->partyId);
        $ledgerLastTranscation = PartyLedger::where('party_id', $request->partyId)
            ->whereDate('date', '<', $request->start_date)
            ->latest()->first();

        // if ($party->type == 'Marka') {
        //     $orders = Order::where('marka_id', $request->partyId)
        //         ->whereBetween('date', [$request->start_date, $request->end_date])
        //         ->get();
        // }

        if ($party->type == 'Supplier_id') {
            $orders = Order::where('party_id', $request->partyId)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->get();
        }

        if ($party->type == 'Customer') {
            $orders = PartyLedger::where('party_id', $request->partyId)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->get();
        }

        $makePayments = MakePaymentItems::join('make_payments', 'make_payment_items.make_payment_id', '=', 'make_payments.id')
            ->where('make_payment_items.particular_id', $request->partyId)
            ->where('make_payment_items.particular', 'Party')
            ->whereBetween('make_payments.date', [$request->start_date, $request->end_date])
            ->get(['make_payment_items.*', 'make_payments.*', 'make_payments.id as payment_id']);

        $receivedPayments = ReceivedPaymentItems::join('received_payments', 'received_payment_items.received_payment_id', '=', 'received_payments.id')
            ->where('received_payment_items.particular_id', $request->partyId)
            ->where('received_payment_items.particular', 'Party')
            ->whereBetween('received_payments.date', [$request->start_date, $request->end_date])
            ->get(['received_payment_items.*', 'received_payments.*', 'received_payments.id as received_id']);

        $ordersCollection = collect($orders);
        $makePaymentsCollection = collect($makePayments);
        $receivedPaymentsCollection = collect($receivedPayments);

        $mergedCollection = $ordersCollection->merge($makePaymentsCollection)->merge($receivedPaymentsCollection);

        // Sort the merged collection by the date column in ascending order
        $sortedCollection = $mergedCollection->sortBy('date')->values();

        $previousParty = Party::where('type', $party->type)
            ->where('id', '<', $request->partyId)
            ->whereDate('created_at', '<=', $request->end_date) // Corrected to match the field name
            ->orderBy('id', 'desc')
            ->first();

        $nextParty = Party::where('type', $party->type)
            ->where('id', '>', $request->partyId)
            ->whereDate('created_at', '>=', $request->start_date) // Corrected to match the field name
            ->orderBy('id')
            ->first();


        // Get the previous and next party IDs
        $previousPartyId = $previousParty ? $previousParty->id : null;
        $nextPartyId = $nextParty ? $nextParty->id : null;

        // Convert the sorted collection to an array if needed
        $sortedArray = $sortedCollection;
        $request = $request->all();
        return view('adminPanel.party.partyReports.partyStatementDateWise', compact('party', 'sortedArray', 'request', 'ledgerLastTranscation', 'previousPartyId', 'nextPartyId'));
    }
}
