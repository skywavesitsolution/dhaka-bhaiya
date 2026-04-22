<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    {{ ucfirst(
            $criteriaType === 'measuring_unit' 
            ? 'Measuring Unit' 
            : $criteriaType
        ) }} wise Variant  Report
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
        <h6>
            {{ ucfirst(
            $criteriaType === 'measuring_unit' 
            ? 'Measuring Unit' 
            : $criteriaType
        ) }}: 
            {{ 
                ucfirst($relatedData) == 'All' 
                ? 'All' 
                : (
                    $criteriaType === 'product' 
                    ? ucfirst($relatedData->product_name) 
                    : ($criteriaType === 'measuring_unit' 
                        ? ucfirst($relatedData->symbol) 
                        : ucfirst($relatedData->name)
                    )
                ) 
            }}
        </h6>
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
                        <th>Variant Name</th>
                        <th>Variant Urdu Name</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Cost Price</th>
                        <th>Retail Price</th>
                        <th>Stock</th>

                        @if($criteriaType != 'category' || $relatedData == 'All')
                            <th>Category</th>
                        @endif

                        @if($criteriaType != 'brand' || $relatedData == 'All')
                            <th>Brand</th>
                        @endif

                        @if($criteriaType != 'supplier' || $relatedData == 'All')
                            <th>Supplier</th>
                        @endif

                        @if($criteriaType != 'product' || $relatedData == 'All')
                            <th>Product</th>
                        @endif

                        @if($criteriaType != 'locations' || $relatedData == 'All')
                            <th>Location</th>
                        @endif

                        @if($criteriaType != 'measuring_unit' || $relatedData == 'All')
                            <th>Measuring Unit</th>
                        @endif
                        

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($productVariants)
                        @foreach($productVariants as $index => $productVariant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $productVariant->id }}</td>
                                <td>{{ $productVariant->code }}</td>
                                <td>{{ $productVariant->product_variant_name }}</td>
                                <td>{{ $productVariant->product_variant_urdu_name }}</td>
                                <td>{{ $productVariant->color->name ?? '---' }}</td>
                                <td>{{ $productVariant->size->name ?? '---' }}</td>
                                <td>{{ $productVariant->rates->cost_price ?? '---' }}</td>
                                <td>{{ $productVariant->rates->retail_price ?? '---' }}</td>
                                <td>{{ $productVariant->stock->stock ?? '---' }}</td>

                                @if($criteriaType != 'category' || $relatedData == 'All')
                                    <th>{{ $productVariant->product->category->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'brand' || $relatedData == 'All')
                                    <th>{{ $productVariant->product->brand->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'supplier' || $relatedData == 'All')
                                     <th>{{ $productVariant->product->supplier->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'product' || $relatedData == 'All')
                                   <th>{{ $productVariant->product->product_name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'locations' || $relatedData == 'All')
                                    <th>{{ $productVariant->location->name ?? '---' }}</th>
                                @endif

                                @if($criteriaType != 'measuring_unit' || $relatedData == 'All')
                                    <th>{{ $productVariant->measuringUnit->symbol ?? '---' }}</th>
                                @endif
                        

                                
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
