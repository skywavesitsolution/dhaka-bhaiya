@extends('adminPanel/print_master')
@section('content')

@if($request['product_id'] == 'all_products')
    <h3 style="margin-top:40px;">Products Ledger Report</h3>
@else
    @php
        $product = App\Models\Product\Product::find($request['product_id']);
    @endphp
    <h3 style="margin-top:40px;">{{ $product->product_name ?? 'Unknown Product' }} Ledger Report</h3>
@endif

</section>
<div class="row pl-5 pr-5">
    <div class="col-md-9">
        <h5>Report</h5>
    </div>
    <div class="col-md-3">
        <h5>Details</h5>
    </div>
    <div class="col-md-9">
        <h6>User: {{ getUserName(\Auth::user()->id) }}</h6>
    </div>
    <div class="col-md-3">
        @php
            $product = App\Models\Product\Product::find($request['product_id']);
        @endphp
        <h6>Report Type: {{ $product ? $product->product_name : 'All Products' }} Ledger</h6>
    </div>
    <div class="col-md-9">
        <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
    </div>
    <div class="col-md-3">
        <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
    </div>
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <h6>Product Name: {{ $product ? $product->product_name : 'All Products' }}</h6>
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
                        <th style="border:1px solid black;">Product Name</th>
                        <th style="border:1px solid black;">Measuring Unit</th>
                        <th style="border:1px solid black;">Particular</th>
                        <th style="border:1px solid black;">Debit</th>
                        <th style="border:1px solid black;">Credit</th>
                        <th style="border:1px solid black;">Running Balance</th>
                    </tr>
                </thead>
                <tbody style="border: 2px solid black;">
                    @isset($ledger_data)
                        @php
                            $grandTotalDebit = 0;
                            $grandTotalCredit = 0;
                            $runningBalance = $opening_stock;
                        @endphp

                        {{-- Opening Stock Row --}}
                        <tr>
                            <td style="border:1px solid black;">-</td>
                            <td style="border:1px solid black;">{{ $product_name }}</td>
                            <td style="border:1px solid black;">-</td>
                            <td style="border:1px solid black;">Opening Stock</td>
                            <td style="border:1px solid black;">-</td>
                            <td style="border:1px solid black;">-</td>
                            <td style="border:1px solid black;">{{ $runningBalance }}</td>
                        </tr>

                        {{-- Ledger Data Rows --}}
                        @foreach($ledger_data as $ledger)
                            @php
                                $grandTotalDebit += $ledger->debit;
                                $grandTotalCredit += $ledger->credit;
                                $runningBalance = $runningBalance + $ledger->debit - $ledger->credit;
                            @endphp
                            <tr>
                                <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                <td style="border:1px solid black;">{{ $ledger->product_name }}</td>
                                <td style="border:1px solid black;">{{ $ledger->measuring_unit_name }}</td>
                                <td style="border:1px solid black;">{{ $ledger->particular }}</td>
                                <td style="border:1px solid black;">{{ $ledger->debit }}</td>
                                <td style="border:1px solid black;">{{ $ledger->credit }}</td>
                                <td style="border:1px solid black;">{{ $runningBalance }}</td>
                            </tr>
                        @endforeach

                        {{-- Grand Total Row --}}
                        <tr>
                            <td colspan="4" style="border:1px solid black; text-align: right;"><strong>Grand Total</strong></td>
                            <td style="border:1px solid black;">{{ $grandTotalDebit }}</td>
                            <td style="border:1px solid black;">{{ $grandTotalCredit }}</td>
                            <td style="border:1px solid black;">{{ $runningBalance }}</td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection

@section('prepaid_by')
{{ getUserName(\Auth::user()->id) }}
@endsection
