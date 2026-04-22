<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Day Book Report</h3>

</section>
<div class="row pl-5 pr-5">
    <div class="col-md-9">
        <h5>Report </h5>
    </div>
    <div class="col-md-3">
        <h5>Details</h5>
    </div>
    <div class="col-md-9">
        <h6>User: {{ Helper::getUserName(\Auth::user()->id) }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Day Book Date: {{ date('Y-m-d',strtotime($date)) }}</h6>
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
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                <thead style="color: black; border: 1px solid black;">
                    <tr style="background-color: lightgray; color: black;">
                        <th style="border:1px solid black;">Sr</th>
                        <th style="border:1px solid black;">Date</th>
                        <th style="border:1px solid black;">Account</th>
                        <th style="border:1px solid black;">Desctiption</th>
                        <th style="border:1px solid black;">Payment</th>
                        <th style="border:1px solid black;">Received</th>

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">

                    @php
                    $total_payments = 0;
                    $total_recevied = 0;
                    @endphp
                    @isset($day_book_data)

                    @foreach($day_book_data as $pay_res)

                    <?php
                    $desc = '';
                    if (isset($pay_res->deposit_id)) {
                        $desc = "Despoist Amount | Id:" . $pay_res->deposit_id;
                    }

                    if (isset($pay_res->payment_id)) {
                        $desc = "Payment Amount | Id:" . $pay_res->payment_id;
                    }

                    if (isset($pay_res->recevied_id)) {
                        $desc = "Received Amount | Id:" . $pay_res->recevied_id;
                    }

                    if (isset($pay_res->file_id)) {
                        $desc = "File Amount | Id:" . $pay_res->file_id;
                    }

                    if (isset($pay_res->property_id)) {
                        $desc = "Property Amount | Id:" . $pay_res->property_id;
                    }

                    if (isset($pay_res->expense_id)) {
                        $desc = "Expense Amount | Id:" . $pay_res->expense_id;
                    }

                    // print_r($pay_res);
                    // die;
                    $CashAccountsdata = Helper::getCashAccountName($pay_res->account_id);

                    $total_payments += $pay_res->payment;
                    $total_recevied += $pay_res->received;
                    ?>
                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($pay_res->created_at)) }}</td>
                        <td style="border:1px solid black;">{{ $CashAccountsdata->account_name." / ".$CashAccountsdata->account_number}}</td>
                        <td style="border:1px solid black;">{{ $desc }}</td>
                        <td style="border:1px solid black;">{{ number_format($pay_res->payment) }}</td>
                        <td style="border:1px solid black;">{{ number_format($pay_res->received) }}</td>
                    </tr>

                    @endforeach
                    @endisset
                    <tr>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">Totals</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($total_payments) }}</td>
                        <td style="border:1px solid black;">{{ number_format($total_recevied) }}</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

    @endsection


    @section('prepaid_by')
    {{ Helper::getUserName(\Auth::user()->id) }}
    @endsection