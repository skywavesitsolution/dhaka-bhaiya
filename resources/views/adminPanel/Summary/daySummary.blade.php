@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    Summary Day Wise
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
            <h6>Summary Date: {{ $date }}</h6>
        </div>
        <div class="col-md-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
        </div>

    </div>

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Cash Sale</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Sales Type</th> 
                            <th style="border:1px solid black;">Product</th>
                            <th style="border:1px solid black;">Quantity Sold</th>
                            <th style="border:1px solid black;">Rate</th>
                            <th style="border:1px solid black;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                            $totalCashSaleAmount = 0;
                        @endphp
                        @isset($allSales)
                        @foreach($allSales as $index => $sale)
                            <tr>
                                <td style="border:1px solid black;">{{ $index + 1 }}</td>
                                <td style="border:1px solid black;">{{ $sale['sale_type'] }}</td>
                                <td style="border:1px solid black;">{{ $sale['name'] }}</td>
                                <td style="border:1px solid black;">{{ $sale['quantity'] }}</td>
                                <td style="border:1px solid black;">{{ $sale['price'] }}</td>
                                <td style="border:1px solid black;">{{ $sale['amount'] }}</td>
                            </tr>
                            @php
                                $totalCashSaleAmount += $sale['amount'];
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
                            <td style="border:1px solid black;">{{ $totalCashSaleAmount }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Credit Sale</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Sales Type</th> <!-- Sale Type: 'Product Sales' or 'Nozzle Sales' -->
                            <th style="border:1px solid black;">Product</th>
                            <th style="border:1px solid black;">Quantity Sold</th>
                            <th style="border:1px solid black;">Rate</th>
                            <th style="border:1px solid black;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                            $totalCreditSaleAmount = 0;
                        @endphp
                        @isset($creditSaleProducts)
                            @foreach($creditSaleProducts as $index => $sale)
                                <tr>
                                    <td style="border:1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border:1px solid black;">{{ $sale->sale_type }}</td>
                                    <td style="border:1px solid black;">{{ $sale->name }}</td>
                                    <td style="border:1px solid black;">{{ $sale->quantity }}</td>
                                    <td style="border:1px solid black;">{{ $sale->price }}</td>
                                    <td style="border:1px solid black;">{{ $sale->amount }}</td>
                                </tr>
                                @php
                                    $totalCreditSaleAmount += $sale['amount'];
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
                            <td style="border:1px solid black;">{{ $totalCreditSaleAmount }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Expanses</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">
                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">ID</th>
                            <th style="border:1px solid black;">Date</th>
                            <th style="border:1px solid black;">Name</th>
                            <th style="border:1px solid black;">Category</th>
                            <th style="border:1px solid black;">Sub Category</th>
                            <th style="border:1px solid black;">Amount</th>
                            <th style="border:1px solid black;">Paid From</th>
                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                        $totalExpenseAmount = 0;
                        @endphp
                        @isset($expense)

                        @foreach($expense as $expense_res)
                        <tr>
                            <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->id }}</td>
                            <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($expense_res->date)) }}</td>

                            <td style="border:1px solid black;">{{ $expense_res->exp_name }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->exp_category_name }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->exp_sub_category }}</td>
                            <td style="border:1px solid black;">{{ number_format($expense_res->total_amount) }}</td>
                            <td style="border:1px solid black;">{{ $expense_res->account_name." / ".$expense_res->account_number}}</td>
                        </tr>
                        @php
                        $totalExpenseAmount += $expense_res->total_amount;
                        @endphp
                        @endforeach
                        @endisset

                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ $totalExpenseAmount }}</td>
                            <td style="border:1px solid black;"></td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

    <section style="margin: 20px;">
        <h4 class="m-4" style="text-align: center;" id="">Customer Received Payments</h4>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <thead style="color: black; border: 1px solid black;">
                        <tr style="background-color: lightgray; color: black;">

                            <th style="border:1px solid black;">Sr</th>
                            <th style="border:1px solid black;">Receivable Payment Id</th>
                            <th style="border:1px solid black;">Particular</th>
                            <th style="border:1px solid black;">Particular Name</th>
                            <th style="border:1px solid black;">Payment</th>
                            <th style="border:1px solid black;">Remarks</th>
                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">
                        @php
                        $totalPayments = 0;
                        @endphp
                        @isset($reaceivedPayment)
                        @foreach($reaceivedPayment as $payment)
                        @foreach($payment->paymentItems as $item)
                        <tr>
                            <td style="border:1px solid black;">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border:1px solid black;">
                                {{ $item->received_payment_id }}
                            </td>
                            <td style="border:1px solid black;">
                                {{ $item->particular }}
                            </td>
                            <td style="border:1px solid black;">
                                {{ $item->particular_name}}
                            </td>
                            <td style="border:1px solid black;">
                                {{ number_format($item->payment) }}
                            </td>
                            <td style="border:1px solid black;">
                                {{ $item->remarks }}
                            </td>


                        </tr>
                        @php
                        $totalPayments += $item->payment;
                        @endphp
                        @endforeach
                        @endforeach
                        @endisset

                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

                        <tr class="font-weight-bold">
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ $totalPayments }}</td>
                            <td style="border:1px solid black;"></td>
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
                        $netCashTotal = 0;
                        $grossTotal = 0;
                        $grandTotal = 0;
                        $netCashTotal = $totalCashSaleAmount - $totalCreditSaleAmount;
                        $grossTotal = $netCashTotal + $totalPayments;
                        $grandTotal = $grossTotal - $totalExpenseAmount;

                    @endphp
                    <tr>
                        <th>Total Cash Sale Amount</th>
                        <td>
                            {{ $totalCashSaleAmount }}
                        </td>
                    </tr>
                    <tr>
                        <th>Total Credit Sale Amount</th>
                        <td>
                            {{ $totalCreditSaleAmount }}
                        </td>
                    </tr>
                    <tr>
                        <th>Net Cash Sale Amount</th>
                        <td>
                            {{ $netCashTotal }}
                        </td>
                    </tr>
                    <tr>
                        <th>Total Customer Recieved</th>
                        <td>
                            {{ $totalPayments }}
                        </td>
                    </tr>
                    <tr>
                        <th>Gross Total</th>
                        <td>
                            {{ $grossTotal }}
                        </td>
                    </tr>
                    <tr>
                        <th>Total Expense</th>
                        <td>
                            {{ $totalExpenseAmount }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Grand Total</th>
                        <td>
                            {{ $grandTotal }}
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