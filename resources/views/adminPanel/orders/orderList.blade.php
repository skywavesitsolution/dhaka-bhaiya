@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <h4 class="page-title">Orders list</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 d-flex flex-row flex-row-reverse">

                                <a href="{{ URL::to('create-order') }}" target="_blank" class="btn btn-warning">Create
                                    order</a>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-10">
                                Orders List
                            </div>
                            <!-- <div class="col-sm-1">

                                    <div class="text-sm-end">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#standard-modal">Add Accounts</button>
                                    </div>
                                </div>
                                <div class="col-sm-1">

                                    <div class="text-sm-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cash-deposit">Cash Deposit</button>
                                    </div>
                                </div> -->
                            <!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-sm table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>Sr No</th>
                                        <th>Order Date</th>
                                        <th>Order ID</th>
                                        <th>Product Type</th>
                                        <th>Purchase</th>
                                        <th>Carriage</th>
                                        <th>Sale</th>
                                        <th>Profit</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($allOrders)
                                        @foreach ($allOrders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-m-Y', strtotime($order->date)) }}</td>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->product->name ?? '' }}</td>
                                                <td>
                                                    <h6>Maraka: {{ $order->maraka->name }}</h6>
                                                    <h6>Qty: {{ number_format($order->purchase_qty) }}</h6>
                                                    <h6>Purchase Rate: {{ number_format($order->purchase_rate) }}</h6>
                                                    <h6>Total Purchase: {{ number_format($order->total_purchase) }}</h6>
                                                </td>
                                                <td>
                                                    <h6>Driver: {{ $order->driver->name }}</h6>
                                                    <h6>Carriage Amount: {{ number_format($order->carriage_amount) }}</h6>
                                                    <h6>Total Carriage: {{ number_format($order->total_carriage) }}</h6>
                                                </td>
                                                <td>
                                                    <h6>Supplier: {{ $order->supplier->name }}</h6>
                                                    <h6>Customer: {{ $order->customer->name }}</h6>
                                                    <h6>Sale Price: {{ number_format($order->sale_rate) }}</h6>
                                                    <h6>Total Sale: {{ number_format($order->total_sale_amount) }}</h6>
                                                </td>
                                                <td>{{ number_format($order->profit) }}</td>
                                                @php
                                                    $user = Auth::user();
                                                    $currentDate = date('Y-m-d');
                                                    $orderDate = date('Y-m-d', strtotime($order->created_at));
                                                @endphp

                                                @if ($user->roles->first()->name == 'User' && $currentDate == $orderDate)
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a href="{{ URL::to('order-update/' . $order->id) }}"
                                                                class="btn btn-info btn-sm"><i
                                                                    class="mdi mdi-clipboard-edit-outline"></i></a>
                                                            <a href="{{ URL::to('order-delete/' . $order->id) }}"
                                                                onclick="return confirm('Are You Sure To Delete This')"
                                                                class="btn btn-danger btn-sm">X</a>
                                                        </div>
                                                    </td>
                                                @endif
                                                @if ($user->roles->first()->name == 'Admin')
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a href="{{ URL::to('order-update/' . $order->id) }}"
                                                                class="btn btn-info btn-sm"><i
                                                                    class="mdi mdi-clipboard-edit-outline"></i></a>
                                                            <a href="{{ URL::to('order-delete/' . $order->id) }}"
                                                                onclick="return confirm('Are You Sure To Delete This')"
                                                                class="btn btn-danger btn-sm">X</a>
                                                        </div>
                                                    </td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>

                            {!! $allOrders->links() !!}

                        </div>


                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>



        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script>
        $("#scroll-horizontal-datatable").DataTable({
            scrollX: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        })
        console.log('page is load now');
    </script>
@endsection
<!-- container -->
