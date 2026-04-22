<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

    @if ($request['product_id'] == 'all_products')
        <h3 style="margin-top:40px;">All Products Purchase Report</h3>
    @else
        @php
            $product = App\Models\Product\Product::find($request['product_id']);
        @endphp
        <h3 style="margin-top:40px;">{{ $product ? $product->product_name : 'Unknown Product' }} Purchase Report</h3>
    @endif


    </section>
    <div class="row pl-5 pr-5">
        <div class="col-md-9">
            <h5>Report </h5>
        </div>
        <div class="col-md-3">
            <h5>Details</h5>
        </div>
        <div class="col-md-9">
            <h6>User: {{getUserName(\Auth::user()->id) }}</h6>
        </div>
        <div class="col-md-3">
            @if ($request['product_id'] == 'all_products')
                <h6>Report Type: All Products</h6>
            @else
                @php
                    $product = App\Models\Product\Product::find($request['product_id']);
                @endphp
                <h6>Report Type: {{ $product ? $product->product_name : 'Unknown Product' }}</h6>
            @endif
        </div>
        <div class="col-md-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
        </div>
        <div class="col-md-9">
            <h6>Start Date: {{ $request['start_date'] }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Start Date: {{ $request['end_date'] }}</h6>
        </div>
    </div>
    <section style="margin: 20px;">
        <h4 style="text-align: right;" id=""></h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Purchase ID</th>
                            <th style="border:1px solid black;">Date</th>
                            <th style="border:1px solid black;">Product Name</th>
                            <th style="border:1px solid black;">Supplier Name</th>
                            <th style="border:1px solid black;">Quantity</th>
                            <th style="border:1px solid black;">Cost Price</th>
                            <th style="border:1px solid black;">Discount</th>
                            <th style="border:1px solid black;">Total</th>
                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">
                        @isset($purchase_data)
                            @php
                                $total_amount = 0;
                                $qty_total = 0;
                            @endphp
                            @foreach ($purchase_data as $purchase)
                                <tr>
                                    <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                    <td style="border:1px solid black;">{{ $purchase->purchase_id }}</td>
                                    <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($purchase->received_date)) }}
                                    </td>
                                    <td style="border:1px solid black;">{{ $purchase->product_name }}</td>
                                    <td style="border:1px solid black;">{{ $purchase->supplier_name ?? 'N/A' }}</td>
                                    <td style="border:1px solid black;">{{ $purchase->qty }}</td>
                                    <td style="border:1px solid black;">{{ number_format($purchase->cost_price, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($purchase->discount ?? 0, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($purchase->total, 2) }}</td>
                                </tr>
                                @php
                                    $total_amount += $purchase->total;
                                    $qty_total += $purchase->qty;
                                @endphp
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ number_format($qty_total, 2) }}</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ number_format($total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    @endsection

    @section('prepaid_by')
        {{getUserName(\Auth::user()->id) }}
    @endsection
