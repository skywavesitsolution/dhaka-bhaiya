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
                    <h4 class="page-title">Fixed Asset Products</h4>



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
                                <h4 class="page-title">Asset Product List</h4>

                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('product.create') }}" class="btn btn-success"><i
                                            class="mdi mdi-plus-circle me-2"></i>Add New Product</a>
                                    <a href="{{ route('product-variant.create') }}" class="btn btn-warning"><i
                                            class="mdi mdi-plus-circle me-2"></i>Add Product Variant</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Product Code</th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>پروڈکٹ کا نام</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Supplier</th>
                                        <th>Manage Variant</th>
                                        <th>Fixed Asset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($products)
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $product->code ?? '--' }}
                                                </td>
                                                <td>
                                                    @if ($product->hasMedia('pro_thumbnail_images'))
                                                        @php
                                                            $media = $product->getFirstMedia('pro_thumbnail_images');
                                                            // $imageUrl = str_replace(
                                                            //     'storage/',
                                                            //     'public/storage/',
                                                            //     $media->getFullUrl(),
                                                            // );
                                                            $imageUrl = $media->getFullUrl();
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="Product Image" width="60"
                                                            height="60" style="object-fit: cover; border-radius:6px;">
                                                    @else
                                                        <p>No Image Available</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $product->product_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $product->product_urdu_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $product->category->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $product->brand->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $product->supplier->name ?? '--' }}
                                                </td>
                                                <td>
                                                    @if ($product->is_manage_variants === 1)
                                                        <span style="color: green;">✔</span>
                                                    @elseif($product->is_manage_variants === 0)
                                                        <span style="color: red;">✘</span>
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>

                                                <!-- Best Selling Product -->
                                                <td>
                                                    <span class="toggle-status">
                                                        @if ($product->is_fixed_asset === 1)
                                                            <span class="status-1" style="color: green;">✔</span>
                                                        @elseif($product->is_fixed_asset === 0)
                                                            <span class="status-0" style="color: red;">✘</span>
                                                        @else
                                                            <span>--</span>
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $products !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>
@endsection

@section('scripts')
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
