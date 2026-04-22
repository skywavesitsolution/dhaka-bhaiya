<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

@isset($request['party_id'])
@if($request['party_id'] == 'all_suppliers')
    <h3 style="margin-top:40px;">All Supplier Stock Report</h3>
 @else
     @php
         $party = App\Models\party::find($request['party_id']); 
     @endphp
     <h3 style="margin-top:40px;">{{ $party ? $party->name : 'Unknown Supplier' }} Stock Report</h3>
 @endif
    
@endisset


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
        @isset($request['party_id'])
        @if($request['party_id'] == 'all_suppliers')
            <h6>Report Type: All Suppliers</h6>
        @else
            @php
         $party = App\Models\party::find($request['party_id']);
            @endphp
            <h6>Report Type: {{ $party ? $party->name : 'Unknown Supplier' }} Stock</h6>
        @endif
            
        @endisset
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
                        <th style="border:1px solid black;">Supplier Name</th>
                        <th style="border:1px solid black;">Product Name</th>
                        {{-- <th style="border:1px solid black;">Measuring Unit</th> --}}
                        <th style="border:1px solid black;">Available Stock</th>
                        <th style="border:1px solid black;">Total Cost</th>
                        <th style="border:1px solid black;">Total Retail</th>
                    </tr>
                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($stock_data)
                     @php
                            $grandTotalCost = 0;
                            $grandTotalRetail = 0;
                        @endphp
                        @foreach($stock_data as $stock)
                            <tr>
                                <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                <td style="border:1px solid black;">{{ $stock->supplier_name }}</td>
                                <td style="border:1px solid black;">{{ $stock->product_name }}</td>
                                {{-- <td style="border:1px solid black;">{{ $stock->measuring_unit_name }}</td> --}}
                                <td style="border:1px solid black;">{{ $stock->stock }}</td>
                                <td style="border:1px solid black;">{{ $stock->total_cost }}</td>
                                <td style="border:1px solid black;">{{ $stock->total_retail }}</td>
                            </tr>
                            @php
                                $grandTotalCost += $stock->total_cost;
                                $grandTotalRetail += $stock->total_retail;
                            @endphp
                        @endforeach
                    @endisset
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">
                    <tr class="font-weight-bold">
                        <td style="border:1px solid black;">Totals</td>
                        <td style="border:1px solid black;"></td>
                        {{-- <td style="border:1px solid black;"></td> --}}
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($grandTotalCost, 2) }}</td>
                        <td style="border:1px solid black;">{{ number_format($grandTotalRetail, 2) }}</td>
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