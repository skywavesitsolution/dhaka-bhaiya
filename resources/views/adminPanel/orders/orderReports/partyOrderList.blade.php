@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">
    @if($request['reportType'] == 'dateWise')
    Date Wise
    @endif
    {{ $request['particularType'] }} Orders List
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
    @if($request['reportType'] == 'dateWise')
    <div class="col-md-3">
        <h6>Start Date: {{ $request['start_date'] }}</h6>
    </div>
    <div class="col-md-9">
        <h6>End Date: {{ $request['end_date'] }}</h6>
    </div>
    @endif
    <div class="col-md-3">
        <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
    </div>
    <div class="col-md-9">
        <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
    </div>
    <div class="col-md-3">
        <h6>{{ $request['particularType'] }}: {{ $partyName }}</h6>
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
                        <th style="border:1px solid black;">Product Type</th>
                        <th style="border:1px solid black;">Marka</th>
                        <th style="border:1px solid black;">Quantity</th>
                        <th style="border:1px solid black;">Pr Rate</th>
                        <th style="border:1px solid black;">Total Pr</th>
                        <th style="border:1px solid black;">Driver</th>
                        <th style="border:1px solid black;">Car Amt</th>
                        <th style="border:1px solid black;">Total Cr</th>
                        <th style="border:1px solid black;">Supplier</th>
                        <th style="border:1px solid black;">Customer</th>
                        <th style="border:1px solid black;">Sale Pr</th>
                        <th style="border:1px solid black;">Total Sale</th>
                        <th style="border:1px solid black;">Profit</th>
                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">

                    @php
                    $totalPurchase = 0;
                    $totalCarraige = 0;
                    $totalSale = 0;
                    $totalProfit = 0;
                    $productTypes = [];
                    @endphp
                    @isset($orders)

                    @foreach($orders as $order)
                    <?php
                    $totalPurchase += $order->total_purchase;
                    $totalCarraige += $order->total_carriage;
                    $totalSale += $order->total_sale_amount;
                    $totalProfit += $order->profit;

                    $productType = $order->product->name ?? '';
                    if ($productType != '') {
                        if (isset($productTypes[$productType]))
                            $productTypes[$productType] = $productTypes[$productType] + $order->purchase_qty;
                        else
                            $productTypes[$productType] = $order->purchase_qty;
                    }

                    ?>

                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">{{ date('d-m-Y',strtotime($order->date)) }}</td>
                        <td style="border:1px solid black;">{{ $order->product->name ?? '' }}</td>
                        <td style="border:1px solid black;">{{ $order->maraka->name }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->purchase_qty, 2, '.', ',') }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->purchase_rate) }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->total_purchase) }}</td>
                        <td style="border:1px solid black;">{{ $order->driver->name }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->carriage_amount) }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->total_carriage) }}</td>
                        <td style="border:1px solid black;">{{ $order->supplier->name }}</td>
                        <td style="border:1px solid black;">{{ $order->customer->name }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->sale_rate) }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->total_sale_amount) }}</td>
                        <td style="border:1px solid black;">{{ number_format($order->profit) }}</td>
                    </tr>

                    @endforeach
                    @endisset

                </tbody>

            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-4">
            <h4>Summary:</h4>
            <hr>
        </div>
        <div class="col-md-8 p-5">
            <h5>Product Types Totals</h5>

            @foreach($productTypes as $index => $productType)
            <h6>{{ $index }}: {{ number_format($productType) }}</h6>
            @endforeach
        </div>
        <div class="col-md-4 p-5">
            <h6>Total Purchase: {{ number_format($totalPurchase) }}</h6>
            <h6>Total Carriage: {{ number_format($totalCarraige) }}</h6>
            <h6>Total Sale: {{ number_format($totalSale) }}</h6>
            <h6>Total Profit: {{ number_format($totalProfit) }}</h6>
        </div>
    </div>


    @endsection

    @section('prepaid_by')
    {{ \Auth::user()->name }}
    @endsection