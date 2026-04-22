@extends('adminPanel/master')

@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <h4 class="page-title">User</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class U="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Users List
                            </div>
                            <div class="col-sm-7 mt-2">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-modal">
                                        Add New
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rights</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($users)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @php
                                                        $userPermissions = $user->getAllPermissions()->pluck('name');
                                                    @endphp
                                                    @foreach ($userPermissions as $permission)
                                                        {{ $permission }}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary edit-user"
                                                        data-bs-toggle="modal" data-bs-target="#user-modal"
                                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-rights="{{ implode(',', $userPermissions->toArray()) }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Edit User Modal -->
        <div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="user-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="user-modalLabel">Edit User</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form id="user-form" action="{{ URL::to('/update-user') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" id="user-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <!-- General Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.category" id="generalCategory">
                                                <label class="form-check-label" for="generalCategory">Category</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.location" id="generalLocation">
                                                <label class="form-check-label" for="generalLocation">Location</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.measuring_unit" id="generalMeasuringUnit">
                                                <label class="form-check-label" for="generalMeasuringUnit">Measuring
                                                    Unit</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.manage_tables" id="generalManageTables">
                                                <label class="form-check-label" for="generalManageTables">Manage
                                                    Tables</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.manage_deals" id="generalManageDeals">
                                                <label class="form-check-label" for="generalManageDeals">Manage
                                                    Deals</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="general.manage_recipes" id="generalManageRecipes">
                                                <label class="form-check-label" for="generalManageRecipes">Manage
                                                    Recipes</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.create_product" id="productCreateProduct">
                                                <label class="form-check-label" for="productCreateProduct">Create
                                                    Product</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.product" id="productProduct">
                                                <label class="form-check-label" for="productProduct">Product</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.product_variant" id="productVariant">
                                                <label class="form-check-label" for="productVariant">Product
                                                    Variant</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.trashed" id="productTrashed">
                                                <label class="form-check-label" for="productTrashed">Trashed
                                                    Products</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.print_variants_barcode" id="productPrintBarcode">
                                                <label class="form-check-label" for="productPrintBarcode">Print Variants
                                                    Barcode</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="product.low_stock_list" id="productLowStock">
                                                <label class="form-check-label" for="productLowStock">Low Stock
                                                    List</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Management Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.stock_adjustment" id="stockAdjustment">
                                                <label class="form-check-label" for="stockAdjustment">Stock
                                                    Adjustment</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.transfer_stock" id="transferStock">
                                                <label class="form-check-label" for="transferStock">Transfer Stock</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.transfer_stock_list" id="transferStockList">
                                                <label class="form-check-label" for="transferStockList">Transfer Stock
                                                    List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.trashed_transfer_stock_list"
                                                    id="trashedTransferStockList">
                                                <label class="form-check-label" for="trashedTransferStockList">Trashed
                                                    Transfer Stock List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.purchase" id="purchase">
                                                <label class="form-check-label" for="purchase">Purchase</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.purchase_return" id="purchaseReturn">
                                                <label class="form-check-label" for="purchaseReturn">Purchase
                                                    Return</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="stock_management.purchase_list" id="purchaseList">
                                                <label class="form-check-label" for="purchaseList">Purchase List</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sale Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="sale.sale" id="saleSale">
                                                <label class="form-check-label" for="saleSale">Sale</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="sale.sale_list" id="saleList">
                                                <label class="form-check-label" for="saleList">Sale List</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Party Permission -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="party" id="party">
                                                <label class="form-check-label" for="party">Party</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accounts Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.accounts_list" id="accountsList">
                                                <label class="form-check-label" for="accountsList">Accounts List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.payments_receiving" id="paymentsReceiving">
                                                <label class="form-check-label" for="paymentsReceiving">Payments &
                                                    Receiving</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.capital_management" id="capitalManagement">
                                                <label class="form-check-label" for="capitalManagement">Capital
                                                    Management</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.balance_sheet" id="balanceSheet">
                                                <label class="form-check-label" for="balanceSheet">Balance Sheet</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.trial_balance_sheet" id="trialBalanceSheet">
                                                <label class="form-check-label" for="trialBalanceSheet">Trial Balance
                                                    Sheet</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="accounts.date_wise_profit_margin" id="dateWiseProfitMargin">
                                                <label class="form-check-label" for="dateWiseProfitMargin">Date Wise
                                                    Profit Margin</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Expense Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="expense.expense_list" id="expenseList">
                                                <label class="form-check-label" for="expenseList">Expense List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="expense.categories" id="expenseCategories">
                                                <label class="form-check-label" for="expenseCategories">Categories</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="expense.sub_categories" id="expenseSubCategories">
                                                <label class="form-check-label" for="expenseSubCategories">Sub
                                                    Categories</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- User Management Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="user_management.user_list" id="userList">
                                                <label class="form-check-label" for="userList">User List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="user_management.employees" id="employees">
                                                <label class="form-check-label" for="employees">Employees</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="user_management.trashed_employees" id="trashedEmployees">
                                                <label class="form-check-label" for="trashedEmployees">Trashed
                                                    Employees</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- POS Closing Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="pos_closing.today_invoices" id="todayInvoices">
                                                <label class="form-check-label" for="todayInvoices">Today Invoices</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="pos_closing.pos_closing" id="posClosing">
                                                <label class="form-check-label" for="posClosing">POS Closing</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="pos_closing.day_book" id="dayBook">
                                                <label class="form-check-label" for="dayBook">Day Book</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="pos_closing.datewise_day_book" id="datewiseDayBook">
                                                <label class="form-check-label" for="datewiseDayBook">Datewise Day
                                                    Book</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reports Permissions -->
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.stock_report" id="stockReport">
                                                <label class="form-check-label" for="stockReport">Stock Report</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.sale_report" id="saleReport">
                                                <label class="form-check-label" for="saleReport">Sale Report</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input  name="userRight[]"
                                                    value="reports.purchase_report" id="purchaseReport">
                                                <label class="form-check-label" for="purchaseReport">Purchase
                                                    Report</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.expanse_report" id="expenseReport">
                                                <label class="form-check-label" for="expenseReport">Expense Report</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.payments_recv_report" id="paymentsRecvReport">
                                                <label class="form-check-label" for="paymentsRecvReport">Payments & Recv
                                                    Report</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.ledgers_reports" id="ledgersReports">
                                                <label class="form-check-label" for="ledgersReports">Ledgers
                                                    Reports</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.summary_reports" id="summaryReports">
                                                <label class="form-check-label" for="summaryReports">Summary
                                                    Reports</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3" style="margin-top:2rem;">
                                            <div class="form-check form-checkbox-success mb-2">
                                                <input type="checkbox" class="form-check-input" name="userRight[]"
                                                    value="reports.party_statements" id="partyStatements">
                                                <label class="form-check-label" for="partyStatements">Party
                                                    Statements</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add New User Modal -->
        <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="add-modalLabel">Add New User</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-user') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">User Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="User Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Password">
                                        @error('password')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Confirm Password">
                                        @error('password_confirmation')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <!-- General Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.category" id="addGeneralCategory">
                                                    <label class="form-check-label"
                                                        for="addGeneralCategory">Category</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.location" id="addGeneralLocation">
                                                    <label class="form-check-label"
                                                        for="addGeneralLocation">Location</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.measuring_unit" id="addGeneralMeasuringUnit">
                                                    <label class="form-check-label"
                                                        for="addGeneralMeasuringUnit">Measuring Unit</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.manage_tables" id="addGeneralManageTables">
                                                    <label class="form-check-label" for="addGeneralManageTables">Manage
                                                        Tables</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.manage_deals" id="generalManageDeals">
                                                    <label class="form-check-label" for="generalManageDeals">Manage
                                                        Deals</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="general.manage_recipes" id="addGeneralManageRecipes">
                                                    <label class="form-check-label" for="addGeneralManageRecipes">Manage
                                                        Recipes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Product Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.create_product" id="addProductCreateProduct">
                                                    <label class="form-check-label" for="addProductCreateProduct">Create
                                                        Product</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.product" id="addProductProduct">
                                                    <label class="form-check-label"
                                                        for="addProductProduct">Product</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.product_variant" id="addProductVariant">
                                                    <label class="form-check-label" for="addProductVariant">Product
                                                        Variant</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.trashed" id="addProductTrashed">
                                                    <label class="form-check-label" for="addProductTrashed">Trashed
                                                        Products</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.print_variants_barcode"
                                                        id="addProductPrintBarcode">
                                                    <label class="form-check-label" for="addProductPrintBarcode">Print
                                                        Variants Barcode</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="product.low_stock_list" id="addProductLowStock">
                                                    <label class="form-check-label" for="addProductLowStock">Low Stock
                                                        List</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stock Management Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.stock_adjustment" id="addStockAdjustment">
                                                    <label class="form-check-label" for="addStockAdjustment">Stock
                                                        Adjustment</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.transfer_stock" id="addTransferStock">
                                                    <label class="form-check-label" for="addTransferStock">Transfer
                                                        Stock</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.transfer_stock_list"
                                                        id="addTransferStockList">
                                                    <label class="form-check-label" for="addTransferStockList">Transfer
                                                        Stock List</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.trashed_transfer_stock_list"
                                                        id="addTrashedTransferStockList">
                                                    <label class="form-check-label"
                                                        for="addTrashedTransferStockList">Trashed Transfer Stock
                                                        List</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.purchase" id="addPurchase">
                                                    <label class="form-check-label" for="addPurchase">Purchase</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.purchase_return" id="addPurchaseReturn">
                                                    <label class="form-check-label" for="addPurchaseReturn">Purchase
                                                        Return</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="stock_management.purchase_list" id="addPurchaseList">
                                                    <label class="form-check-label" for="addPurchaseList">Purchase
                                                        List</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sale Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="sale.sale" id="addSaleSale">
                                                    <label class="form-check-label" for="addSaleSale">Sale</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="sale.sale_list" id="addSaleList">
                                                    <label class="form-check-label" for="addSaleList">Sale List</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Party Permission -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="party" id="addParty">
                                                    <label class="form-check-label" for="addParty">Party</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accounts Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.accounts_list" id="addAccountsList">
                                                    <label class="form-check-label" for="addAccountsList">Accounts
                                                        List</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.payments_receiving" id="addPaymentsReceiving">
                                                    <label class="form-check-label" for="addPaymentsReceiving">Payments &
                                                        Receiving</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.capital_management" id="addCapitalManagement">
                                                    <label class="form-check-label" for="addCapitalManagement">Capital
                                                        Management</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.balance_sheet" id="addBalanceSheet">
                                                    <label class="form-check-label" for="addBalanceSheet">Balance
                                                        Sheet</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.trial_balance_sheet" id="addTrialBalanceSheet">
                                                    <label class="form-check-label" for="addTrialBalanceSheet">Trial
                                                        Balance Sheet</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="accounts.date_wise_profit_margin"
                                                        id="addDateWiseProfitMargin">
                                                    <label class="form-check-label" for="addDateWiseProfitMargin">Date
                                                        Wise Profit Margin</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Expense Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="expense.expense_list" id="addExpenseList">
                                                    <label class="form-check-label" for="addExpenseList">Expense
                                                        List</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="expense.categories" id="addExpenseCategories">
                                                    <label class="form-check-label"
                                                        for="addExpenseCategories">Categories</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="expense.sub_categories" id="addExpenseSubCategories">
                                                    <label class="form-check-label" for="addExpenseSubCategories">Sub
                                                        Categories</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- User Management Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="user_management.user_list" id="addUserList">
                                                    <label class="form-check-label" for="addUserList">User List</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="user_management.employees" id="addEmployees">
                                                    <label class="form-check-label" for="addEmployees">Employees</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="user_management.trashed_employees"
                                                        id="addTrashedEmployees">
                                                    <label class="form-check-label" for="addTrashedEmployees">Trashed
                                                        Employees</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- POS Closing Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="pos_closing.today_invoices" id="addTodayInvoices">
                                                    <label class="form-check-label" for="addTodayInvoices">Today
                                                        Invoices</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="pos_closing.pos_closing" id="addPosClosing">
                                                    <label class="form-check-label" for="addPosClosing">POS
                                                        Closing</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="pos_closing.day_book" id="addDayBook">
                                                    <label class="form-check-label" for="addDayBook">Day Book</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="pos_closing.datewise_day_book" id="addDatewiseDayBook">
                                                    <label class="form-check-label" for="addDatewiseDayBook">Datewise
                                                        Day Book</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reports Permissions -->
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.stock_report" id="addStockReport">
                                                    <label class="form-check-label" for="addStockReport">Stock
                                                        Report</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.sale_report" id="addSaleReport">
                                                    <label class="form-check-label" for="addSaleReport">Sale
                                                        Report</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.purchase_report" id="addPurchaseReport">
                                                    <label class="form-check-label" for="addPurchaseReport">Purchase
                                                        Report</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.expanse_report" id="addExpenseReport">
                                                    <label class="form-check-label" for="addExpenseReport">Expense
                                                        Report</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.payments_recv_report" id="addPaymentsRecvReport">
                                                    <label class="form-check-label" for="addPaymentsRecvReport">Payments
                                                        & Recv Report</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.ledgers_reports" id="addLedgersReports">
                                                    <label class="form-check-label" for="addLedgersReports">Ledgers
                                                        Reports</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.summary_reports" id="addSummaryReports">
                                                    <label class="form-check-label" for="addSummaryReports">Summary
                                                        Reports</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3" style="margin-top:2rem;">
                                                <div class="form-check form-checkbox-success mb-2">
                                                    <input type="checkbox" class="form-check-input" name="userRight[]"
                                                        value="reports.party_statements" id="addPartyStatements">
                                                    <label class="form-check-label" for="addPartyStatements">Party
                                                        Statements</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script>
        $("#scroll-horizontal-datatable").DataTable({
            scrollX: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-user').forEach(function(button) {
                button.addEventListener('click', function() {
                    var userId = this.getAttribute('data-id');
                    var userName = this.getAttribute('data-name');
                    var userEmail = this.getAttribute('data-email');
                    var userRights = this.getAttribute('data-rights').split(',');

                    document.getElementById('user-id').value = userId;
                    document.getElementById('name').value = userName;
                    document.getElementById('email').value = userEmail;

                    document.querySelectorAll('input[name="userRight[]"]').forEach(function(
                        checkbox) {
                        checkbox.checked = userRights.includes(checkbox.value);
                    });
                });
            });
        });
    </script>
@endsection
