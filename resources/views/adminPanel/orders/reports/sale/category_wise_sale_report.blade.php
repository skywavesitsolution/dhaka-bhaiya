<?php
use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')

    @if ($request['category_id'] == 'all_category')
        <h3 style="margin-top:40px;">All Category Sale Report</h3>
    @else
        @php
            $category = App\Models\Product\Category\ProductCategory::find($request['category_id']);
        @endphp
        <h3 style="margin-top:40px;">{{ $category ? $category->name : 'Unknown Category' }} Sale Report</h3>
    @endif

    </section>
    <div class="row pl-5 pr-5">
        <div class="col-md-9">
            <h5>Report </h5>
        </div>
        <div class="col-md-3">
            <h5>Details</h5>
        </div>
        <div class="col-md-9">
            <h6>User: {{ getUserName(\Auth::user()->id) }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Report Type: Category Wise</h6>
        </div>
        <div class="col-md-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
        </div>
        <div class="col-md-9">
            <h6>Start Date: {{ $request['start_date'] }}</h6>
        </div>
        <div class="col-md-3">
            <h6>End Date: {{ $request['end_date'] }}</h6>
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
                            @if ($request['category_id'] == 'all_category')
                                <th style="border:1px solid black;">Category Name</th>
                            @endif
                            <th style="border:1px solid black;">Product Name</th>
                            <th style="border:1px solid black;">Quantity</th>
                            <th style="border:1px solid black;">Retail Price</th>
                            <th style="border:1px solid black;">Discount</th>
                            <th style="border:1px solid black;">Total</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @isset($sale_data)
                            @php
                                $grand_total_amount = 0;
                                $grand_total_qty = 0;
                                $grand_total_discount = 0;
                            @endphp
                            @foreach ($sale_data as $product_id => $product)
                                @php
                                    $calculated_total =
                                        $product['retail_price'] * $product['total_qty'] - $product['total_discount'];
                                    $grand_total_amount += $calculated_total;
                                    $grand_total_qty += $product['total_qty'];
                                    $grand_total_discount += $product['total_discount'];
                                @endphp
                                <tr>
                                    <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                    <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($request['start_date'])) }}
                                    </td>
                                    @if ($request['category_id'] == 'all_category')
                                        <td style="border:1px solid black;">{{ $product['category_name'] }}</td>
                                    @endif
                                    <td style="border:1px solid black;">{{ $product['product_name'] }}</td>
                                    <td style="border:1px solid black;">{{ number_format($product['total_qty'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($product['retail_price'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($product['total_discount'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($calculated_total, 2) }}</td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important;">
                            <td style="border:1px solid black;"
                                colspan="{{ $request['category_id'] == 'all_category' ? 4 : 3 }}">Totals</td>
                            <td style="border:1px solid black;">{{ number_format($grand_total_qty, 2) }}</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ number_format($grand_total_discount, 2) }}</td>
                            <td style="border:1px solid black;">{{ number_format($grand_total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection

@section('prepaid_by')
    {{ getUserName(\Auth::user()->id) }}
@endsection
