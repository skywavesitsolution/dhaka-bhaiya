<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    {{ ucfirst($criteriaType) }} wise Product  Report
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
        <h6>{{ ucfirst($criteriaType) }}: {{ ucfirst($relatedData) == 'All' ? 'All' : ucfirst($relatedData->name) }}</h6>
    </div>
    <div class="col-md-3">
        <h6>User: 
            {{-- {{ Helper::getUserName(\Auth::user()->id) }} --}}
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
                        <th>ID</th>
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Product Urdu Name</th>

                        @if($criteriaType != 'category' || $relatedData == 'All')
                            <th>Category</th>
                        @endif

                        @if($criteriaType != 'brand' || $relatedData == 'All')
                            <th>Brand</th>
                        @endif

                        @if($criteriaType != 'supplier' || $relatedData == 'All')
                            <th>Supplier</th>
                        @endif
                        
                        <th>No of Variants</th>

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($products)
                        @foreach($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->product_urdu_name }}</td>

                                @if($criteriaType != 'category' || $relatedData == 'All')
                                    <th>{{ $product->category->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'brand' || $relatedData == 'All')
                                    <th>{{ $product->brand->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'supplier' || $relatedData == 'All')
                                    <th>{{ $product->supplier->name ?? '---' }}</th>
                                @endif

                                
                                <td>{{ $product->variants_count ?? '---' }}</td>
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
