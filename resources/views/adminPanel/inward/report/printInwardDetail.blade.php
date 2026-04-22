<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    Inward Detail
</h3>


</section>
<div class="row pl-5 pr-5">
    <div class="col-md-9">
        <h5>Report </h5>
    </div>
    <div class="col-md-3">
        <h5>Details</h5>
    </div>
    <div class="col-md-9">
        <h6>Inward Date: 
            {{ $inwardDate }}
        </h6>
    </div>
    <div class="col-md-3">
        <h6>Inward Time: 
            {{ $inwardTime }}
        </h6>
    </div>
    <div class="col-md-9">
        <h6>Supplier: {{ $supplierName }}</h6>
    </div>
    
    <div class="col-md-3">
        <h6>Status: 
            <span style="color: 
                {{ $inwardStatus == 'Pending' ? '#721c24' : ($inwardStatus == 'Approved' ? '#155724' : '#6c757d') }};
            ">{{ $inwardStatus }}</span>
        </h6>
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
                        <th>Sr#</th>
                        <th>Inward ID</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($inwardDetails)
                        @foreach($inwardDetails as $index => $inwardDetail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $inwardDetail->id ?? '---' }}</td>
                                <td>{{ $inwardDetail->product_name ?? '---' }}</td>
                                <td>{{ $inwardDetail->qty ?? '---' }}</td>
                            </tr>
                        @endforeach
                    @endisset

                </tbody>
            </table>

        </div>
    </div>

    @endsection


    @section('prepaid_by')
    {{-- {{ Helper::getUserName(\Auth::user()->id) }} --}}
    @endsection
