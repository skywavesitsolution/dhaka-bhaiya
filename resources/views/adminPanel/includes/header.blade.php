<?php

use Illuminate\Support\Facades\Auth;

$agent_data = Auth::user()->img;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | RMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $settingsData = \App\Models\Setting::pluck('value', 'key')->toArray();
        $appLogoSetting = \App\Models\Setting::where('key', 'app_logo')->first();
        $appFaviconUrl = $appLogoSetting && $appLogoSetting->hasMedia('logo') 
            ? asset($appLogoSetting->getFirstMediaUrl('logo')) 
            : asset('adminPanel/assets/images/favicon.ico');
        $companyName = $settingsData['company_name'] ?? 'TechPOS RMS';
    @endphp
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $appFaviconUrl }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <meta name="theme-color" content="#0d6efd">
    <link rel="icon" href="{{ $appFaviconUrl }}" type="image/png">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- third party css -->
    <link href="{{ asset('adminPanel/assets/css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{ asset('adminPanel/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adminPanel/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @yield('style')

    <!-- <link href="{{ asset('adminPanel/assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <style>
        .change_user:hover {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .topbar-menu {
                display: none;
            }
        }
    </style>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('✅ Service Worker registered:', reg))
                    .catch(err => console.error('❌ Service Worker error:', err));
            });
        }
    </script>

</head>

