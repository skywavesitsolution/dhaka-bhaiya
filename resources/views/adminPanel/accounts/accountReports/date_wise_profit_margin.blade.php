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

                @if (session('success'))
                    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content modal-filled bg-success">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="dripicons-checkmark h1"></i>
                                        <h4 class="mt-2">Success!</h4>
                                        <p class="mt-3">{{ session('success') }}</p>
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
                    <h4 class="page-title">Profit Margin</h4>
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
                                <h4 class="page-title">Date wise Profit Margin</h4>
                            </div>
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>

                        <!-- Card containing the form -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Profit Margin Report</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ URL::to('date_wise_profit_margin') }}" target="blank" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                                name="start_date">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">End Date</label>
                                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                                name="end_date">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" style="margin-top:1.8rem;"
                                                class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
            scrollX: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        })
    </script>

    <script>
        $(document).ready(function() {
            $('select[name="category_id"]').on('change', function() {
                const selectedCategory = $(this).val();
                $.ajax({
                    url: '{{ url('/get-category-product') }}',
                    type: 'GET',
                    data: {
                        category_id: selectedCategory
                    },
                    success: function(data) {
                        const productSelect = $('select[name="product_id"]');
                        productSelect.empty();
                        productSelect.append(
                            '<option value="all_products" selected>All Product</option>');
                        $.each(data.products, function(index, product) {
                            productSelect.append('<option value="' + product.id + '">' +
                                product.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching products:', error);
                    }
                });
            });
        });
    </script>
@endsection
