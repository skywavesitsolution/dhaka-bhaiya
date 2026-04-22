<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statement</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .table thead th {
            background-color: lightgray;
            color: #1f1f1f;
        }

        .section-title {
            background-color: #ecf0f1;
            font-weight: bold;
        }

        .highlighted {
            background-color: #ffe4e1;
            /* Light coral */
        }

        .text-right {
            text-align: right;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>


    <!-- jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('pdfButton').addEventListener('click', async () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const content = document.querySelector('.container');

            // Generate PDF
            await doc.html(content, {
                callback: function(doc) {
                    doc.save('Financial_Statement.pdf');
                },
                x: 10,
                y: 10,
                html2canvas: {
                    scale: 0.8, // Adjust scale for better quality
                },
            });
        });
    </script>
</body>

</html>
























<?php

use App\Helpers\Helper;
use App\Models\Order;

?>

@extends('adminPanel/print_master')


@section('content')


    <div class="container">
        <!-- PDF Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary" onclick="convertToPDF()">Convert to PDF</button>
        </div>

        <h2>Balance Sheet</h2>
        <div class="row g-4">
            <!-- Assets Table -->
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Assets</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="section-title">
                            <td colspan="2">Current assets</td>
                        </tr>
                        <tr>
                            <td>Inventory</td>
                            <td class="text-right">{{ number_format($inventory, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Cash and cash equivalents</td>
                            <td class="text-right">{{ number_format($cashAndEquivalents, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Accounts receivable</td>
                            <td class="text-right">{{ number_format($accountsReceivable, 2) }}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total current assets</td>
                            <td class="text-right">{{ number_format($totalCurrentAssets, 2) }}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total assets</td>
                            <td class="text-right">{{ number_format($overAlltotalAssets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Liabilities Table -->
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fix Assets</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="section-title">
                            <td colspan="2">Non-current assets</td>
                        </tr>
                        @foreach ($fix_assets as $fix_asset)
                            <tr>
                                {{-- <td>{{ $fix_asset->name }}</td> <!-- Assuming 'name' holds the asset name --> --}}
                                <td>{{ $fix_asset->product_variant_name }}</td> <!-- Correct dynamic field -->
                                <td class="text-right">
                                    {{ $fix_asset->stock->stock * $fix_asset->rates->cost_price ?? 0 }}</td>
                                <!-- Total cost = stock * cost -->
                            </tr>
                        @endforeach
                        {{-- <tr>
                            <td>Fixed Assets</td>
                            <td class="text-right">{{ $fixAssets }}</td>
                        </tr> --}}
                        <tr class="section-title">
                            <td>Total non-current assets</td>
                            <td class="text-right">{{ $fixAssets }}</td>
                        </tr>

                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Liabilities</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="section-title">
                            <td colspan="2">Current liabilities</td>
                        </tr>
                        <tr>
                            <td>Accounts payable</td>
                            <td class="text-right">{{ $accountsPayable }}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total liabilities</td>
                            <td class="text-right">{{ $accountsPayable }}</td>
                        </tr>
                        <tr class="highlighted">
                            <td colspan="2">Shareholders' equity</td>
                        </tr>
                        <tr>
                            <td>Capital stock</td>
                            <td class="text-right">{{ $capitalInvested }}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total liabilities & stockholders' equity</td>
                            <td class="text-right">{{ $totalLiabilitiesAndEquity }}</td>
                        </tr>
                    </tbody>
                </table>
            @section('prepaid_by')
                {{ \Auth::user()->name }}
            @endsection
        </div>
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
