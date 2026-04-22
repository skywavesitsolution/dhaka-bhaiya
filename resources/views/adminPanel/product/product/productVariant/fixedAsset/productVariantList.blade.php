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
                    <h4 class="page-title">Product Variants</h4>
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
                                <h4 class="page-title">Product Variant List</h4>

                            </div>
                            <div class="col-sm-5 text-sm-end">
                                <div class="filter">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select Columns To Display
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><input type="checkbox" class="column-checkbox" data-column="0" checked> ID
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="1" checked>
                                                Product Variant Code</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="2" checked>
                                                Image</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="3" checked>
                                                Product Variant Name</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="4" checked>
                                                پروڈکٹ ورینٹ کا نام</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="5" checked> Size
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="6" checked>
                                                Color</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="8" checked>
                                                Measuring Unit</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="9" checked> SKU
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="10"> Min Order
                                                Qty</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="11"> Cost Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="12"> Whole Sale
                                                Price</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="13"> Retail
                                                Price</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="14"> Opening
                                                Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="15"> Low Stock
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="16"> Inner Packs
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="17"> Loose Packs
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- end col-->
                            <div class="col-sm-2 text-sm-end">
                                <a href="{{ route('product-variant.create') }}" class="btn btn-success"><i
                                        class="mdi mdi-plus-circle me-2"></i>Add Product Variant</a>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Variant Code</th>
                                        <th>Image</th>
                                        <th>Product Variant Name</th>
                                        <th>پروڈکٹ ورینٹ کا نام</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Measuring Unit</th>
                                        <th>SKU</th>
                                        <th>Min Order Qty</th>
                                        <th>Cost Price</th>
                                        <th>Whole Sale Price</th>
                                        <th>Retail Price</th>
                                        <th>Opening Stock</th>
                                        <th>Low Stock</th>
                                        <th>Inner Packs</th>
                                        <th>Loose Packs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($productVariants)
                                        @foreach ($productVariants as $productVariant)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->code ?? '--' }}
                                                </td>
                                                <td>
                                                    @if ($productVariant->hasMedia('pro_var_images'))
                                                        @php
                                                            $media = $productVariant->getFirstMedia('pro_var_images');
                                                            // $imageUrl = str_replace(
                                                            //     'storage/',
                                                            //     'public/storage/',
                                                            //     $media->getFullUrl(),
                                                            // );
                                                            $imageUrl = $media->getFullUrl();
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="Product Var Image" width="60"
                                                            height="60" style="object-fit: cover; border-radius:6px;">
                                                    @else
                                                        <p>No Image Available</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $productVariant->product_variant_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->product_variant_urdu_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->size->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->color->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->measuringUnit->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->SKU ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->min_order_qty ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->rates->cost_price ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->rates->wholesale_price ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->rates->retail_price ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->stock->opening_stock ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->stock->low_stock ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->stock->inner_pack ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productVariant->stock->loose_pack ?? '--' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $productVariants !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <script>
        $(document).ready(function() {
            let savedColumns = JSON.parse(localStorage.getItem('columnSettings')) || [0, 1, 2, 3, 4, 5, 6, 8, 9,
                18
            ];

            $('#scroll-horizontal-datatable tbody tr, #scroll-horizontal-datatable thead tr').each(function() {
                $(this).children('th, td').each(function(index) {
                    if (!savedColumns.includes(index)) {
                        $(this).hide();
                        $('.column-checkbox[data-column="' + index + '"]').prop('checked', false);
                    } else {
                        $('.column-checkbox[data-column="' + index + '"]').prop('checked', true);
                    }
                });
            });

            $('.column-checkbox').on('change', function() {
                var columnIndex = $(this).data('column');
                var isChecked = $(this).prop('checked');

                $('#scroll-horizontal-datatable tbody tr, #scroll-horizontal-datatable thead tr').each(
                    function() {
                        $(this).children('th, td').eq(columnIndex).toggle(isChecked);
                    });

                if (isChecked) {
                    savedColumns.push(columnIndex);
                } else {
                    savedColumns = savedColumns.filter(function(item) {
                        return item !== columnIndex;
                    });
                }

                localStorage.setItem('columnSettings', JSON.stringify(savedColumns));
            });
        });
    </script>
@endsection
<!-- container -->
