<?php

use App\Helpers\Helper;
?>
@extends('adminPanel/print_master')
@section('content')
<h3 style="margin-top:40px;">{{ $type }} Last Transcation Report</h3>

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
        <h6>From Date: {{ $from_date }}</h6>
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
                        <th style="border:1px solid black;">Sr</th>
                        <th style="border:1px solid black;">ID</th>
                        <th style="border:1px solid black;">Name</th>
                        <th style="border:1px solid black;">Balance</th>
                        <th style="border:1px solid black;">Last Transcation</th>

                    </tr>

                </thead>
                <tbody style="border: 2px solid black;">

                 
                    @isset($partyLastTransArr)

                    @foreach($partyLastTransArr as $party)



                    <tr>
                        <td style="border:1px solid black;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid black;">
                            {{ $party->id }}
                        </td>

                        <td style="border:1px solid black;">
                            {{ $party->name }}
                            {{-- @if($type == 'Customer') --}}
                            <br>
                            type:{{ $type  }}
                            {{-- @endif --}}
                        </td>
                        <td style="border:1px solid black;">
                            {{ $party->balance }}
                        </td>
                        <td style="border:1px solid black;">
                            {{ $party->lastTranscation }}
                        </td>
                    </tr>

                   
                    @endforeach
                    @endisset


                </tbody>

            </table>
        </div>
    </div>

    @endsection


    @section('prepaid_by')
    {{ \Auth::user()->name }}
    @endsection