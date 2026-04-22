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
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Order Reports</h4>
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
                                <h4 class="page-title">Order Reports</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <!-- <a href="{{ URL::to('payments-add') }}" class="btn btn-success" ><i class="mdi mdi-plus-circle me-2"></i>Add Payment</a> -->
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                    <li class="nav-item">
                                        <a href="#cash_ledeger" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0 active">
                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Order List</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#date_wise_cash_ledeger" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Party Wise Order</span>
                                        </a>
                                    </li>


                                    <li class="nav-item">
                                        <a href="#supplier_customer_list" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Supplier Wise Order</span>
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane show active" id="cash_ledeger">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Orders List</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('print-order-list') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">

                                                        <div class="col-md-3">
                                                            <label for="exampleInputEmail1" class="form-label">Start
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="start_date"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="example-input-normal" class="form-label">End
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="end_date" id="">

                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="exampleInputEmail1" class="form-label">Report
                                                                Type</label>
                                                            <select name="reportType" class="form-control" id="">
                                                                <option value="All">All</option>
                                                                <option value="dateWise">DateWise</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" style="margin-top:1.8rem;"
                                                                class="btn btn-success">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="date_wise_cash_ledeger">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Party Wise Order</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('party-wise-order') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-sm-3">
                                                            <div class="mb-3">
                                                                <label for="example-input-normal" class="form-label">Select
                                                                    Particular</label>
                                                                <select name="particular" class="form-control"
                                                                    onchange="fetchParticulars('particular','particularId')"
                                                                    id="particular">
                                                                    <option value="">Chose One</option>
                                                                    <option value="Party" party-type="Marka">Marka
                                                                    </option>
                                                                    <option value="Party" party-type="Driver">Driver
                                                                    </option>
                                                                    <option value="Party" party-type="Customer">Customer
                                                                    </option>
                                                                </select>
                                                                <input type="text" hidden name="particularType"
                                                                    id="particularType">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3" id="supplier-div-payment"
                                                            style="display: none;">
                                                            <div class="mb-3">
                                                                <label for="example-input-normal"
                                                                    class="form-label">Select Supplier</label>
                                                                <select name=""
                                                                    onchange="fetchSupplierCustomers('supplier_id','particularId')"
                                                                    class="form-control select2" id="supplier_id">
                                                                    <option value="-1">Select One</option>
                                                                    @isset($suppliers)
                                                                        @foreach ($suppliers as $supplier)
                                                                            <option value="{{ $supplier->id }}">
                                                                                {{ $supplier->name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-3">
                                                                <label for="example-input-normal"
                                                                    class="form-label">Select Account</label>
                                                                <select name="partyId"
                                                                    onchange="fetchParticularBalnce('particular','particularId','particular-balance')"
                                                                    class="form-control" id="particularId">
                                                                    <option value="-1">Select One</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="exampleInputEmail1" class="form-label">Start
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="start_date"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="example-input-normal" class="form-label">End
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="end_date"
                                                                id="">

                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="exampleInputEmail1" class="form-label">Report
                                                                Type</label>
                                                            <select name="reportType" class="form-control"
                                                                id="">
                                                                <option value="All">All</option>
                                                                <option value="dateWise">DateWise</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" style="margin-top:1.8rem;"
                                                                class="btn btn-success">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="supplier_customer_list">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Supplier Wise Order</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('supplier-wise-order') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">


                                                        <div class="col-sm-4">
                                                            <div class="mb-3">
                                                                <label for="example-input-normal"
                                                                    class="form-label">Select Supplier</label>
                                                                <select name="supplier_id"
                                                                    onchange="fetchSupplierCustomers('supplier_id','particularId')"
                                                                    class="form-control select2" id="supplier_id">
                                                                    <option value="-1">Select One</option>
                                                                    @isset($suppliers)
                                                                        @foreach ($suppliers as $supplier)
                                                                            <option value="{{ $supplier->id }}">
                                                                                {{ $supplier->name }}</option>
                                                                        @endforeach
                                                                    @endisset
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="exampleInputEmail1" class="form-label">Start
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="start_date"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="example-input-normal" class="form-label">End
                                                                Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="end_date"
                                                                id="">

                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="exampleInputEmail1" class="form-label">Report
                                                                Type</label>
                                                            <select name="reportType" class="form-control"
                                                                id="">
                                                                <option value="All">All</option>
                                                                <option value="dateWise">DateWise</option>
                                                            </select>
                                                        </div>



                                                        <div class="col-md-2">
                                                            <button type="submit" style="margin-top:1.8rem;"
                                                                class="btn btn-success">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>


@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script>
        @if (session('success'))
            $(document).ready(function() {
                $("#success-alert-modal").modal('show');
            })
        @endif

        @if (session('error'))
            $(document).ready(function() {
                $("#error-alert-modal").modal('show');
            })
        @endif

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

        var parties = [];
        var counter = 1;

        function getAllAccount() {
            $.ajax({
                url: "{{ URL::to('get-all-types-account') }}",
                type: 'GET',
                data: {},
                success: function(account) {
                    parties = account['data']['parties'];
                }
            });
        }

        getAllAccount();

        function fetchParticulars(particularType, typeId) {
            var type = $('#' + particularType + '').val();
            var partyType = $('#' + particularType + '').find('option:selected').attr('party-type');
            $('#particularType').val(partyType);
            $('#' + typeId + '').html('<option value="-1">Select One</option>')
            var particularsList = `<option value="-1">Select One</option>`;
            if (type == 'Party') {
                parties.forEach((party) => {
                    if (partyType == 'Marka' && party['type'] == 'Marka') {
                        particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                    }

                    if (partyType == 'Driver' && party['type'] == 'Driver') {
                        particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                    }

                    if (partyType == 'Customer' && party['type'] == 'Customer') {
                        console.log('particular' + particularType);
                        if (particularType == 'particular') {
                            console.log('Enter in particular');
                            $('#supplier-div-payment').css('display', 'block');
                        } else {
                            $('#supplier-div-datewise').css('display', 'block');
                        }
                    }
                })

                if (partyType !== 'Customer') {
                    $('#supplier-div-payment').css('display', 'none');
                    $('#supplier-div-datewise').css('display', 'none');
                }
            }

            $('#' + typeId + '').html(particularsList);
            $('#' + typeId + '').select2();

        }

        function fetchSupplierCustomers(supplierId, displayId) {
            $('#' + displayId + '').html('<option value="-1">Select One</option>');
            var supplierIdGet = $('#' + supplierId + '').val();
            var particularsList = `<option value="-1">Select One</option>`;
            parties.forEach((party) => {
                if (party['type'] == 'Customer' && party['supplier_id'] == supplierIdGet) {
                    particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                }
            })

            $('#' + displayId + '').html(particularsList);
            $('#' + displayId + '').select2();
        }
    </script>
@endsection
<!-- container -->
