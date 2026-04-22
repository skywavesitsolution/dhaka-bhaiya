<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

<h3 style="margin-top:40px;">Date Wise Products Sale Report</h3>

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
        <h6>Report Type: All Products</h6>
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
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                <thead style="color: black; border: 1px solid black;">
                    <tr style="background-color: lightgray; color: black;">
                        <th style="border:1px solid black;">Sr</th>
                        <th style="border:1px solid black;">Date</th>
                        <th style="border:1px solid black;">Product Name</th>
                        <th style="border:1px solid black;">Quantity</th>
                        <th style="border:1px solid black;">Retail Price</th>
                        <th style="border:1px solid black;">Discount</th>
                        <th style="border:1px solid black;">Total</th>
                    </tr>
                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($sale_data)
                        @php
                            $total_amount = 0;
                            $total_disc_amount = 0;
                            $qty_total = 0;
                        @endphp
                        @forelse($sale_data as $sale)
                            <tr>
                                <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($sale->bill_date)) }}</td>
                                <td style="border:1px solid black;">{{ $sale->product_name }}</td>
                                <td style="border:1px solid black;">{{ $sale->total_sale_qty }}</td>
                                <td style="border:1px solid black;">{{ number_format($sale->retail_price, 2) }}</td>
                                <td style="border:1px solid black;">{{ number_format($sale->total_discount ?? 0, 2) }}</td>
                                <td style="border:1px solid black;">{{ number_format($sale->total_sale_amount, 2) }}</td>
                            </tr>
                            @php
                                $total_amount += $sale->total_sale_amount;
                                $total_disc_amount += ($sale->total_discount ?? 0);
                                $qty_total += $sale->total_sale_qty;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center;">No Data Available</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="7" style="text-align: center;">No Data Available</td>
                        </tr>
                    @endisset
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">
                        <td style="border:1px solid black;">Totals</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($qty_total, 2) }}</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($total_disc_amount, 2) }}</td>
                        <td style="border:1px solid black;">{{ number_format($total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-4 mt-3">
            <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                <h5 style="text-align: center; font-weight: bold; color: #333; margin-bottom: 15px;">Summary</h5>
                <table class="table" style="font-size: 16px;">
                    <tbody>
                        <tr>
                            <td><strong>Total Sale Amount:</strong></td>
                            <td style="text-align: right;">{{ number_format($total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Bill Discount:</strong></td>
                            <td style="text-align: right;">{{ number_format($total_bill_discount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Net Sale:</strong></td>
                            <td style="text-align: right; font-weight: bold; color: #28a745;">
                                {{ number_format($total_amount - $total_bill_discount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection

@section('prepaid_by')
{{ getUserName(\Auth::user()->id) }}
@endsection