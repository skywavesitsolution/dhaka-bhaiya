<?php

namespace App\Http\Controllers\Account;

use App\Actions\Account\SaveAccountLedger;
use App\Actions\Account\UpdateAccountBalance;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use Illuminate\Http\Request;
use App\Models\Account\expense;
use App\Models\Account\expenseCategory;
use App\Models\Account\expenseSubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense_data = expense::orderBy("id", 'desc')->paginate(50);
        $allCategories = expenseCategory::all();
        $CashAccounts_data = AccountController::getAllAccounts();
        return view('adminPanel.expense.expense_list', compact('allCategories', 'expense_data', 'CashAccounts_data'));
    }

    public function day_book()
    {
        $date = date('Y-m-d');
        return view('adminPanel.expense.day_book');
    }

    public function day_book_sub(Request $request)
    {
        $day_book_data = AccountLedger::whereDate('created_at', $request->date)->get();;
        // print_r($day_book_data);
        $date = $request->date;
        return view('adminPanel.expense.day_book_display', compact('date', 'day_book_data'));
    }

    public function expense_print($id)
    {
        $expense_data = expense::find($id);
        return view('adminPanel.expense.expense_print', compact('expense_data'));
    }

    public function category_wise_expense(Request $request)
    {

        $category_data = expenseCategory::find($request->category_name);
        if ($request->report_type == 'date_wise') {
            $expense_data = DB::table('expenses')
                ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
                ->where('expenses.category_id', $request->category_name)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number')
                ->get();
        } else {
            $expense_data = DB::table('expenses')
                ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
                ->where('expenses.category_id', $request->category_name)
                ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number')
                ->get();
        }


        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.cateory_wise_expense', compact('expense_data', 'category_data', 'request'));
    }

    public function sub_category_wise_expense(Request $request)
    {
        $sub_category_data = expenseSubCategory::find($request->sub_category_id);

        if ($request->report_type == 'date_wise') {
            $expense_data = DB::table('expenses')
                ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
                ->where('expenses.sub_category_id', $request->sub_category_id)
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
                ->get();
        } else {
            $expense_data = DB::table('expenses')
                ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id') // joining the contacts table , where user_id and contact_user_id are same
                ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
                ->where('expenses.sub_category_id', $request->sub_category_id)
                ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
                ->get();
        }
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.sub_cateory_wise_expense', compact('expense_data', 'sub_category_data', 'request'));
    }

    public function date_wise_expense(Request $request)
    {

        $expense_data = DB::table('expenses')
            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.date_wise_expense', compact('expense_data', 'request'));
    }

    public function cash_account_wise_expense(Request $request)
    {

        $expense_data = DB::table('expenses')
            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
            ->where('account_id', $request->account_id)
            ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
            ->get();

        $account_data =  Account::find($request->account_id);
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.cash_account_wise_expense', compact('expense_data', 'request', 'account_data'));
    }



    public function print_all_expense(Request $request)
    {

        $expense_data = DB::table('expenses')
            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id') // joining the contacts table , where user_id and contact_user_id are same
            ->join('accounts', 'accounts.id', '=', 'expenses.account_id') // joining the contacts table , where user_id and contact_user_id are same
            ->select('expenses.*', 'expense_categories.exp_category_name', 'accounts.account_name', 'accounts.account_number', 'expense_sub_categories.exp_sub_category')
            ->orderBy("id", 'asc')
            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.print_all_expense', compact('expense_data'));
    }





    public function expense_reports()
    {
        $allCategories = expenseCategory::all();
        $CashAccountsdata = AccountController::getAllAccounts();
        return view('adminPanel.expense.expense_reports', compact('allCategories', 'CashAccountsdata'));
    }



    public function expense_categories()
    {
        $allCategories = expenseCategory::all();
        return view('adminPanel.expense.expense_cat_list', compact('allCategories'));
    }

    public function expense_sub_categories()
    {
        $allCategories = expenseCategory::all();
        $allSubCategories = expenseSubCategory::all();
        return view('adminPanel.expense.sub_cat_exp_list', compact('allSubCategories', 'allCategories'));
    }






    public function storeCategory(Request $request)
    {
        //
        $expenseCategory =  new expenseCategory;
        $expenseCategory->exp_category_name = $request->exp_category_name;
        $result = $expenseCategory->save();
        if ($result) {
            return redirect()->back()->with(['success' => 'Expense Category is Added Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
        // print_r($request->all());
    }

    public function expense_sub_cat_submit(Request $request)
    {
        //
        $expensesubCategory =  new expenseSubCategory;
        $expensesubCategory->exp_sub_category = $request->exp_sub_category;
        $expensesubCategory->category_id = $request->category_id;
        $expensesubCategory->user_id = Auth::user()->id;
        $result = $expensesubCategory->save();
        if ($result) {
            return redirect()->back()->with(['success' => 'Expense Sub Category is Added Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
        // print_r($request->all());
    }

    public function fetch_sub_category(Request $request)
    {
        $sub_categories = expenseSubCategory::where('category_id', $request->category_id)
            ->select('id', 'exp_sub_category')
            ->get();

        return $sub_categories;
        // print_r($location_socities);
        // echo "Socitites Function is call now ";
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'exp_name' => 'required',
            'total_amount' => 'required',
            'date' => 'required',
            'account_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        $expenseObj = new expense;
        $expenseObj->exp_name = $request->exp_name;
        $expenseObj->total_amount = $request->total_amount;
        $expenseObj->date = $request->date;
        $expenseObj->account_id = $request->account_id;
        $expenseObj->category_id = $request->category_id;
        $expenseObj->sub_category_id = $request->sub_category_id;
        $expenseObj->user_id = Auth::user()->id;

        try {
            DB::transaction(function () use ($expenseObj, $request) {
                $expenseObj->save();
                // Update Account Balance
                $updateAccountBalance = app(UpdateAccountBalance::class);
                $updateAccountBalance->execute($request->account_id, $request->total_amount, 'decrement');

                // Insert Account Ledger
                $saveAccountLedger = app(SaveAccountLedger::class);
                $saveAccountLedger->execute($request->account_id, $request->total_amount, 'payment', 'expense_id', $expenseObj->id);
            });
            return redirect('/expense-list')->with(['success' => 'Expense added Successfully']);
        } catch (\PDOException $e) {
            // Woopsy
            echo $e;
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // dd($request->all());
        $expenseCategory =  expenseCategory::find($request->category_id);
        $expenseCategory->exp_category_name = $request->category_name;
        $result = $expenseCategory->update();

        if ($result) {
            return redirect()->back()->with(['success' => 'Expense Category is Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function sub_cat_update(Request $req)
    {

        $result = DB::table('expense_sub_categories')->where('id', $req->exp_sub_id)->update([
            'exp_sub_category' => $req->exp_sub_category,
            'category_id' => $req->category_id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Expense Sub Category is Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
