<?php

use App\Helpers\Helper;
?>

@extends('adminPanel/print_master')
@section('content')
    <h3 style="margin-top:40px;">Invoice Profit & Loss Report</h3>

    <div class="row pl-5 pr-5">
        <div class="col-md-9">
            <h5>Report</h5>
        </div>
        <div class="col-md-3">
            <h5>Details</h5>
        </div>
        <div class="col-md-9">
            <h6>User: {{ getUserName(\Auth::user()->id) }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Report Type: All Invoices</h6>
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
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Invoice ID</th>
                            <th style="border:1px solid black;">Invoice Date</th>
                            <th style="border:1px solid black;">Total Quantity</th>
                            <th style="border:1px solid black;">Total Cost</th>
                            <th style="border:1px solid black;">Total Sale</th>
                            <th style="border:1px solid black;">Total Discount</th>
                            <th style="border:1px solid black;">Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @isset($sale_data)
                            @php
                                $grand_qty = 0;
                                $grand_cost = 0;
                                $grand_sale = 0;
                                $grand_discount = 0;
                                $grand_profit = 0;

                                // Group data by invoice_id
                                $grouped = collect($sale_data)->groupBy('invoice_id');
                            @endphp

                            @foreach ($grouped as $invoice_id => $items)
                                @php
                                    $invoice_date = $items->first()->bill_date;
                                    $qty_total = $items->sum('sale_qty');
                                    $cost_total = $items->sum(fn($i) => $i->costprice * $i->sale_qty);
                                    $sale_total = $items->sum(fn($i) => $i->retail_price * $i->sale_qty);
                                    $discount_total = $items->sum('product_discount_actual_value');
                                    $profit_total = $sale_total - $cost_total - $discount_total;

                                    $grand_qty += $qty_total;
                                    $grand_cost += $cost_total;
                                    $grand_sale += $sale_total;
                                    $grand_discount += $discount_total;
                                    $grand_profit += $profit_total;
                                @endphp
                                <tr>
                                    <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                    <td style="border:1px solid black;">{{ $invoice_id }}</td>
                                    <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($invoice_date)) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($qty_total, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($cost_total, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale_total, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($discount_total, 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($profit_total, 2) }}</td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 18px;">
                            <td colspan="3" style="text-align:right; border:1px solid black;">Grand Totals</td>
                            <td style="border:1px solid black;">{{ number_format($grand_qty, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($grand_cost, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($grand_sale, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($grand_discount, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($grand_profit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('prepaid_by')
    {{ getUserName(\Auth::user()->id) }}
@endsection
