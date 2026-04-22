<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

@if($request->product_id == 'all_products')
    <h3 style="margin-top:40px;">All Products Sale Report</h3>
@else
    @php
        $product = App\Models\Product\Product::find($request->product_id);
    @endphp
    <h3 style="margin-top:40px;">{{ $product ? $product->product_name : 'Unknown Product' }} Sale Report</h3>
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
        @if($request->product_id == 'all_products')
        <h6>Report Type: All Products</h6>
    @else
        @php
            $product = App\Models\Product\Product::find($request->product_id); 
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
            <h6>Start Date: {{ $request->start_date }}</h6>
        </div>
        <div class="col-md-3">
            <h6>End Date: {{ $request->end_date }}</h6>
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
                        <th style="border:1px solid black;">Invoice ID</th>
                        <th style="border:1px solid black;">Date</th>
                        <th style="border:1px solid black;">Product Name</th>
                        <th style="border:1px solid black;">Type</th>
                        <th style="border:1px solid black;">Quantity</th>
                        <th style="border:1px solid black;">Retail Price</th>
                        <th style="border:1px solid black;">Discount</th>
                        <th style="border:1px solid black;">Total</th>
                    </tr>
                </thead>
                <tbody style="border: 2px solid black;">
                    @php
                        $total_amount = 0;
                        $qty_total = 0;
                    @endphp
                    @foreach($sale_data as $sale)
                        <tr>
                            <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                            <td style="border:1px solid black;">{{ $sale->invoice_id }}</td>
                            <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($sale->bill_date)) }}</td>
                            <td style="border:1px solid black;">{{ $sale->product_name }}</td>
                            <td style="border:1px solid black;">{{ $sale->item_type }}</td>
                            <td style="border:1px solid black;">{{ number_format($sale->sale_qty, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($sale->retail_price, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($sale->product_discount_actual_value, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($sale->net_payable, 2) }}</td>
                        </tr>
                        @php
                            $total_amount += $sale->net_payable;
                            $qty_total += $sale->sale_qty;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold" style="background-color: #f0eded; font-size: 20px;">
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