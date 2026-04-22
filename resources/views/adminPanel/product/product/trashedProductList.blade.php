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
                    {{-- <h4 class="page-title">Product Variants</h4> --}}
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
                                <h4 class="page-title">Trashed Product List</h4>
                            </div>
                            <div class="col-sm-5 text-sm-end">
                                <div class="filter">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select Columns To Display
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><input type="checkbox" class="column-checkbox" data-column="0" checked> Sr#
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="1" checked> Code
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="2" checked>
                                                Image</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="3" checked> Name
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="4"> پروڈکٹ کا
                                                نام</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="5" checked>
                                                Measuring Unit</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="6" checked> SKU
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="7"> Cost Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="8"> Retail Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="9"> Opening
                                                Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="10"> Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="11"> Low Stock
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="12"> Best
                                                Selling</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="13"> Service
                                                Item</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="14"> Finish
                                                Goods</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="15"> Ingredients
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="16"> Manage Deal
                                                Item</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="17" checked>
                                                Action</li>
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
                                        <th data-column="sr">Sr#</th>
                                        <th data-column="variant_code">Code</th>
                                        <th data-column="image">Image</th>
                                        <th data-column="product_variant_name">Name</th>
                                        <th data-column="product_variant_urdu_name">پروڈکٹ کا نام</th>
                                        <th data-column="measuring_unit">Measuring Unit</th>
                                        <th data-column="sku">SKU</th>
                                        <th data-column="cost_price">Cost Price</th>
                                        <th data-column="retail_price">Retail Price</th>
                                        <th data-column="opening_stock">Opening Stock</th>
                                        <th data-column="stock">Stock</th>
                                        <th data-column="low_stock">Low Stock</th>
                                        <th data-column="best_selling">Best Selling</th>
                                        <th data-column="service_item">Service Item</th>
                                        <th data-column="finish_goods">Finish Goods</th>
                                        <th data-column="raw_material">Ingredients</th>
                                        <th data-column="manage_deal_items">Manage Deal Item</th>
                                        <th data-column="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($productVariants)
                                        @foreach ($productVariants as $productVariant)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $productVariant->code ?? '--' }}</td>
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
                                                <td>{{ $productVariant->product_variant_name ?? '--' }}</td>
                                                <td>{{ $productVariant->product_variant_urdu_name ?? '--' }}</td>
                                                <td>{{ $productVariant->measuringUnit->name ?? '--' }}</td>
                                                <td>{{ $productVariant->SKU ?? '--' }}</td>
                                                <td>{{ $productVariant->rates->cost_price ?? '--' }}</td>
                                                <td>{{ $productVariant->rates->retail_price ?? '--' }}</td>
                                                <td>{{ $productVariant->stock->opening_stock ?? '--' }}</td>
                                                <td>{{ $productVariant->stock->stock ?? '--' }}</td>
                                                <td>{{ $productVariant->stock->low_stock ?? '--' }}</td>
                                                <td>
                                                    <span class="toggle-status" data-id="{{ $productVariant->product->id }}"
                                                        data-field="best_selling_product">
                                                        @if ($productVariant->product->best_selling_product === 1)
                                                            <span class="status-1"
                                                                style="color: green; cursor: pointer;">✔</span>
                                                        @elseif($productVariant->product->best_selling_product === 0)
                                                            <span class="status-0"
                                                                style="color: red; cursor: pointer;">✘</span>
                                                        @else
                                                            <span>--</span>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($productVariant->service_item === 1)
                                                        <span style="color: green;">✔</span>
                                                    @elseif($productVariant->service_item === 0)
                                                        <span style="color: red;">✘</span>
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($productVariant->finish_goods === 1)
                                                        <span style="color: green;">✔</span>
                                                    @elseif($productVariant->finish_goods === 0)
                                                        <span style="color: red;">✘</span>
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($productVariant->raw_material === 1)
                                                        <span style="color: green;">✔</span>
                                                    @elseif($productVariant->raw_material === 0)
                                                        <span style="color: red;">✘</span>
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($productVariant->manage_deal_items === 1)
                                                        <span style="color: green;">✔</span>
                                                    @elseif($productVariant->manage_deal_items === 0)
                                                        <span style="color: red;">✘</span>
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-success pro-variant-restore-btn"
                                                        data-id="{{ $productVariant->id }}"><i
                                                            class="mdi mdi-restore"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $productVariants->links() !!}
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
        $(document).ready(function() {
            $('body').on('click', '.pro-variant-restore-btn', function() {
                var productVariantId = $(this).data('id');
                var row = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to restore this product variant!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product-variant.restore', ':id') }}".replace(
                                ':id', productVariantId),
                            type: 'post',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire(
                                        'Restored!',
                                        response.message,
                                        'success'
                                    )
                                    .then(() => {
                                        row.remove();
                                    });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message ||
                                    'An error occurred while restoring the product variant.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>

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
    </script>

    <script>
        $(document).ready(function() {
            let savedColumns = JSON.parse(localStorage.getItem('trashedProductColumnSettings')) || [0, 1, 2, 3, 5,
                6, 17
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

                localStorage.setItem('trashedProductColumnSettings', JSON.stringify(savedColumns));
            });
        });
    </script>
@endsection
<!-- container -->
