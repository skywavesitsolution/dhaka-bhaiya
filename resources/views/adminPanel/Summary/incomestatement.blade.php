@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    Day Wise Income Statement
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
            <h6>User: {{ \Auth::user()->name }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Report type : Income Statement</h6>
        </div>
        <div class="col-md-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
        </div>
        <div class="col-md-9">
            <h6>Start Date:  {{ $start_date }}</h6>
        </div>
        <div class="col-md-3">
            <h6>End Date:  {{ $end_date }}</h6>
        </div>

    </div>

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">General Sale's</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Product Name</th>
                            <th style="border:1px solid black;">Sale Qty</th>
                            <th style="border:1px solid black;">Cost Price</th>
                            <th style="border:1px solid black;">Retail Price</th>
                            <th style="border:1px solid black;">Profit Price</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                            $totalgeneralSaleProfit = 0;
                        @endphp
                        @isset($generalSales)
                            @foreach($generalSales as $index => $sale)
                                <tr>
                                    <td style="border:1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border:1px solid black;">{{ $sale['name'] }}</td>
                                    <td style="border:1px solid black;">{{ $sale['total_quantity'] }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_cost'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_retail'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_retail'] - $sale['total_cost'], 2) }}</td>
                                </tr>
                                @php
                                    $totalgeneralSaleProfit += ($sale['total_retail'] - $sale['total_cost']);
                                @endphp
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">
                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;">{{ number_format($totalgeneralSaleProfit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

    {{-- <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Nozzle Sale's</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Product Name</th>
                            <th style="border:1px solid black;">Sale Qty</th>
                            <th style="border:1px solid black;">Cost Price</th>
                            <th style="border:1px solid black;">Retail Price</th>
                            <th style="border:1px solid black;">Profit Price</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                            $totalNozzleProfit = 0;
                        @endphp
                        @isset($nozzleSales)
                            @foreach($nozzleSales as $index => $sale)
                                <tr>
                                    <td style="border:1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border:1px solid black;">{{ $sale['name'] }}</td>
                                    <td style="border:1px solid black;">{{ $sale['total_quantity'] }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_cost'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_retail'], 2) }}</td>
                                    <td style="border:1px solid black;">{{ number_format($sale['total_retail'] - $sale['total_cost'], 2) }}</td>
                                </tr>
                                @php
                                    $totalNozzleProfit += ($sale['total_retail'] - $sale['total_cost']);
                                @endphp
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">
                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;">{{ number_format($totalNozzleProfit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section> --}}

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Expanses</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Date</th>
                            <th style="border:1px solid black;">Name</th>
                            <th style="border:1px solid black;">Category</th>
                            <th style="border:1px solid black;">Sub Category</th>
                            <th style="border:1px solid black;">Paid From</th>
                            <th style="border:1px solid black;">Amount</th>
                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                        $totalExpense = 0;
                        @endphp
                        @isset($expense)
                        @foreach($expense as $expense_res)
                        <tr>
                            <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                            <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($expense_res->date)) }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->exp_name }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->exp_category_name }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->exp_sub_category }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->account_name." / ".$expense_res->account_number}}</td>
                            <td style="border:1px solid black;">{{ number_format($expense_res->total_amount) }}</td>
                        </tr>
                        @php
                        $totalExpense += $expense_res->total_amount;
                        @endphp
                        @endforeach
                        @endisset

                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;">{{ $totalExpense }}</td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-8"></div>
            <div class="col-4">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th colspan="4" class="text-center" style="background-color: lightgray; color: black;">Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $gross_total = 0;
                            $gross_total = $totalgeneralSaleProfit;
                            $net_profit = 0;
                            $net_profit = $gross_total - $totalExpense;


                        @endphp
                        <tr>
                            <th>Total General Sale profit</th>
                            <td>
                               {{ $totalgeneralSaleProfit}}
                            </td>
                        </tr>
                        {{-- <tr>
                            <th>Total Nozzle Sale profit</th>
                            <td>
                                {{ $totalNozzleProfit }}
                            </td>
                        </tr> --}}
                        <tr>
                            <th>Gross Total</th>
                            <td>
                                {{ $gross_total }}
                            </td>
                        </tr>
                        <tr>
                            <th>Total Expense</th>
                            <td>
                                {{ $totalExpense }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Net Profit</th>
                            <td>
                                {{ $net_profit }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection

@section('prepaid_by')
    {{ \Auth::user()->name }}
@endsection