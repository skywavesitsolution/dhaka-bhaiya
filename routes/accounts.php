<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AccountReportsController;
use App\Http\Controllers\Account\CashDepositController;
use App\Http\Controllers\Account\ExpenseController;
use App\Http\Controllers\Account\MakePaymentController;
use App\Http\Controllers\Account\PaymentReportController;
use App\Http\Controllers\Account\ReceivedPaymentController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['can:accounts', 'auth'])->group(function () {
//     // Accounts 
//     Route::get('/get-all-types-account', [AccountController::class, 'fetchAllAcounts']);
//     Route::get('/account/{id}', [AccountController::class, 'getAccount']);
//     Route::get('/add-account', [AccountController::class, 'accountsList']);
//     Route::post('/add-account', [AccountController::class, 'addAccount']);
//     Route::post('/update-account', [AccountController::class, 'updateAccount'])->name('account.update');
//     Route::get('/balance-sheet', [AccountController::class, 'balanceSheet']);
//     Route::get('/show_profit_margin', [AccountController::class, 'show_profit_margin']);
//     Route::get('/TrialbalanceSheet', [AccountController::class, 'trialbalanceSheet']);


//     // Cash Deposit
//     Route::post('/add-cash-deposit', [CashDepositController::class, 'addCashDesposit']);
//     Route::get('/cashdeposit_data/{id}', [CashDepositController::class, 'cashdeposit_print'])->name('cashdeposit_print');

//     // Payments
//     Route::get('/add-make-payment', [MakePaymentController::class, 'getPaymentAddPage']);
//     Route::post('/add-make-payment', [MakePaymentController::class, 'addMakePayment']);
//     Route::get('/make-payment-list', [MakePaymentController::class, 'paymentsList']);
//     Route::get('/view-payment-details/{id}', [MakePaymentController::class, 'viewPaymentDetails']);
//     Route::get('/fetch-payable', [MakePaymentController::class, 'alert']);
//     Route::get('/fetch-recevable', [MakePaymentController::class, 'alertReceving']);


//     // Received Payments
//     Route::post('/add-received-payment', [ReceivedPaymentController::class, 'addReceivedPayment']);
//     Route::get('/receive-payment-list', [ReceivedPaymentController::class, 'receivePaymentsList']);
//     Route::get('/view-receive-payment-details/{id}', [ReceivedPaymentController::class, 'viewReceivePaymentDetails']);
// });

// Route::middleware(['can:expense', 'auth'])->group(function () {
//     // Expense 
//     Route::get('/add-expense', [ExpenseController::class, 'create']);
//     Route::get('/expense-list', [ExpenseController::class, 'index']);
//     Route::post('/expense-sub', [ExpenseController::class, 'store']);
//     Route::get('/expense-categories', [ExpenseController::class, 'expense_categories']);
//     Route::post('/expense-cat-submit', [ExpenseController::class, 'storeCategory']);
//     Route::get('/expense-sub-categories', [ExpenseController::class, 'expense_sub_categories']);
//     Route::post('/expense-sub-cat-submit', [ExpenseController::class, 'expense_sub_cat_submit']);
//     Route::post('/fetch_sub_category', [ExpenseController::class, 'fetch_sub_category']);
//     Route::get('/expense_print/{id}', [ExpenseController::class, 'expense_print']);
//     Route::post('/expense-cat-update', [ExpenseController::class, 'update']);
//     Route::post('/expense-sub-cat-update', [ExpenseController::class, 'sub_cat_update']);
// });

// Route::middleware(['can:reports', 'can:expense-reports', 'auth'])->group(function () {
//     // Expense Reports

//     Route::get('/day-book', [ExpenseController::class, 'day_book']);
//     Route::post('/day-book', [ExpenseController::class, 'day_book_sub']);
//     Route::get('/expense-reports', [ExpenseController::class, 'expense_reports']);
//     Route::post('/category-wise-expense', [ExpenseController::class, 'category_wise_expense']);
//     Route::post('/sub-category-wise-expense', [ExpenseController::class, 'sub_category_wise_expense']);
//     Route::get('/print-all-expense', [ExpenseController::class, 'print_all_expense']);
//     Route::post('/date-wise-expense', [ExpenseController::class, 'date_wise_expense']);
//     Route::post('/cash-account-wise-expense', [ExpenseController::class, 'cash_account_wise_expense']);
// });
// Route::middleware(['can:reports', 'can:payment-receiving-reports', 'auth'])->group(function () {
//     // Payments Report
//     Route::get('/payments-report', [PaymentReportController::class, 'paymentsReports']);
//     Route::post('/date-wise-payment', [PaymentReportController::class, 'dateWisePayment']);
//     Route::post('/date-wise-recveive-payments', [PaymentReportController::class, 'dateWiseReceivedPayment']);
// });

// Route::middleware(['can:reports', 'can:ledger', 'auth'])->group(function () {
//     // Ledger

//     Route::get('/reports-list', [AccountController::class, 'reports_list']);
//     Route::get('/ledger-reports', [AccountReportsController::class, 'ledgersReports']);
//     Route::post('/print-cash-account-ledeger', [AccountReportsController::class, 'cashAccountLedeger']);
//     Route::post('/date-wise-cash-account-ledeger', [AccountReportsController::class, 'dateWiseCashAccountLedeger']);
//     Route::post('/print-party-ledeger', [AccountReportsController::class, 'partyLedger']);
//     Route::post('/date-wise-party-ledeger', [AccountReportsController::class, 'dateWisePartyledeger']);
//     Route::post('/supplier-customer-list-print', [AccountReportsController::class, 'supplierCustomerList']);
//     Route::get('/payable-receivable', [AccountReportsController::class, 'payableAndReceivableReport']);
// });
