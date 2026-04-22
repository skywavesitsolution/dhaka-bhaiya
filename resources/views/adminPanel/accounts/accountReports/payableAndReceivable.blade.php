<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">Payable & Receivable report</h3>

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
    <div class="col-md-3">
        <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
    </div>
    <div class="col-md-9">
        <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
    </div>
</div>
<section style="margin: 20px;">
    <h4 style="text-align: right;"></h4>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                <thead style="color: black; border: 1px solid black;">
                    <tr style="background-color: lightgray; color: black;">
                        <th style="border:1px solid black;">Date</th>
                        <th style="border:1px solid black;">Particular</th>
                        <th style="border:1px solid black;">Payable</th>
                        <th style="border:1px solid black;">Receivable</th>
                        <th style="border:1px solid black;">Balance</th>
                    </tr>
                </thead>
                <tbody style="border: 2px solid black;">
                    @php
                        $totalPayable = 0;
                        $totalReceivable = 0;
                    @endphp

                    {{-- Loop through each supplier and display their payable/receivable details --}}
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td style="border:1px solid black;">{{ date('d-m-Y') }}</td>
                        <td style="border:1px solid black;">{{ $supplier->name }} (Supplier)</td>
                        <td style="border:1px solid black;">
                            {{ $supplier->balance > 0 ? number_format($supplier->balance) : '0' }}
                        </td>
                        <td style="border:1px solid black;">
                            {{ $supplier->balance < 0 ? number_format(abs($supplier->balance)) : '0' }}
                        </td>
                        <td style="border:1px solid black;">
                            {{ number_format($supplier->balance) }}
                        </td>
                    </tr>
                    @php
                        $totalPayable += ($supplier->balance > 0 ? $supplier->balance : 0);
                        $totalReceivable += ($supplier->balance < 0 ? abs($supplier->balance) : 0);
                    @endphp
                    @endforeach

                    {{-- Loop through each customer and display their payable/receivable details --}}
                    @foreach($customers as $customer)
                    <tr>
                        <td style="border:1px solid black;">{{ date('d-m-Y') }}</td>
                        <td style="border:1px solid black;">{{ $customer->name }} (Customer)</td>
                        <td style="border:1px solid black;">
                            {{ $customer->balance < 0 ? number_format(abs($customer->balance)) : '0' }}
                        </td>
                        <td style="border:1px solid black;">
                            {{ $customer->balance > 0 ? number_format($customer->balance) : '0' }}
                        </td>
                        <td style="border:1px solid black;">
                            {{ number_format($customer->balance) }}
                        </td>
                    </tr>
                    @php
                        $totalPayable += ($customer->balance < 0 ? $customer->balance : 0);
                        $totalReceivable += ($customer->balance > 0 ? abs($customer->balance) : 0);
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">
                        <td style="border:1px solid black;">Totals</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($totalPayable) }}</td>
                        <td style="border:1px solid black;">{{ number_format($totalReceivable) }}</td>
                        <td style="border:1px solid black;">
                            {{ number_format($totalReceivable - $totalPayable) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>
    @endsection

    @section('prepaid_by')
    {{getUserName(\Auth::user()->id) }}
    @endsection