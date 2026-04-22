<?php

use App\Helpers\Helper;
use App\Models\Order;

?>
@extends('adminPanel/print_master')
@section('content')
    <h3 style="margin-top:40px;">{{ $party->name }}Report</h3>

    <div class="row mb-5" id="buttonRow">
        <div class="col-md-6"></div>
        <div class="col-md-6 d-flex flex-row-reverse">
            <button onclick="printDocument();" class="btn btn-warning ">Print</button>

            @if($nextPartyId)
                <form action="{{ URL::to('generate-party-statement') }}" method="POST">
                    @csrf
                    <input type="hidden" name="partyId" value="{{ $nextPartyId }}">
                    <button type="submit" class="btn btn-primary mr-2">Next</button>
                    {{-- <button type="submit" class=" bg-primary">Next Party</button> --}}
                </form>
            @else
                <button type="submit" class="btn btn-primary mr-2 d-none">Last</button>
            @endif

            @if($previousPartyId)
                <form action="{{ URL::to('generate-party-statement') }}" method="POST">
                    @csrf
                    <input type="hidden" name="partyId" value="{{ $previousPartyId }}">
                    <button type="submit" class="btn btn-primary mx-4">Previous</button>
                    {{-- <button type="submit" class=" bg-primary">Next Party</button> --}}
                </form>
            @else
                <button type="submit" class="btn btn-primary mr-2 d-none">Last</button>
            @endif
        </div>
    </div>

    </section>
    <div class="row pl-5 pr-5">
        <div class="col-9">
            <h5>Report </h5>
        </div>
        <div class="col-3">
            <h5>Details</h5>
        </div>
        <div class="col-9">
            <h6>User: {{ \Auth::user()->name }}</h6>
        </div>
        {{-- @if($party->type == 'Customer')
        <div class="col-3">
            <h6>Supplier: {{ $party->supplier->name }}</h6>
        </div>
        @endif --}}
        <div class="col-9">
            <h6>Party Name: {{ $party->name }}</h6>
        </div>
        <div class="col-3">
            <h6>Party Type: {{ $party->type }}</h6>
        </div>
        <div class="col-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-3">
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
                            <th style="border:1px solid black;">Date</th>
                            <th style="border:1px solid black;">Desctiption</th>
                            <th style="border:1px solid black;">Dr</th>
                            <th style="border:1px solid black;">Cr</th>

                        </tr>

                    </thead>
                    <tbody style="border: 2px solid black;">

                        @php
                            $total_amount = 0;
                            $total_credit = 0;
                            $total_debit = 0;

                        @endphp

                        @php
                            $totalQty = 0;
                            $totalPurchase = 0;
                            $totalCarraige = 0;
                            $totalSale = 0;
                            $totalProfit = 0;
                            $productTypes = [];
                            $orderIds = [];
                        @endphp

                        @isset($sortedArray)

                            @foreach($sortedArray as $ledgerItem)

                                            <?php
                                $desc = '';
                                if (isset($ledgerItem->order_id)) {
                                    $desc = "Order | Id:" . $ledgerItem->order_id;
                                }


                                if (isset($ledgerItem->payment_id)) {
                                    $desc = "Payment Amount | Voucher Id:" . $ledgerItem->payment_id . " | Transaction Id: " . $ledgerItem->id . "";
                                    $desc .= "<br>Account: " . $ledgerItem->load('makePayment')->makePayment->account->account_name
                                }

                                if (isset($ledgerItem->received_id)) {
                                    $desc = "Received Amount | Voucher Id:" . $ledgerItem->received_id . " | Transaction Id: " . $ledgerItem->id . "";
                                    $desc .= "<br>Account: " . $ledgerItem->load('receivedPayment')->receivedPayment->account->account_name
                                }


                                            ?>
                                            <tr>
                                                <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                                <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($ledgerItem->date)) }}</td>
                                                <td style="border:1px solid black;">{!! $desc !!}

                                                    <?php
                                if (isset($ledgerItem->purchase_qty)) {
                                    $order = $ledgerItem;
                                    if ($order) {
                                        if (!in_array($ledgerItem->id, $orderIds)) {
                                            $totalPurchase += $order->total_purchase;
                                            $totalCarraige += $order->total_carriage;
                                            $totalSale += $order->total_sale_amount;
                                            $totalQty += $order->purchase_qty;

                                            $productType = $order->product->name ?? '';
                                            if ($productType != '') {
                                                if (isset($productTypes[$productType]))
                                                    $productTypes[$productType] = $productTypes[$productType] + $order->purchase_qty;
                                                else
                                                    $productTypes[$productType] = $order->purchase_qty;
                                            }

                                            $orderIds[] = $ledgerItem->order_id;
                                        }
                                    }

                                    if ($party->type == 'Marka') {
                                                    ?>
                                                    <h6>Order ID: {{ $order->id }} | Maraka: {{ $order->maraka->name }} | Product:
                                                        {{ $productType }}| Qty: {{ number_format($order->purchase_qty) }} | Purchase Rate:
                                                        {{ number_format($order->purchase_rate) }} </h6>

                                                    <?php
                                    }

                                    if ($party->type == 'Driver') {
                                                        ?>
                                                    <h6>Order ID: {{ $order->id }} | Maraka: {{ $order->maraka->name }} | Product:
                                                        {{ $productType }}| Qty: {{ number_format($order->purchase_qty) }} | Carriage Amount:
                                                        {{ number_format($order->carriage_amount) }}</h6>

                                                    <?php
                                    }

                                    if ($party->type == 'Customer') {
                                                        ?>
                                                    <h6>Supplier: {{ $order->supplier->name }} | Order ID: {{ $order->id }} | Maraka:
                                                        {{ $order->maraka->name }} | Product: {{ $productType }}| Qty:
                                                        {{ number_format($order->purchase_qty) }} | Sale Price:
                                                        {{ number_format($order->sale_rate) }}</h6>


                                                    <?php
                                    }
                                }
                                                    ?>
                                                    Note: {!! $ledgerItem->remarks !!}
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php
                                if ($party->type == 'Customer') {
                                    if (isset($ledgerItem->payment_id)) {
                                                    ?>
                                                    @if($ledgerItem->payment !== NULL)
                                                                <?php
                                                        if ($ledgerItem->payment >= 0) {
                                                            echo number_format($ledgerItem->payment);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->payment) . " ]";
                                                        }
                                                                        ?>
                                                                @php
                                                                    $total_debit += $ledgerItem->payment;
                                                                @endphp
                                                    @endif
                                                    <?php
                                    }

                                    if (isset($ledgerItem->total_sale_amount)) {
                                        if ($ledgerItem->total_sale_amount >= 0) {
                                            echo number_format($ledgerItem->total_sale_amount);
                                        } else {
                                            echo "[ " . number_format($ledgerItem->total_sale_amount) . " ]";
                                        }
                                        $total_debit += $ledgerItem->total_sale_amount;
                                    }
                                } else {
                                    if (isset($ledgerItem->payment_id)) {
                                                        ?>
                                                    @if($ledgerItem->payment !== NULL)
                                                                <?php
                                                        if ($ledgerItem->payment >= 0) {
                                                            echo number_format($ledgerItem->payment);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->payment) . " ]";
                                                        }
                                                                        ?>
                                                                @php
                                                                    $total_debit += $ledgerItem->payment;
                                                                @endphp
                                                    @endif
                                                    <?php
                                    }
                                }
                                                    ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php
                                if ($party->type == 'Customer') {
                                    if (isset($ledgerItem->received_id)) {
                                                    ?>
                                                    @if($ledgerItem->payment !== NULL)
                                                                <?php
                                                        if ($ledgerItem->payment >= 0) {
                                                            echo number_format($ledgerItem->payment);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->payment) . " ]";
                                                        }
                                                                        ?>
                                                                @php
                                                                    $total_credit += $ledgerItem->payment;
                                                                @endphp
                                                    @endif

                                                    <?php
                                    }
                                } else {
                                    if (isset($ledgerItem->received_id)) {
                                                        ?>
                                                    @if($ledgerItem->payment !== NULL)
                                                                <?php
                                                        if ($ledgerItem->payment >= 0) {
                                                            echo number_format($ledgerItem->payment);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->payment) . " ]";
                                                        }
                                                                        ?>
                                                                @php
                                                                    $total_credit += $ledgerItem->payment;
                                                                @endphp
                                                    @endif


                                                    <?php
                                    }
                                    if ($party->type == 'Marka') {
                                        if (isset($ledgerItem->total_purchase)) {
                                            if ($ledgerItem->total_purchase >= 0) {
                                                echo number_format($ledgerItem->total_purchase);
                                            } else {
                                                echo "[ " . number_format($ledgerItem->total_purchase) . " ]";
                                            }

                                            $total_credit += $ledgerItem->total_purchase;
                                        }
                                    } else {
                                        if (isset($ledgerItem->total_carriage)) {
                                            if ($ledgerItem->total_carriage >= 0) {
                                                echo number_format($ledgerItem->total_carriage);
                                            } else {
                                                echo "[ " . number_format($ledgerItem->total_carriage) . " ]";
                                            }

                                            $total_credit += $ledgerItem->total_carriage;
                                        }
                                    }
                                }
                                                    ?>
                                                </td>

                                            </tr>

                            @endforeach
                        @endisset
                        <tr>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Total</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ $total_debit }}</td>
                            <td style="border:1px solid black;">{{ $total_credit }}</td>

                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

            </div>
            <div class="col-md-6">
                <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
                    <tbody>
                        <tr>

                            <td style="border:1px solid black;">Difference</td>

                            <td style="border:1px solid black;text-align:right">{{ $total_credit - $total_debit }}</td>


                        </tr>
                        <tr>

                            <td style="border:1px solid black;">Opening Balance</td>

                            <td style="border:1px solid black;text-align:right">
                                <?php

    $openingBalance = $party->opening_balance;
    if ($ledgerLastTranscation) {
        $openingBalance = $ledgerLastTranscation->balance;
    }

    if ($openingBalance >= 0) {
        echo number_format($openingBalance);
    } else {
        echo "[ " . number_format(abs($openingBalance)) . " ]";
    }
                                ?>


                            </td>
                        </tr>


                        <tr>

                            <td style="border:1px solid black;">Balance</td>

                            <td style="border:1px solid black;text-align:right">
                                <?php
    $balance = $party->balance;
    if ($balance >= 0) {
        echo number_format($balance);
    } else {
        echo "[ " . number_format($balance) . " ]";
    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <h4>Summary:</h4>
                <hr>
            </div>
            <div class="col-8 p-5">
                <h5>Product Types Totals</h5>

                @foreach($productTypes as $index => $productType)
                    <h6>{{ $index }}: {{ number_format($productType) }}</h6>
                @endforeach
            </div>
            <div class="col-4 p-5">

                <h6>Total Qty: {{ number_format($totalQty) }}</h6>
                @if ($party->type == 'Marka')
                    <h6>Total Purchase: {{ number_format($totalPurchase) }}</h6>
                @endif
                @if ($party->type == 'Driver')
                    <h6>Total Carriage: {{ number_format($totalCarraige) }}</h6>
                @endif
                @if ($party->type == 'Customer')
                    <h6>Total Sale: {{ number_format($totalSale) }}</h6>
                @endif
            </div>
        </div>

@endsection


    @section('prepaid_by')
        {{ \Auth::user()->name }}
    @endsection

    <script>
        function printDocument() {
            window.print();
        }

    </script>