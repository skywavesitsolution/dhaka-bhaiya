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

                    <h4 class="page-title">Accounts</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a href="#home" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                    <i class="mdi mdi-home-variant d-md-none d-block"></i>
                    <span class="d-none d-md-block">Add Account</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#profile" data-bs-toggle="tab" aria-expanded="true" class="nav-link ">
                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                    <span class="d-none d-md-block">Cash Deposit</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-10">
                                        Accounts List
                                    </div>
                                    <div class="text-sm-end">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#standard-modal">Add Accounts</button>
                                    </div>
                                    <!-- end col-->
                                </div>
                                <div class="table-responsive">
                                    <table id="scroll-horizontal-datatable"
                                        class="table table-sm table-centered w-100 nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Account Name</th>
                                                <th>Account Number</th>
                                                <th>Opening Balance</th>
                                                <th>Balance</th>
                                                <th style="width: 85px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($accounts)
                                                @foreach ($accounts as $account)
                                                    <tr>
                                                        <td>
                                                            {{ $account->id }}
                                                        </td>

                                                        <td>
                                                            {{ $account->account_name }}
                                                        </td>
                                                        <td>
                                                            {{ $account->account_number }}
                                                        </td>
                                                        <td>
                                                            {{ $account->opening_balance }}
                                                        </td>
                                                        <td>
                                                            {{ $account->balance }}
                                                        </td>

                                                        <td class="table-action">
                                                            <a href="javascript:void(0)" class="action-icon text-success"
                                                                data-id="{{ $account->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#edit-modal"> <i
                                                                    class="mdi mdi-square-edit-outline"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>

                                </div>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>
            <div class="tab-pane show " id="profile">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-10">
                                        Cash Deposit
                                    </div>
                                    <div class="text-sm-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#cash-deposit">Cash Deposit</button>
                                    </div>
                                    <!-- end col-->
                                </div>
                                <div class="table-responsive">
                                    <table id="scroll-horizontal-datatable2" class="table table-centered w-100 nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Account Name</th>
                                                <th>Account Number</th>
                                                <th>Deposited By</th>
                                                <th>Deposited Amount</th>
                                                {{-- <th style="width: 85px;">Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($cashdeposits)
                                                @foreach ($cashdeposits as $cashdeposit)
                                                    <tr>
                                                        <td>
                                                            {{ $cashdeposit->id }}
                                                        </td>

                                                        <td>
                                                            {{ $cashdeposit->accounts->account_name }}
                                                        </td>
                                                        <td>
                                                            {{ $cashdeposit->accounts->account_number }}
                                                        </td>
                                                        <td>
                                                            {{ $cashdeposit->deposit_by }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($cashdeposit->deposit_amount) }}
                                                        </td>
                                                        {{-- <td class="table-action">
                                                        <a href="{{ route('cashdeposit_print', $cashdeposit->id) }}" target="_blank" class="action-icon text-success"> <i class="dripicons-print"></i></a>
                                            </td> --}}
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>

                                </div>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>
        </div>

        {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-10">
                            Accounts List
                        </div>
                        <div class="col-sm-1">

                            <div class="text-sm-end">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#standard-modal">Add Accounts</button>
                            </div>
                        </div>
                        <div class="col-sm-1">

                            <div class="text-sm-end">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#cash-deposit">Cash Deposit</button>
                            </div>
                        </div>
                        <!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-sm table-centered w-100 nowrap">
                            <thead class="table-light">
                                <tr>


                                    <th>ID</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Opening Balance</th>
                                    <th>Balance</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($accounts)
                                @foreach ($accounts as $account)

                                <tr>
                                    <td>
                                        {{ $account->id }}
    </td>

    <td>
        {{ $account->account_name }}
    </td>
    <td>
        {{ $account->account_number }}
    </td>
    <td>
        {{ $account->opening_balance }}
    </td>
    <td>
        {{ $account->balance }}
    </td>

    <td class="table-action">
        <a href="#" class="action-icon text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
    </td>
    </tr>
    @endforeach
    @endisset
    </tbody>
    </table>

</div>
</div> <!-- end card-body-->
</div> <!-- end card-->
</div> <!-- end col -->
</div> --}}

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add Accounts</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-account') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Accounts Name</label>
                                        <input type="text" name="account_name" class="form-control"
                                            placeholder="Accounts Name">
                                        @error('account_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Account Number</label>
                                        <input type="text" name="accountNumber" class="form-control"
                                            placeholder="Account Number">
                                        @error('accountNumber')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="text" name="openingBalance" class="form-control" value="0"
                                            placeholder="Opening Balance">
                                        @error('openingBalance')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- edit modal -->
        <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modalLabel">Edit Accounts</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ route('account.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="accountId" id="account-id-field">
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Accounts Name</label>
                                        <input type="text" id="account_name" name="account_name"
                                            class="form-control">
                                        @error('account_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Account Number</label>
                                        <input type="text" id="accountNumber" name="accountNumber"
                                            class="form-control">
                                        @error('accountNumber')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="text" id="openingBalance" name="openingBalance" readonly
                                            class="form-control" value="0">
                                        @error('openingBalance')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- cash deposit modal -->
        <div id="cash-deposit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="cash-depositLabel">Cash Deposit</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-cash-deposit') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Accounts Name</label>
                                        <select name="accountId" id="" class="form-control">
                                            @isset($accounts)
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('accountId')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Deposit By</label>
                                        <input type="text" name="depositBy" class="form-control"
                                            placeholder="Deposited By">
                                        @error('depositBy')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Amount</label>
                                        <input type="text" name="depositAmount" class="form-control"
                                            placeholder="Amount">
                                        @error('depositAmount')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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
        })
        console.log('page is load now');
    </script>

    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            $.ajax({
                url: 'account/' + id,
                method: 'GET',
                success: function(data) {
                    $('#account-id-field').val((data.data.id));
                    $('#account_name').val(data.data.account_name);
                    $('#accountNumber').val(data.data.account_number);
                    $('#openingBalance').val(data.data.opening_balance);
                }
            });
        });
    </script>
@endsection
<!-- container -->
