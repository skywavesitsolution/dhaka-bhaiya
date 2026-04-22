@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .dropdown-menu {
            min-width: 200px;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
        }

        .dropdown-menu .form-check {
            margin-bottom: 8px;
        }
    </style>
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
                    {{-- <h4 class="page-title">Products</h4> --}}
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <h4 class="page-title">Product List</h4>
                            </div>
                            <div class="col-sm-7 text-sm-end">
                                {{-- <div class="filter">
                                    <div class="dropdown">
                                        <button class="btn btn-warning dropdown-toggle" type="button"
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
                                                Category</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="6" checked>
                                                Measuring Unit</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="7"> SKU</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="8"> Cost Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="9"> Retail Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="10"> Opening
                                                Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="11"> Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="12"> Low Stock
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="13"> Best
                                                Selling</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="14"> Service
                                                Item</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="15"> Finish
                                                Goods</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="16"> Ingredients
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="17"> Manage Deal
                                                Item</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="18" checked>
                                                Action</li>
                                        </ul>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-sm-2 text-sm-end">
                                <a href="{{ route('product.create') }}" class="btn"
                                    style="background-color: black; color:white;"><i
                                        class="mdi mdi-plus-circle me-2"></i>Add Product</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead style="background-color: black; color:white;">
                                    <tr>
                                        <th data-column="sr">Sr#</th>
                                        <th data-column="variant_code">Code</th>
                                        <th data-column="image">Image</th>
                                        <th data-column="product_variant_name">Name</th>
                                        <th data-column="product_variant_urdu_name">پروڈکٹ کا نام</th>
                                        <th data-column="category">Category</th>
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
                                                <td>{{ $productVariant->product->category->name ?? '--' }}</td>
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
                                                            <span class="status-0" style="color: red; cursor: pointer;">✘</span>
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
                                                <td class="table-action"><a
                                                        href="{{ route('product.edit', ['id' => $productVariant->id]) }}"
                                                        class="action-icon text-warning"><i
                                                            class="mdi mdi-file-document-edit"></i></a>
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-danger pro-variant-del-btn"
                                                        data-id="{{ $productVariant->id }}"><i class="mdi mdi-delete"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {{-- {!! $productVariants->links() !!} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="update-product-variant-code-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="update-product-variant-code-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="update-product-variant-code-modalLabel">Update Product Variant Code
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/product-variant/update-product-variant-code') }}" method="post">
                        @csrf
                        <input type="hidden" id="product_variant_id1" name="product_variant_id">
                        <div class="modal-body">
                            <div class="mb-2 row">
                                <div class="col-6">
                                    <label for="product_variant_name1" class="form-label">Product Variant Name</label>
                                    <input type="text" id="product_variant_name1" readonly name="product_variant_name"
                                        class="form-control">
                                </div>

                                <div class="col-6">
                                    <label for="product_variant_code1" class="form-label">Old Product Variant Code</label>
                                    <input type="text" id="product_variant_code1" readonly name="product_variant_code"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-12">
                                    <label for="new_variant_code" class="form-label">New Variant Code<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="new_variant_code" name="new_variant_code"
                                        placeholder="New Product Variant Code" class="form-control" required>
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
        </div>
        <!-- end row -->

        <div id="update-product-variant-retailprice-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="update-product-variant-retailprice-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="update-product-variant-retailprice-modalLabel">Update Product Variant
                            Retail price</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/product-variant/update-product-variant-retail-price') }}" method="post">
                        @csrf
                        <input type="hidden" id="product_variant_id" name="product_variant_id">
                        <div class="modal-body">
                            <div class="mb-2 row">
                                <div class="col-6">
                                    <label for="product_variant_code" class="form-label">Product Variant Code</label>
                                    <input type="text" id="product_variant_code" readonly name="product_variant_code"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="product_variant_name" class="form-label">Product Variant Name</label>
                                    <input type="text" id="product_variant_name" readonly name="product_variant_name"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-6">
                                    <label for="old_retail_price" class="form-label">Old Retail Price</label>
                                    <input type="text" id="old_retail_price" readonly name="old_retail_price"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="new_retail_price" class="form-label">New Retail Price<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="new_retail_price" name="new_retail_price"
                                        class="form-control" required>
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
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // let savedColumns = JSON.parse(localStorage.getItem('columnSettings')) || [0, 1, 2, 3, 5, 6, 18];

            // $('#scroll-horizontal-datatable tbody tr, #scroll-horizontal-datatable thead tr').each(function() {
            //     $(this).children('th, td').each(function(index) {
            //         if (!savedColumns.includes(index)) {
            //             $(this).hide();
            //             $('.column-checkbox[data-column="' + index + '"]').prop('checked', false);
            //         } else {
            //             $('.column-checkbox[data-column="' + index + '"]').prop('checked', true);
            //         }
            //     });
            // });

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

            // $('.column-checkbox').on('change', function() {
            //     var columnIndex = $(this).data('column');
            //     var isChecked = $(this).prop('checked');

            //     $('#scroll-horizontal-datatable tbody tr, #scroll-horizontal-datatable thead tr').each(
            //         function() {
            //             $(this).children('th, td').eq(columnIndex).toggle(isChecked);
            //         });

            //     if (isChecked) {
            //         savedColumns.push(columnIndex);
            //     } else {
            //         savedColumns = savedColumns.filter(function(item) {
            //             return item !== columnIndex;
            //         });
            //     }

            //     localStorage.setItem('columnSettings', JSON.stringify(savedColumns));
            // });
        });
    </script>

    {{-- <script>
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
    </script> --}}

    <script>
        $(document).ready(function() {
            $('body').on('click', '.pro-variant-del-btn', function() {
                var productVariantId = $(this).data('id');
                var row = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product-variant.soft.destroy', ':id') }}"
                                .replace(':id', productVariantId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire(
                                        'Soft Deleted!',
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
                                    'An error occurred while deleting the product variant.',
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
        $(document).ready(function() {
            $('body').on('click', '.toggle-status', function() {
                var productId = $(this).data('id');
                var field = $(this).data('field');

                var currentStatus = $(this).find('.status-1, .status-0');
                var newStatus = currentStatus.hasClass('status-1') ? 0 : 1;

                $.ajax({
                    url: '{{ route('product.update.status') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        productId: productId,
                        field: field,
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            if (newStatus === 1) {
                                currentStatus.removeClass('status-0').addClass('status-1').css(
                                    'color', 'green').text('✔');
                            } else {
                                currentStatus.removeClass('status-1').addClass('status-0').css(
                                    'color', 'red').text('✘');
                            }
                        } else {
                            alert('Failed to update status.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status.');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.update-retailprice-btn', function() {
                var productVariantId = $(this).data('id');
                $('#product_variant_id').val(productVariantId);

                $.ajax({
                    url: "{{ url('/product-variant/get-product-variant-retail-price') }}/" +
                        productVariantId,
                    method: 'GET',
                    success: function(response) {
                        $('#product_variant_code').val(response.product_variant_code);
                        $('#product_variant_name').val(response.product_variant_name);
                        $('#old_retail_price').val(response.old_retail_price);
                        $('#new_retail_price').val(response.old_retail_price);
                        $('#update-product-variant-retailprice-modal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Something went wrong:', xhr);
                    }
                });
                $('#update-product-variant-retailprice-modal').modal('show');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.update-code-btn', function() {
                var productVariantId = $(this).data('id');
                $('#product_variant_id1').val(productVariantId);
                $.ajax({
                    url: "{{ url('/product-variant/get-product-variant-code') }}/" +
                        productVariantId,
                    method: 'GET',
                    success: function(response) {
                        $('#product_variant_code1').val(response.product_variant_code);
                        $('#product_variant_name1').val(response.product_variant_name);
                        $('#update-product-variant-code-modal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Something went wrong:', xhr);
                    }
                });
                $('#update-product-variant-code-modal').modal('show');
            });
        });
    </script>
@endsection
<!-- container -->
