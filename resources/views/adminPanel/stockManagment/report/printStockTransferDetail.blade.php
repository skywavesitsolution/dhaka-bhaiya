<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    Stock Transfer Detail
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
        <h6>Stock Transfer Date: 
            {{ $stockTransfersDate }}
        </h6>
    </div>
    <div class="col-md-3">
        <h6>Stock Transfer Time: 
            {{ $stockTransfersTime }}
        </h6>
    </div>
    <div class="col-md-9">
        <h6>From Location: {{ $fromLocation }}</h6>
    </div>
    
    <div class="col-md-3">
        <h6>Total Qty: 
            {{ $totalQty }}
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
                        <th>Product Variant Code</th>
                        <th>Product Variant Name</th>
                        <th>To Location</th>
                        <th>Stock At Transfer Time</th>
                        <th>Qty</th>
                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($stockTransferDetails)
                        @foreach($stockTransferDetails as $index => $stockTransferDetail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $stockTransferDetail->productVariant->code ?? '--' }}
                                </td>
                                <td>
                                    {{ $stockTransferDetail->productVariant->product_variant_name ?? '--' }}
                                </td>
                                <td>
                                    {{ $stockTransferDetail->toLocation->name ?? '--' }}
                                </td>
                                <td>
                                    {{ $stockTransferDetail->stock_at_time_of_transfer ?? '--' }}
                                </td>
                                <td>
                                    {{ $stockTransferDetail->qty ?? '--' }}
                                </td>
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