<body class="loading" data-layout-color="light" data-leftbar-theme="light" data-layout-mode="fluid"
    data-rightbar-onstart="true" data-leftbar-compact-mode="condensed" data-rightbar-onstart="true">
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <!-- <a href="{{ URL::to('/dashboard') }}" style="font-size: 1.3rem;color: #0acf97;"
                class="logo text-center logo-light">
                ERP
                <span class="logo-sm">
                    <img src="{{ asset('adminPanel/assets/images/logo_sm.png') }}" alt="" height="16">
                </span>
            </a> -->

            <!-- LOGO -->
            <a href="{{ URL::to('/dashboard') }}" class="logo text-center logo-dark">
                <span class="logo-lg">
                    <img src="{{ $appFaviconUrl }}" alt="" height="30">
                    <span style="font-weight: 700; color: #1e293b; margin-left: 5px;">{{ $companyName }}</span>
                </span>
                <span class="logo-sm">
                    <img src="{{ $appFaviconUrl }}" alt="" height="30">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>

                <!--- Sidemenu -->
                <ul class="side-nav">
                    <li class="side-nav-title side-nav-item">Navigation</li>

                    <!-- General Section -->
                    @canany(['general.category', 'general.location', 'general.measuring_unit', 'general.manage_tables',
                        'general.manage_deals', 'general.manage_recipes'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#general" aria-expanded="false" aria-controls="general"
                                class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <span> General </span>
                            </a>
                            <div class="collapse" id="general">
                                <ul class="side-nav-second-level">
                                    @can('general.category')
                                        <li>
                                            <a href="{{ URL::to('product-category') }}">Category</a>
                                        </li>
                                    @endcan
                                    @can('general.location')
                                        <li>
                                            <a href="{{ URL::to('product-location') }}">Location</a>
                                        </li>
                                    @endcan
                                    @can('general.measuring_unit')
                                        <li>
                                            <a href="{{ URL::to('measuring-unit') }}">Measuring Unit</a>
                                        </li>
                                    @endcan
                                    @can('general.manage_tables')
                                        <li>
                                            <a href="{{ URL::to('table') }}">Manage Tables</a>
                                        </li>
                                    @endcan
                                    @can('general.manage_deals')
                                        <li>
                                            <a href="{{ URL::to('deal') }}">Manage Deals</a>
                                        </li>
                                    @endcan
                                    @can('general.manage_recipes')
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('recipes.index') }}" role="button">
                                                Manage Recipes
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Product Section -->
                    @canany(['product.create_product', 'product.product', 'product.product_variant', 'product.trashed',
                        'product.print_variants_barcode', 'product.low_stock_list'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#product" aria-expanded="false" aria-controls="product"
                                class="side-nav-link">
                                <i class="uil-box"></i>
                                <span> Product </span>
                            </a>
                            <div class="collapse" id="product">
                                <ul class="side-nav-second-level">
                                    @can('product.create_product')
                                        <li>
                                            <a href="{{ URL::to('product/create') }}">Create Product</a>
                                        </li>
                                    @endcan
                                    @can('product.product')
                                        <li>
                                            <a href="{{ URL::to('product/') }}">Product</a>
                                        </li>
                                    @endcan
                                    @can('product.product')
                                        <li>
                                            <a href="{{ URL::to('product/trashed') }}">Trashed Products</a>
                                        </li>
                                    @endcan
                                    {{-- @can('product.product_variant')
                                <li>
                                    <a href="{{ URL::to('product-variant') }}">Product Variant</a>
                                </li>
                                @endcan --}}
                                    {{-- @can('product.trashed')
                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#trashed-product" aria-expanded="false" aria-controls="trashed-product" class="side-nav-link">
                                        <span>Trashed</span>
                                        <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <div class="collapse" id="trashed-product">
                                        <ul class="side-nav-second-level">
                                            <li>
                                                <a href="{{ URL::to('product/trashed') }}">Trashed Products</a>
                                            </li>
                                            <li>
                                                <a href="{{ URL::to('product-variant/trashed') }}">Trashed Product Variants</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endcan --}}
                                    @can('product.print_variants_barcode')
                                        <li>
                                            <a href="{{ URL::to('product-variant-barcode') }}">Print Product Barcode</a>
                                        </li>
                                    @endcan
                                    @can('product.low_stock_list')
                                        <li>
                                            <a href="{{ URL::to('product/low-stock') }}">Low Stock List</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Stock Management Section -->
                    @canany(['stock_management.stock_adjustment', 'stock_management.transfer_stock',
                        'stock_management.transfer_stock_list', 'stock_management.trashed_transfer_stock_list',
                        'stock_management.purchase', 'stock_management.purchase_return', 'stock_management.purchase_list'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#stockManagment" aria-expanded="false"
                                aria-controls="stockManagment" class="side-nav-link">
                                <i class="uil-clipboard-notes"></i>
                                <span> Stock Management </span>
                            </a>
                            <div class="collapse" id="stockManagment">
                                <ul class="side-nav-second-level">
                                    @can('stock_management.stock_adjustment')
                                        <li>
                                            <a href="{{ URL::to('product-stock-adjustment') }}">Stock Adjustment</a>
                                        </li>
                                    @endcan
                                    {{-- @can('stock_management.transfer_stock')
                                        <li>
                                            <a href="{{ URL::to('stock-transfer') }}">Transfer Stock</a>
                                        </li>
                                    @endcan
                                    @can('stock_management.transfer_stock_list')
                                        <li>
                                            <a href="{{ URL::to('stock-transfer/list') }}">Transfer Stock List</a>
                                        </li>
                                    @endcan
                                    @can('stock_management.trashed_transfer_stock_list')
                                        <li>
                                            <a href="{{ URL::to('stock-transfer/trashed') }}">Trashed Transfer Stock List</a>
                                        </li>
                                    @endcan --}}
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- POS Section -->
                    @canany(['sale.sale', 'sale.sale_list'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#pos" aria-expanded="false" aria-controls="pos"
                                class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> POS </span>
                            </a>
                            <div class="collapse" id="pos">
                                <ul class="side-nav-second-level">
                                    @can('sale.sale')
                                        <li>
                                            <a href="{{ URL::to('get-sale-invoice') }}">Sale</a>
                                        </li>
                                    @endcan
                                    @can('sale.sale_list')
                                        <li>
                                            <a href="{{ URL::to('today-sale-inovice-list') }}">Today's Sale List</a>
                                        </li>
                                    @endcan
                                    @can('sale.sale_list')
                                        <li>
                                            <a href="{{ URL::to('sale-inovice-list') }}">Sale List</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Stock Management Section -->
                    @canany(['stock_management.purchase', 'stock_management.purchase_return',
                        'stock_management.purchase_list'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#purchase" aria-expanded="false" aria-controls="purchase"
                                class="side-nav-link">
                                <i class="uil-clipboard-notes"></i>
                                <span> Purchase </span>
                            </a>
                            <div class="collapse" id="purchase">
                                <ul class="side-nav-second-level">
                                    @can('stock_management.purchase')
                                        <li>
                                            <a href="{{ route('purchase.form') }}">Purchase</a>
                                        </li>
                                    @endcan
                                    @can('stock_management.purchase_list')
                                        <li>
                                            <a href="{{ route('purchase.list') }}">Purchase List</a>
                                        </li>
                                    @endcan
                                    @can('stock_management.purchase_return')
                                        <li>
                                            <a href="{{ route('purchase.return') }}">Purchase Return</a>
                                        </li>
                                    @endcan
                                    @can('stock_management.purchase_return')
                                        <li>
                                            <a href="{{ route('purchase.return.list') }}">Purchase Return List</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Party Section -->
                    @can('party')
                        <li class="side-nav-item">
                            <a href="{{ URL::to('get-parties-list') }}" class="side-nav-link">
                                <i class="uil-users-alt"></i>
                                <span>Party</span>
                            </a>
                        </li>
                    @endcan

                    <!-- Accounts Section -->
                    @canany(['accounts.accounts_list', 'accounts.payments_receiving', 'accounts.capital_management',
                        'accounts.balance_sheet', 'accounts.trial_balance_sheet', 'accounts.date_wise_profit_margin'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#account" aria-expanded="false" aria-controls="account"
                                class="side-nav-link">
                                <i class="uil-wallet"></i>
                                <span> Accounts </span>
                            </a>
                            <div class="collapse" id="account">
                                <ul class="side-nav-second-level">
                                    @can('accounts.accounts_list')
                                        <li>
                                            <a href="{{ URL::to('add-account') }}">Accounts List</a>
                                        </li>
                                    @endcan
                                    @can('accounts.payments_receiving')
                                        <li>
                                            <a href="{{ URL::to('add-make-payment') }}">Payments & Receiving</a>
                                        </li>
                                    @endcan
                                    @can('accounts.capital_management')
                                        <li>
                                            <a href="{{ URL::to('Capital') }}">Capital Management</a>
                                        </li>
                                    @endcan
                                    @can('accounts.balance_sheet')
                                        <li>
                                            <a href="{{ URL::to('balance-sheet') }}">Balance Sheet</a>
                                        </li>
                                    @endcan
                                    @can('accounts.trial_balance_sheet')
                                        <li>
                                            <a href="{{ URL::to('TrialbalanceSheet') }}">Trial Balance Sheet</a>
                                        </li>
                                    @endcan
                                    @can('accounts.date_wise_profit_margin')
                                        <li>
                                            <a href="{{ URL::to('show_profit_margin') }}">Date Wise Profit Margin</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Expense Section -->
                    @canany(['expense.expense_list', 'expense.categories', 'expense.sub_categories'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#expenseNav" aria-expanded="false"
                                aria-controls="expenseNav" class="side-nav-link">
                                <i class="uil-money-withdraw"></i>
                                <span> Expense </span>
                            </a>
                            <div class="collapse" id="expenseNav">
                                <ul class="side-nav-second-level">
                                    @can('expense.expense_list')
                                        <li>
                                            <a href="{{ URL::to('expense-list') }}">Expense List</a>
                                        </li>
                                    @endcan
                                    @can('expense.categories')
                                        <li>
                                            <a href="{{ URL::to('expense-categories') }}">Categories</a>
                                        </li>
                                    @endcan
                                    @can('expense.sub_categories')
                                        <li>
                                            <a href="{{ URL::to('expense-sub-categories') }}">Sub Categories</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Users Section -->
                    @canany(['user_management.user_list', 'user_management.employees',
                        'user_management.trashed_employees'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#usersNav" aria-expanded="false" aria-controls="usersNav"
                                class="side-nav-link">
                                <i class="uil-user-circle"></i>
                                <span> Users </span>
                            </a>
                            <div class="collapse" id="usersNav">
                                <ul class="side-nav-second-level">
                                    @can('user_management.user_list')
                                        <li>
                                            <a href="{{ URL::to('users-list') }}">User List</a>
                                        </li>
                                    @endcan
                                    @can('user_management.employees')
                                        <li>
                                            <a href="{{ URL::to('employee') }}">Employees</a>
                                        </li>
                                    @endcan
                                    @can('user_management.trashed_employees')
                                        <li>
                                            <a href="{{ URL::to('employee/trashed') }}">Trashed Employees</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Closings Section -->
                    @canany(['pos_closing.today_invoices', 'pos_closing.pos_closing', 'pos_closing.day_book',
                        'pos_closing.datewise_day_book'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#closing" aria-expanded="false" aria-controls="closing"
                                class="side-nav-link">
                                <i class="uil uil-receipt"></i>
                                <span>Closings</span>
                            </a>
                            <div class="collapse" id="closing">
                                <ul class="side-nav-second-level">
                                    @can('pos_closing.today_invoices')
                                        <li>
                                            <a href="{{ URL::to('todayInvoices') }}">Today Invoices</a>
                                        </li>
                                    @endcan
                                    @can('pos_closing.pos_closing')
                                        <li>
                                            <a href="{{ URL::to('pos-closing') }}">Pos Closing</a>
                                        </li>
                                    @endcan
                                    @can('pos_closing.day_book')
                                        <li>
                                            <a href="{{ URL::to('day-bookss') }}">Day Book</a>
                                        </li>
                                    @endcan
                                    @can('pos_closing.datewise_day_book')
                                        <li>
                                            <a href="{{ URL::to('day-book-datewise') }}">Datewise Day Book</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany

                    <!-- Reports Section -->
                    @canany(['reports.stock_report', 'reports.sale_report', 'reports.purchase_report',
                        'reports.expanse_report', 'reports.payments_recv_report', 'reports.ledgers_reports',
                        'reports.summary_reports', 'reports.party_statements'])
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#reportsNav" aria-expanded="false"
                                aria-controls="reportsNav" class="side-nav-link">
                                <i class="uil-chart-line"></i>
                                <span> Reports </span>
                            </a>
                            <div class="collapse" id="reportsNav">
                                <ul class="side-nav-second-level">
                                    @can('reports.stock_report')
                                        <li>
                                            <a href="{{ URL::to('product-stock-reports') }}">Stock Report</a>
                                        </li>
                                    @endcan
                                    @can('reports.sale_report')
                                        <li>
                                            <a href="{{ URL::to('product-sale-reports') }}">Sale Report</a>
                                        </li>
                                    @endcan
                                    @can('reports.purchase_report')
                                        <li>
                                            <a href="{{ URL::to('product-purchase-reports') }}">Purchase Report</a>
                                        </li>
                                    @endcan
                                    @can('reports.expanse_report')
                                        <li>
                                            <a href="{{ URL::to('expense-reports') }}">Expense Report</a>
                                        </li>
                                    @endcan
                                    @can('reports.payments_recv_report')
                                        <li>
                                            <a href="{{ URL::to('payments-report') }}">Payments & Recv Report</a>
                                        </li>
                                    @endcan
                                    @can('reports.ledgers_reports')
                                        <li>
                                            <a href="{{ URL::to('ledger-reports') }}">Ledgers Reports</a>
                                        </li>
                                    @endcan
                                    @can('reports.summary_reports')
                                        <li>
                                            <a href="{{ URL::to('summary-reports') }}">Summary Reports</a>
                                        </li>
                                    @endcan
                                    @can('reports.party_statements')
                                        <li>
                                            <a href="{{ URL::to('party-statemsent') }}">Party Statements</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcanany
                    <li class="side-nav-item">
                        <a href="{{ URL::to('settings') }}" class="side-nav-link">
                            <i class="uil-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                    {{-- @can('backup-restore') --}}
                    <li class="side-nav-item">
                        <a href="{{ URL::to('backup-restore') }}" class="side-nav-link">
                            <i class="uil-cloud-download"></i>
                            <span>Backup & Restore</span>
                        </a>
                    </li>
                    {{-- @endcan --}}
                </ul>



                <!-- Help Box -->

                <!-- end Help Box -->
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                <div class="navbar-custom">

                    <ul class="list-unstyled topbar-menu float-end mb-0">

                        <li class="dropdown notification-list d-lg-none">
                            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="dripicons-search noti-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                <form class="p-3">
                                    <input type="text" class="form-control" placeholder="Search bar..."
                                        aria-label="Recipient's username">
                                </form>
                            </div>
                        </li>



                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="account-user-avatar">

                                    {{-- <img src="{{ asset('images/persons/' . Auth::user()->img . '') }}" alt="user-image" --}}
                                    {{-- class="rounded-circle"> --}}
                                </span>

                                <span>
                                    <span class="account-user-name">{{ Auth::user()->name }}</span>

                                </span>
                            </a>
                            <div
                                class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                <!-- item-->


                                <!-- item-->
                                <a href="{{ route('password.change') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle me-1"></i>
                                    <span>Change Password</span>
                                </a>



                                <!-- item-->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout me-1"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </li>

                    </ul>
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <ul class="list-unstyled topbar-menu float-start mt-2 mb-0">


                        @can('party')
                            <li class="dropdown notification-list">
                                <a class="nav-link" href="{{ URL::to('get-parties-list') }}" role="button">
                                    <i class="btn btn-secondary rounded-pill">Party</i>
                                </a>
                            </li>
                        @endcan
                        @can('sale.sale')
                            <li class="dropdown notification-list">
                                <a class="nav-link" href="{{ URL::to('get-sale-invoice') }}" role="button">
                                    <i class="btn btn-info rounded-pill">Sale</i>
                                </a>
                            </li>
                        @endcan
                        <li class="dropdown notification-list ">
                            <a class="nav-link " href="{{ route('purchase.form') }}" role="button">
                                <i class="btn btn-info rounded-pill">Purchase</i>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        @can('accounts.payments_receiving')
                            <li class="dropdown notification-list">
                                <a class="nav-link" href="{{ URL::to('add-make-payment') }}" role="button">
                                    <i class="btn btn-warning rounded-pill">Payments and Receiving</i>
                                </a>
                            </li>
                        @endcan
                        {{-- @can('accounts.payments_receiving')
                        <li class="dropdown notification-list">
                            <a class="nav-link position-relative" href="{{ URL::to('fetch-payable') }}" role="button">
                                <i class="btn btn-secondary rounded-pill">
                                    Payments Alerts
                                </i>
                                @isset($purchaseCount)
                                    @if ($purchaseCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $purchaseCount ?? 'null' }}
                                    </span>
                                    @endif
                                @endisset
                            </a>
                        </li>
                        @endcan


                        @can('accounts.payments_receiving')
                        <li class="dropdown notification-list">
                            <a class="nav-link position-relative" href="{{ URL::to('fetch-recevable') }}" role="button">
                                <i class="btn btn-warning rounded-pill">
                                    Receiving Alerts
                                </i>
                                @isset($saleCount)
                                    @if ($saleCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                        {{ $saleCount }}
                                    </span>
                                    @endif
                                @endisset
                            </a>
                        </li>
                        @endcan --}}
                    </ul>
                    {{-- @can('expense')
                            <li class="dropdown notification-list" style="margin-right: 10px">
                                <button type="button" class="btn btn-success rounded-pill position-relative"
                                    id="holdmodalButton" data-bs-toggle="modal" data-bs-target="#hold-modal"
                                    data-toggle-state="off">
                                    Hold
                                    <span id="holdCountBadge"
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="display: none;">
                                        0
                                    </span>
                                </button>
                            </li>
                        @endcan
                        @can('expense-reports')
                            <li class="dropdown notification-list">
                                <button type="button" class="btn btn-success rounded-pill" id="quotationButton"
                                    data-bs-toggle="modal" data-bs-target="#quotation-modal" data-toggle-state="off">
                                    Quotation & Orders
                                </button>
                                <span id="quotationCountBadge"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="display: none;">
                                    0
                                </span>
                            </li>
                        @endcan --}}


                    </ul>



                </div>
                <!-- end Topbar -->
