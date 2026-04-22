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
                    <h4 class="page-title">Assets Management</h4>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <!-- Tabs for Add/Withdraw Capital -->
        <ul class="nav nav-tabs" id="capitalTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="add-capital-tab" data-bs-toggle="tab" href="#add-capital" role="tab"
                    aria-controls="add-capital" aria-selected="true">Deposite Assets</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="withdraw-capital-tab" data-bs-toggle="tab" href="#withdraw-capital" role="tab"
                    aria-controls="withdraw-capital" aria-selected="false">Withdraw Assets</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="capitalTabContent">
            <!-- Add Capital Tab -->
            <div class="tab-pane fade show active" id="add-capital" role="tabpanel" aria-labelledby="add-capital-tab">
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
                                        Deposite Assets
                                    </button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Assets Name</th>
                                        <th>Deposite Value</th>
                                        <th>Remarks</th>
                                        <th>Current Assets</th>
                                    </tr>
                                </thead>
                                <tbody>

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
                                        Withdraw Assets
                                    </button>
                                </div>
                            </div><!-- end col-->
                        </div>


                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Assets Name</th>
                                        <th>Withdrawal Value</th>
                                        <th>Current Assets</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>


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
                        <h4 class="modal-title" id="add-capital-modalLabel">Deposite Assets</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/Assets/store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" id="deposite_date"
                                    placeholder="Enter Amount">
                            </div>
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
                                <label for="capital_amount" class="form-label">Assets Name</label>
                                <input type="text" name="assets_name" class="form-control"
                                    placeholder="Enter Amount">
                            </div>
                            <div class="mb-3">
                                <label for="capital_amount" class="form-label">Assets Value</label>
                                <input type="number" name="assets_value" class="form-control"
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

    <script>
        function setdate() {
            // Set the current date in YYYY-MM-DD format
            document.getElementById('deposite_date').value = new Date().toISOString().split('T')[0];
        }

        // Ensure setdate is called when the page loads
        window.onload = function() {
            setdate();
        };
    </script>
@endsection
