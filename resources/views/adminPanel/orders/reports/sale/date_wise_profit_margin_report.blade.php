<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

{{-- @if($request['location_id'] == 'all_location') --}}
    <h3 style="margin-top:40px;">Date Wise Profit Margin Report</h3>
{{-- @else
    @php
        $location = App\Models\Product\Location\ProductLocation::find($request['location_id']);
    @endphp
    <h3 style="margin-top:40px;">{{ $location ? $location->name : 'Unknown location' }} Sale Report</h3>
@endif --}}


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
        {{-- @if($request->product_id == 'all_products') --}}
        <h6>Report Type: All Products</h6>
    {{-- @else --}}
        {{-- @php
            $product = App\Models\Product\Product::find($request->product_id); 
        @endphp
        <h6>Report Type: {{ $product ? $product->product_name : 'Unknown Product' }}</h6>
    @endif --}}
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
            <h6>End Date: {{ $request['end_date'] }}</h6>
        </div>
</div>
<section style="margin: 20px;">
    <h4 style="text-align: right;" id=""></h4>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
    <thead style="color: black; border: 1px solid black;">
        <tr style="background-color: lightgray; color: black;">
            <th style="border:1px solid black;">Date</th>
            <th style="border:1px solid black;">Product Name</th>
            <th style="border:1px solid black;">Total Sale</th>
            <th style="border:1px solid black;">Total Cost</th>
            <th style="border:1px solid black;">Gross Profit</th>
            <th style="border:1px solid black;">Total Expenses</th>
            <th style="border:1px solid black;">Net Profit</th>
            <th style="border:1px solid black;">Profit/Loss Status</th>  <!-- New column for Profit/Loss -->
        </tr>
    </thead>
    <tbody style="border: 2px solid black;">
        @foreach($sale_data as $data)
            <tr>
                <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($data->bill_date)) }}</td>
                <td style="border:1px solid black;">{{ $data->product_name }}</td>
                <td style="border:1px solid black;">{{ number_format($data->total_sale, 2) }}</td>
                <td style="border:1px solid black;">{{ number_format($data->total_cost, 2) }}</td>
                <td style="border:1px solid black;">{{ number_format($data->gross_profit, 2) }}</td>
                <td style="border:1px solid black;">{{ number_format($data->total_expenses, 2) }}</td>
                <td style="border:1px solid black;">{{ number_format($data->net_profit, 2) }}</td>

                <!-- Check if net_profit is positive or negative -->
                <td style="border:1px solid black;">
                    @if($data->net_profit > 0)
                        <span style="color: green;">Profit</span>
                    @elseif($data->net_profit < 0)
                        <span style="color: red;">Loss</span>
                    @else
                        <span style="color: gray;">No Profit/Loss</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
                    

        </div>
    </div>

    @endsection

    @section('prepaid_by')
    {{getUserName(\Auth::user()->id) }}
    @endsection