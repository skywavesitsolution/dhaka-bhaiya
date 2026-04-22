@extends('adminPanel/master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">&nbsp;</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-8">
                                <h4 class="page-title">Create Product ('Fields marked with <span class="text-danger">
                                        *</span> are mandatory.')</h4>
                            </div>
                            <div class="col-sm-6">
                            </div><!-- end col-->
                        </div>
                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <!-- Product Code (Auto-generated) -->
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Product Code<span class="text-danger">
                                            *</span></label>
                                    <input type="text" id="code" name="code" class="form-control"
                                        value="{{ $productCode }}">
                                    @error('code')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Product Name -->
                                <div class="col-md-3 mb-3">
                                    <label for="product_name" class="form-label">Product Name<span class="text-danger">
                                            *</span></label>
                                    <input type="text" id="product_name" name="product_name"
                                        value="{{ old('product_name') }}" class="form-control"
                                        placeholder="Enter product name" autocomplete="off" required>
                                    <div id="suggestions" class="dropdown-menu"></div>
                                    @error('name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Product Name Urdu -->
                                <div class="col-md-3 mb-3">
                                    <label for="product_urdu_name" class="form-label">پروڈکٹ کا نام</label>
                                    <input type="text" id="product_urdu_name" name="product_urdu_name"
                                        value="{{ old('product_urdu_name') }}" class="form-control"
                                        placeholder="پروڈکٹ کا نام درج کریں">
                                    @error('name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="col-md-12 mb-3">
                                    <label for="category" class="form-label">Category<span class="text-danger">
                                            *</span></label>
                                    <select id="category" name="category_id" class="form-control select2"
                                        data-toggle="select2" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($allproductCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                {{ $category->id == $lastCategoryId ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Manage Variants -->
                                <div class="col-md-2 mb-3 d-none">
                                    <input type="checkbox" id="is_manage_variants" name="is_manage_variants"
                                        class="form-check-input">
                                    <label for="is_manage_variants" class="form-label">Manage Variants</label>
                                    <input type="hidden" id="is_manage_variants_hidden" name="is_manage_variants"
                                        value="1">
                                </div>

                                <div class="col-md-2 mb-3 d-none">
                                    <input type="checkbox" id="is_fixed_asset" name="is_fixed_asset"
                                        class="form-check-input">
                                    <label for="is_fixed_asset" class="form-label">Fixed Asset</label>
                                    <input type="hidden" id="is_fixed_asset_hidden" name="is_fixed_asset" value="1">
                                </div>

                                <!-- Featured -->
                                <div class="col-md-2 mb-3 d-none">
                                    <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input">
                                    <label for="is_featured" class="form-label">Featured</label>
                                    <input type="hidden" id="is_featured_hidden" name="is_featured" value="0">
                                </div>

                                <!-- New Arrival -->
                                <div class="col-md-2 mb-3 d-none">
                                    <input type="checkbox" id="new_arrival" name="new_arrival" class="form-check-input">
                                    <label for="new_arrival" class="form-label">New Arrival</label>
                                    <input type="hidden" id="new_arrival_hidden" name="new_arrival" value="0">
                                </div>

                                <!-- Best Selling Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="best_selling_product" name="best_selling_product"
                                        class="form-check-input">
                                    <label for="best_selling_product" class="form-label">Best Selling Product</label>
                                    <input type="hidden" id="best_selling_product_hidden" name="best_selling_product"
                                        value="0">
                                </div>
                                <!-- Service Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="service_item" name="service_item"
                                        class="form-check-input">
                                    <label for="service_item" class="form-label">Service Item</label>
                                    <input type="hidden" id="service_item_hidden" name="service_item" value="0">
                                </div>
                                <!-- Finish Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="finish_goods" name="finish_goods"
                                        class="form-check-input">
                                    <label for="finish_goods" class="form-label">Finish Goods</label>
                                    <input type="hidden" id="finish_goods_hidden" name="finish_goods" value="0">
                                </div>
                                <!-- Raw material Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="raw_material" name="raw_material"
                                        class="form-check-input">
                                    <label for="raw_material" class="form-label">Ingredients</label>
                                    <input type="hidden" id="raw_material_hidden" name="raw_material" value="0">
                                </div>
                                <!-- Manage Deal Item -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="manage_deal_id_checkbox" name="manage_deal_id"
                                        class="form-check-input">
                                    <label for="manage_deal_id" class="form-label">Manage Deal Item</label>
                                    <input type="hidden" id="manage_deal_id_hidden" name="manage_deal_id"
                                        value="1">
                                </div>

                                <!-- Extra Fields - Display if is_manage_variants is not checked -->
                                <div id="extra-fields" style="display:none;">
                                    <div class="row">

                                        <!-- Opening Stock -->
                                        <div class="col-md-3 mb-3" id="opning_stock_div">
                                            <label for="opening_stock" class="form-label">Opening Stock</label>
                                            <input type="number" id="opening_stock"
                                                value="{{ old('opening_stock', 0) }}" name="opening_stock"
                                                class="form-control" placeholder="Opening Stock">
                                            @error('opening_stock')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Low Stock -->
                                        <div class="col-md-3 mb-3" id="low_stock_div">
                                            <label for="low_stock" class="form-label">Low Stock</label>
                                            <input type="number" id="low_stock" value="{{ old('low_stock', 0) }}"
                                                name="low_stock" class="form-control" placeholder="Low Stock">
                                            @error('low_stock')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Inner Packs -->
                                        <div class="col-md-3 mb-3" id="inner_pack_div">
                                            <label for="inner_pack" class="form-label">Inner Packs</label>
                                            <input type="number" id="inner_pack" value="{{ old('inner_pack', 1) }}"
                                                name="inner_pack" class="form-control" placeholder="Inner Packs">
                                            @error('inner_pack')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Loose Packs -->
                                        <div class="col-md-3 mb-3" id="loose_pack_div">
                                            <label for="loose_pack" class="form-label">Loose Packs (No of Packs in Inner
                                                Packs)</label>
                                            <input type="number" id="loose_pack" value="{{ old('loose_pack', 1) }}"
                                                name="loose_pack" class="form-control" placeholder="Loose Packs">
                                            @error('loose_pack')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Cost Price -->
                                        <div class="col-md-4 mb-3">
                                            <label for="cost_price" class="form-label">Cost Price<span
                                                    class="text-danger"> *</span></label>
                                            <input type="number" id="cost_price" name="cost_price"
                                                value="{{ old('cost_price') }}" class="form-control"
                                                placeholder="Cost Price">
                                            @error('cost_price')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Retail Price -->
                                        <div class="col-md-4 mb-3">
                                            <label for="retail_price" class="form-label">Retail Price<span
                                                    class="text-danger"> *</span></label>
                                            <input type="number" id="retail_price" value="{{ old('retail_price') }}"
                                                name="retail_price" class="form-control" placeholder="Retail Price">
                                            @error('retail_price')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Measuring Unit ID -->
                                        <div class="col-md-4 mb-3">
                                            <label for="measuring_unit_id" class="form-label">Measuring Unit<span
                                                    class="text-danger"> *</span></label>
                                            <select id="measuring_unit_id" name="measuring_unit_id"
                                                class="form-control select2" data-toggle="select2">
                                                <option value="" selected disabled>Select Unit</option>
                                                @foreach ($allmeasuringUnits as $measuringUnit)
                                                    <option value="{{ $measuringUnit->id }}"
                                                        {{ old('measuring_unit_id') == $measuringUnit->id ? 'selected' : '' }}
                                                        {{ $measuringUnit->id == $lastMeasuringUnitId ? 'selected' : '' }}>
                                                        {{ $measuringUnit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Product Location -->
                                        <div class="col-md-4 mb-3" id="variant_location_div">
                                            <label for="product_location_id" class="form-label">Product Location<span
                                                    class="text-danger"> *</span></label>
                                            <select id="product_location_id" name="product_location_id"
                                                class="form-control select2" data-toggle="select2">
                                                {{-- <option value="" selected disabled>Select Location</option> --}}
                                                @foreach ($allLocations as $allLocation)
                                                    <option value="{{ $allLocation->id }}"
                                                        {{ old('product_location_id') == $allLocation->id ? 'selected' : '' }}
                                                        {{ $allLocation->id == $lastLocationId ? 'selected' : '' }}>
                                                        {{ $allLocation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Minimum Order Quantity -->
                                        <div class="col-md-4 mb-3">
                                            <label for="min_order_qty" class="form-label">Minimum Order Quantity<span
                                                    class="text-danger"> *</span></label>
                                            <input type="number" id="min_order_qty"
                                                value="{{ old('min_order_qty', 1) }}" name="min_order_qty"
                                                class="form-control" placeholder="Minimum Order Quantity">
                                            @error('min_order_qty')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Image Upload Field -->
                                        <div class="col-md-6 mb-3">
                                            <label for="image" class="form-label">Product Image<span
                                                    class="text-danger"> *</span></label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                accept="image/*" onchange="previewImage(event)">
                                            @error('image')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Image Preview -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Image Preview</label>
                                            <div>
                                                <img id="imagePreview" src="#" alt="Image Preview"
                                                    style="max-width: 10%; height: auto; display: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="productDescription" class="form-label">Short Description</label>
                                    <textarea id="productDescription" name="product_description" class="form-control" rows="3"
                                        placeholder="Enter Product description">{{ old('product_description') }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Create Product</button>
                                    <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const manageVariantsCheckbox = document.getElementById('is_manage_variants');
            const manageVariantsHidden = document.getElementById('is_manage_variants_hidden');
            const extraFields = document.getElementById('extra-fields');
            const opningStockDiv = document.getElementById('opning_stock_div');
            const lowStockDiv = document.getElementById('low_stock_div');
            const innerPackDiv = document.getElementById('inner_pack_div');
            const loosePackDiv = document.getElementById('loose_pack_div');
            const variantLocationDiv = document.getElementById('variant_location_div');
            const minOrderQtyDiv = document.querySelector('.col-md-4.mb-3:has(#min_order_qty)');
            const productImageDiv = document.querySelector('.col-md-6.mb-3:has(#image)');
            const imagePreviewDiv = document.querySelector('.col-md-6.mb-3:has(#imagePreview)');

            const checkboxes = [{
                    id: 'service_item',
                    hiddenId: 'service_item_hidden'
                },
                {
                    id: 'finish_goods',
                    hiddenId: 'finish_goods_hidden'
                },
                {
                    id: 'raw_material',
                    hiddenId: 'raw_material_hidden'
                }
            ];

            // Function to toggle inventory fields visibility
            function toggleInventoryFields() {
                const serviceItemChecked = document.getElementById('service_item').checked;
                const finishGoodsChecked = document.getElementById('finish_goods').checked;
                const rawMaterialChecked = document.getElementById('raw_material').checked;

                // Base display logic for most fields
                const showInventoryFields = (finishGoodsChecked || rawMaterialChecked) && !serviceItemChecked;
                const display = showInventoryFields || (!serviceItemChecked && !finishGoodsChecked && !
                    rawMaterialChecked) ? 'block' : 'none';

                // Apply display logic to inventory-related fields
                opningStockDiv.style.display = display;
                lowStockDiv.style.display = display;
                variantLocationDiv.style.display = manageVariantsCheckbox.checked ? 'none' : 'block';
                innerPackDiv.style.display = display;
                loosePackDiv.style.display = display;
                minOrderQtyDiv.style.display = display;

                // Specific logic for each checkbox state
                if (rawMaterialChecked) {
                    // Hide these fields only when Ingredients is selected
                    innerPackDiv.classList.add('d-none');
                    loosePackDiv.classList.add('d-none');
                    minOrderQtyDiv.classList.add('d-none');
                    productImageDiv.classList.add('d-none');
                    imagePreviewDiv.classList.add('d-none');
                } else {
                    // Show fields based on base display logic when not Ingredients
                    innerPackDiv.classList.remove('d-none');
                    loosePackDiv.classList.remove('d-none');
                    minOrderQtyDiv.classList.remove('d-none');
                    productImageDiv.classList.remove('d-none');
                    imagePreviewDiv.classList.remove('d-none');

                    // Apply display logic to Inner Packs, Loose Packs, and Minimum Order Quantity for Finish Goods/Service Item
                    if (finishGoodsChecked) {
                        innerPackDiv.classList.add('d-none');
                        loosePackDiv.classList.add('d-none');
                        minOrderQtyDiv.classList.add('d-none');
                    }

                    // Always apply base display logic to image fields unless hidden by Ingredients
                    productImageDiv.style.display = manageVariantsCheckbox.checked ? 'none' : 'block';
                    imagePreviewDiv.style.display = manageVariantsCheckbox.checked ? 'none' : 'block';
                }
            }

            // Initialize hidden inputs and field visibility
            checkboxes.forEach(({
                id,
                hiddenId
            }) => {
                const checkbox = document.getElementById(id);
                const hiddenInput = document.getElementById(hiddenId);

                hiddenInput.value = checkbox.checked ? '1' : '0';
                toggleInventoryFields();

                checkbox.addEventListener('change', function() {
                    // Uncheck other checkboxes to enforce mutual exclusivity
                    checkboxes.forEach(ch => {
                        if (ch.id !== id) {
                            document.getElementById(ch.id).checked = false;
                            document.getElementById(ch.hiddenId).value = '0';
                        }
                    });

                    hiddenInput.value = this.checked ? '1' : '0';
                    toggleInventoryFields();
                });
            });

            // Handle "Manage Variants" checkbox
            manageVariantsHidden.value = manageVariantsCheckbox.checked ? '1' : '0';
            extraFields.style.display = manageVariantsCheckbox.checked ? 'none' : 'block';

            manageVariantsCheckbox.addEventListener('change', function() {
                manageVariantsHidden.value = this.checked ? '1' : '0';
                extraFields.style.display = this.checked ? 'none' : 'block';
                toggleInventoryFields();
            });

            // Handle other checkboxes (Fixed Asset, Featured, etc.)
            const isFixedAssetCheckbox = document.getElementById('is_fixed_asset');
            const isFixedAssetHidden = document.getElementById('is_fixed_asset_hidden');
            isFixedAssetHidden.value = isFixedAssetCheckbox.checked ? '1' : '0';
            isFixedAssetCheckbox.addEventListener('change', function() {
                isFixedAssetHidden.value = this.checked ? '1' : '0';
            });

            const featuredCheckbox = document.getElementById('is_featured');
            const featuredHidden = document.getElementById('is_featured_hidden');
            featuredHidden.value = featuredCheckbox.checked ? '1' : '0';
            featuredCheckbox.addEventListener('change', function() {
                featuredHidden.value = this.checked ? '1' : '0';
            });

            const newArrivalCheckbox = document.getElementById('new_arrival');
            const newArrivalHidden = document.getElementById('new_arrival_hidden');
            newArrivalHidden.value = newArrivalCheckbox.checked ? '1' : '0';
            newArrivalCheckbox.addEventListener('change', function() {
                newArrivalHidden.value = this.checked ? '1' : '0';
            });

            const bestSellingCheckbox = document.getElementById('best_selling_product');
            const bestSellingHidden = document.getElementById('best_selling_product_hidden');
            bestSellingHidden.value = bestSellingCheckbox.checked ? '1' : '0';
            bestSellingCheckbox.addEventListener('change', function() {
                bestSellingHidden.value = this.checked ? '1' : '0';
            });

            const manageDealCheckbox = document.getElementById('manage_deal_id_checkbox');
            const manageDealHidden = document.getElementById('manage_deal_id_hidden');
            manageDealHidden.value = manageDealCheckbox.checked ? '1' : '0';
            manageDealCheckbox.addEventListener('change', function() {
                manageDealHidden.value = this.checked ? '1' : '0';
            });
        });
    </script>

    <script>
        function previewProductImage(event) {
            const image = document.getElementById('productImagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    image.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                image.src = '#';
                image.style.display = 'none';
            }
        }

        function previewImage(event) {
            const image = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    image.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                image.src = '#';
                image.style.display = 'none';
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#product_name').on('input', function() {
                let query = $(this).val();

                if (query.length > 1) {
                    $.ajax({
                        url: '{{ url('/product/get-product-suggestions') }}',
                        method: 'GET',
                        data: {
                            name: query
                        },
                        success: function(response) {
                            let suggestions = response.map(product =>
                                `<a class="dropdown-item">${product.product_name}</a>`);
                            $('#suggestions').html(suggestions.join('')).show();
                        }
                    });
                } else {
                    $('#suggestions').hide();
                }
            });

            $(document).on('click', '#suggestions .dropdown-item', function() {
                $('#product_name').val($(this).text());
                $('#suggestions').hide();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to create this product?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, create it!',
                    cancelButtonText: 'No, cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading animation
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while the product is being created.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Prepare form data for AJAX
                        let formData = new FormData(this);

                        $.ajax({
                            url: '{{ route('product.store') }}',
                            method: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Reload the page after 3 seconds
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.close(); // Close loading animation

                                let errorMessage =
                                    'An error occurred. Please try again.';
                                if (xhr.status === 422) {
                                    // Handle validation errors
                                    let errors = xhr.responseJSON.errors;
                                    let errorList = '<ul>';
                                    $.each(errors, function(key, value) {
                                        errorList += `<li>${value[0]}</li>`;
                                    });
                                    errorList += '</ul>';
                                    errorMessage = errorList;
                                } else if (xhr.responseJSON && xhr.responseJSON
                                    .message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    html: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
<!-- container -->
