@extends('adminPanel/print_master')
@section('content')
    <h3 style="margin-top:40px;">Payments Voucher</h3>

    </section>
    <div class="row pl-5 pr-5">
        <div class="col-md-9">
            <h5>Report </h5>
        </div>
        <div class="col-md-3">
            <h5>Details</h5>
        </div>
        <div class="col-md-9">
            <h6>User: {{ Auth::user()->name }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Payment Date: {{ date('d-m-Y', strtotime($payment->date)) }}</h6>
        </div>
        <div class="col-md-9">
            <h6>Payment ID: {{ $payment->id }}</h6>
        </div>
        <div class="col-md-3">
            <h6>Payment From: {{ $payment->account->account_name }}</h6>
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
        <table class="table table-sm table-hover table-bordered" style="border: 2px solid black;">
            <thead style="color: black; border: 1px solid black;">
                <tr style="background-color: lightgray; color: black;">
                    <th style="border:1px solid black;">Sr</th>
                    <th style="border:1px solid black;">Type</th>
                    <th style="border:1px solid black;">Particular</th>
                    <td style="border:1px solid black;">Remarks</td>

                    <th style="border:1px solid black;">Credit</th>
                    <th style="border:1px solid black;">Debit</th>
                </tr>

            </thead>
            <tbody style="border: 2px solid black;">
                <tr>
                    <td style="border:1px solid black;">1</td>
                    <td style="border:1px solid black;">Account</td>
                    <td style="border:1px solid black;">{{ $payment->account->account_name }}</td>
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;">{{ number_format($payment->total_payments) }}</td>
                    <td style="border:1px solid black;"></td>
                </tr>

                @php
                    $total_payment = 0;
                @endphp

                @foreach($payment->paymentItems as $key => $paymentItem)
                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration + 1 }}</td>
                        <td style="border:1px solid black;"> @php
                            $particular = $paymentItem->paymentParticular;
                            if ($paymentItem->particular == 'Account') {
                                echo "Cash Account";
                            } else {
                                echo $particular->type;
                            }
                        @endphp</td>
                        <td style="border:1px solid black;">{{ $paymentItem->particular_name }}</td>
                        <td style="border:1px solid black;">{{ $paymentItem->remarks }}</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{ number_format($paymentItem->payment) }}</td>
                    </tr>
                    @php
                        $total_payment += $paymentItem->payment;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-weight-bold" style="background-color: #f0eded !important; font-size: 20px;">

                <tr class="font-weight-bold">
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;">{{ number_format($payment->total_payments) }}</td>
                    <td style="border:1px solid black;">{{ number_format($total_payment) }}</td>
                </tr>
            </tfoot>
        </table>
@endsection

    @section('prepaid_by')
        {{ Auth::user()->name }}
    @endsection