@extends('adminPanel/print_master')

@section('content')
    <div class="container">
        <!-- PDF Button -->
        
        <h2>Trial Balance Sheet</h2>
        <div class="d-flex justify-content-end mb-4">
            {{-- <button class="btn btn-primary" onclick="convertToPDF()">Convert to PDF</button> --}}
        </div>
        <div class="row g-4">
            <div class="col-md-12">
                <table class="table table-bordered" style="border: 2px solid black;">
                    <thead style="background-color: lightgray">
                        <tr>
                            <th>Account Name</th>
                            <th>Debit Amount</th>
                            <th>Credit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Assets Section -->
                        <tr class="section-title">
                            <td colspan="3">Assets</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td class="text-right">{{ number_format($cashAndEquivalents, 2) }}</td>
                            <td class="text-right">0.00</td>
                        </tr>
                        <tr>
                            <td>Account Receivable</td>
                            <td class="text-right">{{ number_format($accountsReceivable, 2) }}</td>
                            <td class="text-right">0.00</td>
                        </tr>
                        <tr>
                            <td>inventory</td>
                            <td class="text-right">{{ number_format($inventory, 2) }}</td>
                            <td class="text-right">0.00</td>
                        </tr>
                        <tr>
                            <td>Fix Assets</td>
                            <td class="text-right">{{ number_format($fixAssets, 2) }}</td>
                            <td class="text-right">0.00</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bolder">Total Assets</td>
                            <td style="font-weight: bolder" class="text-right">{{ number_format($overAlltotalAssets, 2) }}</td>
                            <td style="font-weight: bolder" class="text-right">0.00</td>
                        </tr>
                        
                       

                        <!-- Liabilities Section -->
                        <tr class="section-title">
                            <td colspan="3">Liabilities</td>
                        </tr>

                        <tr>
                            <td>Accounts Payable</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">{{ number_format($accountsPayable, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Sale revenue</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">{{ number_format($sale_revenue, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Expenses</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">{{ number_format($expense, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bolder">Total Liabilitie</td>
                            <td class="text-right" style="font-weight: bolder">0.00</td>
                            <td class="text-right" style="font-weight: bolder">{{ number_format($accountsPayable, 2) }}</td>
                        </tr>
                        

                        <!-- Equity Section -->
                        <tr class="section-title">
                            <td colspan="3">Equity</td>
                        </tr>
                        <tr>
                            <td>Owner's Equity</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">{{ number_format($capitalInvested, 2) }}</td>
                        </tr>

                       

                        <!-- Final Total -->
                        <tr class="section-title">
                            <td style="font-weight: bolder">Total</td>
                            <td style="font-weight: bolder" class="text-right">{{ number_format($overAlltotalAssets, 2) }}</td>
                            <td style="font-weight: bolder" class="text-right">{{ number_format($totalLiabilitiesAndEquity, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('prepaid_by')
    {{ \Auth::user()->name }}
@endsection

