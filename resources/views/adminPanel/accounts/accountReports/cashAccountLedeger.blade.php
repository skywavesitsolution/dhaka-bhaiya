<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Accounts Ledeger report</h3>

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
        <h6>Account Name: {{ $cashAccountsdata->account_name }}</h6>
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
                        <th style="border:1px solid black;">Desctiption</th>
                        <th style="border:1px solid black;">Payment (Cr)</th>
                        <th style="border:1px solid black;">Received (Dr)</th>
                        <th style="border:1px solid black;">Balance</th>

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    <tr>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">Opening Balance</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($cashAccountsdata->opening_balance) }}</td>

                    </tr>
                    @php
                    $total_amount = 0;
                    @endphp
                    @isset($CashAccountsLedeger)

                    @foreach($CashAccountsLedeger as $ledgerItem)

                    <?php
                    $desc = '';
                    if (isset($ledgerItem->sale_id)) {
                        $desc = "Sale Amount | Id:" . $ledgerItem->sale_id;
                    }
                    if (isset($ledgerItem->purchase_id)) {
                        $desc = "Purchase Amount | Id:" . $ledgerItem->purchase_id;
                    }
                    if (isset($ledgerItem->deposit_id)) {
                        $desc = "Despoist Amount | Id:" . $ledgerItem->deposit_id;
                    }

                    if (isset($ledgerItem->payment_id)) {
                        $desc = "Payment Amount | Id:" . $ledgerItem->payment_id;
                    }

                    if (isset($ledgerItem->received_id)) {
                        $desc = "Received Amount | Id:" . $ledgerItem->received_id;
                    }

                    if (isset($ledgerItem->sub_payment_id)) {
                        $desc = "Received Amount | Payment Item ID:" . $ledgerItem->sub_payment_id;
                    }

                    if (isset($ledgerItem->sub_recevied_payment_id)) {
                        $desc = "Payment Amount | Received Payment Item Id:" . $ledgerItem->sub_recevied_payment_id;
                    }

                    if (isset($ledgerItem->expense_id)) {
                        $desc = "Expense Amount | Id:" . $ledgerItem->expense_id;
                    }
                    ?>
                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($ledgerItem->date)) }}</td>
                        <td style="border:1px solid black;">{{ $desc }}
                            <br> Remarks: {{ $ledgerItem->remarks }}
                        </td>
                        <td style="border:1px solid black;">{{ number_format($ledgerItem->payment) }}</td>
                        <td style="border:1px solid black;">{{ number_format($ledgerItem->received) }}</td>

                        <td style="border:1px solid black;">{{ number_format($ledgerItem->balance) }}</td>

                    </tr>

                    @endforeach
                    @endisset

                </tbody>

            </table>
        </div>
    </div>

    @endsection


    @section('prepaid_by')
    {{getUserName(\Auth::user()->id) }}
    @endsection