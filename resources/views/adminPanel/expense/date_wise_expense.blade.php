<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Date wise expanse report</h3>

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
        <h6>Start Date: {{ $request->start_date }}</h6>
    </div>
    <div class="col-md-9">
        <h6>End Date: {{ $request->end_date }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
    </div>
    <div class="col-md-9">
        <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
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
                        <th style="border:1px solid black;">ID</th>
                        <th style="border:1px solid black;">Date</th>
                        <th style="border:1px solid black;">Name</th>
                        <th style="border:1px solid black;">Category</th>
                        <th style="border:1px solid black;">Sub Category</th>
                        <th style="border:1px solid black;">Amount</th>
                        <th style="border:1px solid black;">Paid From</th>
                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($expense_data)
                    @php
                    $total_amount = 0;
                    @endphp
                    @foreach($expense_data as $expense_res)
                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">{{ $expense_res->id }}</td>
                        <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($expense_res->date)) }}</td>

                        <td style="border:1px solid black;">{{ $expense_res->exp_name }}</td>
                        <td style="border:1px solid black;">{{ $expense_res->exp_category_name }}</td>
                        <td style="border:1px solid black;">{{ $expense_res->exp_sub_category }}</td>
                        <td style="border:1px solid black;">{{ number_format($expense_res->total_amount) }}</td>
                        <td style="border:1px solid black;">{{ $expense_res->account_name." / ".$expense_res->account_number}}</td>
                    </tr>
                    @php
                    $total_amount += $expense_res->total_amount;
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
                        <td style="border:1px solid black;">{{getAmountInWords($total_amount) }}</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ $total_amount }}</td>
                        <td style="border:1px solid black;"></td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @endsection



    @section('prepaid_by')
    {{getUserName(\Auth::user()->id) }}
    @endsection