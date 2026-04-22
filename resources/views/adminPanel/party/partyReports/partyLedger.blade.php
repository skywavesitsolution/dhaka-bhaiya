<?php

use App\Helpers\Helper;
use App\Models\Sales\SaleInvoice;

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
        {{-- @if ($party->type == 'Customer')
    <div class="col-md-3">
        <h6>Supplier: {{ $party->supplier->name ?? 'none' }}</h6>
    </div>
    @endif --}}
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
                            <th style="border:1px solid black;">Dr</th>
                            <th style="border:1px solid black;">Cr</th>
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
                            <td style="border:1px solid black;">{{ number_format($party->opening_balance) }}</td>

                        </tr>
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
                            $orderIds = [];
                        @endphp

                        @isset($partyLedeger)
                            @foreach ($partyLedeger as $ledgerItem)
                                <?php
                                $desc = '';
                                if (isset($ledgerItem->sale_id)) {
                                    $desc = 'Sale | Id:' . $ledgerItem->sale_id;
                                }
                                
                                if (isset($ledgerItem->payment_id)) {
                                    $desc = 'Payment Amount | Id:' . $ledgerItem->payment_id;
                                }
                                
                                if (isset($ledgerItem->recevied_id)) {
                                    $desc = 'Received Amount | Id:' . $ledgerItem->recevied_id;
                                }
                                if (isset($ledgerItem->purchase_id)) {
                                    $desc = 'Purchase | Id:' . $ledgerItem->purchase_id;
                                }
                                ?>
                                <tr>
                                    <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                    <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($ledgerItem->date)) }}</td>
                                    <td style="border:1px solid black;">{{ $desc }}

                                        <?php
                                        if (isset($ledgerItem->sale_id)) {
                                            $order = SaleInvoice::find($ledgerItem->sale_id);
                                            if ($order) {
                                                if (!in_array($ledgerItem->sale_id, $orderIds)) {
                                                    $totalCarraige += $order->total_carriage;
                                                    $totalSale += $order->net_payable;
                                                    $totalQty += $order->purchase_qty;
                                        
                                                    $productType = $order->product->name ?? '';
                                                    if ($productType != '') {
                                                        if (isset($productTypes[$productType])) {
                                                            $productTypes[$productType] = $productTypes[$productType] + $order->purchase_qty;
                                                        } else {
                                                            $productTypes[$productType] = $order->purchase_qty;
                                                        }
                                                    }
                                        
                                                    $orderIds[] = $ledgerItem->order_id;
                                                }
                                            }
                                        }
                                        if (isset($ledgerItem->purchase_id)) {
                                            $purchase = \App\Models\Purchase::find($ledgerItem->purchase_id);
                                            // dd($purchase);
                                            if ($purchase) {
                                                    $totalPurchase += $purchase->net_payable;
                                            }
                                        }
                                        ?>
                                        Note: {!! $ledgerItem->remarks !!}
                                    </td>
                                    <td style="border:1px solid black;">
                                        @if ($ledgerItem->payment !== null)
                                            {{ number_format($ledgerItem->payment) }}
                                        @endif
                                        @if ($ledgerItem->received !== null)
                                            {{ number_format($ledgerItem->received) }}
                                        @endif
                                    </td>
                                    <td style="border:1px solid black;">
                                        @if ($ledgerItem->sale_id !== null)
                                            {{ number_format($ledgerItem->sale->net_payable) }}
                                        @endif
                                        @if ($ledgerItem->purchase_id !== null)
                                            {{ number_format($ledgerItem->price) }}
                                        @endif
                                    </td>
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
            {{-- <div class="col-md-8 p-5">
                <h5>Product Types Totals</h5>

                @foreach ($productTypes as $index => $productType)
                    <h6>{{ $index }}: {{ number_format($productType) }}</h6>
                @endforeach
            </div> --}}
            <div class="col-md-4 p-5">

                {{-- <h6>Total Qty: {{ number_format($totalQty) }}</h6> --}}
                @if ($party->type == 'Supplier')
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
