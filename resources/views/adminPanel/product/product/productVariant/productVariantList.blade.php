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
                    {{-- <form action="{{ route('product-variant.crietaria-wise-report') }}" target="_blank" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="search_criteria" class="form-label">Search Criteria<span class="text-danger"> *</span></label>
                            <select id="search_criteria" name="search_criteria" class="form-control">
                                <option value="" selected disabled>Select Criteria</option>
                                <option value="product">Product</option>
                                <option value="category">Category</option>
                                <option value="brand">Brand</option>
                                <option value="supplier">Supplier</option>
                                <option value="locations">Location</option>
                                <option value="measuring_unit">Measuring Unit</option>
                            </select>
                        </div>


                        <div id="product_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_id" class="form-label">Product<span class="text-danger"> *</span></label>
                            <select id="product_id" name="product_id" class="form-control select2" data-toggle="select2">
                                <option value="all_products" selected>All Product</option>
                                @foreach ($all_products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->code . '-' . $product->product_name }}{{ $product->product_urdu_name ? '-' . $product->product_urdu_name : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="category_dropdown" class="col-md-3 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_category_id" class="form-label">Product Category<span class="text-danger"> *</span></label>
                            <select id="product_category_id" name="product_category_id" class="form-control select2" data-toggle="select2">
                                <option value="" disabled selected>Select Category</option>
                                <option value="all_categories" >All Categories</option>
                                @foreach ($all_productCategories as $allProductCategories)
                                    <option value="{{ $allProductCategories->id }}">{{ $allProductCategories->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="brand_dropdown" class="col-md-3 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_brand_id" class="form-label">Product Brand<span class="text-danger"> *</span></label>
                            <select id="product_brand_id" name="product_brand_id" class="form-control select2" data-toggle="select2">
                                <option value="" disabled selected>Select Brand</option>
                                <option value="all_brands" >All Brand</option>
                                @foreach ($all_productBrands as $allProductBrands)
                                    <option value="{{ $allProductBrands->id }}">{{ $allProductBrands->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="supplier_dropdown" class="col-md-3 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_supplier_id" class="form-label">Product Supplier<span class="text-danger"> *</span></label>
                            <select id="product_supplier_id" name="product_supplier_id" class="form-control select2" data-toggle="select2">
                                <option value="" disabled selected>Select Supplier</option>
                                <option value="all_suppliers" >All Supplier</option>
                                @foreach ($all_suppliers as $allSuppliers)
                                    <option value="{{ $allSuppliers->id }}">{{ $allSuppliers->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="locations_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_location_id" class="form-label">Product Location<span class="text-danger"> *</span></label>
                            <select id="product_location_id" name="product_location_id" class="form-control select2" data-toggle="select2">
                                <option value="all_locations" selected>All Location</option>
                                @foreach ($all_locations as $allLocation)
                                    <option value="{{ $allLocation->id }}">{{ $allLocation->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="measuring_unit_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_measuring_unit_id" class="form-label">Product Measuring Unit<span class="text-danger"> *</span></label>
                            <select id="product_measuring_unit_id" name="product_measuring_unit_id" class="form-control select2" data-toggle="select2">
                                <option value="all_measuringUnits" selected>All Measuring Unit</option>
                                @foreach ($all_measuringUnits as $all_measuringUnits)
                                    <option value="{{ $all_measuringUnits->id }}">
                                        {{ $all_measuringUnits->name }}{{ $all_measuringUnits->symbol ? '-' . $all_measuringUnits->symbol : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="criteria_product_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="criteria_product_id" class="form-label">Product<span class="text-danger"> *</span></label>
                            <select id="criteria_product_id" name="criteria_product_id" class="form-control criteria_product_select select2" data-toggle="select2">
                                <option value="" selected disabled>Select Product</option>
                            </select>
                        </div>

                        <div class="col-md-2 mt-3">
                            <button id="search_button" class="btn btn-primary" type="submit" style="display: none;">Search</button>
                        </div>
                    </div>
                </form> --}}
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
                            <div class="col-sm-7 text-sm-end">
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
                                                Measuring Unit</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="7" checked> SKU
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="8"> Min Order
                                                Qty</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="9"> Cost Price
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="10"> Whole Sale
                                                Price</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="11"> Retail
                                                Price</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="12"> Opening
                                                Stock</li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="13"> Low Stock
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="14"> Inner Packs
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="15"> Loose Packs
                                            </li>
                                            <li><input type="checkbox" class="column-checkbox" data-column="16" checked>
                                                Action</li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- end col-->
                            {{-- <div class="col-sm-2 text-sm-end">
                            <a href="{{ route('product-variant.create') }}" class="btn btn-success"><i class="mdi mdi-plus-circle me-2"></i>Add Product Variant</a>
                        </div> --}}
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
                                        <th style="width: 85px;">Action</th>
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
                                                <td class="table-action">
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-warning update-code-btn"
                                                        data-id="{{ $productVariant->id }}"><i
                                                            class="mdi mdi-file-document-edit"></i></a>
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-secondary update-retailprice-btn"
                                                        data-id="{{ $productVariant->id }}"><i
                                                            class="mdi mdi-circle-edit-outline"></i></a>
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-danger pro-variant-del-btn"
                                                        data-id="{{ $productVariant->id }}"><i
                                                            class="mdi mdi-delete"></i></a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            let savedColumns = JSON.parse(localStorage.getItem('columnSettings')) || [0, 1, 2, 3, 4, 5, 6, 7, 16];

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
        document.addEventListener('DOMContentLoaded', function() {
            const searchCriteria = document.getElementById('search_criteria');
            const dropdowns = document.querySelectorAll('.criteria-dropdown');
            const searchButton = document.getElementById('search_button');

            searchButton.style.display = 'none';

            searchCriteria.addEventListener('change', function() {
                if (searchCriteria.value) {
                    searchButton.style.display = 'block';
                }

                dropdowns.forEach(dropdown => {
                    dropdown.style.display = 'none';
                    const selectElement = dropdown.querySelector('select2');
                    if (selectElement) {
                        selectElement.value = "all_" + dropdown.id.split('_')[0];

                        if ($(selectElement).hasClass('select2')) {
                            $(selectElement).val("all_" + dropdown.id.split('_')[0]).trigger(
                                'change');
                        }
                    }
                });

                const selectedCriteria = searchCriteria.value;
                const targetDropdown = document.getElementById(`${selectedCriteria}_dropdown`);
                if (targetDropdown) {
                    targetDropdown.style.display = 'block';
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script loaded');

            $('#product_category_id, #product_brand_id, #product_supplier_id').select2();

            const dropdowns = ['product_category_id', 'product_brand_id', 'product_supplier_id'];

            dropdowns.forEach(id => {
                const dropdown = document.getElementById(id);
                console.log(dropdown);

                if (dropdown) {
                    console.log(`Binding select2 change event to #${id}`);

                    $(dropdown).on('select2:select', function(e) {
                        const selectedValue = e.params.data.id;
                        console.log(`Selected value for ${id}:`, selectedValue);

                        if (!selectedValue) {
                            console.warn(`No value selected for ${id}`);
                        } else {
                            console.log(`Selected value for ${id}:`, selectedValue);

                            let criteriaType = '';
                            if (id === 'product_category_id') criteriaType = 'category';
                            else if (id === 'product_brand_id') criteriaType = 'brand';
                            else if (id === 'product_supplier_id') criteriaType = 'supplier';

                            $('#criteria_product_dropdown').show();

                            $.ajax({
                                url: `{{ url('/product/get-products-by-${criteriaType}/') }}/${selectedValue}`,
                                method: 'GET',
                                success: function(data) {
                                    console.log('Data fetched:', data);

                                    const productSelect = $('.criteria_product_select');
                                    productSelect.html(
                                        '<option value="" selected disabled>Select Product</option>'
                                    );

                                    if (data.products.length > 0) {
                                        productSelect.html(
                                            '<option value="all_products">All Products</option>'
                                        );
                                        data.products.forEach(product => {
                                            productSelect.append(
                                                `<option value="${product.id}">${product.code} - ${product.product_name}- ${product.product_urdu_name}</option>`
                                            );
                                        });
                                        productSelect.trigger('change');
                                    } else {
                                        productSelect.append(
                                            '<option value="" disabled>No products found</option>'
                                        );
                                        Swal.fire({
                                            title: 'No Product Found',
                                            text: 'No products were found for the selected criteria.',
                                            icon: 'warning',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Error fetching products:', xhr);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
<!-- container -->
