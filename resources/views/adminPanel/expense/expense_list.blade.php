<?php

use App\Helpers\Helper;
?>
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
                @if (session('success'))
                    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content modal-filled bg-success">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="dripicons-checkmark h1"></i>
                                        <h4 class="mt-2">Well Done!</h4>
                                        <p class="mt-3">{{ session('success') }}</p>
                                        <button type="button" class="btn btn-light my-2"
                                            data-bs-dismiss="modal">Continue</button>
                                    </div>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                @endif

                @if (session('error'))
                    <div id="error-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content modal-filled bg-danger">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="dripicons-wrong h1"></i>
                                        <h4 class="mt-2">Oh snap!</h4>
                                        <p class="mt-3">{{ session('error') }}</p>
                                        <button type="button" class="btn btn-light my-2"
                                            data-bs-dismiss="modal">Continue</button>
                                    </div>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                @endif
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Expense</h4>
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
                                <h4 class="page-title">Expense List</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal"><i class="mdi mdi-plus-circle me-2"></i>Add
                                        New</button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Paid From</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($expense_data)
                                        @foreach ($expense_data as $exp_res)
                                            <tr>
                                                <td>
                                                    {{ $exp_res->id }}
                                                </td>
                                                <td>
                                                    {{ date('m-d-Y', strtotime($exp_res->date)) }}
                                                </td>
                                                <td>
                                                    {{ $exp_res->exp_name }}
                                                </td>
                                                <td>
                                                    {{ $exp_res->total_amount }}
                                                </td>

                                                <td>
                                                    {{ $exp_res->expenseAccount->account_name }}
                                                </td>
                                                <td>
                                                    {{ $exp_res->expenseCategory->exp_category_name }}
                                                </td>

                                                <td>
                                                    {{ $exp_res->expenseSubCategory->exp_sub_category }}
                                                </td>

                                                <td class="table-action">

                                                    <a href="{{ URL::to('expense_print/' . $exp_res->id . '') }}" target="blank"
                                                        class="action-icon text-success"> <i class="dripicons-print"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset

                                </tbody>
                            </table>
                            {!! $expense_data->links() !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>

    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Expense</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ URL::to('expense-sub') }}" id="expense_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="exp_name" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="Enter  Name">
                                    @error('exp_name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Total Amount</label>
                                    <input type="number" class="form-control" name="total_amount"
                                        id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter  Amount">
                                    @error('total_amount')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="Enter  Name">
                                    @error('date')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Payment From</label>
                                    <select class="form-select" name="account_id" id="example-select">
                                        @isset($CashAccounts_data)
                                            @foreach ($CashAccounts_data as $account_res)
                                                <option value="{{ $account_res->id }}">{{ $account_res->account_name }} /
                                                    {{ $account_res->account_number }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('account_id')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Category</label>
                                    <select class="form-select" name="category_id" onchange="fetchSubCategory()"
                                        id="category_id">
                                        @foreach ($allCategories as $cat_res)
                                            <option value="{{ $cat_res->id }}">{{ $cat_res->exp_category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('date')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="example-input-normal" class="form-label">Sub Category</label>
                                    <select class="form-select" name="sub_category_id" id="sub_category_id">

                                    </select>
                                    @error('sub_category_id')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="disabledSubmitButton(this)" class="btn btn-success">Save
                            changes</button>
                    </div>

                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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

        var submit_form = true;

        function disabledSubmitButton(form) {
            console.log(form);
            console.log('Form is submit now ');
            if (submit_form) {
                submit_form = false;
                $('#expense_form').submit();
            }

        }

        fetchSubCategory = () => {
            $.ajax({
                url: "{{ URL::to('fetch_sub_category') }}",
                type: 'POST',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'category_id': $('#category_id').val()
                },
                success: function(data) {
                    console.log(data);
                    let subCategoryHtml = ``;
                    data.forEach((marala) => {
                        subCategoryHtml +=
                            `<option value="${marala['id']}">${marala['exp_sub_category']}</option>`

                    });

                    $('#sub_category_id').html(subCategoryHtml);
                }
            });
        }

        fetchSubCategory();

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
    </script>
@endsection
<!-- container -->
