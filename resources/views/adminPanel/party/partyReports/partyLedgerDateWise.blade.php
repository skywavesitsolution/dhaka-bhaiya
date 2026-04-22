<?php

use App\Helpers\Helper;
use App\Models\Order;

?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Party Ledeger report</h3>

</section>
<div class="row pl-5 pr-5">
    <div class="col-md-9">
        <h5>Report </h5>
    </div>
    <div class="col-md-3">
        <h5>Details</h5>
    </div>
    <div class="col-md-9">
        <h6>User: {{ \Auth::user()->name }}</h6>
    </div>
    @if($party->type == 'Customer')
    <div class="col-md-3">
        <h6>Supplier: {{ $party->supplier->name }}</h6>
    </div>
    @endif
    <div class="col-md-9">
        <h6>Party Name: {{ $party->name }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Party Type: {{ $party->type }}</h6>
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
                        <th style="border:1px solid black;">Payment (Dr)</th>
                        <th style="border:1px solid black;">Received (Cr)</th>
                        <th style="border:1px solid black;">Balance</th>

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">

                    @php
                    $total_amount = 0;
                    @endphp

                    @php
                    $totalQty = 0;
                    $totalPurchase = 0;
                    $totalCarraige = 0;
                    $totalSale = 0;
                    $totalProfit = 0;
                    $productTypes = [];
                    @endphp

                    @isset($partyLedeger)

                    @foreach($partyLedeger as $ledgerItem)

                    <?php
                    $desc = '';
                    if (isset($ledgerItem->order_id)) {
                        $desc = "Order | Id:" . $ledgerItem->order_id;
                    }

                    if (isset($ledgerItem->payment_id)) {
                        $desc = "Payment Amount | Id:" . $ledgerItem->payment_id;
                    }

                    if (isset($ledgerItem->recevied_id)) {
                        $desc = "Received Amount | Id:" . $ledgerItem->recevied_id;
                    }

                    ?>
                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($ledgerItem->date)) }}</td>
                        <td style="border:1px solid black;">{{ $desc }}
                            <?php
                            if (isset($ledgerItem->order_id)) {
                                $order = Order::find($ledgerItem->order_id);

                                $totalPurchase += $order->total_purchase;
                                $totalCarraige += $order->total_carriage;
                                $totalSale += $order->total_sale_amount;
                                $totalQty += $order->purchase_qty;

                                $productType = $order->product->name ?? '';
                                if ($productType != '') {
                                    if (isset($productTypes[$productType]))
                                        $productTypes[$productType] = $productTypes[$productType] + $order->purchase_qty;
                                    else
                                        $productTypes[$productType] = $order->purchase_qty;
                                }

                                if ($party->type == 'Marka') {
                            ?>
                                    <h6>Qty: {{ number_format($order->purchase_qty) }}</h6>
                                    <h6>Purchase Rate: {{ number_format($order->purchase_rate) }}</h6>
                                    <h6>Total Purchase: {{ number_format($order->total_purchase) }}</h6>

                                <?php
                                }

                                if ($party->type == 'Driver') {
                                ?> <h6>Qty: {{ number_format($order->purchase_qty) }}</h6>
                                    <h6>Carriage Amount: {{ number_format($order->carriage_amount) }}</h6>
                                    <h6>Total Carriage: {{ number_format($order->total_carriage) }}</h6>
                                <?php
                                }

                                if ($party->type == 'Customer') {
                                ?>
                                    <h6>Qty: {{ number_format($order->purchase_qty) }}</h6>
                                    <h6>Sale Price: {{ number_format($order->sale_rate) }}</h6>
                                    <h6>Total Sale: {{ number_format($order->total_sale_amount) }}</h6>
                            <?php
                                }
                            }
                            ?>
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

    <div class="row">
        <div class="col-md-12 mt-4">
            <h4>Summary:</h4>
            <hr>
        </div>
        <div class="col-md-8 p-5">
            <h5>Product Types Totals</h5>

            @foreach($productTypes as $index => $productType)
            <h6>{{ $index }}: {{ number_format($productType) }}</h6>
            @endforeach
        </div>
        <div class="col-md-4 p-5">

            <h6>Total Qty: {{ number_format($totalQty) }}</h6>
            @if ($party->type == 'Marka')
            <h6>Total Purchase: {{ number_format($totalPurchase) }}</h6>
            @endif
            @if ($party->type == 'Driver')
            <h6>Total Carriage: {{ number_format($totalCarraige) }}</h6>
            @endif
            @if ($party->type == 'Customer')
            <h6>Total Sale: {{ number_format($totalSale) }}</h6>
            @endif
        </div>
    </div>


    @endsection


    @section('prepaid_by')
    {{ \Auth::user()->name }}
    @endsection