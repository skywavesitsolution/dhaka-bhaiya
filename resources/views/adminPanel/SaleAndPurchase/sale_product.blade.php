@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        html,
        body {
            overflow: hidden;
            height: 100%;
        }

        #logout {
            display: none;
            background-color: #ffffff;
            padding: 10px;
            margin-top: 5px;
            color: rgb(113, 113, 204);
            border-radius: 5px;
            position: absolute;
            top: 40px;
            right: 10px;
            z-index: 9999;
            cursor: pointer;
        }

        .table-centered {
            table-layout: fixed;
        }

        #tableBody {
            display: block;
            max-height: 65vh;
            overflow-x: hidden;
        }

        #product_images {
            display: block;
            height: 100%;
        }

        #product_images {}

        thead {
            display: table;
            width: 100%;
        }

        table {
            width: 100%;
        }

        .small-table {
            font-size: 12px;
        }

        .small-font {
            font-size: 12px;
        }

        .small-table .form-control {
            font-size: 12px;
            padding: 5px;
        }

        .small-table th,
        .small-table td {
            padding: 5px;
            text-align: center;
        }

        .small-btn {
            width: 20px;
            height: 20px;
            font-size: 10px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .table td,
        .table th {
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        #image_section {

            overflow: hidden;
            overflow-y: auto;
        }

        #image_section .card {
            margin-bottom: 10px;
            box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.1);
        }

        #image_section .small-card {
            padding: 4px;
        }

        #image_section .card-img-top {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        #image_section .card-body {
            padding: 5px;
            height: 400px;
        }

        #image_section .card-title {
            font-size: 14px;
            margin-bottom: 5px;
        }

        #image_section .card-text {
            font-size: 15px;
            margin-bottom: 0;
        }

        .product-card {
            position: relative;
            overflow: hidden;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            width: 150px;
        }

        .product-card img {
            object-fit: cover;
            width: 100%;
            height: auto;
            max-height: 100px;
            position: relative;
        }


        .product-card h6 {
            top: 50%;
            left: 5%;
            right: 5%;
            position: absolute;

            font-size: 0.9rem;
            line-height: 1.2rem;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            text-align: center;
            margin-top: -15px;
            background-color: rgba(92, 84, 84, 0.7);
            color: #fff;
        }

        .product-card .card-body {
            padding: 0;
            text-align: center;
        }

        .product-card p {
            margin: 0;
            font-size: 0.8rem;
            position: relative;
        }

        #table_card {
            display: flex;
            flex-direction: column;
        }

        .table-responsive {
            max-height: 100%;
        }

        .leftside-menu,
        .button-menu-mobile,
        .topbar-menu,
        .navbar-custom {
            display: none !important;
        }

        .content-page {
            margin: 0 !important;
            padding: 0 !important;
        }

        .custom-item {
            max-width: 80px;
        }

        @media screen and (min-width: 786px) {
            .products {
                max-height: 90vh;
            }

            #table_card {
                height: 65vh;
            }

            #image_section {

                max-height: 82vh;
            }
        }

        .list-group-item.active {
            background-color: #007bff !important;
            color: white !important;
            border-color: #007bff !important;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid m-0 p-0">

        <nav class="navbar navbar-expand-lg navbar-light  me-auto" style="color: #ffffff; background-color:#1E293B">
            <div class="container-fluid">
                <a class="navbar-brand w-50" href="{{ url('dashboard') }}" style="color:white;font-weight:bold;">TechPOS
                    Inventory Managment System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse  " id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="dropdown notification-list gap-2" style="margin-right: 10px">
                            <a class="btn btn-success  position-relative" href="{{ url('today-sale-inovice-list') }}">Today
                                Invoices</a>
                        </li>

                        <li class="dropdown notification-list" style="margin-right: 10px">
                            <button type="button" class="btn btn-success  position-relative" id="holdmodalButton"
                                data-bs-toggle="modal" data-bs-target="#hold-modal" data-toggle-state="off">
                                Hold
                                <span id="holdCountBadge"
                                    class="position-absolute top-0 start-100 translate-middle badge  bg-danger"
                                    style="display: none;">
                                    0
                                </span>
                            </button>
                        </li>
                        <li class="dropdown notification-list">
                            <button type="button" class="btn btn-success " id="quotationButton" data-bs-toggle="modal"
                                data-bs-target="#quotation-modal" data-toggle-state="off">
                                Kitchen Orders
                            </button>
                            <span id="quotationCountBadge"
                                class="position-absolute top-0 start-100 translate-middle badge  bg-danger"
                                style="display: none;">
                                0
                            </span>
                        </li>
                    </ul>
                    <div class="d-flex " style="gap: 20px; align-items:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="30" height="30" x="0" y="0" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512;cursor: pointer;color:white" xml:space="preserve"
                            onclick="toggleFullScreen()">
                            <g>
                                <path
                                    d="M32.458 361.617h.012a8.016 8.016 0 0 0 8.015-8l.307-220.233a22.186 22.186 0 0 1 22.154-22.122h386.415a22.179 22.179 0 0 1 22.154 22.154v220.212a8.016 8.016 0 0 0 16.031 0V133.411a38.229 38.229 0 0 0-38.185-38.185H62.946a38.241 38.241 0 0 0-38.186 38.131l-.306 220.234a8.016 8.016 0 0 0 8.004 8.026zM304.047 371.279a8.016 8.016 0 0 0-8 8.55 8.2 8.2 0 0 0 8.273 7.482h190.095a21.482 21.482 0 0 1-19.894 13.432H37.479a21.482 21.482 0 0 1-19.894-13.432h190.431a8.2 8.2 0 0 0 8.273-7.482 8.016 8.016 0 0 0-8-8.55H8.016A8.016 8.016 0 0 0 0 379.3a37.479 37.479 0 0 0 37.479 37.479h437.042A37.479 37.479 0 0 0 512 379.3a8.016 8.016 0 0 0-8.016-8.016z"
                                    fill="#ffffff" opacity="1" data-original="#ffffff"></path>
                                <path
                                    d="M241.43 371.279a8.016 8.016 0 0 0 0 16.032h29.14a8.016 8.016 0 1 0 0-16.032zM118.5 183.2l-36.732-36.731h14.247a8.016 8.016 0 1 0 0-16.031H62.738a8.347 8.347 0 0 0-8.338 8.337v35.915a8.016 8.016 0 0 0 16.032 0v-16.885l36.732 36.733A8.016 8.016 0 1 0 118.5 183.2zM457.6 135.085a8.348 8.348 0 0 0-8.338-8.338h-35.916a8.016 8.016 0 0 0 0 16.031h16.886L393.5 179.51a8.016 8.016 0 0 0 11.336 11.337l36.732-36.733v14.248a8.016 8.016 0 0 0 16.032 0zM407.969 341.484a8.017 8.017 0 0 0 8.016 8.016h33.277a8.348 8.348 0 0 0 8.338-8.338v-35.915a8.016 8.016 0 0 0-16.032 0v16.886L404.836 285.4a8.016 8.016 0 0 0-11.336 11.336l36.732 36.732h-14.247a8.016 8.016 0 0 0-8.016 8.016zM118.5 289.091a8.016 8.016 0 0 0-11.336 0l-36.732 36.732v-14.247a8.016 8.016 0 0 0-16.032 0v33.277a8.347 8.347 0 0 0 8.338 8.337h35.916a8.016 8.016 0 0 0 0-16.031H81.768l36.732-36.732a8.015 8.015 0 0 0 0-11.336z"
                                    fill="#ffffff" opacity="1" data-original="#ffffff"></path>
                            </g>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="30" height="30" x="0" y="0" viewBox="0 0 32 32"
                            style="enable-background:new 0 0 512 512;cursor: pointer;" xml:space="preserve"
                            onclick="refreshPage()">
                            <g>
                                <g data-name="Layer 2">
                                    <path
                                        d="M27.7 1H4.3A3.306 3.306 0 0 0 1 4.3v19.4A3.306 3.306 0 0 0 4.3 27H5a1 1 0 0 0 0-2h-.7A1.3 1.3 0 0 1 3 23.7V9h26v14.7a1.3 1.3 0 0 1-1.3 1.3H27a1 1 0 0 0 0 2h.7a3.306 3.306 0 0 0 3.3-3.3V4.3A3.306 3.306 0 0 0 27.7 1zM29 7H3V4.3A1.3 1.3 0 0 1 4.3 3h23.4A1.3 1.3 0 0 1 29 4.3z"
                                        fill="#fff" opacity="1" data-original="#000000"></path>
                                    <circle cx="6" cy="5" r="1" fill="#ffffff" opacity="1"
                                        data-original="#fffff"></circle>
                                    <circle cx="9" cy="5" r="1" fill="#ffffff" opacity="1"
                                        data-original="#fffff"></circle>
                                    <circle cx="12" cy="5" r="1" fill="#fffff" opacity="1"
                                        data-original="#fffff"></circle>
                                    <path
                                        d="M24 21a1 1 0 0 0-1 1 7.005 7.005 0 1 1-2.1-5h-.765a1 1 0 0 0 0 2h3a1 1 0 0 0 1-1v-3a1 1 0 0 0-2 0v.426A8.99 8.99 0 1 0 25 22a1 1 0 0 0-1-1z"
                                        fill="#fff" opacity="1" data-original="#000000"></path>
                                </g>
                            </g>
                        </svg>

                        <div id="hamza" style="cursor: pointer"> <svg xmlns="http://www.w3.org/2000/svg"
                                version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="10"
                                x="0" y="0" viewBox="0 0 32 32" style="color:white; enable-background:new 0 0 512 512"
                                xml:space="preserve" class="">
                                <g>
                                    <path
                                        d="M29.604 10.528 17.531 23.356a2.102 2.102 0 0 1-3.062 0L2.396 10.528c-.907-.964-.224-2.546 1.1-2.546h25.008c1.324 0 2.007 1.582 1.1 2.546z"
                                        fill="#ffffff" opacity="" data-original="#ffffff"></path>
                                </g>
                            </svg></div>

                        <div id="logout">Logout <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0"
                                viewBox="0 0 512.005 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                class="">
                                <g>
                                    <path
                                        d="M320 277.336c-11.797 0-21.332 9.559-21.332 21.332v85.336c0 11.754-9.559 21.332-21.336 21.332h-64v-320c0-18.219-11.605-34.496-29.055-40.555l-6.316-2.113h99.371c11.777 0 21.336 9.578 21.336 21.336v64c0 11.773 9.535 21.332 21.332 21.332s21.332-9.559 21.332-21.332v-64c0-35.285-28.715-64-64-64H48c-.812 0-1.492.363-2.281.469-1.028-.086-2.008-.47-3.051-.47C19.137.004 0 19.138 0 42.669v384c0 18.219 11.605 34.496 29.055 40.555L157.44 510.02c4.352 1.343 8.68 1.984 13.227 1.984 23.531 0 42.664-19.137 42.664-42.668v-21.332h64c35.285 0 64-28.715 64-64v-85.336c0-11.773-9.535-21.332-21.332-21.332zm0 0"
                                        fill="#ffffff" opacity="" data-original="#ffffff"></path>
                                    <path
                                        d="m505.75 198.254-85.336-85.332a21.33 21.33 0 0 0-23.25-4.633C389.207 111.598 384 119.383 384 128.004v64h-85.332c-11.777 0-21.336 9.555-21.336 21.332 0 11.777 9.559 21.332 21.336 21.332H384v64c0 8.621 5.207 16.406 13.164 19.715a21.335 21.335 0 0 0 23.25-4.63l85.336-85.335c8.34-8.34 8.34-21.824 0-30.164zm0 0"
                                        fill="#fffff" opacity="" data-original="#ffffff"></path>
                                </g>
                            </svg></div>



                    </div>

                </div>
            </div>
        </nav>

        <div class="row m-0 p-0 " id="screen-full">
            <button type="button" class="btn p-0 "
                style="position: fixed; z-index:9999; right:0;  width:max-content; border-radius:10px 0px 0px 10px;"
                data-bs-toggle="modal" data-bs-target="#info_modal">
                <i class="bi bi-eye btn btn-dark" style="opacity: 0.5;"></i>
                <!-- Eye Icon with transparency -->
            </button>
            <div class="col-12 m-0 p-0 " id="">
                <div class="card p-0 m-0" height="100% ">
                    <div class="card-body p-1" id="over_all">
                        <div class="row" height="100%">
                            <div class="col-md-12" style="padding: unset;margin:unset">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form id="saleForm" method="post">
                                    @csrf
                                    {{-- <input type="hidden" id="form_action" name="form_action"> --}}

                                    <div class="row m-0 p-0 products">
                                        <div class="col-lg-4 col-sm-12 col-md-6 row  mb-2 m-0 p-0">

                                            <div class="col-sm-12 row g-1 p-0 m-0" style="height: max-content">


                                                <div class="col-sm-5 row  p-0 m-0">
                                                    <!-- Buttons Section -->

                                                    <div class=" col-md-12 ">
                                                        <h4 style="font-weight: bold;margin-left:10px;">
                                                            Sales & Return</h4>

                                                    </div>
                                                    <div class=" col-md-11 " style="margin-left:10px">
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-primary btn-sm mx-1"
                                                            id="returnModeButton"
                                                            onclick="toggleReturnMode()">Sale</button>
                                                        <!-- Change ID if needed -->
                                                        {{-- <button type="button" class="btn btn-secondary btn-sm mx-1">Hold</button> --}}
                                                        {{-- <button type="button" class="btn btn-success btn-sm" id="wholesaleButton">Wholesale</button> --}}
                                                    </div>

                                                    <div class="col-sm-12 d-none">
                                                        <button type="button" class="btn btn-primary btn-sm mx-1"
                                                            onclick="toggleReturnMode()">Sale</button>
                                                        {{-- <button type="button" class="btn btn-secondary btn-sm mx-1">Hold</button> --}}
                                                        <button type="button" class="btn btn-success btn-sm "
                                                            id="wholesaleButton">Wholesale</button>
                                                    </div>

                                                </div>


                                                {{-- <div class=" row"> --}}
                                                <div class="col-sm-7 row g-1">
                                                    <!-- Left Column -->
                                                    <div class="col-sm-6">
                                                        <div class="mb-1" style="padding: 0;">
                                                            <div class="card text-center small-card"
                                                                style="height: 60px; margin-bottom: 0;">
                                                                <div class="card-body p-1 rounded"
                                                                    style="background-color: rgb(255, 247, 0);">
                                                                    <h4 class="card-text"
                                                                        style=" text-align:left;  left: 10px; margin: 0;">
                                                                        Net Sale:<br> </h4>
                                                                    <h4 id="net-sale" style="text-align:right"
                                                                        class="p-0 m-0">0.00</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-1" style="padding: 0;">
                                                            <div class="card text-center small-card"
                                                                style="height: 60px; margin-bottom: 0; position: relative;">
                                                                <div class="card-body p-1 rounded"
                                                                    style="background-color: rgb(0, 128, 255);color:white;">

                                                                    <h4 class="card-text"
                                                                        style=" text-align:left;  left: 10px; margin: 0;">
                                                                        Quantity:<br> </h4>
                                                                    <h4 id="qty" style="text-align:right"
                                                                        class="p-0 m-0">0.00</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Right Column -->
                                                    <div class="col-sm-6">
                                                        <div class="mb-1" style="padding: 0;">
                                                            <div class="card text-center small-card"
                                                                style="height: 60px; margin-bottom: 0; position: relative;">
                                                                <div class="card-body p-1 rounded"
                                                                    style="background-color: rgb(92, 94, 96); color:white;">

                                                                    <h4 class="card-text"
                                                                        style=" text-align:left;  left: 10px; margin: 0;">
                                                                        Discount:<br> </h4>
                                                                    <h4 id="discount-display" style="text-align:right"
                                                                        class="p-0 m-0">0.00</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-1" style="padding: 0;">
                                                            <div class="card text-center small-card "
                                                                style="height: 60px; margin-bottom: 0; position: relative;">
                                                                <div class="card-body p-1 rounded"
                                                                    style="background-color: rgb(255, 0, 0); color:white;">

                                                                    <h4 class="card-text"
                                                                        style=" text-align:left;  left: 10px; margin: 0;">
                                                                        Payable:<br> </h4>
                                                                    <h4 id="total" style="text-align:right"
                                                                        class="p-0 m-0">0.00</h4>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                {{-- </div> --}}
                                            </div>
                                            <div class="row m-0 p-2 mt-0 pt-0">
                                                <div class="col-md-12 m-0 p-0">
                                                    <input type="text" id="product_search"
                                                        class="form-control form-control-sm"
                                                        placeholder="Search product by name or code..." autofocus>
                                                    <ul id="product_list" class="list-group"
                                                        style="max-height: 150px;  overflow-y: auto; display: none; position: absolute; z-index: 1000; width: 30%; background: #fff; border: 1px solid #ccc;">

                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-sm-12" style="height: 100%;">
                                                <div class="card" id="table_card">
                                                    <div class="card-body p-1">
                                                        <div class="col-md-12" style="  height:100%;">
                                                            <div class="table-responsive"
                                                                style="height: 100% ; overflow-x:unset;">
                                                                <table
                                                                    class="table table-centered w-100 nowrap small-table"
                                                                    style="height: 100%">
                                                                    <thead class="table-light">
                                                                        <tr class="p-0">
                                                                            <th class="p-0 " style="width: 40%;">Name
                                                                            </th>
                                                                            <th class="p-0">Sale Price</th>
                                                                            <th class="p-0">Qty</th>
                                                                            <th class="p-0">Total</th>
                                                                            <th class="p-0">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tableBody" class="small-table"></tbody>
                                                                </table>

                                                            </div>
                                                        </div>



                                                    </div>
                                                    <div class="row" style="gap: 10px">

                                                        <button type="button" class="btn btn-success col"
                                                            id="endCashButton" data-bs-toggle="modal"
                                                            data-bs-target="#standard-modal" data-toggle-state="off"> <i
                                                                class="mdi mdi-plus-circle me-2"></i>End
                                                            Cash</button>
                                                        <button type="button" id="quotation_modal_button"
                                                            class="btn btn-primary col" data-bs-toggle="modal"
                                                            data-bs-target="#quotation-customer-modal">
                                                            Kitchen Invoice
                                                        </button>


                                                        <button type="submit" class="btn btn-success col"
                                                            id="holdButton"><i
                                                                class="mdi mdi-plus-circle me-2"></i>Hold</button>

                                                        <button hidden id="removeStorageButton" type="button"
                                                            class="btn btn-success col">Remove Data from
                                                            Storage</button>
                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                        <div class="col-lg-8 col-sm-12 col-md-6 p-0 m-0">

                                            <div class="card mb-3" id="product_images" style="">
                                                <div class="card-body  p-1 m-0">
                                                    <div class="row">

                                                        <!-- Category Dropdown -->
                                                        <div class="mb-1" style="max-width: 300px;">
                                                            {{-- <label for="categoryFilter" class="form-label">Category:</label> --}}
                                                            <select id="categoryFilter" class="form-select"
                                                                onchange="filterByCategory()">
                                                                <option value="">All Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col" id="customer">
                                                            <div class="mb-3">
                                                                {{-- <label for="example-input-normal"
                                                                                class="form-label mb-2">Select
                                                                                Customer</label> --}}
                                                                <select name="party_id" class="form-control"
                                                                    id="customer_name">
                                                                    <option value="">Chose One</option>
                                                                    @isset($parties)
                                                                        @foreach ($parties as $party)
                                                                            <option value="{{ $party->id }}">
                                                                                {{ $party->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                                @error('party_id')
                                                                    <p class="text-danger mt-2">
                                                                        {{ $message }}
                                                                    </p>
                                                                @enderror
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-3" id="customer_receiveable">
                                                            <div class="mb-3">
                                                                {{-- <label for="customer_reciveable"
                                                                                class="form-label">Customer
                                                                                Receivable</label> --}}
                                                                <input type="number" readonly name="customer_reciveable"
                                                                    id="customer_reciveable"
                                                                    value="{{ old('customer_reciveable') }}"
                                                                    class="form-control" placeholder="Total amount"
                                                                    readonly>
                                                                @error('total_bill')
                                                                    <p class="text-danger mt-2">
                                                                        {{ $message }}
                                                                    </p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col" style="max-width: 300px;">
                                                            <button class="btn btn-secondary" id="bestSellingBtn">Best
                                                                Selling Product</button>
                                                        </div>
                                                        {{-- </div> --}}
                                                    </div>

                                                    <!-- Product Cards Section -->
                                                    <div class="" id="image_section" style="">
                                                        <div class="card-body m-0" style="height: 100%;">
                                                            <div class="d-flex" id="productCards"
                                                                style="cursor: pointer; gap: 15px; flex-wrap: wrap; justify-content: flex-start;">
                                                                @foreach ($products as $product)
                                                                    <div style="position: relative;">
                                                                        <div class="card text-center small-card product-card"
                                                                            style="background-color: rgb(219, 244, 248); width: 180px; border: none; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);"
                                                                            data-product-id="{{ $product->id }}"
                                                                            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 15px rgba(0, 0, 0, 0.15)'; this.style.backgroundColor='rgb(200, 236, 242)'; this.querySelector('img').style.opacity='0.9';"
                                                                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 10px rgba(0, 0, 0, 0.05)'; this.style.backgroundColor='rgb(219, 244, 248)'; this.querySelector('img').style.opacity='1';">
                                                                            @if ($product->hasMedia('pro_var_images'))
                                                                                @php
                                                                                    $media = $product->getFirstMedia(
                                                                                        'pro_var_images',
                                                                                    );
                                                                                    $imageUrl = $media->getFullUrl();
                                                                                @endphp
                                                                                <img src="{{ $imageUrl }}"
                                                                                    alt="Product Var Image" width="90"
                                                                                    height="90"
                                                                                    style="object-fit: cover; border-radius: 6px; margin-top: 10px; transition: opacity 0.2s;">
                                                                            @else
                                                                                <img src="{{ asset('adminPanel/assets/images/placeholderimage.png') }}"
                                                                                    alt="Dummy Product Image"
                                                                                    width="90" height="90"
                                                                                    style="object-fit: cover; border-radius: 6px; margin-top: 10px; transition: opacity 0.2s;">
                                                                            @endif
                                                                            <div class="card-body" style="padding: 10px;">
                                                                                <h6 class="card-title"
                                                                                    style="margin: 10px 0 5px; font-size: 14px; font-weight: 600; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                                    {{ $product->product_variant_name }}
                                                                                </h6>
                                                                                <span
                                                                                    style="background-color: #28a745; color: white; font-size: 12px; padding: 5px 10px; position: absolute; top: 10px; right: 10px; border-radius: 5px;">
                                                                                    Rs {{ $product->rates->retail_price }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- Product Table -->
                            <div class="row">
                                <!-- /.modal -->
                                <div id="quotation-customer-modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="standard-modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="standard-modalLabel">Order Details</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-hidden="true"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="customer-name" class="form-label">Order Type</label>
                                                        <select name="order_type" id="order_type" class="form-control">
                                                            <option selected>Select Order Type</option>
                                                            <option value="dine-in">Dine In</option>
                                                            <option value="delivery">Delivery</option>
                                                            <option value="takeaway">Takeaway</option>

                                                        </select>
                                                    </div>
                                                    <div class="col-6 d-none" id = "table_number">
                                                        <label for="customer-name" class="form-label">Select Table</label>
                                                        <select name="table_number" id="table_numbers"
                                                            class="form-control">
                                                            <option value="">Select Table</option>
                                                            @foreach ($tables as $table)
                                                                <option value="{{ $table->id }}">
                                                                    {{ $table->location->name }}/{{ $table->table_number }}/{{ $table->status }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    <div class="col-12 d-none mt-2" id = "employees">
                                                        <label for="">Select Employee</label>
                                                        <select name="employee" id="employee" class="form-control">
                                                            @isset($employees)
                                                                @foreach ($employees as $employee)
                                                                    <option value="{{ $employee->id }}">{{ $employee->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                        @error('payment_type')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-6 d-none" id="customer-name">
                                                        <label for="customer-name" class="form-label">Customer
                                                            Name</label>
                                                        <input type="text" name="customer_name" id="customer-names"
                                                            class="form-control" placeholder="Enter Customer Name">
                                                    </div>

                                                    <div class="col-12 d-none mt-2" id="customer-number">
                                                        <label for="customer-numbers" class="form-label">Customer
                                                            Number</label>
                                                        <input type="text" name="customer_number"
                                                            id="customer-numbers" class="form-control" maxlength="14">
                                                    </div>
                                                    <div class="col-12 d-none mt-2" id="customer-address">
                                                        <label for="customer-address" class="form-label">Customer
                                                            Address</label>
                                                        <textarea name="customer_address" id="customer-address" class="form-control" rows="3"
                                                            placeholder="Enter Customer Address"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-6 mt-3 d-flex">
                                                    <button type="submit" class="btn btn-success"
                                                        id="quotation_save_button">
                                                        <i class="mdi mdi-plus-circle me-2"></i>Save
                                                    </button>
                                                    <div class="m-2">
                                                        <label for="">Print </label>
                                                        <input type="checkbox" name="print_quotation"
                                                            id="print_quotation" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->


                                <!-- /.modal -->
                                <!-- Modal -->
                                <div id="info_modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="standard-modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="standard-modalLabel">Shortcut Keys Table</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-hidden="true"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                    {{-- <thead> --}}
                                                    <tr>
                                                        <th>Function</th>
                                                        <th>Shortcut</th>
                                                    </tr>
                                                    {{-- </thead> --}}
                                                    <tbody>
                                                        <tr>
                                                            <!-- Replace the static content with dynamic data -->
                                                            <td>End cash</td>
                                                            <td>End button</td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Replace the static content with dynamic data -->
                                                            <td>Qty</td>
                                                            <td>F2</td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Replace the static content with dynamic data -->
                                                            <td>Save Sale</td>
                                                            <td>Ctrel + S</td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Replace the static content with dynamic data -->
                                                            <td>Save Hold</td>
                                                            <td>Ctrel + H</td>
                                                        </tr>
                                                        <tr>
                                                            <!-- Replace the static content with dynamic data -->
                                                            <td>Save Quotation</td>
                                                            <td>Ctrel + Q</td>
                                                        </tr>
                                                        <!-- Add more rows as needed -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.modal -->






                                <!-- Modal Form inside main form -->
                                <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="standard-modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="standard-modalLabel"></h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-hidden="true"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-2">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <!-- Employee Type -->
                                                                    {{-- <div class="mb-3 col">
                                                                    <label for="payment_type" class="mb-2">
                                                                        Employee</label>
                                                                    <select name="employee" id="employee"
                                                                        class="form-control @error('employee') is-invalid @enderror">
                                                                        <option value="">Select type</option>
                                                                        @isset($employees)
                                                                        @foreach ($employees as $employee)
                                                                        <option value="{{$employee->id}}">{{$employee->name}}</option>

                                                                        @endforeach

                                                                        @endisset

                                                                    </select>
                                                                    @error('payment_type')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div> --}}
                                                                    <!-- Payment Type -->
                                                                    <div class="mb-3 col">
                                                                        <label for="payment_type" class="mb-2">Payment
                                                                            Type</label>
                                                                        <select name="payment_type" id="payment_type"
                                                                            class="form-control @error('payment_type') is-invalid @enderror">
                                                                            <option value="cash"
                                                                                {{ old('payment_type') == 'cash' ? 'selected' : '' }}
                                                                                selected>
                                                                                cash</option>
                                                                            <option value="credit"
                                                                                {{ old('payment_type') == 'credit' ? 'selected' : '' }}>
                                                                                credit</option>
                                                                            <option value="cash+credit"
                                                                                {{ old('payment_type') == 'cash+credit' ? 'selected' : '' }}>
                                                                                Partial Payment</option>
                                                                        </select>
                                                                        @error('payment_type')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                    <!-- Customer Select -->
                                                                    {{-- <div class="col d-none" id="customer">
                                                                        <div class="mb-3">
                                                                            <label for="example-input-normal"
                                                                                class="form-label mb-2">Select
                                                                                Customer</label>
                                                                            <select name="party_id" class="form-control"
                                                                                id="customer_name">
                                                                                <option value="">Chose One</option>
                                                                                @isset($parties)
                                                                                    @foreach ($parties as $party)
                                                                                        <option value="{{ $party->id }}">
                                                                                            {{ $party->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                @endisset
                                                                            </select>
                                                                            @error('party_id')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="col" id="received_amount_cont">
                                                                        <label for="payment_amount"
                                                                            class="mb-2">Received Amount</label>
                                                                        <input type="number" name="payment_amount"
                                                                            id="received_amount" class="form-control"
                                                                            oninput="calculateBalance()"
                                                                            style="font-size: 1.5rem; height: 50px; padding: 10px;">
                                                                        @error('payment_amount')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>


                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-sm-3">
                                                                        <div class="mb-3">
                                                                            <label for="example-input-normal"
                                                                                class="form-label">Discount Type</label>
                                                                            <select name="discount_type"
                                                                                id="discount-type"
                                                                                class="form-control calculate-total">
                                                                                <option selected disabled>Select</option>
                                                                                <option value="Fixed">Flat</option>
                                                                                <option value="Percentage">Percentage
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Discount Value -->
                                                                    <div class="col-sm-3">
                                                                        <div class="">
                                                                            <label for="example-input-normal"
                                                                                class="form-label">Discount Value</label>
                                                                            <input type="number" step="any"
                                                                                id="discount-value" value="0"
                                                                                name="discount_value"
                                                                                value="{{ old('discount_value') }}"
                                                                                class="form-control calculate-total"
                                                                                placeholder="Discount Value">
                                                                            @error('discount_value')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <!-- Discount Amount -->
                                                                    <div class="col-sm-3">
                                                                        <div class="">
                                                                            <label for="example-input-normal"
                                                                                class="form-label">Discount Amount</label>
                                                                            <input type="text" readonly
                                                                                id="discount-amount" value="0"
                                                                                name="discount_actual_value"
                                                                                value="{{ old('discount_actual_value') }}"
                                                                                class="form-control"
                                                                                placeholder="Total Bill">
                                                                            @error('discount_actual_value')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <!-- Service Charges -->
                                                                    <div class="col-sm-3">
                                                                        <div class="">
                                                                            <label for="service_charges"
                                                                                class="form-label">Service Charges</label>
                                                                            <input type="number" step="any"
                                                                                id="service_charges"
                                                                                name="service_charges" value="0"
                                                                                class="form-control calculate-total"
                                                                                placeholder="Service Charges"
                                                                                min="0">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Total Bill -->
                                                                    <div class="col-sm-3">
                                                                        <div class="">
                                                                            <label for="example-input-normal"
                                                                                class="form-label">Total Bill</label>
                                                                            <input type="text" readonly
                                                                                name="total_bill" id="total-bill"
                                                                                value="{{ old('total_bill') }}"
                                                                                class="form-control"
                                                                                placeholder="Total Bill">
                                                                            @error('total_bill')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    {{-- <div class="col-sm-3">
                                                                        <div class="">
                                                                            <input type="text" readonly
                                                                                name="walking_customer_id" id="walking-customer-id"
                                                                                value="{{ old('walking_customer_id') }}"
                                                                                class="form-control">

                                                                        </div>
                                                                    </div> --}}

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-3" id="account_name">
                                                                        <label for="account_name" class="mb-1">Account
                                                                            Name</label>
                                                                        <select name="account_name"
                                                                            class="form-control mb-3 @error('account_name') is-invalid @enderror">
                                                                            @foreach ($accounts as $account)
                                                                                <option value="{{ $account->id }}"
                                                                                    {{ $account->account_name == 'cash in hand' ? 'selected' : '' }}>
                                                                                    {{ $account->account_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('account_name')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-sm-3 d-none" id="due_date">
                                                                        <label for="due_date" class="mb-1">Due
                                                                            Date</label>
                                                                        <input type="date" name="due_date"
                                                                            id="due_date" class="form-control">
                                                                        @error('account_name')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <!-- Customer Receivable -->
                                                                    <div class="col-sm-3 d-none"
                                                                        id="customer_receiveable">
                                                                        <div class="mb-3">
                                                                            <label for="customer_reciveable"
                                                                                class="form-label">Customer
                                                                                Receivable</label>
                                                                            <input type="number" readonly
                                                                                name="customer_reciveable"
                                                                                id="customer_reciveable"
                                                                                value="{{ old('customer_reciveable') }}"
                                                                                class="form-control"
                                                                                placeholder="Total amount" readonly>
                                                                            @error('total_bill')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-3 d-none">
                                                                        <div class="mb-3">
                                                                            <label for="example-input-normal"
                                                                                class="form-label">Net payble</label>
                                                                            <input type="text" readonly
                                                                                name="net_payable" id="net-payable"
                                                                                value="{{ old('total_bill') }}"
                                                                                class="form-control"
                                                                                placeholder="Total Bill">
                                                                            @error('total_bill')
                                                                                <p class="text-danger mt-2">
                                                                                    {{ $message }}
                                                                                </p>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <!-- Net Payable Card -->
                                                                <div class="card mb-3">
                                                                    <div class="card-header bg-primary text-white">
                                                                        Net Payable
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <h1 class="card-title">PKR <span
                                                                                id="net-payable-card"></span></h1>

                                                                    </div>
                                                                </div>

                                                                <!-- Balance Card -->
                                                                <div class="card">
                                                                    <div class="card-header bg-success text-white">
                                                                        Return Amount
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <h1 class="card-title">PKR <span
                                                                                id="balance"></span>
                                                                        </h1>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <label for="">Print </label>
                                                <input type="checkbox" name="print_sale" id="print_sale" checked>
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" id="saleButton">Save
                                                    Sale</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>



                            </div>
                            <!-- end row -->



                            <div id="hold-modal" class="modal fade" tabindex="-1" role="dialog"
                                aria-labelledby="standard-modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="standard-modalLabel">Hold Incoives</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-hidden="true"></button>
                                        </div>
                                        <table id="" class="table">
                                            {{-- <thead class=""> --}}
                                            <tr>
                                                <th>ID</th>
                                                <th>Date&Time</th>
                                                <th>Name</th>
                                                <th>Total Amount</th>
                                                <th style="width: 85px;">Action</th>
                                            </tr>
                                            {{-- </thead> --}}
                                            <tbody>
                                                @isset($holdInvoices)
                                                    @foreach ($holdInvoices as $holdInvoice)
                                                        <tr>
                                                            <td>
                                                                {{ $holdInvoice->id ?? '--' }}
                                                            </td>

                                                            <td>
                                                                {{ $holdInvoice->created_at ?? '--' }}
                                                            </td>
                                                            <td>
                                                                {{ $holdInvoice->name ?? 'Customer' }}
                                                            </td>
                                                            <td>
                                                                {{ $holdInvoice->net_payable ?? '--' }}
                                                            </td>
                                                            <td class="table-action">
                                                                <a href="javascript:void(0)" class="action-icon text-success"
                                                                    data-id="{{ $holdInvoice->id }}"
                                                                    onclick="reloadCartData({{ $holdInvoice->id }}, 'invoice')">
                                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                                </a>
                                                                <a href="{{ route('delete.holdInvoice', $holdInvoice) }}"><i
                                                                        class="mdi mdi-trash-can-outline"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
                                            </tbody>
                                        </table>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                            <!-- /.modal -->
                            <div id="quotation-modal" class="modal fade" tabindex="-1" role="dialog"
                                aria-labelledby="standard-modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="standard-modalLabel">Quotation & Orders</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-hidden="true"></button>
                                        </div>
                                        <table id="" class="table">
                                            {{-- <thead class=""> --}}
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Date&Time</th>
                                                <th>Customer Name</th>
                                                <th>Number</th>
                                                <th>Order Type</th>
                                                <th>Table Number</th>
                                                {{-- <th>status</th> --}}
                                                {{-- <th>Total Amount</th> --}}
                                                <th style="width: 85px;">Action</th>
                                            </tr>
                                            {{-- </thead> --}}
                                            <tbody>
                                                @isset($quotationInvoives)
                                                    @foreach ($quotationInvoives as $quotationInvoive)
                                                        <tr>
                                                            <td>
                                                                {{ $quotationInvoive->id ?? '--' }}
                                                            </td>
                                                            <td>
                                                                {{ $quotationInvoive->created_at ?? '--' }}
                                                            </td>
                                                            <td>
                                                                {{ $quotationInvoive->customer_name ?? 'Customer' }}
                                                            </td>
                                                            <td>
                                                                {{ $quotationInvoive->customer_number ?? 'null' }}
                                                            </td>
                                                            <td>
                                                                {{ $quotationInvoive->order_type ?? '--' }}
                                                            </td>
                                                            {{-- <td>
                                                                {{ $quotationInvoive->table->table_number ?? '--' }}/{{$quotationInvoive->table->location->name ?? '--' }}
                                                            </td> --}}

                                                            <td>
                                                                <select name="table_id" class="form-control table-dropdown"
                                                                    data-quotation-id="{{ $quotationInvoive->id }}">
                                                                    <option value="">Select Table</option>
                                                                    @foreach ($tables as $table)
                                                                        <option value="{{ $table->id }}"
                                                                            {{ $quotationInvoive->table_number == $table->table_number ? 'selected' : '' }}>
                                                                            {{ $table->location->name }}/{{ $table->table_number }}/{{ $table->status }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            {{-- <td>
                                                                <select name="status" class="form-control status-dropdown"
                                                                    data-id="{{ $quotationInvoive->id }}">
                                                                    <option value="pending"
                                                                        {{ $quotationInvoive->status == 'pending' ? 'selected' : '' }}>
                                                                        Pending</option>
                                                                    <option value="inprocess"
                                                                        {{ $quotationInvoive->status == 'inprocess' ? 'selected' : '' }}>
                                                                        In Process</option>
                                                                    <option value="ready_to_serve"
                                                                        {{ $quotationInvoive->status == 'ready_to_serve' ? 'selected' : '' }}>
                                                                        Ready to Serve</option>
                                                                    <option value="complete"
                                                                        {{ $quotationInvoive->status == 'complete' ? 'selected' : '' }}>
                                                                        Complete</option>
                                                                </select> --}}
                                                            </td>
                                                            {{-- <td>
                                                                {{ $quotationInvoive->net_payable ?? '--' }}
                                                            </td> --}}
                                                            <td class="table-action">
                                                                <a href="javascript:void(0)" class="action-icon text-success"
                                                                    data-id="{{ $quotationInvoive->id }}"
                                                                    onclick="reloadCartData({{ $quotationInvoive->id }}, 'quotation')">
                                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                                </a>
                                                                {{-- <a
                                                                    href="{{ route('delete.quotationInvoice', $quotationInvoive->id) }}"><i
                                                                        class="mdi mdi-trash-can-outline"></i>
                                                                </a> --}}
                                                                <button class="btn btn-sm text-danger delete-quotation-btn"
                                                                    data-id="{{ $quotationInvoive->id }}">
                                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                                </button>

                                                                <a
                                                                    href="{{ route('printquotation', $quotationInvoive->id) }}"><i
                                                                        class="mdi mdi-eye"></i>
                                                                </a>
                                                                <a href="{{ route('printCustomerInvoice', $quotationInvoive->id) }}"
                                                                    class="action-icon text-primary"
                                                                    title="Print Customer Invoice">
                                                                    <i class="mdi mdi-printer"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
                                            </tbody>
                                        </table>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        @endsection

                        @section('scripts')
                            <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
                            <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <script>
                                $('#row-' + product['id']).css({
                                    'background-color': '#d4edda',
                                    'transition': 'background-color 1s'
                                }).delay(1000).queue(function(next) {
                                    $(this).css('background-color', 'transparent');
                                    next();
                                });
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.getElementById('product_search').focus();
                                });

                                $('#hamza').on('click', function() {
                                    $('#logout').toggle(); // This will show or hide the logout div
                                });
                            </script>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const customerNumberInput = document.getElementById("customer-numbers");

                                    customerNumberInput.value = "+92-3";

                                    customerNumberInput.removeAttribute("readonly");

                                    customerNumberInput.addEventListener("input", function() {
                                        let value = this.value;

                                        // Ensure starting format is always +92-3
                                        if (!value.startsWith("+92-3")) {
                                            this.value = "+92-3";
                                        }

                                        // Allow only numbers after "+92-3"
                                        this.value = "+92-3" + this.value.slice(5).replace(/[^0-9]/g, "");

                                        // Restrict length to 13 characters (+92-3XXXXXXXXX)
                                        if (this.value.length > 14) {
                                            this.value = this.value.slice(0, 14);
                                        }
                                    });

                                    // Prevent backspace from removing "+92-3"
                                    customerNumberInput.addEventListener("keydown", function(event) {
                                        if ((event.key === "Backspace" || event.key === "Delete") && this.selectionStart <= 5) {
                                            event.preventDefault(); // Backspace prevent karega agar "+92-3" se pehle delete kare
                                        }
                                    });

                                    // Ensure "+92-3" remains when input is focused
                                    customerNumberInput.addEventListener("focus", function() {
                                        if (!this.value.startsWith("+92-3")) {
                                            this.value = "+92-3";
                                        }
                                    });
                                });
                            </script>

                            <script>
                                $(document).on('click', '.delete-quotation-btn', function() {
                                    const id = $(this).data('id');
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "Do you really want to delete this quotation invoice?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, delete it!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Swal.fire({
                                                title: 'Deleting...',
                                                didOpen: () => {
                                                    Swal.showLoading()
                                                },
                                                allowOutsideClick: false,
                                                allowEscapeKey: false
                                            });

                                            $.ajax({
                                                url: "{{ route('delete.quotationInvoice', ':id') }}".replace(':id', id),
                                                type: 'GET',
                                                data: {
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                success: function(response) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: response.message || 'Deleted!',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    }).then(() => {
                                                        location.reload(); // Reload page after success
                                                    });
                                                },
                                                error: function(xhr) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Oops!',
                                                        text: xhr.responseJSON?.message ||
                                                            'Something went wrong.'
                                                    });
                                                }
                                            });
                                        }
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const tableDropdowns = document.querySelectorAll('.table-dropdown');

                                    // Function to update all dropdowns with latest table data
                                    function updateTableDropdowns(selectedQuotationId, selectedTableId) {
                                        fetch('{{ route('get.tables') }}', {
                                                method: 'GET',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                        'content')
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    tableDropdowns.forEach(dropdown => {
                                                        const quotationId = dropdown.getAttribute('data-quotation-id');
                                                        const currentTableId = quotationId === selectedQuotationId ?
                                                            selectedTableId : dropdown.value;

                                                        // Store the current value to restore it after updating options
                                                        let newOptions = '<option value="">Select Table</option>';
                                                        data.tables.forEach(table => {
                                                            const isSelected = table.id == currentTableId ? 'selected' :
                                                                '';
                                                            newOptions += `
                                    <option value="${table.id}" ${isSelected}>
                                        ${table.location_name}/${table.table_number}/${table.status}
                                    </option>`;
                                                        });

                                                        // Update dropdown options
                                                        dropdown.innerHTML = newOptions;
                                                    });
                                                } else {
                                                    console.error('Failed to fetch tables:', data.message);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error fetching tables:', error);
                                            });
                                    }

                                    tableDropdowns.forEach(dropdown => {
                                        dropdown.addEventListener('change', function() {
                                            const quotationId = this.getAttribute('data-quotation-id');
                                            const newTableId = this.value;
                                            const oldTableId = this.querySelector('option[selected]')?.value || '';

                                            if (!newTableId || newTableId === oldTableId) {
                                                return;
                                            }

                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Do you want to change the table for this order?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes, change it!',
                                                cancelButtonText: 'No, cancel'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    fetch(`{{ url('/update-quotation-table') }}/${quotationId}`, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': document.querySelector(
                                                                    'meta[name="csrf-token"]').getAttribute(
                                                                    'content')
                                                            },
                                                            body: JSON.stringify({
                                                                table_id: newTableId,
                                                                old_table_id: oldTableId
                                                            })
                                                        })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                Swal.fire({
                                                                    title: 'Success!',
                                                                    text: 'Table updated successfully.',
                                                                    icon: 'success',
                                                                    timer: 1500,
                                                                    showConfirmButton: false
                                                                });

                                                                // Update all dropdowns with latest table statuses
                                                                updateTableDropdowns(quotationId, newTableId);

                                                                // Update the selected attribute for the current dropdown
                                                                dropdown.querySelectorAll('option').forEach(
                                                                    option => {
                                                                        option.removeAttribute('selected');
                                                                        if (option.value === newTableId) {
                                                                            option.setAttribute('selected',
                                                                                'selected');
                                                                        }
                                                                    });
                                                            } else {
                                                                Swal.fire({
                                                                    title: 'Error!',
                                                                    text: data.message ||
                                                                        'Failed to update table.',
                                                                    icon: 'error'
                                                                });
                                                                // Revert dropdown to old value
                                                                dropdown.value = oldTableId;
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error('Error:', error);
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: 'An error occurred while updating the table.',
                                                                icon: 'error'
                                                            });
                                                            // Revert dropdown to old value
                                                            dropdown.value = oldTableId;
                                                        });
                                                } else {
                                                    // Revert dropdown to old value if user cancels
                                                    dropdown.value = oldTableId;
                                                }
                                            });
                                        });
                                    });
                                });
                            </script>

                            <script>
                                function reloadCartData(id, type) {
                                    const modalId = type === 'quotation' ? 'quotation-modal' : 'hold-modal';
                                    const url = type === 'quotation' ? `{{ url('/get-quotation') }}/${id}` :
                                        `{{ url('/get-held-invoice') }}/${id}`;
                                    const tableBody = $('#tableBody');

                                    const modal = document.getElementById(modalId);
                                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                                    if (bootstrapModal) {
                                        bootstrapModal.hide();
                                    }

                                    $.ajax({
                                        url: url,
                                        type: 'GET',
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                const invoiceItems = response.data.items;
                                                const kitchenInvoice = response.data.kitchen_invoice;
                                                const kitchenInvoiceDetails = response.data.kitchen_invoice_details;

                                                // Clear existing cart
                                                addToCartProducts = [];
                                                tableBody.empty();

                                                // Add items to cart
                                                invoiceItems.forEach(item => {
                                                    if (addToCartProducts.findIndex(product => product.id === item
                                                            .product_id) === -1) {
                                                        addToCartProducts.push({
                                                            id: item.product_id,
                                                            quantity: item.sale_qty
                                                        });

                                                        // Construct table row HTML
                                                        var tableRowHTML = `
                                    <tr id="row-${item.product_id}" class="small-font">
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm p-0 small-btn" title="Add Discount" onclick="toggleDiscount(${item.product_id})">+</button>
                                        </td>
                                        <td>
                                            <input type="hidden" name="product_id[]" required class="form-control small-input" value="${item.product_id}">
                                            <input type="hidden" name="${type}_invoice_id" value="${id}">
                                            <input type="hidden" name="kitchen_invoice_id" value="${kitchenInvoice ? kitchenInvoice.id : ''}">
                                        </td>
                                        <td>
                                            <p style="font-weight:700;">${item.variant.product_variant_name}</p>
                                            <input type="hidden" name="product_name[]" required class="form-control small-input" value="${item.variant.product_variant_name}">
                                        </td>
                                        <td>
                                            <input type="text" name="retail_price[]" required id="retail-price${item.product_id}" product-id="${item.product_id}" class="form-control small-input calculate-total" value="${item.retail_price}">
                                        </td>
                                        <td>
                                            <input type="text" name="qty[]" required id="qty${item.product_id}" product-id="${item.product_id}" class="form-control small-input calculate-total" value="${item.sale_qty}">
                                        </td>
                                        <td>
                                            <input type="text" readonly name="total[]" required id="total-price${item.product_id}" class="form-control small-input" value="${item.sale_amount}">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check[${item.product_id}]" id="include-product-${item.product_id}" value="on" checked onclick="toggleProductInclusion(${item.product_id})">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm p-0 small-btn" title="Remove Product" onclick="removeProduct(${item.product_id})">×</button>
                                        </td>
                                    </tr>
                                    <tr id="discount-row-${item.product_id}" class="d-none">
                                        <td colspan="5">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select name="product_discount_type[]" id="discountType${item.product_id}" product-id="${item.product_id}" class="form-control calculate-total">
                                                        <option value="Fixed" ${item.dicount_type === "Fixed" ? "selected" : ""}>Flat</option>
                                                        <option value="Percentage" ${item.dicount_type === "Percentage" ? "selected" : ""}>Percentage</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="product_discount_value[]" id="discount-value${item.product_id}" product-id="${item.product_id}" class="form-control calculate-total" value="${item.dicount_value}">
                                                    <input type="text" hidden name="product_discount_actual_value[]" id="disc-actual-value${item.product_id}" product-id="${item.product_id}" class="form-control" value="${item.discountActualValue}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="location[]" id="location-field${item.product_id}" product-id="${item.product_id}" class="form-control form-control-sm small-input" placeholder="Location Field" value="${item.variant?.location?.name ?? ''}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="remarks[]" id="remarks-field${item.product_id}" product-id="${item.product_id}" class="form-control form-control-sm small-input" placeholder="Remarks Field">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;

                                                        tableBody.prepend(tableRowHTML);

                                                        // Recalculate totals
                                                        calculateItemTotals(item.product_id);
                                                    } else {
                                                        $('#qty' + item.product_id).focus();
                                                    }
                                                });

                                                // Populate kitchen invoice details in form
                                                if (kitchenInvoice) {
                                                    $('#order_type').val(kitchenInvoice.order_type);
                                                    $('#table_numbers').val(kitchenInvoice.table_number);
                                                    $('#employee').val(kitchenInvoice.employee);
                                                    $('#customer-names').val(kitchenInvoice.customer_name);
                                                    $('#customer-numbers').val(kitchenInvoice.customer_number);
                                                    $('#customer-address').val(kitchenInvoice.customer_address);
                                                }
                                                location.reload();
                                            } else {
                                                alert(response.message);
                                            }
                                        },
                                        error: function() {
                                            alert('An error occurred while trying to reload the invoice.');
                                        }
                                    });
                                }
                                // $("#scroll-horizontal-datatable").DataTable({
                                //     scrollX: !0,
                                //     language: {
                                //         paginate: {
                                //             previous: "<i class='mdi mdi-chevron-left'>",
                                //             next: "<i class='mdi mdi-chevron-right'>"
                                //         }
                                //     },
                                //     drawCallback: function() {
                                //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                                //     }
                                // })
                                console.log('page is load now');

                                var addToCartProducts = [];


                                let isWholesale = false;
                                let newProductIds = []; // Array to keep track of newly added products

                                document.getElementById('wholesaleButton').addEventListener('click', function() {
                                    // Toggle wholesale/retail price mode
                                    isWholesale = !isWholesale;

                                    if (isWholesale) {
                                        this.textContent = "Retail";
                                    } else {
                                        this.textContent = "Wholesale";
                                    }

                                    updatePriceMode();
                                });

                                function updatePriceMode() {
                                    const productRows = document.querySelectorAll('#tableBody tr');
                                    productRows.forEach(row => {
                                        const productId = row.querySelector('input[name="product_id[]"]').value;
                                        const priceInput = row.querySelector(`#retail-price${productId}`);
                                        const totalInput = row.querySelector(`#total-price${productId}`);

                                        const priceField = isWholesale ? 'wholesale_price' : 'retail_price';

                                        // Only update prices for newly added products
                                        if (newProductIds.includes(productId)) {
                                            $.ajax({
                                                url: `{{ URL::to('get-product') }}/${productId}`,
                                                type: 'GET',
                                                success: function(data) {
                                                    const productData = data.data.product;
                                                    const newPrice = productData[priceField];
                                                    priceInput.value = newPrice;
                                                    calculateItemTotals(productId);
                                                }
                                            });
                                        }
                                    });
                                }


                                let isReturnMode = false;

                                function toggleReturnMode() {
                                    isReturnMode = !isReturnMode;

                                    // Notify the user about the mode change
                                    $.toast({
                                        heading: isReturnMode ? 'Return Mode Activated' : 'Return Mode Deactivated',
                                        text: isReturnMode ?
                                            "Newly added product quantities will now be saved as negative values." :
                                            "Quantities will now be saved as positive values.",
                                        icon: 'success',
                                        loader: true,
                                        loaderBg: '#007bff',
                                        position: "top-right",
                                    });

                                    // Change the button text based on return mode
                                    const button = document.querySelector('button[onclick="toggleReturnMode()"]');
                                    if (isReturnMode) {
                                        button.textContent = 'Return'; // Set button text to "Return"
                                        button.className = 'btn btn-danger btn-sm mx-1';
                                    } else {
                                        button.textContent = 'Sale'; // Set button text to "Sale"
                                        button.className = 'btn btn-primary btn-sm mx-1';
                                    }
                                }


                                var customerId = null;

                                // Capture the change event of the select input to get the customer ID
                                $('#customer_name').change(function() {
                                    customerId = $(this).val(); // Get the selected customer ID
                                    console.log("Selected Customer ID:", customerId);
                                });

                                var customerId = null;

                                // Capture the change event of the select input to get the customer ID
                                $('#customer_name').change(function() {
                                    customerId = $(this).val(); // Get the selected customer ID
                                    console.log("Selected Customer ID:", customerId);
                                });

                                function addToCart(productId) {
                                    if (productId) {
                                        productId = parseFloat(productId);

                                        if (!addToCartProducts.some(item => item.id === productId)) {
                                            // Initialize quantity (1 by default or as per your logic)
                                            var quantity = isReturnMode ? -1 : 1;

                                            // Add product with its quantity to the array
                                            addToCartProducts.push({
                                                id: productId,
                                                quantity: quantity
                                            });

                                            // Fetch product details using AJAX
                                            $.ajax({
                                                url: `{{ URL::to('get-product') }}/${productId}`,
                                                type: 'GET',
                                                success: function(data) {
                                                    var product = data['data']['product'];
                                                    var price = isWholesale ? product['wholesale_price'] : product['retail_price'];
                                                    var quantity = isReturnMode ? -1 : 1; // Set quantity based on Return Mode

                                                    // Fetch discount details for the customer on this product
                                                    $.ajax({
                                                        url: `{{ URL::to('get-customer-discount') }}/${customerId}/${productId}`,
                                                        type: 'GET',
                                                        success: function(discountData) {
                                                            var discount = discountData.discount || 0;
                                                            var discountType = discountData.discount_type || 'Fixed';

                                                            // Always add hidden rows with fields like discount type, location, and remarks
                                                            var hiddenRowHTML = `
                                        <tr id="discount-row-${product['id']}" class="d-none">
                                            <td colspan="12">
                                                <div class="row g-1">
                                                    <div class="col-md-2">
                                                        <select name="product_discount_type[]" id="discountType${product['id']}" product-id="${product['id']}" class="form-control form-control-sm calculate-total small-input">
                                                            <option value="Fixed" ${discountType === 'Fixed' ? 'selected' : ''}>Flat</option>
                                                            <option value="Percentage" ${discountType === 'Percentage' ? 'selected' : ''}>Percentage</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" name="product_discount_value[]" id="discount-value${product['id']}" product-id="${product['id']}" class="form-control form-control-sm calculate-total small-input" value="${discount}">
                                                        <input type="text" hidden name="product_discount_actual_value[]" id="disc-actual-value${product['id']}" product-id="${product['id']}" class="form-control form-control-sm small-input" value="${discount}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" name="location[]" id="location-field${product['id']}" product-id="${product['id']}" class="form-control form-control-sm small-input" placeholder="Location Field" value="${product['location'] || ''}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="remarks[]" id="remarks-field${product['id']}" product-id="${product['id']}" class="form-control form-control-sm small-input" placeholder="Remarks Field">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    `;

                                                            // Add the product row HTML to the table
                                                            var tableRowHTML = `
                                        <tr id="row-${product['id']}" class="small-font" data-is-return="${isReturnMode}">
                                            <td class="col">
                                                <button type="button" class="btn btn-primary btn-sm p-0 small-btn" title="Add Discount" onclick="toggleDiscount(${product['id']})">+</button>
                                            </td>
                                            <td class="col">
                                                <input type="hidden" name="product_id[]" value="${product['id']}" class="form-control small-input">
                                            </td>
                                            <td class="col-4">
                                                <p style="font-weight:700;">${product['name']}</p>
                                                <input type="hidden" name="product_name[]" value="${product['name']}" class="form-control small-input">
                                            </td>
                                            <td class="col">
                                                <input type="text" name="retail_price[]" id="retail-price${product['id']}" product-id="${product['id']}" value="${price}" class="form-control small-input calculate-total">
                                            </td>
                                            <td class="col">
                                                <input type="text" name="qty[]" id="qty${product['id']}" product-id="${product['id']}" value="${quantity}" class="form-control small-input calculate-total">
                                            </td>
                                            <td class="col">
                                                <input type="text" readonly name="total[]" id="total-price${product['id']}" class="form-control small-input">
                                            </td>
                                            <td class="col">
                                                <!-- Checkbox for Inclusion -->
                                                <input
                                                    type="checkbox"
                                                    name="check[${product['id']}]"
                                                    id="include-product-${product['id']}"
                                                    value="on"
                                                    checked
                                                    onclick="toggleProductInclusion(${product['id']})">
                                            </td>
                                            <td class="col">
                                                <button type="button" class="btn btn-danger btn-sm p-0 small-btn" title="Remove Product" onclick="removeProduct(${product['id']})">&times;</button>
                                            </td>
                                        </tr>
                                    `;

                                                            // Append the product row first
                                                            $('#tableBody').prepend(tableRowHTML);

                                                            // Then append the hidden row after the product row
                                                            $('#tableBody').find('#row-' + product['id']).after(
                                                                hiddenRowHTML);

                                                            // If there's a discount, show SweetAlert for confirmation
                                                            if (discount > 0) {
                                                                Swal.fire({
                                                                    title: 'Apply Product Discount?',
                                                                    text: `Would you like to apply the discount ${discount} in / ${discountType}  to the Product?`,
                                                                    icon: 'question',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Yes',
                                                                    cancelButtonText: 'No'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Set the discount value dynamically in the hidden fields
                                                                        $('#discount-value' + product['id']).val(
                                                                            discount); // Set discount value
                                                                        // $('#disc-actual-value' + product['id']).val(discount);  // Set actual discount value

                                                                        // Set the discount type (Percentage or Flat) in the hidden fields
                                                                        $('#discountType' + product['id']).val(
                                                                            discountType); // Set discount type

                                                                        // Add event listeners for calculation after discount is applied
                                                                        $('.calculate-total').on('keyup change',
                                                                            function() {
                                                                                var productId = $(this).attr(
                                                                                    'product-id');
                                                                                calculateItemTotals(productId);
                                                                            });

                                                                        calculateItemTotals(product['id']);
                                                                    } else {
                                                                        // If no discount is applied, just add event listeners for calculation without changing the hidden fields
                                                                        $('#discount-value' + product['id']).val(
                                                                            ''
                                                                        ); // Clear the discount field if 'No' is clicked
                                                                        $('#disc-actual-value' + product['id']).val(
                                                                            ''); // Clear the actual discount field
                                                                        $('#discountType' + product['id']).val(
                                                                            ''); // Clear the discount type field

                                                                        calculateItemTotals(product['id']);
                                                                    }
                                                                });
                                                            } else {
                                                                // No discount, just add event listeners for calculation
                                                                calculateItemTotals(product['id']);
                                                            }


                                                            // Add event listeners for calculation
                                                            $('.calculate-total').on('keyup change', function() {
                                                                var productId = $(this).attr('product-id');
                                                                calculateItemTotals(productId);
                                                            });

                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error("Error fetching discount data:", error);
                                                        }
                                                    });
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error("Error fetching product data:", error);
                                                }
                                            });
                                        } else {
                                            $('#qty' + productId).focus();
                                        }
                                    } else {
                                        $.toast({
                                            heading: 'Information',
                                            text: "Please Select Product",
                                            icon: 'error',
                                            loader: true,
                                            loaderBg: '#ff0000',
                                            position: "top-right"
                                        });
                                    }
                                }

                                document.getElementById('productCards').addEventListener('click', function(event) {
                                    // Check if a product card was clicked
                                    const card = event.target.closest('.product-card');
                                    if (card) {
                                        const productId = card.getAttribute('data-product-id');

                                        // Check if a quantity input exists for the product
                                        const qtyInput = document.querySelector(`#qty${productId}`);
                                        if (qtyInput) {
                                            let currentQty = parseInt(qtyInput.value || 0);

                                            // Increment or decrement based on return mode
                                            if (isReturnMode) {
                                                qtyInput.value = currentQty - 1;
                                            } else {
                                                qtyInput.value = currentQty + 1;
                                            }

                                            calculateItemTotals(productId); // Update totals
                                        } else {
                                            addToCart(productId); // Add to cart if not already present
                                        }
                                    }
                                });



                                // function calculateItemTotals(productId) {
                                //     var salePrice = parseFloat($('#retail-price' + productId).val()) || 0; // Default to 0 if empty
                                //     var qty = parseFloat($('#qty' + productId).val()) || 0; // Default to 0 if empty
                                //     var discountType = $('#discountType' + productId).val(); // Discount type: Fixed or Percentage
                                //     var discountValue = parseFloat($('#discount-value' + productId).val()) || 0; // Default to 0 if empty

                                //     // Debugging input values
                                //     console.log("Product ID:", productId);
                                //     console.log("Sale Price:", salePrice);
                                //     console.log("Quantity:", qty);
                                //     console.log("Discount Type:", discountType);
                                //     console.log("Discount Value:", discountValue);

                                //     // Calculate total sale price
                                //     var totalSalePrice = salePrice * qty;
                                //     console.log("Total Sale Price (Before Discount):", totalSalePrice);

                                //     // Initialize net sale to total sale price
                                //     var netSale = totalSalePrice;

                                //     // Apply discount if specified
                                //     if (discountType === 'Fixed' && discountValue > 0) {
                                //         netSale -= discountValue;
                                //     } else if (discountType === 'Percentage' && discountValue > 0) {
                                //         discountValue = (totalSalePrice * discountValue) / 100;
                                //         netSale -= discountValue;
                                //     } else {
                                //         discountValue = 0; // No discount applied
                                //     }

                                //     console.log("Discount Actual Value:", discountValue);
                                //     console.log("Net Sale (After Discount):", netSale);

                                //     // Update discount and total price fields
                                //     $('#disc-actual-value' + productId).val(discountValue.toFixed(2)); // Display discount (0 if not applied)
                                //     $('#total-price' + productId).val(netSale.toFixed(2)); // Display net sale, even if no discount is applied

                                //     $('#discount-display').html('Discount:<br>' + discountValue.toFixed(2));


                                //     // Trigger grand total calculation
                                //     calculateGrandTotal();
                                //     updateTotalProducts();

                                // }

                                function calculateItemTotals(productId) {
                                    var salePrice = parseFloat($(`#retail-price${productId}`).val()) || 0;
                                    var qty = parseFloat($(`#qty${productId}`).val()) || 0;
                                    var discountType = $(`#discountType${productId}`).val();
                                    var discountValue = parseFloat($(`#discount-value${productId}`).val()) || 0;

                                    // Calculate unit price after discount
                                    var unitPriceAfterDiscount = salePrice;
                                    var totalDiscount = 0;

                                    if (discountType === 'Fixed' && discountValue > 0) {
                                        unitPriceAfterDiscount -= discountValue;
                                        totalDiscount = discountValue * qty;
                                    } else if (discountType === 'Percentage' && discountValue > 0) {
                                        var discountPerUnit = (salePrice * discountValue) / 100;
                                        unitPriceAfterDiscount -= discountPerUnit;
                                        totalDiscount = discountPerUnit * qty;
                                    }

                                    // Ensure unit price doesn't go negative
                                    unitPriceAfterDiscount = Math.max(unitPriceAfterDiscount, 0);

                                    // Calculate total
                                    var netSale = unitPriceAfterDiscount * qty;

                                    $(`#disc-actual-value${productId}`).val(totalDiscount.toFixed(2));
                                    $(`#total-price${productId}`).val(netSale.toFixed(2));
                                    $('#discount-display').html(totalDiscount.toFixed(2));

                                    calculateGrandTotal();
                                    updateTotalProducts();
                                }


                                function updateTotalProducts() {
                                    let totalProducts = 0;

                                    // Ensure addToCartProducts contains products with the expected structure
                                    addToCartProducts.forEach((product) => {
                                        const qtyElement = $(`#qty${product.id}`); // Get the quantity input field for each product
                                        if (qtyElement.length) { // Ensure the element exists
                                            const qty = parseFloat(qtyElement.val()) || product
                                                .quantity; // Fallback to product.quantity if input is empty
                                            totalProducts += qty;
                                        } else {
                                            console.error(`Quantity input for product with ID ${product.id} not found!`);
                                        }
                                    });

                                    // Update the total quantity display
                                    $('#qty').html(totalProducts);
                                }

                                // Define global variables
                                let isReturnChecked = false;
                                const saleItems = new Set(); // Set to track items added as sales
                                const returnItems = new Set(); // Set to track items added as returns

                                // Set up the checkbox and add an event listener
                                const returnCheckbox = document.getElementById("return-checkbox");
                                returnCheckbox.addEventListener("change", function() {
                                    isReturnChecked = returnCheckbox.checked;
                                    console.log(`Checkbox state: ${isReturnChecked ? "Return Mode" : "Sale Mode"}`);
                                });

                                function calculateGrandTotal() {
                                    let totalAmount = 0;
                                    let netPayable = 0;

                                    // Calculate total from all products
                                    addToCartProducts.forEach((product) => {
                                        let itemTotalElement = document.getElementById('total-price' + product.id);
                                        if (itemTotalElement) {
                                            let itemTotal = parseFloat(itemTotalElement.value) || 0;
                                            totalAmount += itemTotal;
                                        }
                                    });

                                    $('#net-sale').html(totalAmount.toFixed(2));
                                    netPayable = totalAmount;

                                    // Apply discount
                                    let discountType = document.getElementById('discount-type').value;
                                    let discountValue = parseFloat(document.getElementById('discount-value').value) || 0;
                                    let discountAmount = 0;

                                    if (discountType === 'Fixed') {
                                        discountAmount = discountValue;
                                        netPayable -= discountAmount;
                                    } else if (discountType === 'Percentage') {
                                        discountAmount = (totalAmount * discountValue) / 100;
                                        netPayable -= discountAmount;
                                    }
                                    document.getElementById('discount-amount').value = discountAmount.toFixed(2);
                                    $('#discount-display').html(discountAmount.toFixed(2));

                                    // Add service charges
                                    let serviceCharges = parseFloat(document.getElementById('service_charges').value) || 0;
                                    netPayable += serviceCharges;

                                    // Update UI
                                    document.getElementById('total-bill').value = totalAmount.toFixed(2);
                                    $('#total').html(netPayable.toFixed(2));
                                    $('#net-payable-card').html(netPayable.toFixed(2));
                                    document.getElementById('net-payable').value = netPayable.toFixed(2);

                                    calculateBalance();
                                }
                                $('.calculate-total').on('keyup change', function() {
                                    calculateGrandTotal();
                                });

                                // Initial call when modal loads
                                $('#standard-modal').on('shown.bs.modal', function() {
                                    calculateGrandTotal();
                                });
                            </script>

                            <script>
                                // Initialize Select2 for all elements with the class "select2"
                                $(document).ready(function() {
                                    $('.select2').select2();
                                });
                            </script>

                            <script>
                                function calculateBalance() {
                                    // Get the values of received amount and net payable
                                    const receivedAmount = parseFloat(document.getElementById('received_amount').value) || 0;
                                    const netPayable = parseFloat(document.getElementById('net-payable').value) || 0;
                                    const balance = receivedAmount - netPayable;
                                    document.getElementById('balance').textContent = ' ' + balance.toFixed(2);
                                }
                            </script>

                            <script>
                                function removeProduct(productId) {
                                    if (!productId) {
                                        console.error("Invalid productId provided to removeProduct");
                                        return;
                                    }

                                    // Reset the quantity to 0 before removing
                                    const qtyInput = $(`#qty${productId}`);
                                    if (qtyInput.length > 0) {
                                        qtyInput.val(0).trigger('change'); // Trigger any event listeners attached
                                    }

                                    // Remove the product row from the DOM
                                    $(`#row-${productId}`).remove();
                                    $(`#discount-row-${productId}`).remove();

                                    // Remove the product from the addToCartProducts array
                                    const productIndex = addToCartProducts.findIndex(item => item.id === productId);
                                    if (productIndex !== -1) {
                                        addToCartProducts.splice(productIndex, 1);
                                    }

                                    // Recalculate grand totals after product removal
                                    calculateGrandTotal();
                                    updateTotalProducts();
                                }



                                function toggleProductInclusion(productId) {
                                    const checkbox = $(`#include-product-${productId}`);
                                    const isChecked = checkbox.is(':checked');
                                    const productRow = $(`#row-${productId}`);
                                    const discountRow = $(`#discount-row-${productId}`);

                                    if (isChecked) {
                                        // Check if product already exists in addToCartProducts
                                        const existingProduct = addToCartProducts.find(product => product.id === productId);
                                        if (!existingProduct) {
                                            // Add the product with default quantity (e.g., 1) , quantity: 0
                                            addToCartProducts.push({
                                                id: productId
                                            });
                                        }
                                    } else {
                                        // Remove product from addToCartProducts
                                        addToCartProducts = addToCartProducts.filter(product => product.id !== productId);
                                    }

                                    // Recalculate totals if needed
                                    calculateItemTotals(productId);
                                }
                            </script>

                            <script>
                                document.getElementById('customer_name').addEventListener('change', function() {
                                    const customerName = this.value;

                                    // Reset the discount fields before applying new data
                                    document.getElementById('discount-type').value = 'Select'; // Reset discount type
                                    document.getElementById('discount-value').value = '0'; // Reset discount value

                                    // Call the calculateGrandTotal() to update any existing calculations
                                    calculateGrandTotal(); // Ensure calculation runs when discount fields are reset

                                    $.ajax({
                                        type: 'GET',
                                        url: 'get-customer-balance/' + customerName,
                                    }).done(function(data) {
                                        const customerBalance = data.data.customerBalance.balance; // Extract balance
                                        const discountType = data.data.customerBalance.discount_type;
                                        const discountValue = data.data.customerBalance.discount_value;

                                        // Show the customer balance in the customer receivable input field
                                        document.getElementById('customer_reciveable').value = customerBalance;

                                        // If discount type and value are not 'null', show the SweetAlert
                                        if (discountType !== 'null' && discountValue !== 'null') {
                                            // Show SweetAlert to confirm whether to apply the discount
                                            Swal.fire({
                                                title: 'Apply Customer Bill Discount?',
                                                text: `Would you like to apply the discount ${discountValue} in / ${discountType}  to the bill?`,
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // If Yes, set the discount type and value in the fields
                                                    document.getElementById('discount-type').value = discountType;
                                                    document.getElementById('discount-value').value = discountValue;

                                                    // Recalculate grand total to include the discount
                                                    calculateGrandTotal();
                                                } else {
                                                    // If No, reset the discount fields
                                                    document.getElementById('discount-type').value =
                                                        'Select'; // Reset to default option
                                                    document.getElementById('discount-value').value = '0'; // Reset to 0

                                                    // Recalculate grand total to remove any previously applied discount
                                                    calculateGrandTotal();
                                                }
                                            });
                                        } else {
                                            // No discount for the customer, so just reset and calculate the total
                                            calculateGrandTotal();
                                        }
                                    });
                                });
                            </script>

                            <!-- JavaScript to toggle visibility -->
                            <script>
                                document.getElementById('payment_type').addEventListener('change', function() {
                                    const accountNameContainer = document.getElementById('account_name');
                                    const received_amount_cont = document.getElementById('received_amount_cont');
                                    const due_date = document.getElementById('due_date');

                                    if (this.value === 'credit') {
                                        accountNameContainer.classList.add('d-none'); // Hide account name field
                                        received_amount_cont.classList.add('d-none'); // Hide received amount field
                                        due_date.classList.remove('d-none'); // Show due date field
                                    } else if (this.value === 'cash+credit') {
                                        accountNameContainer.classList.remove('d-none'); // Show account name field
                                        received_amount_cont.classList.remove('d-none'); // Show received amount field
                                        due_date.classList.add('d-none'); // Hide due date field
                                    } else {
                                        // Default case (e.g., 'cash')
                                        accountNameContainer.classList.remove('d-none'); // Show account name field
                                        received_amount_cont.classList.remove('d-none'); // Show received amount field
                                        due_date.classList.add('d-none'); // Hide due date field
                                    }
                                });
                            </script>

                            {{-- discount button  --}}
                            <script>
                                function toggleDiscount(productId) {
                                    const discountRow = document.getElementById(`discount-row-${productId}`);
                                    if (discountRow.classList.contains('d-none')) {
                                        discountRow.classList.remove('d-none');
                                    } else {
                                        discountRow.classList.add('d-none');
                                    }
                                }
                            </script>

                            {{-- filter category wise product --}}
                            <script>
                                function filterByCategory() {
                                    const categoryId = document.getElementById('categoryFilter').value;

                                    // Send an AJAX request to filter products by category
                                    fetch(`{{ url('/filter-products') }}?category=${categoryId}`, {
                                            method: 'GET',
                                            headers: {
                                                'Content-Type': 'application/json',
                                            },
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('No products found');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            const productCards = document.getElementById('productCards');
                                            productCards.innerHTML = ''; // Clear existing cards

                                            if (data.filterproducts && data.filterproducts.length > 0) {
                                                // Loop through the filtered products and create cards
                                                data.filterproducts.forEach(product => {
                                                    // Use the media URL for product image
                                                    const imageUrl = product.media ||
                                                        "https://via.placeholder.com/100"; // Fallback if no image is found

                                                    const card = `
                                    <div class="card text-center small-card product-card" data-product-id="${product.id}" style="background-color: rgb(219, 244, 248)">
                                        <img src="${imageUrl}" alt="Product Var Image" width="60" height="60" style="object-fit: cover; border-radius:6px;">
                                        <div class="card-body">
                                            <h6 class="card-title">${product.product_variant_name}</h6>
                                            <p class="card-text"style="font-weight:700;">Price: ${product.rates.retail_price}</p>
                                        </div>
                                    </div>
                            `;
                                                    document.getElementById("productCards").innerHTML +=
                                                        card; // Append each new card to the productCards container
                                                });
                                            } else {
                                                // If no products found, show a placeholder message
                                                document.getElementById("productCards").innerHTML =
                                                    '<p class="text-center">No products found</p>';
                                            }


                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            document.getElementById('productCards').innerHTML = '<p class="text-center">No products found</p>';
                                        });
                                }
                            </script>

                            {{-- f2 qty shortcut --}}
                            <script>
                                // Listen for the F2 key press event
                                document.addEventListener('keydown', function(event) {
                                    if (event.keyCode === 113) {
                                        const firstQtyField = document.querySelector('input[name="qty[]"]');
                                        if (firstQtyField) {
                                            firstQtyField.focus();
                                        }
                                    }
                                });
                            </script>

                            {{-- end button shortcut key --}}
                            <script>
                                document.addEventListener('keydown', function(event) {
                                    if (event.keyCode === 35) { // Right Arrow key
                                        const button = document.getElementById('endCashButton');
                                        const currentState = button.getAttribute('data-toggle-state');

                                        // Toggle button state
                                        if (currentState === 'off') {
                                            button.setAttribute('data-toggle-state', 'on');
                                            button.classList.remove('btn-success');
                                            button.classList.add('btn-danger');
                                            button.innerHTML = '<i class="mdi mdi-minus-circle me-2"></i>Close Cash';
                                        } else {
                                            button.setAttribute('data-toggle-state', 'off');
                                            button.classList.remove('btn-danger');
                                            button.classList.add('btn-success');
                                            button.innerHTML = '<i class="mdi mdi-plus-circle me-2"></i>End Cash';
                                        }

                                        // Trigger the modal
                                        const modal = new bootstrap.Modal(document.getElementById('standard-modal'));
                                        modal.show();

                                        // Prevent default action of the key if necessary
                                        event.preventDefault();
                                    }
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const saleButton = document.getElementById('saleButton');
                                    const receivedAmountInput = document.getElementById('received_amount');
                                    const paymentTypeSelect = document.getElementById('payment_type');

                                    // Add click event listener to Save Sale button
                                    saleButton.addEventListener('click', function(event) {
                                        const paymentType = paymentTypeSelect.value;
                                        const receivedAmount = parseFloat(receivedAmountInput.value) || 0;

                                        // Check if payment type is 'cash' or 'cash+credit' and received amount is empty or zero
                                        if ((paymentType === 'cash' || paymentType === 'cash+credit') && receivedAmount <= 0) {
                                            event.preventDefault(); // Prevent form submission

                                            Swal.fire({
                                                title: 'Received Amount Required',
                                                text: 'Please enter a valid received amount greater than 0.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                receivedAmountInput.focus(); // Focus on the received amount field
                                            });

                                            return; // Exit the function to prevent form submission
                                        }

                                        // If validation passes, allow form submission
                                        document.getElementById('saleForm').submit();
                                    });

                                    // Existing keyboard shortcut handler
                                    document.addEventListener('keydown', function(event) {
                                        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                                            event.preventDefault();
                                            const paymentType = paymentTypeSelect.value;
                                            const receivedAmount = parseFloat(receivedAmountInput.value) || 0;

                                            if ((paymentType === 'cash' || paymentType === 'cash+credit') && receivedAmount <= 0) {
                                                Swal.fire({
                                                    title: 'Received Amount Required',
                                                    text: 'Please enter a valid received amount greater than 0.',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    receivedAmountInput.focus();
                                                });
                                            } else {
                                                saleButton.click();
                                            }
                                        }
                                        // ... rest of your existing keyboard shortcuts ...
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Get references to elements
                                    const endCashButton = document.getElementById('endCashButton');
                                    const paymentTypeSelect = document.getElementById('payment_type');
                                    const customerSelect = document.getElementById('customer_name');
                                    const modal = new bootstrap.Modal(document.getElementById('standard-modal'));

                                    // Add click event listener to End Cash button
                                    endCashButton.addEventListener('click', function() {
                                        // Get current values when modal is about to open
                                        const paymentType = paymentTypeSelect.value;
                                        const customerId = customerSelect.value;

                                        // Check conditions
                                        if (paymentType === 'cash') {
                                            // If payment type is cash, do nothing and let modal open normally
                                            modal.show();
                                        } else if (paymentType === 'credit' || paymentType === 'cash+credit') {
                                            // If payment type is credit or partial payment
                                            if (customerId && customerId !== '') {
                                                // If party is selected, do nothing and let modal open normally
                                                modal.show();
                                            } else {
                                                // If party is not selected, show SweetAlert and reset to cash
                                                Swal.fire({
                                                    title: 'Customer Not Selected',
                                                    text: 'Please select a Customer first for credit or partial payment.',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    // Reset payment type to cash
                                                    paymentTypeSelect.value = 'cash';
                                                    // Trigger change event to update any dependent UI
                                                    paymentTypeSelect.dispatchEvent(new Event('change'));
                                                });
                                            }
                                        }
                                    });

                                    // Optional: Real-time validation when payment type changes
                                    paymentTypeSelect.addEventListener('change', function() {
                                        const paymentType = this.value;
                                        const customerId = customerSelect.value;

                                        // Only perform check if modal is about to be opened
                                        if ((paymentType === 'credit' || paymentType === 'cash+credit') &&
                                            !customerId &&
                                            document.getElementById('standard-modal').classList.contains('show')) {
                                            Swal.fire({
                                                title: 'Party Not Selected',
                                                text: 'Please select a party first for credit or partial payment.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                this.value = 'cash';
                                                this.dispatchEvent(new Event('change'));
                                            });
                                        }
                                    });
                                });
                            </script>

                            <script>
                                // Flag to track if form has been submitted
                                let isFormSubmitted = false;

                                // Save data to localStorage before the page unloads
                                window.addEventListener('beforeunload', function() {
                                    if (!isFormSubmitted) {
                                        console.log('Saving cart data to localStorage...');

                                        // Create an array to hold products and their quantities
                                        const cartData = addToCartProducts.map(product => {
                                            const qtyForLocal = $('#qty' + product.id)
                                                .val(); // Get the quantity from the input field
                                            return {
                                                id: product.id,
                                                quantity: qtyForLocal, // Save the quantity from the input field
                                            };
                                        });

                                        // Save the data to localStorage
                                        localStorage.setItem('cartData', JSON.stringify({
                                            products: cartData,
                                            tableHtml: $('#tableBody').html()
                                        }));
                                    } else {
                                        console.log('Form submitted, not saving cart data.');
                                    }
                                });

                                // Restore data from localStorage on page load
                                document.addEventListener('DOMContentLoaded', function() {
                                    const removeStorageButton = document.getElementById('removeStorageButton');

                                    if (removeStorageButton) {
                                        removeStorageButton.addEventListener('click', function() {
                                            console.log('Remove Storage button clicked, clearing cart data from localStorage...');
                                            localStorage.removeItem('cartData');
                                            $('#tableBody').html('');
                                            addToCartProducts = [];
                                            alert('Cart data has been removed from localStorage and the table is cleared.');
                                        });
                                    } else {
                                        console.error('Remove Storage button not found.');
                                    }

                                    // Restore data only if the form has not been submitted
                                    if (!isFormSubmitted) {
                                        const cartData = localStorage.getItem('cartData');
                                        if (cartData) {
                                            console.log('Restoring cart data from localStorage...');
                                            const {
                                                products,
                                                tableHtml
                                            } = JSON.parse(cartData);

                                            // Restore products array and table HTML
                                            addToCartProducts = products || [];
                                            $('#tableBody').html(tableHtml || '');

                                            // Restore quantity in input fields and trigger total recalculation for each product
                                            addToCartProducts.forEach(product => {
                                                // Find the correct input field and set the quantity value
                                                const qtyInput = $('#qty' + product.id);
                                                if (qtyInput.length) {
                                                    qtyInput.val(product.quantity); // Restore quantity in the input
                                                    calculateItemTotals(product.id); // Trigger recalculation for the product
                                                } else {
                                                    console.error(`Input field for product ID ${product.id} not found.`);
                                                }
                                            });

                                            // Rebind event listeners for quantity changes
                                            $('.calculate-total').on('keyup change', function() {
                                                const productId = $(this).attr('product-id');
                                                calculateItemTotals(productId); // Trigger total recalculation
                                            });
                                        } else {
                                            console.log('No cart data found in localStorage.');
                                        }
                                    }

                                });

                                // Form submit event
                                document.querySelector('#saleForm').addEventListener('submit', function(event) {
                                    console.log('Form submitted. Removing cart data from localStorage...');
                                    isFormSubmitted = true; // Mark form as submitted
                                    localStorage.removeItem('cartData'); // Remove cart data from localStorage
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const form = document.getElementById('saleForm');
                                    const formActionInput = document.getElementById('form_action');

                                    document.getElementById('holdButton').addEventListener('click', function() {

                                        form.action = "{{ route('hold.invoice') }}";
                                        formActionInput.value = 'hold';
                                        form.submit();
                                    });

                                    document.getElementById('saleButton').addEventListener('click', function() {

                                        form.action = "{{ route('sale.invoice') }}";
                                        formActionInput.value = 'sale';
                                        form.submit();
                                    });

                                    document.getElementById('quotation_save_button').addEventListener('click', function() {
                                        form.action = "{{ route('quotaion.invoice') }}";
                                        formActionInput.value = 'quotation';
                                        form.submit();
                                    });
                                });
                            </script>

                            <script>
                                let currentIndex = -1;
                                let scanBuffer = '';
                                let scanTimeout;
                                let debounceTimer;
                                const cache = {};
                                let currentSelectedIndex = -1;

                                // Function to handle product search and display results - REPLACE THE ENTIRE FUNCTION
                                function searchProduct() {
                                    const query = $('#product_search').val().trim();
                                    const productList = $('#product_list');

                                    if (query.length < 2) {
                                        productList.hide();
                                        productList.html("");
                                        currentSelectedIndex = -1; // Reset selection
                                        return;
                                    }

                                    $.ajax({
                                        url: "{{ url('/fetch-products-for-sale') }}",
                                        type: "GET",
                                        data: {
                                            query: query
                                        },
                                        success: function(data) {
                                            if (data.length > 0) {
                                                let listItems = '';
                                                data.forEach((product, index) => {
                                                    listItems += `
                                                        <li class="list-group-item list-item"
                                                            data-index="${index}"
                                                            data-id="${product.id}"
                                                            data-code="${product.code}"
                                                            data-name="${product.product_variant_name}"
                                                            style="cursor: pointer;"
                                                            onclick="selectProductFromList(this)">
                                                            ${product.code} / ${product.product_variant_name}
                                                        </li>`;
                                                });
                                                productList.html(listItems);
                                                productList.show();
                                                currentSelectedIndex = -1; // Reset when new results
                                            } else {
                                                productList.html('<li class="list-group-item text-muted">No products found</li>');
                                                productList.show();
                                            }
                                        }
                                    });
                                }

                                // NEW FUNCTION to select product from list - ADD THIS AFTER searchProduct() function
                                function selectProductFromList(element) {
                                    const productId = $(element).data('id');
                                    const productCode = $(element).data('code');
                                    const productName = $(element).data('name');

                                    addProductToCart(productId, productCode, productName);
                                }

                                // NEW: Helper function to add product to cart - ADD THIS AFTER selectProductFromList
                                function addProductToCart(productId, productCode, productName) {
                                    const qtyInput = $(`#qty${productId}`);

                                    if (qtyInput.length > 0) {
                                        // Product already in cart, update quantity
                                        let currentQty = parseInt(qtyInput.val()) || 0;
                                        if (isReturnMode) {
                                            qtyInput.val(Math.max(currentQty - 1, 0));
                                        } else {
                                            qtyInput.val(currentQty + 1);
                                        }
                                        calculateItemTotals(productId);
                                    } else {
                                        // Add new product to cart
                                        addToCart(productId);
                                    }

                                    // Clear search
                                    $('#product_search').val('');
                                    $('#product_list').hide().html('');
                                    currentSelectedIndex = -1;
                                }

                                // Listen for input events to automatically search for the product as it is scanned or typed
                                $('#product_search').on('input', function() {
                                    const query = $(this).val().trim();
                                    clearTimeout(debounceTimer);
                                    debounceTimer = setTimeout(() => {
                                        if (query.length >= 2) {
                                            searchProduct(query); // Trigger product search after debounce delay
                                        }
                                    }, 300); // Adjust debounce time for optimal performance
                                    if (query) {

                                        $('#product_list').hide();
                                    }
                                });

                                // Prevent form submission when scanner simulates an "Enter" key press
                                $('#product_search').on('keydown', function(e) {
                                    const productList = $('#product_list');
                                    const items = productList.find('.list-item'); // Changed from .list-group-item to .list-item

                                    if (e.key === 'Enter') {
                                        e.preventDefault();

                                        if (items.length > 0) {
                                            // If user pressed Enter without using arrow keys, use first item
                                            if (currentSelectedIndex === -1) {
                                                currentSelectedIndex = 0;
                                            }

                                            const selectedItem = items.eq(currentSelectedIndex);
                                            if (selectedItem.length) {
                                                const productId = selectedItem.data('id');
                                                const productCode = selectedItem.data('code');
                                                const productName = selectedItem.data('name');

                                                addProductToCart(productId, productCode, productName);
                                            }
                                        }

                                        // Clear and hide after selection
                                        $(this).val('');
                                        productList.hide().html('');
                                        currentSelectedIndex = -1;

                                    } else if (e.key === 'ArrowDown') {
                                        e.preventDefault();
                                        if (items.length > 0) {
                                            items.removeClass('active');

                                            currentSelectedIndex = (currentSelectedIndex + 1) % items.length;

                                            const selectedItem = items.eq(currentSelectedIndex);
                                            selectedItem.addClass('active');

                                            // ADD THIS LINE TO SCROLL TO SELECTED ITEM
                                            selectedItem[0].scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'nearest'
                                            });
                                        }

                                    } else if (e.key === 'ArrowUp') {
                                        e.preventDefault();
                                        if (items.length > 0) {
                                            items.removeClass('active');

                                            currentSelectedIndex = (currentSelectedIndex - 1 + items.length) % items.length;

                                            const selectedItem = items.eq(currentSelectedIndex);
                                            selectedItem.addClass('active');

                                            // ADD THIS LINE TO SCROLL TO SELECTED ITEM
                                            selectedItem[0].scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'nearest'
                                            });
                                        }
                                    } else if (e.key === 'Escape') {
                                        // Clear on escape
                                        $(this).val('');
                                        productList.hide().html('');
                                        currentSelectedIndex = -1;
                                    }
                                });

                                $(document).on('click', function(event) {
                                    const searchInput = $('#product_search');
                                    const productList = $('#product_list');

                                    if (!$(event.target).closest('#product_search').length && !$(event.target).closest('#product_list')
                                        .length) {
                                        searchInput.val('');
                                        productList.hide();
                                        productList.html("");
                                        currentIndex = -1;
                                    }
                                });
                            </script>

                            <script>
                                function fetchHoldCount() {
                                    fetch(`{{ url('/get-hold-count') }}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && data.count >= 0) {
                                                updateHoldCount(data.count);
                                            }
                                        })
                                        .catch(error => console.error('Error fetching hold count:', error));
                                }

                                function updateHoldCount(count) {
                                    const holdBadge = document.getElementById('holdCountBadge'); // Badge element
                                    if (count > 0) {
                                        holdBadge.textContent = count;
                                        holdBadge.style.display = 'inline';
                                    } else {
                                        holdBadge.style.display = 'none';
                                    }
                                }

                                window.onload = fetchHoldCount;
                            </script>

                            <script>
                                function fetchQuotationCount() {
                                    fetch(`{{ url('/get-quotation-count') }}`)
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(`HTTP error! status: ${response.status}`);
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data && data.count >= 0) {
                                                updateQuotationCount(data.count);
                                            }
                                        })
                                        .catch(error => console.error('Error fetching quotation count:', error));
                                }

                                function updateQuotationCount(count) {
                                    const quotationBadge = document.getElementById('quotationCountBadge');
                                    if (count > 0) {
                                        quotationBadge.textContent = count;
                                        quotationBadge.style.display = 'inline';
                                    } else {
                                        quotationBadge.style.display = 'none';
                                    }
                                }

                                document.addEventListener('DOMContentLoaded', fetchQuotationCount);
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const saleButton = document.getElementById('saleButton');
                                    const quotationButton = document.getElementById('quotation_modal_button');
                                    const holdButton = document.getElementById('holdButton');
                                    const received_amount = document.getElementById('received_amount');
                                    const payment_type = document.getElementById('payment_type');

                                    // Listen for keydown events on the entire document
                                    document.addEventListener('keydown', function(event) {
                                        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
                                            event.preventDefault();
                                            saleButton.click();
                                        }

                                        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                                            event.preventDefault();
                                            quotationButton.click();
                                        }
                                        if ((event.ctrlKey || event.metaKey) && event.key === 'h') {
                                            event.preventDefault();
                                            holdButton.click();
                                        }
                                        if ((event.ctrlKey || event.metaKey) && event.key === 'r') {
                                            event.preventDefault();

                                            if (received_amount) {
                                                received_amount.focus();
                                            }
                                        }

                                    });
                                });


                                const table_card = document.getElementById('table_card');

                                const tableBody = document.getElementById('tableBody');

                                function toggleFullScreen() {
                                    // Check if the document is already in fullscreen mode
                                    if (!document.fullscreenElement) {
                                        // Request fullscreen on the body (or any element you want to)
                                        if (document.documentElement.requestFullscreen) {
                                            document.documentElement.requestFullscreen();
                                        } else if (document.documentElement.mozRequestFullScreen) { // Firefox
                                            document.documentElement.mozRequestFullScreen();
                                        } else if (document.documentElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
                                            document.documentElement.webkitRequestFullscreen();
                                        } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
                                            document.documentElement.msRequestFullscreen();
                                        }
                                        table_card.style.maxHeight = '65vh';
                                        tableBody.style.maxHeight = '55vh';
                                        table_card.style.height = '65vh';


                                    } else {
                                        table_card.style.maxHeight = '65vh';
                                        tableBody.style.maxHeight = '55vh';
                                        table_card.style.height = '65vh';
                                        // If already in fullscreen, exit fullscreen
                                        if (document.exitFullscreen) {
                                            document.exitFullscreen();
                                        } else if (document.mozCancelFullScreen) { // Firefox
                                            document.mozCancelFullScreen();
                                        } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
                                            document.webkitExitFullscreen();
                                        } else if (document.msExitFullscreen) { // IE/Edge
                                            document.msExitFullscreen();
                                        }

                                    }
                                }

                                document.addEventListener('keydown', function(event) {
                                    if (event.key === "F11") {
                                        event.preventDefault();
                                        toggleFullScreen();

                                    }
                                });

                                function refreshPage() {
                                    location.reload();
                                }
                            </script>

                            <script>
                                $(document).ready(function() {
                                    let isFilterApplied = false; // Track whether the filter is applied or not
                                    let originalProducts = []; // Store the original product cards

                                    $('#bestSellingBtn').click(function(event) {
                                        // Prevent form submission (if the button is inside a form)
                                        event.preventDefault();

                                        // Toggle filter state
                                        isFilterApplied = !isFilterApplied;

                                        if (isFilterApplied) {
                                            // Save the current product cards (if not already saved)
                                            if (originalProducts.length === 0) {
                                                originalProducts = $('#productCards').html();
                                            }

                                            // Apply the filter
                                            $.ajax({
                                                url: '{{ URL::to('best-selling-products') }}', // Adjust URL if needed
                                                type: 'GET',
                                                success: function(data) {
                                                    // Clear existing products
                                                    $('#productCards').html('');

                                                    // Check if we have products in the response
                                                    if (data && data.length > 0) {
                                                        // Loop through the fetched best-selling products and add them to the page
                                                        data.forEach(function(product) {
                                                            const imageUrl = product.media ||
                                                                "https://via.placeholder.com/100"; // Fallback if no image is found
                                                            let productCard = `
                                        <div class="col custom-item">
                                            <div class="card text-center small-card product-card" style="background-color: rgb(219, 244, 248)" data-product-id="${product.id}">
                                                <img src="${imageUrl}" alt="Product Var Image" width="60" height="60" style="object-fit: cover; border-radius: 6px;">
                                                <div class="card-body">
                                                    <h6 class="card-title">${product.product_variant_name}</h6>
                                                    <p class="card-text" style="font-weight:700;">Price: ${product.rates ? product.rates.retail_price : 'N/A'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                                            $('#productCards').append(productCard);
                                                        });
                                                        $('#bestSellingBtn').text(
                                                            'Remove Filter'); // Update button text
                                                    } else {
                                                        $('#productCards').html(
                                                            '<p class="text-center">No best-selling products found.</p>'
                                                        );
                                                        $('#bestSellingBtn').text(
                                                            'Remove Filter'); // Update button text
                                                    }
                                                },
                                                error: function() {
                                                    alert('Failed to fetch best-selling products');
                                                }
                                            });
                                        } else {
                                            // Remove the filter and restore the original products
                                            $('#productCards').html(originalProducts);
                                            $('#bestSellingBtn').text('Apply Best-Selling Filter'); // Update button text
                                        }
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const orderType = document.getElementById('order_type');
                                    const tableSelection = document.getElementById('table_number');
                                    const customername = document.getElementById('customer-name');
                                    const customernumber = document.getElementById('customer-number');
                                    const customerAddress = document.getElementById('customer-address');
                                    const employee = document.getElementById('employees');

                                    // Event listener for order type selection
                                    orderType.addEventListener('change', function() {
                                        const selectedValue = this.value;

                                        // Pehle sabko hide kar do
                                        tableSelection.classList.add('d-none');
                                        employee.classList.add('d-none');
                                        customername.classList.add('d-none');
                                        customernumber.classList.add('d-none');
                                        customerAddress.classList.add('d-none'); // Ensure address is hidden by default

                                        if (selectedValue === 'dine-in') {
                                            tableSelection.classList.remove('d-none'); // Show table selection
                                            employee.classList.remove('d-none'); // Show employee selection
                                        } else if (selectedValue === 'delivery') {
                                            customername.classList.remove('d-none'); // Show customer details
                                            customernumber.classList.remove('d-none'); // Show customer number
                                            customerAddress.classList.remove('d-none'); // Show customer address
                                        } else if (selectedValue === 'takeaway') {
                                            customername.classList.remove('d-none'); // Show customer name only
                                            customernumber.classList.remove('d-none'); // Show customer number
                                        }
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const statusDropdowns = document.querySelectorAll('.status-dropdown');

                                    statusDropdowns.forEach(dropdown => {
                                        dropdown.addEventListener('change', function() {
                                            const invoiceId = this.getAttribute('data-id');
                                            const newStatus = this.value;

                                            // Use Laravel's `url()` helper to dynamically generate the endpoint
                                            const updateUrl = `{{ url('/update-status') }}/${invoiceId}`;

                                            fetch(updateUrl, {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector(
                                                            'meta[name="csrf-token"]').getAttribute('content')
                                                    },
                                                    body: JSON.stringify({
                                                        status: newStatus
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        alert('Status updated successfully!');
                                                    } else {
                                                        alert('Failed to update status.');
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('An error occurred while updating the status.');
                                                });
                                        });
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const tableDropdown = document.getElementById('table_numbers');
                                    const orderTypeDropdown = document.getElementById('order_type');

                                    // Function to check table status via AJAX
                                    function checkTableStatus(tableId) {
                                        if (!tableId) return; // Exit if no table is selected

                                        fetch(`{{ url('/check-table-status') }}/${tableId}`, {
                                                method: 'GET',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                        'content')
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status === 'reserved') {
                                                    Swal.fire({
                                                        title: 'Table Already Reserved',
                                                        text: `Table ${data.table_number} is already reserved. Please select another table.`,
                                                        icon: 'warning',
                                                        confirmButtonText: 'OK'
                                                    }).then(() => {
                                                        // Reset the dropdown to "Select Table" after alert
                                                        tableDropdown.value = '';
                                                    });
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error checking table status:', error);
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: 'An error occurred while checking the table status.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            });
                                    }

                                    // Event listener for table selection change
                                    tableDropdown.addEventListener('change', function() {
                                        const selectedOrderType = orderTypeDropdown.value;
                                        const selectedTableId = this.value;

                                        // Only check table status if order type is "dine-in" and a table is selected
                                        if (selectedOrderType === 'dine-in' && selectedTableId) {
                                            checkTableStatus(selectedTableId);
                                        }
                                    });

                                    // Ensure table check triggers when order type changes to "dine-in"
                                    orderTypeDropdown.addEventListener('change', function() {
                                        const selectedTableId = tableDropdown.value;
                                        if (this.value === 'dine-in' && selectedTableId) {
                                            checkTableStatus(selectedTableId);
                                        }
                                    });
                                });
                            </script>
                        @endsection
                        <!-- container -->
