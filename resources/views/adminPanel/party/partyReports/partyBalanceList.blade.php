<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
    <h3 style="margin-top:40px;">{{ $type }} Wise Payable & Receivable </h3>

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
        {{-- @if ($request['particularType'] == 'Customer')
    <div class="col-md-3">
        <h6>Supplier: {{ $supplier->name }}</h6>
    </div>
    @endif --}}

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
                            <th style="border:1px solid black;">ID</th>
                            <th style="border:1px solid black;">Name</th>
                            <th style="border:1px solid black;">Email</th>
                            <th style="border:1px solid black;">Payable</th>
                            <th style="border:1px solid black;">Receivable</th>

                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">

                        @php
                            $totalPayable = 0;
                            $totalReceiablePayable = 0;
                        @endphp
                        @isset($parties)
                            @foreach ($parties as $party)
                                @if ($party->balance != 0)
                                    <tr>
                                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                        <td style="border:1px solid black;">
                                            {{ $party->id }}
                                        </td>
                                        <td style="border:1px solid black;">
                                            {{ $party->name }}
                                        </td>
                                        <td style="border:1px solid black;">
                                            {{ $party->email }}
                                        </td>

                                        {{-- Check party type and balance --}}
                                        <td style="border:1px solid black;">
                                            @if ($party->type == 'Supplier' && $party->balance > 0)
                                                {{ number_format($party->balance) }}
                                                @php
                                                    $totalPayable += $party->balance;
                                                @endphp
                                            @endif
                                        </td>
                                        <td style="border:1px solid black;">
                                            @if ($party->type == 'Customer' && $party->balance > 0)
                                                {{ number_format($party->balance) }}
                                                @php
                                                    $totalReceiablePayable += $party->balance;
                                                @endphp
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endisset

                        <tr>
                            <td style="border:1px solid black;">Total</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ number_format($totalPayable) }}</td>
                            <td style="border:1px solid black;">{{ number_format($totalReceiablePayable) }}</td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>

    @endsection


    @section('prepaid_by')
        {{ \Auth::user()->name }}
    @endsection
