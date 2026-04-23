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
                    <h4 class="page-title">Add New User</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Create User Profile
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ URL::to('/users-list') }}" class="btn btn-secondary">
                                        Back to List
                                    </a>
                                </div>
                            </div>
                        </div>

                        <form action="{{ URL::to('/add-user') }}" method="post">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">User Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="User Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                        @error('password')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                                        @error('password_confirmation')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4 class="mt-3">User Permissions</h4>
                            <div class="row mt-3">
                                @php
                                    $permissions = [
                                        'General' => [
                                            'general.category' => 'Category',
                                            'general.location' => 'Location',
                                            'general.measuring_unit' => 'Measuring Unit',
                                            'general.manage_tables' => 'Manage Tables',
                                            'general.manage_deals' => 'Manage Deals',
                                            'general.manage_recipes' => 'Manage Recipes',
                                        ],
                                        'Product' => [
                                            'product.create_product' => 'Create Product',
                                            'product.product' => 'Product',
                                            'product.product_variant' => 'Product Variant',
                                            'product.trashed' => 'Trashed Products',
                                            'product.print_variants_barcode' => 'Print Barcode',
                                            'product.low_stock_list' => 'Low Stock List',
                                        ],
                                        'Stock' => [
                                            'stock_management.stock_adjustment' => 'Stock Adjustment',
                                            'stock_management.transfer_stock' => 'Transfer Stock',
                                            'stock_management.transfer_stock_list' => 'Transfer List',
                                            'stock_management.trashed_transfer_stock_list' => 'Trashed Transfer',
                                            'stock_management.purchase' => 'Purchase',
                                            'stock_management.purchase_return' => 'Purchase Return',
                                            'stock_management.purchase_list' => 'Purchase List',
                                        ],
                                        'Sale' => [
                                            'sale.sale' => 'Sale',
                                            'sale.sale_list' => 'Sale List',
                                            'sale.delete_hold_invoice' => 'Delete Hold Invoice',
                                            'sale.delete_quotation' => 'Delete Quotation',
                                            'party' => 'Party',
                                        ],
                                        'Accounts' => [
                                            'accounts.accounts_list' => 'Accounts List',
                                            'accounts.payments_receiving' => 'Payments & Receiving',
                                            'accounts.capital_management' => 'Capital Management',
                                            'accounts.balance_sheet' => 'Balance Sheet',
                                            'accounts.trial_balance_sheet' => 'Trial Balance',
                                            'accounts.date_wise_profit_margin' => 'Profit Margin',
                                        ],
                                        'Expense' => [
                                            'expense.expense_list' => 'Expense List',
                                            'expense.categories' => 'Categories',
                                            'expense.sub_categories' => 'Sub Categories',
                                        ],
                                        'User Management' => [
                                            'user_management.user_list' => 'User List',
                                            'user_management.employees' => 'Employees',
                                            'user_management.trashed_employees' => 'Trashed Employees',
                                        ],
                                        'POS Closing' => [
                                            'pos_closing.today_invoices' => 'Today Invoices',
                                            'pos_closing.pos_closing' => 'POS Closing',
                                            'pos_closing.day_book' => 'Day Book',
                                            'pos_closing.datewise_day_book' => 'Datewise Day Book',
                                        ],
                                        'Reports' => [
                                            'reports.stock_report' => 'Stock Report',
                                            'reports.sale_report' => 'Sale Report',
                                            'reports.purchase_report' => 'Purchase Report',
                                            'reports.expanse_report' => 'Expense Report',
                                            'reports.payments_recv_report' => 'Payments Recv',
                                            'reports.ledgers_reports' => 'Ledgers Reports',
                                            'reports.summary_reports' => 'Summary Reports',
                                            'reports.party_statements' => 'Party Statements',
                                        ]
                                    ];
                                @endphp

                                @foreach($permissions as $group => $items)
                                    <div class="col-12 mt-3">
                                        <h5 class="text-primary">{{ $group }}</h5>
                                        <div class="row">
                                            @foreach($items as $value => $label)
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="form-check form-checkbox-success">
                                                        <input type="checkbox" class="form-check-input" name="userRight[]" 
                                                               value="{{ $value }}" id="perm_{{ Str::slug($value) }}">
                                                        <label class="form-check-label" for="perm_{{ Str::slug($value) }}">{{ $label }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 text-end">
                                <button type="reset" class="btn btn-light me-2">Reset</button>
                                <button type="submit" class="btn btn-primary">Create User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
