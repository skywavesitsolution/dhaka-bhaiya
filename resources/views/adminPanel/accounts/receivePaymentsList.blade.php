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

                    <h4 class="page-title">Received Payments</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Received Payments List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ URL::to('add-make-payment') }}" class="btn btn-warning">Transaction</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Account</th>
                                        <th>Total Payments Received</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($receivedPayments)
                                        @foreach ($receivedPayments as $receivedPayment)
                                            <tr>
                                                <td>
                                                    {{ $receivedPayment->id }}
                                                </td>

                                                <td>
                                                    {{ $receivedPayment->date }}
                                                </td>
                                                <td>
                                                    {{ $receivedPayment->account->account_name }}
                                                </td>
                                                <td>
                                                    {{ $receivedPayment->total_payments }}
                                                </td>

                                                <td class="table-action">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href='{{ URL::to("view-receive-payment-details/{$receivedPayment->id}") }}'
                                                            class="btn btn-info btn-sm action-icon text-white"> <i
                                                                class="mdi mdi-eye"></i></a>
                                                        <button onclick="openUpdatePaymentModel({{ $receivedPayment->id }})"
                                                            class="btn btn-success btn-sm"><i
                                                                class="mdi mdi-clipboard-edit-outline"></i></button>
                                                        <a href='{{ URL::to("print-payment-received-voucher/{$receivedPayment->id}") }}'
                                                            class="btn btn-info btn-sm action-icon text-white"> <i
                                                                class="dripicons-print"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>

                            {{ $receivedPayments->links() }}

                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <div id="payment-modal" class="modal fade" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Update Received Payment</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/update-received-payment') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label"> Date</label>
                                        <input type="text" id="payment-id" hidden name="payment_id" class="form-control"
                                            placeholder="">
                                        <input type="date" id="payment-date" name="date" class="form-control"
                                            placeholder="">
                                        @error('date')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Account</label>
                                        <select name="accountId" required class="form-control select2" id="to-account-id">
                                            <option value="">Chose One</option>
                                            @isset($accounts)
                                                @foreach ($accounts as $account_id)
                                                    <option value="{{ $account_id->id }}">{{ $account_id->account_name }}
                                                    </option>
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
                                        <label for="example-input-normal" class="form-label">Total Payment</label>
                                        <input type="text" name="" id="total-payments" readonly
                                            class="form-control" placeholder="Total Payments">

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

        $(document).ready(function() {
            $(".select2").select2({
                dropdownParent: "#payment-modal"
            });
        })

        function openUpdatePaymentModel(id) {
            console.log('payment id ' + id);
            $('#payment-modal').modal('show');
            $('#to-account-id').val('').change();

            $.ajax({
                url: "{{ URL::to('fetch-received-payment') }}/" + id + "",
                type: 'GET',
                data: {},
                success: function(paymentData) {
                    var payment = paymentData['data']['received_payment'];
                    console.log(payment['account_id']);
                    $('#to-account-id').val(payment['account_id']).change();
                    $('#total-payments').val(payment['total_payments']);
                    $('#payment-date').val(paymentData['data']['payment_date']);
                    $('#payment-id').val(payment['id']);

                }
            });
        }
        console.log('page is load now');
    </script>
@endsection
<!-- container -->
