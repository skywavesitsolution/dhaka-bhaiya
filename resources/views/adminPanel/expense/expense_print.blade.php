<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Expense Voucher</h3>

</section>
<div class="row pl-5 pr-5">
    <div class="col-md-9">
        <h5>Report </h5>
    </div>
    <div class="col-md-3">
        <h5>Details</h5>
    </div>
    <div class="col-md-9">
        <h6>User: {{ getUserName($expense_data->user_id) }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Expense Date: {{ date('d-m-Y',strtotime($expense_data->date)) }}</h6>
    </div>
    <div class="col-md-9">
        <h6>Expense ID: {{ $expense_data->id }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Expense Type: {{ $expense_data->expenseCategory->exp_category_name }}</h6>
    </div>
    <div class="col-md-9">
        <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
    </div>
</div>
<section style="margin: 20px;">
    <h4 style="text-align: right;" id=""></h4>
    <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
        <thead style="color: black; border: 1px solid black;">
            <tr style="background-color: lightgray; color: black;">
                <th style="border:1px solid black;">Sr</th>
                <th style="border:1px solid black;">Particular</th>
                <th style="border:1px solid black;">Credit</th>
                <th style="border:1px solid black;">Debit</th>
            </tr>

        </thead>
        <tbody style="border: 2px solid black;">
            <tr>
                <td style="border:1px solid black;">1</td>
                <td style="border:1px solid black;">{{ $expense_data->expenseAccount->account_name }}</td>
                <td style="border:1px solid black;">{{ number_format($expense_data->total_amount) }}</td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td style="border:1px solid black;">2</td>
                <td style="border:1px solid black;">{{ $expense_data->exp_name }}</td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;">{{ number_format($expense_data->total_amount) }}</td>
            </tr>

        </tbody>
        <tfoot>
            <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

            <tr class="font-weight-bold">
                <td style="border:1px solid black;"></td>
                // <td style="border:1px solid black;">{{ getAmountInWords($expense_data->total_amount) }}</td>
                <td style="border:1px solid black;">{{ number_format($expense_data->total_amount) }}</td>
                <td style="border:1px solid black;">{{ number_format($expense_data->total_amount) }}</td>
            </tr>
        </tfoot>
    </table>
    @endsection

    @section('prepaid_by')
    {{ getUserName($expense_data->user_id) }}
    @endsection