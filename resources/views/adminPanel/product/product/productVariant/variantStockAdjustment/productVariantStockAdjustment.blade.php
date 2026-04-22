@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #update-stock {
            position: relative;
        }

        #update-stock .btn-style {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: none;
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
                    <h4 class="page-title">Stock Adjustment</h4>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search_criteria" class="form-label">Search Criteria<span class="text-danger">
                                    *</span></label>
                            <select id="search_criteria" name="search_criteria" class="form-control">
                                <option value="" selected disabled>Select Criteria</option>
                                {{-- <option value="locations">Location</option> --}}
                                <option value="categories">Category</option>
                                {{-- <option value="brands">Brand</option> --}}
                                {{-- <option value="suppliers">Supplier</option> --}}
                                {{-- <option value="products">Product</option>
                                <option value="measuring_unit">Measuring Unit</option> --}}
                            </select>
                        </div>

                        <!-- Location Dropdown -->
                        <div id="locations_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_location_id" class="form-label">Product Location<span class="text-danger">
                                    *</span></label>
                            <select id="product_location_id" name="product_location_id" class="form-control select2"
                                data-toggle="select2">
                                <option value="" selected disabled>Select Location</option>
                                @foreach ($all_locations as $allLocation)
                                    <option value="{{ $allLocation->id }}">{{ $allLocation->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Dropdown -->
                        <div id="categories_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="category_id" class="form-label">Category<span class="text-danger"> *</span></label>
                            <select id="category_id" name="category_id" class="form-control select2" data-toggle="select2">
                                <option value="" selected disabled>Select Category</option>
                                @foreach ($all_productCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand Dropdown -->
                        <div id="brands_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="brand_id" class="form-label">Brand<span class="text-danger"> *</span></label>
                            <select id="brand_id" name="brand_id" class="form-control select2" data-toggle="select2">
                                <option value="" selected disabled>Select Brand</option>
                                @foreach ($all_productBrands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier Dropdown -->
                        <div id="suppliers_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="supplier_id" class="form-label">Supplier<span class="text-danger">
                                    *</span></label>
                            <select id="supplier_id" name="supplier_id" class="form-control select2" data-toggle="select2">
                                <option value="" selected disabled>Select Supplier</option>
                                @foreach ($all_suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Dropdown -->
                        <div id="products_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="product_id" class="form-label">Product<span class="text-danger"> *</span></label>
                            <select id="product_id" name="product_id" class="form-control select2" data-toggle="select2">
                                <option value="" selected disabled>Select Product</option>
                                @foreach ($all_products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Measuring Unit Dropdown -->
                        <div id="measuring_unit_dropdown" class="col-md-4 mb-3 criteria-dropdown" style="display: none;">
                            <label for="measuring_unit_id" class="form-label">Measuring Unit<span class="text-danger">
                                    *</span></label>
                            <select id="measuring_unit_id" name="measuring_unit_id" class="form-control select2"
                                data-toggle="select2">
                                <option value="" selected disabled>Select Measuring Unit</option>
                                @foreach ($all_measuringUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row ">
            <div class="col-12 ">
                <div class="card ">
                    <div class="card-body ">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <h4 class="page-title">Criteria Related Product List</h4>
                            </div>
                            <div class="col-sm-5 text-sm-end">
                            </div><!-- end col-->
                            <div class="col-sm-2 text-sm-end">
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive ">
                            <form id="update-stock">
                                <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                    <thead style="background-color: black; color:white;">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Code</th>
                                            <th>Product Name</th>
                                            <th>Remain Stock</th>
                                            <th>Enter Stock</th>
                                            <th>Diff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-style"
                                    style="background-color: black; color:white;"></i>Save Changes</button>
                            </form>

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
        document.addEventListener('DOMContentLoaded', function() {
            const searchCriteria = document.getElementById('search_criteria');
            const dropdowns = document.querySelectorAll('.criteria-dropdown');

            searchCriteria.addEventListener('change', function() {
                dropdowns.forEach(dropdown => {
                    dropdown.style.display = 'none';

                    const selectElement = dropdown.querySelector('select');
                    if (selectElement) {
                        selectElement.value = "";

                        if ($(selectElement).hasClass('select2')) {
                            $(selectElement).val("").trigger('change');
                        }
                    }
                });

                const selectedCriteria = searchCriteria.value;
                const targetDropdown = document.getElementById(`${selectedCriteria}_dropdown`);
                if (targetDropdown) {
                    targetDropdown.style.display = 'block';
                }
            });

            $(document).ready(function() {
                // Initialize all select2 dropdowns
                $('.criteria-dropdown select').each(function() {
                    if ($(this).hasClass('select2')) {
                        $(this).select2({
                            width: '100%'
                        });
                    }
                });

                // Handle selection change for all criteria
                $('.criteria-dropdown select').change(function() {
                    const selectedId = $(this).val();
                    const criteria = $('#search_criteria').val();

                    if (selectedId && criteria) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while we load the data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '{{ url('/product-stock-adjustment/get-variant-stock') }}',
                            method: 'GET',
                            data: {
                                criteria: criteria,
                                id: selectedId,
                            },
                            success: function(response) {
                                const tbody = $('#scroll-horizontal-datatable tbody');
                                tbody.empty();

                                if (response.data.length === 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'No Variants Found',
                                        text: 'No variants match the selected criteria. Please try a different selection.',
                                    });
                                    $('button[type="submit"]').hide();
                                    return;
                                }

                                // Special handling for location criteria
                                if (criteria === 'locations') {
                                    response.data.forEach((item, index) => {
                                        // For locations: use item.stock (which is already stock_qty)
                                        const stockQty = item.stock ? parseInt(
                                            item.stock) : 0;
                                        tbody.append(`
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${item.product_variant.code}
                                                    <input type="hidden" name="variant_ids[]" value="${item.id}">
                                                </td>
                                                <td>${item.product_variant.product_variant_name}</td>
                                                <td>${stockQty}</td>
                                                <td>
                                                    <input type="number" class="form-control enter-stock"
                                                        data-variant-location-id="${item.id}"
                                                        data-location-id="${item.location_id}"
                                                        data-variant-id="${item.product_variant.id}"
                                                        data-remain-stock="${stockQty}"
                                                        value="${stockQty}"
                                                        min="0"
                                                        placeholder="Enter stock">
                                                </td>
                                                <td class="diff-column">0</td>
                                            </tr>
                                        `);
                                    });
                                } else {
                                    response.data.forEach((item, index) => {
                                        // For non-location criteria: item.stock is an object, need item.stock.stock
                                        const stockQty = item.stock ? parseInt(
                                            item.stock.stock) : 0;
                                        tbody.append(`
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${item.code}
                                                    <input type="hidden" name="variant_ids[]" value="${item.id}">
                                                </td>
                                                <td>${item.product_variant_name}</td>
                                                <td>${stockQty}</td>
                                                <td>
                                                    <input type="number" class="form-control enter-stock"
                                                        data-variant-id="${item.id}"
                                                        data-remain-stock="${stockQty}"
                                                        value="${stockQty}"
                                                        min="0"
                                                        placeholder="Enter stock">
                                                    <input type="hidden" name="variant_id" value="${item.id}">
                                                </td>
                                                <td class="diff-column">0</td>
                                            </tr>
                                        `);
                                    });
                                }

                                $('button[type="submit"]').show();
                                Swal.close();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'An error occurred: ' + xhr
                                        .responseText,
                                });
                                Swal.close();
                            }
                        });
                    }
                });
            });

            $(document).on('input', '.enter-stock', function() {
                const remainStock = parseFloat($(this).data('remain-stock')) || 0;
                const enteredStock = parseFloat($(this).val()) || 0;
                const diff = remainStock - enteredStock;
                $(this).closest('tr').find('.diff-column').text(diff);
            });

            $(document).on('input', '.enter-stock', function() {
                const value = $(this).val();
                if (value < 0) {
                    $(this).val(0);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        text: 'Stock value cannot be negative.',
                    });
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#update-stock').submit(function(e) {
                e.preventDefault();

                const formData = [];
                $('#scroll-horizontal-datatable tbody tr').each(function() {
                    var variantLocationId = $(this).find('.enter-stock').data(
                        'variant-location-id');
                    var locationId = $(this).find('.enter-stock').data('location-id');
                    var variantId = $(this).find('.enter-stock').data('variant-id');
                    var enteredStock = $(this).find('.enter-stock').val();
                    var diff = $(this).find('.diff-column').text();

                    formData.push({
                        variant_location_id: variantLocationId,
                        location_id: locationId,
                        variant_id: variantId,
                        entered_stock: enteredStock,
                        diff: diff,
                    });
                });

                if (formData.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Data to Submit',
                        text: 'Please enter stock values before saving.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to submit the stock adjustments?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show the "Processing..." Swal
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Saving the changes...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '{{ url('/product-stock-adjustment/variant-stock-update') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                form_data: formData,
                            },
                            success: function(response) {
                                Swal.close();

                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Changes Saved',
                                        text: 'The stock adjustments have been successfully saved.',
                                    });

                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'There was an issue saving the changes. Please try again.',
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.close();

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'An error occurred: ' + xhr
                                        .responseText,
                                });
                            }
                        });
                    } else {
                        // If user cancels the confirmation
                        Swal.fire({
                            icon: 'info',
                            title: 'Cancelled',
                            text: 'Your stock adjustments were not submitted.',
                        });
                    }
                });
            });
        });
    </script>
@endsection
<!-- container -->
