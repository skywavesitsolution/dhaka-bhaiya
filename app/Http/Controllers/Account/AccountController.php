<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Models\Account\Account;
use Illuminate\Validation\Rule;
use App\Models\Account\CashDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PartyController;
use App\Models\Account\AccountLedger;
use App\Models\Account\Addcapital;
use App\Models\Account\capital;
use App\Models\Account\expense;
use App\Models\Account\Withdrawal;
use App\Models\Party;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Sales\SaleInvoice;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    static public function getAllAccounts()
    {
        return Account::all();
    }

    public function accountsList()
    {
        $accounts = Account::all();
        $cashdeposits = CashDeposit::all();
        return view('adminPanel.accounts.addAccounts', ['accounts' => $accounts, 'cashdeposits' => $cashdeposits]);
    }

    public function getAccount($id)
    {
        $account = Account::find($id);
        return response()->json(['data' => $account]);
    }

    public function fetchAllAcounts()
    {
        $account = $this->getAllAccounts();
        $parties = PartyController::getAllParties();
        return response()->json([
            'error' => false,
            'data' => [
                'accounts' => $account,
                'parties' => $parties
            ]
        ]);
    }


    public function addAccount(Request $request)
    {
        $request->validate([
            'account_name' => ['required', 'string', 'unique:accounts'],
            'openingBalance' => ['integer'],
            'accountNumber' => ['required', 'string'],
        ]);
        // dd($request);


        $result = Account::create([
            'account_name' => $request->account_name,
            'opening_balance' => $request->openingBalance,
            'balance' => $request->openingBalance,
            'account_number' => $request->accountNumber,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Account Added Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function updateAccount(Request $request)
    {

        $account = Account::find($request->accountId);

        $request->validate([
            'accountId' => 'required',
        ]);

        $result = $account->update([
            'account_name' => $request->account_name,
            'account_number' => $request->accountNumber,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Account Updated Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function index()
    {

        $accounts = Account::get();
        $capitals = Addcapital::with('account', 'capital')->get();
        $capitalWithdrawals = Withdrawal::with('account', 'capital')->get();
        $totalCapital = Capital::orderBy('id', 'desc')->first();
        // dd($capitalWithdrawals);
        return view('adminPanel.accounts.add_capital', compact('accounts', 'capitals', 'capitalWithdrawals', 'totalCapital'));
    }

    public function show_profit_margin()
    {

        return view('adminPanel.accounts.accountReports.date_wise_profit_margin');
    }



    // Method to add new capital
    public function storeCapital(Request $request)
    {

        $currentcapital = capital::count();

        if ($currentcapital == 0) {
            $addCapital = Addcapital::create([
                'account_id' => $request->account_id,
                'capital_amount' => $request->capital_amount,
                'remarks' => $request->remarks,
                'user_id' => Auth::user()->id,
            ]);
            Capital::create([
                'deposite_id' => $addCapital->id,
                'withdrawal_id' => null,
                'current_capital' => $request->capital_amount,
                'user_id' => Auth::user()->id,
            ]);
        } else {
            $capital = Capital::orderBy('id', 'desc')->first();
            $updateCapital = $capital->current_capital + $request->capital_amount;
            $addCapital = Addcapital::create([
                'account_id' => $request->account_id,
                'capital_amount' => $request->capital_amount,
                'remarks' => $request->remarks,
                'user_id' => Auth::user()->id,
            ]);
            Capital::create([
                'deposite_id' => $addCapital->id,
                'withdrawal_id' => null,
                'current_capital' => $updateCapital,
                'user_id' => Auth::user()->id,
            ]);
        }

        $account = Account::findOrFail($request->account_id);
        $account->balance += $request->capital_amount;
        $account->save();

        $account_ledger = AccountLedger::create([
            'date' => now(),
            'account_id' => $account->id,
            'deposit_id' => $addCapital->id,
            'received' => $request->capital_amount,
            'balance' => $account->balance,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->back()->with([
            'success' => 'Capital added successfully',
            'account_balance' => $account->balance
        ]);
    }




    public function storeWithdrawal(Request $request)
    {
        // dd($request);
        try {
            // Fetch the account from which the withdrawal is being made
            $account = Account::find($request->account_id);

            // Ensure the account exists
            if (!$account) {
                return redirect()->back()->with(['error' => 'Account not found']);
            }

            // Check if the account has sufficient balance
            if ($account->balance < $request->withdraw_amount) {
                return redirect()->back()->with(['error' => 'Insufficient balance']);
            }

            // Fetch the current total capital for the specific account
            $currentTotalCapital = Capital::orderBy('id', 'desc')->first();;

            // dd($currentTotalCapital);

            $crcap = $currentTotalCapital->current_capital;
            // Calculate the updated capital balance after withdrawal
            $updatedCurrentBalance = $crcap - $request->withdraw_amount;

            // Create a new withdrawal entry
            $withdrawal = Withdrawal::create([
                'account_id' => $request->account_id,
                'account_id_to' => $request->receiving_account_id,  // Corrected the variable name
                'withdrawal_amount' => $request->withdraw_amount,  // Corrected the variable name
                'remarks' => $request->remarks,
                'user_id' => Auth::user()->id,
            ]);

            // Create a new capital entry to reflect the change in capital balance
            Capital::create([
                'withdrawal_id' => $withdrawal->id,
                'current_capital' => $updatedCurrentBalance,  // Negative amount as it's a withdrawal
                'remarks' => 'Capital withdrawal',  // You can customize this message
                'user_id' => Auth::user()->id,
            ]);

            // Update the balance of the account from which the withdrawal is made
            $account->balance -= $request->withdraw_amount;
            $account->save();

            $account_ledger = AccountLedger::create([
                'date' => now(),
                'account_id' => $account->id,
                'payment_id' => $withdrawal->id,
                'payment' => $request->withdraw_amount,
                'balance' => $account->balance,
                'user_id' => Auth::user()->id,
            ]);

            // Fetch the receiving account and update its balance
            $receiving_account = Account::find($request->receiving_account_id);
            if ($receiving_account) {
                $receiving_account->balance += $request->withdraw_amount;
                $receiving_account->save();

                $account_ledger = AccountLedger::create([
                    'date' => now(),
                    'account_id' => $receiving_account->id,
                    'deposit_id' => $withdrawal->id,
                    'received' => $request->withdraw_amount,
                    'balance' => $receiving_account->balance,
                    'user_id' => Auth::user()->id,
                ]);
            } else {
                return redirect()->back()->with(['error' => 'Receiving account not found']);
            }

            // Check if the withdrawal was created successfully
            if ($withdrawal) {
                // Return the success response with updated details
                return redirect()->back()->with([
                    'success' => 'Withdrawal successful',
                    'new_total_capital' => $updatedCurrentBalance,  // Display the updated total capital
                ]);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function assetsPageView()
    {

        $accounts = Account::get();
        $capitals = Addcapital::with('account', 'capital')->get();
        $capitalWithdrawals = Withdrawal::with('account', 'capital')->get();
        // dd($capitalWithdrawals);
        return view('adminPanel.accounts.assets_managment', compact('accounts', 'capitals', 'capitalWithdrawals'));
    }


    public function balanceSheet()
    {
        $inventory = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id')
            ->where('product_variants.is_fixed_asset', '0')
            ->select(
                DB::raw('SUM(product_variant_stocks.stock * product_variant_rates.cost_price) as total_inventory')
            )
            ->value('total_inventory') ?? 0;

        // dd($inventory);

        $fix_assets = ProductVariant::with('rates', 'stock')->where('is_fixed_asset', '1')->get();

        // $fix_asset_amount = $fix_assets->sum();


        $fix_asset_amount = ProductVariant::with('rates', 'stock')->where('is_fixed_asset', '1')->get()->sum(function ($variant) {
            // Ensure that you are getting the correct cost_price from the rates relationship
            // Check if 'rates' is not empty and we have a valid cost_price
            $costPrice = $variant->rates->first()->cost_price ?? 0;  // Assuming 'rates' is a relationship
            // Ensure stock quantity is correct; if it's a direct attribute of ProductVariant, fetch it
            $stockQuantity = $variant->stock->stock ?? 0;  // Assuming stock is related to ProductVariant

            // Return the total value of this variant (cost_price * stock quantity)
            return $costPrice * $stockQuantity;
        });

        // dd($fix_assets);
        // cash in systrm

        $cash = Account::get()->pluck('balance');
        $totalcash = $cash->sum();
        // dd($totalcash);

        $customerreceivable = Party::where('type', 'Customer')->where('balance', '>', 0)->get()->pluck('balance')->sum();
        $Supplierreceivable = Party::where('type', 'Supplier')->where('balance', '<', 0)->get()->pluck('balance')->sum();
        $account_receivable = $customerreceivable + $Supplierreceivable;

        $total_assets = $totalcash + $account_receivable + $inventory;
        $over_all_asessts = $fix_asset_amount + $total_assets;




        // assest side uper this

        $customerpayable = Party::where('type', 'Customer')->where('balance', '<', 0)->get()->pluck('balance')->sum();
        $Supplierpayable = Party::where('type', 'Supplier')->where('balance', '>', 0)->get()->pluck('balance')->sum();
        $account_payable = $customerpayable + $Supplierpayable;


        $capital = capital::get()->pluck('current_capital')->sum();
        // dd($capital);

        $total_equity = $account_payable + $capital;
        // dd($total_equity);


        // dd($Supplierpayable);


        // Example data for assets, liabilities, and equity
        $data = [
            'cashAndEquivalents' => $totalcash,
            'accountsReceivable' => $account_receivable,
            'inventory' => $inventory,
            'fix_assets' => $fix_assets,
            'totalCurrentAssets' => $total_assets,
            'fixAssets' => $fix_asset_amount,
            'intangibleAssets' => 15000,
            'longTermInvestments' => 20000,
            'totalNonCurrentAssets' => 135000,
            'overAlltotalAssets' => $over_all_asessts,
            'accountsPayable' => $account_payable,
            'shortTermLoans' => 5000,
            'accruedLiabilities' => 2000,
            'totalCurrentLiabilities' => 17000,
            'longTermLoans' => 50000,
            'bondsPayable' => 30000,
            'totalNonCurrentLiabilities' => 80000,
            'totalLiabilities' => 97000,
            'capitalInvested' => $capital,
            'retainedEarnings' => 43000,
            'totalEquity' => 143000,
            'totalLiabilitiesAndEquity' => $total_equity,
        ];

        return view('adminPanel.accounts.balanceSheet', $data);
    }
    public function TrialbalanceSheet()
    {
        $inventory = ProductVariant::with('rates', 'stock')->where('is_fixed_asset', '0')->get()->sum(function ($variant) {
            // Ensure that you are getting the correct cost_price from the rates relationship
            // Check if 'rates' is not empty and we have a valid cost_price
            $costPrice = $variant->rates->first()->cost_price ?? 0;  // Assuming 'rates' is a relationship
            // Ensure stock quantity is correct; if it's a direct attribute of ProductVariant, fetch it
            $stockQuantity = $variant->stock->stock ?? 0;  // Assuming stock is related to ProductVariant

            // Return the total value of this variant (cost_price * stock quantity)
            return $costPrice * $stockQuantity;
        });

        // dd($inventory);

        $fix_assets = ProductVariant::with('rates', 'stock')->where('is_fixed_asset', '1')->get();

        // $fix_asset_amount = $fix_assets->sum();


        $fix_asset_amount = ProductVariant::with('rates', 'stock')->where('is_fixed_asset', '1')->get()->sum(function ($variant) {
            // Ensure that you are getting the correct cost_price from the rates relationship
            // Check if 'rates' is not empty and we have a valid cost_price
            $costPrice = $variant->rates->first()->cost_price ?? 0;  // Assuming 'rates' is a relationship
            // Ensure stock quantity is correct; if it's a direct attribute of ProductVariant, fetch it
            $stockQuantity = $variant->stock->stock ?? 0;  // Assuming stock is related to ProductVariant

            // Return the total value of this variant (cost_price * stock quantity)
            return $costPrice * $stockQuantity;
        });

        // dd($fix_assets);
        // cash in systrm

        $cash = Account::get()->pluck('balance');
        $totalcash = $cash->sum();
        // dd($totalcash);

        $customerreceivable = Party::where('type', 'Customer')->where('balance', '>', 0)->get()->pluck('balance')->sum();
        $Supplierreceivable = Party::where('type', 'Supplier')->where('balance', '<', 0)->get()->pluck('balance')->sum();
        $account_receivable = $customerreceivable + $Supplierreceivable;

        $total_assets = $totalcash + $account_receivable + $inventory;
        $over_all_asessts = $fix_asset_amount + $total_assets;




        // assest side uper this

        $customerpayable = Party::where('type', 'Customer')->where('balance', '<', 0)->get()->pluck('balance')->sum();
        $Supplierpayable = Party::where('type', 'Supplier')->where('balance', '>', 0)->get()->pluck('balance')->sum();
        $account_payable = $customerpayable + $Supplierpayable;

        $expense = expense::get()->pluck('total_amount')->sum();
        $sale_revenue = SaleInvoice::get()->pluck('net_payable')->sum();


        // $capital = capital::get()->pluck('current_capital')->sum();
        $capital = 1000;

        $total_equity = $account_payable + $capital + $expense + $sale_revenue;
        // dd($capital);


        // dd($Supplierpayable);


        // Example data for assets, liabilities, and equity
        $data = [
            'cashAndEquivalents' => $totalcash,
            'accountsReceivable' => $account_receivable,
            'inventory' => $inventory,
            'fix_assets' => $fix_assets,
            'totalCurrentAssets' => $total_assets,
            'fixAssets' => $fix_asset_amount,
            'expense' => $expense,
            'sale_revenue' => $sale_revenue,
            'totalNonCurrentAssets' => 135000,
            'overAlltotalAssets' => $over_all_asessts,
            'accountsPayable' => $account_payable,
            'shortTermLoans' => 5000,
            'accruedLiabilities' => 2000,
            'totalCurrentLiabilities' => 17000,
            'longTermLoans' => 50000,
            'bondsPayable' => 30000,
            'totalNonCurrentLiabilities' => 80000,
            'totalLiabilities' => 97000,
            'capitalInvested' => $capital,
            'retainedEarnings' => 43000,
            'totalEquity' => 143000,
            'totalLiabilitiesAndEquity' => $total_equity,
        ];

        return view('adminPanel.accounts.trial_balance_Sheet', $data);
    }
}
