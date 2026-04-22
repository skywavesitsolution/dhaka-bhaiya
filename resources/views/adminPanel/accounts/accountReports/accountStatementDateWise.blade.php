<?php

use App\Helpers\Helper;
use App\Models\Order;

?>
@extends('adminPanel/print_master')
@section('content')
    <h3 style="margin-top:40px;">Date Wise Account Statement report</h3>

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

        <div class="col-3">
            <h6>Account Name: {{ $account->account_name }}</h6>
        </div>
        <div class="col-9">
            <h6>Reporting Date: {{ date('d-m-Y') }}</h6>
        </div>
        <div class="col-3">
            <h6>Reporting Time: {{ date('h:i:sa') }}</h6>
        </div>
        <div class="col-9">
            <h6>Start Date: {{ date('d-m-Y', strtotime($request_data['start_date'])) }}</h6>
        </div>
        <div class="col-3">
            <h6>End Date: {{ date('d-m-Y', strtotime($request_data['end_date'])) }}</h6>
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
                            $total_debit = 0;
                            $total_credit = 0;

                        @endphp

                        @isset($sortedArray)

                            @foreach($sortedArray as $ledgerItem)

                                            <?php
                                $desc = '';
                                if (isset($ledgerItem->payment_item_id)) {
                                    $desc = "Payment Amount item| Voucher Id:" . $ledgerItem->make_payment_id . "  | Transcation Id:" . $ledgerItem->payment_item_id;
                                    $desc .= "<br>Account: " . $ledgerItem->load('makePayment')->makePayment->account->account_name
                                }

                                if (isset($ledgerItem->main_received_payment_id)) {
                                    $desc = "Received Amount| Voucher Id:" . $ledgerItem->main_received_payment_id;
                                    foreach ($ledgerItem->paymentItems as $paymentItem) {
                                        $desc .= "<br>Party Type: " . $paymentItem->particular . " | Name: " . $paymentItem->particular_name . " | Amount:" . $paymentItem->payment . "";
                                    }
                                }

                                if (isset($ledgerItem->main_make_payment_id)) {
                                    $desc = "Payment Amount| Voucher Id:" . $ledgerItem->main_make_payment_id;
                                    foreach ($ledgerItem->paymentItems as $paymentItem) {
                                        $desc .= "<br>Party Type: " . $paymentItem->particular . " | Name: " . $paymentItem->particular_name . " | Amount:" . $paymentItem->payment . "";
                                    }
                                }

                                if (isset($ledgerItem->received_item_id)) {
                                    $desc = "Received Amount Item| Voucher Id:" . $ledgerItem->received_payment_id . "  | Transcation  Id:" . $ledgerItem->received_item_id;
                                    $desc .= "<br>Account: " . $ledgerItem->load('receivedPayment')->receivedPayment->account->account_name
                                }

                                if (isset($ledgerItem->deposit_amount)) {
                                    $desc = "Deposit Amount | Id:" . $ledgerItem->id;
                                }
                                if (isset($ledgerItem->received)) {
                                    $desc = "Sale received Amount | Id:" . $ledgerItem->id;
                                }
                                if (isset($ledgerItem->payment)) {
                                    $desc = "Purchase Amount | Id:" . $ledgerItem->id;
                                }


                                            ?>
                                            <tr>
                                                <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                                                <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($ledgerItem->date)) }}</td>
                                                <td style="border:1px solid black;">{!! $desc !!} |

                                                    Note: {!! $ledgerItem->remarks ?? '' !!}
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php
                                if (isset($ledgerItem->payment_item_id)) {
                                                    ?>
                                                    @if($ledgerItem->payment_item_id !== NULL)
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

                                if (isset($ledgerItem->main_received_payment_id)) {
                                                    ?>
                                                    @if($ledgerItem->main_received_payment_id !== NULL)
                                                                    <?php
                                                        if ($ledgerItem->total_payments >= 0) {
                                                            echo number_format($ledgerItem->total_payments);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->total_payments) . " ]";
                                                        }
                                                                        ?>
                                                                    @php
                                                                        $total_debit += $ledgerItem->total_payments;
                                                                    @endphp
                                                    @endif
                                                    <?php

                                }

                                if (isset($ledgerItem->deposit_amount)) {
                                                    ?>
                                                    @if($ledgerItem->deposit_amount !== NULL)
                                                                    <?php
                                                        if ($ledgerItem->deposit_amount >= 0) {
                                                            echo number_format($ledgerItem->deposit_amount);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->deposit_amount) . " ]";
                                                        }
                                                                        ?>
                                                                    @php
                                                                        $total_debit += $ledgerItem->deposit_amount;
                                                                    @endphp
                                                    @endif
                                                    <?php

                                }
                                                    ?>
                                                    <?php



                                if (isset($ledgerItem->received)) {
                                                    ?>
                                                    @if($ledgerItem->received !== NULL)
                                                                    <?php
                                                        if ($ledgerItem->received >= 0) {
                                                            echo number_format($ledgerItem->received);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->received) . " ]";
                                                        }
                                                                        ?>
                                                                    @php
                                                                        $total_debit += $ledgerItem->received;
                                                                    @endphp
                                                    @endif
                                                    <?php

                                }
                                                    ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php
                                if (isset($ledgerItem->main_make_payment_id)) {
                                                    ?>
                                                    @if($ledgerItem->total_payments !== NULL)
                                                                    <?php
                                                        if ($ledgerItem->total_payments >= 0) {
                                                            echo number_format($ledgerItem->total_payments);
                                                        } else {
                                                            echo "[ " . number_format($ledgerItem->total_payments) . " ]";
                                                        }
                                                                        ?>
                                                                    @php
                                                                        $total_credit += $ledgerItem->total_payments;
                                                                    @endphp
                                                    @endif


                                                    <?php

                                }

                                if (isset($ledgerItem->received_item_id)) {
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
                                                    ?>
                                                    <?php



                                if (isset($ledgerItem->payment)) {
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
                                                    ?>
                                                </td>

                                            </tr>

                            @endforeach
                        @endisset
                        <tr>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Totals</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">{{ $total_debit }}</td>
                            <td style="border:1px solid black;">{{ $total_credit }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Difference</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">
                                <?php
    $difference = $total_debit - $total_credit;
    if ($difference >= 0) {
        echo number_format($difference);
    } else {
        echo "[ " . number_format(abs($difference)) . " ]";
    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Opening Balance</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">
                                <?php
    $openingBalance = $account->opening_balance;
    // dd($openingBalance);
    // if ($ledgerLastTranscation) {
    //     $openingBalance = $ledgerLastTranscation->balance;
    // }

    if ($openingBalance >= 0) {
        echo number_format($openingBalance);
    } else {
        echo "[ " . number_format(abs($openingBalance)) . " ]";
    }
                                ?>
                            </td>
                        </tr>


                        <tr>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">Balance</td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;"></td>
                            <td style="border:1px solid black;">
                                <?php
    $balance = $account->balance;
    if ($balance >= 0) {
        echo number_format($balance);
    } else {
        echo "[ " . number_format(abs($balance)) . " ]";
    }
                                ?>
                            </td>
                        </tr>
                    </tbody>

                </table>
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