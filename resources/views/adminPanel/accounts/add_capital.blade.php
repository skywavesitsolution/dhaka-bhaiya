@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Capital Management</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Tabs for Add/Withdraw/Total Capital -->
        <ul class="nav nav-tabs" id="capitalTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="total-capital-tab" data-bs-toggle="tab" href="#total-capital" role="tab"
                    aria-controls="total-capital" aria-selected="true">Total Capital</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="add-capital-tab" data-bs-toggle="tab" href="#add-capital" role="tab"
                    aria-controls="add-capital" aria-selected="false">Add Capital</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="withdraw-capital-tab" data-bs-toggle="tab" href="#withdraw-capital" role="tab"
                    aria-controls="withdraw-capital" aria-selected="false">Withdraw Capital</a>
            </li>
        </ul>


        <!-- Tab Content -->
        <div class="tab-content mt-3" id="capitalTabContent">
            <!-- Add Capital Tab -->
            <div class="tab-pane fade show" id="add-capital" role="tabpanel" aria-labelledby="add-capital-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Capital List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-capital-modal">
                                        Add Capital
                                    </button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light w-100">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account Name</th>
                                        <th>Capital Amount</th>
                                        <th>Remarks</th>
                                        <th>Current Capital</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($capitals)
                                        @foreach ($capitals as $capital)
                                            <tr>
                                                <td>{{ $capital->id }}</td>
                                                <td>{{ $capital->account->account_name }}</td>
                                                <td>{{ $capital->capital_amount }}</td>
                                                <td>{{ $capital->remarks }}</td>
                                                <td>{{ $capital->capital->first()->current_capital }}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdraw Capital Tab -->
            <div class="tab-pane fade" id="withdraw-capital" role="tabpanel" aria-labelledby="withdraw-capital-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Withdraw Capital List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#withdraw-capital-modal">
                                        Withdraw Capital
                                    </button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account Name</th>
                                        <th>Withdrawal Amount</th>
                                        <th>Current Capital</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($capitalWithdrawals)
                                        @foreach ($capitalWithdrawals as $capitalWithdrawal)
                                            <tr>
                                                <td>{{ $capitalWithdrawal->id }}</td>
                                                <td>{{ $capitalWithdrawal->account->account_name }}</td>
                                                <td>{{ $capitalWithdrawal->withdrawal_amount }}</td>
                                                <td>
                                                    @if ($capitalWithdrawal->capital->isNotEmpty())
                                                        {{ $capitalWithdrawal->capital->first()->current_capital }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $capitalWithdrawal->remarks }}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Capital Tab -->
            <div class="tab-pane fade show active" id="total-capital" role="tabpanel" aria-labelledby="total-capital-tab">
                <div class="card w-25">
                    <div class="card-body">
                        <h3 class="card-title">Total Capital</h3>
                        <p class="card-text">
                            {{-- Total Capital in the system RS: <strong>{{ $totalCapital->current_capital ? 'null' }}</strong> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Capital Modal -->
        <div id="add-capital-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="add-capital-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="add-capital-modalLabel">Add Capital</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/Capital/store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="account_id" class="form-label">Account Name</label>
                                <select name="account_id" id="account_id" class="form-control">
                                    <option value="">Select Account</option>
                                    @isset($accounts)
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="capital_amount" class="form-label">Capital Amount</label>
                                <input type="number" name="capital_amount" class="form-control"
                                    placeholder="Enter Amount">
                            </div>
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <input type="text" name="remarks" class="form-control" placeholder="Enter Remarks">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdraw Capital Modal -->
        <div id="withdraw-capital-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="withdraw-capital-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="withdraw-capital-modalLabel">Withdraw Capital</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/Capital/withdraw') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="withdraw_account_id" class="form-label">Account From</label>
                                <select name="account_id" id="withdraw_account_id" class="form-control">
                                    <option value="">Select Account</option>
                                    @isset($accounts)
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="receving_account_id" class="form-label">Account To</label>
                                <select name="receiving_account_id" id="receiving_account_id" class="form-control">
                                    <option value="">Select Account</option>
                                    @isset($accounts)
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="withdraw_amount" class="form-label">Withdraw Amount</label>
                                <input type="number" name="withdraw_amount" class="form-control"
                                    placeholder="Enter Amount">
                            </div>
                            <div class="mb-3">
                                <label for="withdraw_remarks" class="form-label">Remarks</label>
                                <input type="text" name="remarks" class="form-control" placeholder="Enter Remarks">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script>
        $("#scroll-horizontal-datatable").DataTable({
            scrollX: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });

        $("#scroll-horizontal-datatable-withdraw").DataTable({
            scrollX: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    </script>
@endsection




































{{-- <style>
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
            background-color: #ffe4e1; /* Light coral */
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
    <div class="container">
        <!-- PDF Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary" onclick="convertToPDF()">Convert to PDF</button>
        </div>

        <h2>Balance Sheet</h2>
        <div class="row g-4">
            <!-- Assets Table -->
            <div class="col-md-6">
                <table class="table table-bordered" >
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
                            <td class="text-right">{{$inventory}}</td>
                        </tr>
                        <tr>
                            <td>Cash and cash equivalents</td>
                            <td class="text-right">{{$cashAndEquivalents}}</td>
                        </tr>
                        <tr>
                            <td>Accounts receivable</td>
                            <td class="text-right">{{$accountsReceivable}}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total current assets</td>
                            <td class="text-right">{{$totalCurrentAssets}}</td>
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
                        <tr>
                            <td>Fixed Assets</td>
                            <td class="text-right">{{$fixAssets}}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total non-current assets</td>
                            <td class="text-right">{{$fixAssets}}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total assets</td>
                            <td class="text-right">{{$overAlltotalAssets}}</td>
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
                            <td class="text-right">{{$accountsPayable}}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total liabilities</td>
                            <td class="text-right">{{$accountsPayable}}</td>
                        </tr>
                        <tr class="highlighted">
                            <td colspan="2">Shareholders' equity</td>
                        </tr>
                        <tr>
                            <td>Capital stock</td>
                            <td class="text-right">{{$capitalInvested}}</td>
                        </tr>
                        <tr class="section-title">
                            <td>Total liabilities & stockholders' equity</td>
                            <td class="text-right">{{$totalLiabilitiesAndEquity}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('pdfButton').addEventListener('click', async () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const content = document.querySelector('.container');

            // Generate PDF
            await doc.html(content, {
                callback: function (doc) {
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
</body> --}}
