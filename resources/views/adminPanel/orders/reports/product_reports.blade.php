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
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Product Reports</h4>
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
                                <h4 class="page-title">Product Reports</h4>
                            </div>
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                    <li class="nav-item">
                                        <a href="#product_sale_report" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0 active">
                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Product Sale Report</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#product_purchase_report" data-bs-toggle="tab" aria-expanded="false"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Product Purchase Report</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#product_stock_report" data-bs-toggle="tab" aria-expanded="false"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Product Stock Report</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#product_ledger_print" data-bs-toggle="tab" aria-expanded="false"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                            <span class="d-none d-md-block">Product Ledger</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane show active" id="product_sale_report">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Product Sale Report</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('product-sale-report') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Select Product</label>
                                                            <select name="product_id" class="form-control select2"
                                                                data-toggle="select2">
                                                                <option value="all_products" selected>All Product</option>
                                                                @isset($allProducts)
                                                                    @foreach ($allProducts as $allProduct)
                                                                        <option value="{{ $allProduct->id }}">
                                                                            {{ $allProduct->product_variant_name }}</option>
                                                                    @endforeach
                                                                @endisset
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Start Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="start_date">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">End Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="end_date">
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
                                    <div class="tab-pane" id="product_purchase_report">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Product Purchase Report</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('product-purchase-report') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Select Product</label>
                                                            <select name="product_id" class="form-control select2"
                                                                data-toggle="select2">
                                                                <option value="all_products" selected>All Product</option>
                                                                @isset($allProducts)
                                                                    @foreach ($allProducts as $allProduct)
                                                                        <option value="{{ $allProduct->id }}">
                                                                            {{ $allProduct->product_variant_name }}</option>
                                                                    @endforeach
                                                                @endisset
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Start Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="start_date">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">End Date</label>
                                                            <input type="date" class="form-control"
                                                                value="{{ date('Y-m-d') }}" name="end_date">
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
                                    <div class="tab-pane" id="product_stock_report">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Product Stock Report</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('product-stock-report') }}" target="_blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Category</label>
                                                            <select name="category_id" class="form-control select2"
                                                                data-toggle="select2">
                                                                <option selected disabled>Select Category</option>
                                                                <option value="all_category">All Category</option>
                                                                @isset($allCategories)
                                                                    @foreach ($allCategories as $allCategory)
                                                                        <option value="{{ $allCategory->id }}">
                                                                            {{ $allCategory->name }}</option>
                                                                    @endforeach
                                                                @endisset
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Select Product</label>
                                                            <select name="product_id" class="form-control select2"
                                                                data-toggle="select2">
                                                                <option value="all_products" selected>All Product</option>

                                                                @isset($allProducts)
                                                                    @foreach ($allProducts as $allProduct)
                                                                        <option value="{{ $allProduct->id }}">
                                                                            {{ $allProduct->product_variant_name }}</option>
                                                                    @endforeach
                                                                @endisset
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
                                    <div class="tab-pane" id="product_ledger_print">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Product Ledger Report</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <form action="{{ URL::to('product-ledger-report') }}" target="blank"
                                                    method="post">
                                                    @csrf
                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Select Product</label>
                                                            <select name="product_id" class="form-control select2"
                                                                data-toggle="select2">
                                                                <option value="all_products" selected>All Product</option>

                                                                @isset($allProducts)
                                                                    @foreach ($allProducts as $allProduct)
                                                                        <option value="{{ $allProduct->id }}">
                                                                            {{ $allProduct->product_variant_name }}</option>
                                                                    @endforeach
                                                                @endisset
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
<!-- container -->
