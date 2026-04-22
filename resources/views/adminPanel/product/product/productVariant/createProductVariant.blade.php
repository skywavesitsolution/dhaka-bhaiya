@extends('adminPanel/master')
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
                            <div class="col-sm-4">
                                <h4 class="page-title">Create Product Variant ('Fields marked with <span
                                        class="text-danger"> *</span> are mandatory.')</h4>
                            </div>
                            <div class="col-sm-8 d-flex">
                                <div class="col-md-2 mb-3">
                                    <label for="is_fixed_asset" class="form-label">Fixed Asset</label>
                                    <input type="checkbox" id="is_fixed_asset" name="is_fixed_asset"
                                        class="form-check-input">
                                </div>

                                {{-- <!-- Service Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="service_item" name="service_item" class="form-check-input">
                                    <label for="service_item" class="form-label">service Item</label>
                                    <input type="hidden" id="service_item_hidden" name="service_item" value="0">
                                </div>
                                <!-- Finish Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="finish_goods" name="finish_goods" class="form-check-input">
                                    <label for="finish_goods" class="form-label">Finish Goods</label>
                                    <input type="hidden" id="finish_goods_hidden" name="finish_goods" value="0">
                                </div>
                                <!-- Raw material Product -->
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="raw_material" name="raw_material" class="form-check-input">
                                    <label for="raw_material" class="form-label">Raw Material</label>
                                    <input type="hidden" id="raw_material_hidden" name="raw_material" value="0">
                                </div> --}}
                            </div><!-- end col-->

                        </div>
                        <form action="{{ route('product-variant.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Product Variant Code (Auto-generated) -->
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Product Variant Code<span class="text-danger">
                                            *</span></label>
                                    <input type="text" id="code" name="code" class="form-control"
                                        value="{{ $newVariantCode }}">
                                    @error('code')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Product Id -->
                                <div class="col-md-6 mb-3">
                                    <label for="product_id" class="form-label">Product<span class="text-danger">
                                            *</span></label>
                                    <select id="product_id" name="product_id" class="form-control select2"
                                        data-toggle="select2" required>
                                    </select>
                                </div>
                                <!-- Measuring Unit ID -->
                                <div class="col-md-3 mb-3">
                                    <label for="measuring_unit_id" class="form-label">Measuring Unit<span
                                            class="text-danger"> *</span></label>
                                    <select id="measuring_unit_id" name="measuring_unit_id" class="form-control select2"
                                        data-toggle="select2">
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
                                <!-- Product Location ID -->
                                <div class="col-md-3 mb-3" id="variant_location_div">
                                    <label for="location_id" class="form-label">Product Location<span
                                            class="text-danger"> *</span></label>
                                    <select id="location_id" name="location_id" class="form-control select2"
                                        data-toggle="select2">
                                        <option value="" selected disabled>Select Location</option>
                                        @foreach ($allLocations as $allLocation)
                                            <option value="{{ $allLocation->id }}"
                                                {{ old('location_id') == $allLocation->id ? 'selected' : '' }}
                                                {{ $allLocation->id == $lastLocationId ? 'selected' : '' }}>
                                                {{ $allLocation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Product Size Id -->
                                <div class="col-md-3 mb-3">
                                    <label for="size_id" class="form-label">Product Size<span class="text-danger">
                                            *</span></label>
                                    <select id="size_id" name="size_id" class="form-control select2"
                                        data-toggle="select2">
                                        <option value="" selected disabled>Select Size</option>
                                        @foreach ($allSizes as $allSize)
                                            <option value="{{ $allSize->id }}"
                                                {{ old('size_id') == $allSize->id ? 'selected' : '' }}
                                                {{ $allSize->id == $lastSizeId ? 'selected' : '' }}>
                                                {{ $allSize->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Opening Stock -->
                                <div class="col-md-3 mb-3" id="opning_stock_div">
                                    <label for="opening_stock" class="form-label">Opening Stock</label>
                                    <input type="number" id="opening_stock" value="{{ old('opening_stock', 0) }}"
                                        name="opening_stock" class="form-control" placeholder="Opening Stock">
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
                                <div class="col-md-3 mb-3">
                                    <label for="cost_price" class="form-label">Cost Price<span class="text-danger">
                                            *</span></label>
                                    <input type="number" id="cost_price" name="cost_price" class="form-control"
                                        value="{{ old('cost_price') }}" placeholder="Cost Price">
                                    @error('cost_price')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Whole Sale Price -->
                                {{-- <div class="col-md-3 mb-3">
                                <label for="wholesale_price" class="form-label">Whole Sale Price</label>
                                <input type="number" id="wholesale_price" value="{{ old('wholesale_price', 0) }}" name="wholesale_price" class="form-control"  placeholder="Whole Sale Price">
                                @error('wholesale_price')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div> --}}
                                <!-- Retail Price -->
                                <div class="col-md-3 mb-3">
                                    <label for="retail_price" class="form-label">Retail Price<span class="text-danger">
                                            *</span></label>
                                    <input type="number" id="retail_price" name="retail_price"
                                        value="{{ old('retail_price') }}" class="form-control"
                                        placeholder="Retail Price">
                                    @error('retail_price')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Minimum Order Quantity -->
                                <div class="col-md-3 mb-3">
                                    <label for="min_order_qty" class="form-label">Minimum Order Quantity</label>
                                    <input type="number" id="min_order_qty" value="{{ old('min_order_qty', 1) }}"
                                        name="min_order_qty" class="form-control" placeholder="Minimum Order Quantity">
                                    @error('min_order_qty')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Image Upload Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">Product Image<span class="text-danger">
                                            *</span></label>
                                    <input type="file" id="image" name="image" class="form-control"
                                        accept="image/*" required onchange="previewImage(event)">
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
                                <!-- Description  -->
                                <div class="col-md-12 mb-3">
                                    <label for="variantDescription" class="form-label">Description</label>
                                    <textarea id="variantDescription" name="variant_description" class="form-control" rows="3"
                                        placeholder="Enter Product Variant Description">{{ old('variant_description') }}</textarea>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <input type="checkbox" id="manage_deal_id" name="manage_deal_id"
                                        class="form-check-input">
                                    <label for="manage_deal_id" class="form-label">Manage Deal Item</label>
                                    <input type="hidden" id="manage_deal_id_hidden" name="manage_deal_id"
                                        value="0">
                                </div>
                                <!-- Service Product -->
                            <div class="col-md-2 mb-3">
                                <input type="checkbox" id="service_item" name="service_item" class="form-check-input">
                                <label for="service_item" class="form-label">service Item</label>
                                <input type="hidden" id="service_item_hidden" name="service_item" value="0">
                            </div>
                            <!-- Finish Product -->
                            <div class="col-md-2 mb-3">
                                <input type="checkbox" id="finish_goods" name="finish_goods" class="form-check-input">
                                <label for="finish_goods" class="form-label">Finish Goods</label>
                                <input type="hidden" id="finish_goods_hidden" name="finish_goods" value="0">
                            </div>
                            <!-- Raw material Product -->
                            <div class="col-md-2 mb-3">
                                <input type="checkbox" id="raw_material" name="raw_material" class="form-check-input">
                                <label for="raw_material" class="form-label">Raw Material</label>
                                <input type="hidden" id="raw_material_hidden" name="raw_material" value="0">
                            </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Create Product Variant</button>
                                    <a href="{{ route('product-variant.index') }}" class="btn btn-secondary">Cancel</a>
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
        document.addEventListener("DOMContentLoaded", function() {
            // const manageVariantsCheckbox = document.getElementById('is_manage_variants');
            // const manageVariantsHidden = document.getElementById('is_manage_variants_hidden');
            // const extraFields = document.getElementById('extra-fields');


            const opning_stock_div = document.getElementById('opning_stock_div');
            const low_stock_div = document.getElementById('low_stock_div');
            const inner_pack_div = document.getElementById('inner_pack_div');
            const loose_pack_div = document.getElementById('loose_pack_div');
            const variant_location_div = document.getElementById('variant_location_div');

            const serviceItemCheckbox = document.getElementById('service_item');
            const serviceItemHidden = document.getElementById('service_item_hidden');

            const finish_goodsCheckbox = document.getElementById('finish_goods');
            const finish_goodsHidden = document.getElementById('finish_goods_hidden');

            const raw_materialCheckbox = document.getElementById('raw_material');
            const raw_materialHidden = document.getElementById('raw_material_hidden');


            const manage_deal_Checkbox = document.getElementById('manage_deal_id');
            const manage_deal_Hidden = document.getElementById('manage_deal_id_hidden');


            if (raw_materialCheckbox.checked) {
                raw_materialHidden.value = '1';
            } else {
                raw_materialHidden.value = '0';
            }

            raw_materialCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    raw_materialHidden.value = '1';
                } else {
                    raw_materialHidden.value = '0';
                }
            });

            if (finish_goodsCheckbox.checked) {
                finish_goodsHidden.value = '1';
            } else {
                finish_goodsHidden.value = '0';
            }

            finish_goodsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    finish_goodsHidden.value = '1';
                } else {
                    finish_goodsHidden.value = '0';
                }
            });

            if (serviceItemCheckbox.checked) {
                serviceItemHidden.value = '1';
                opning_stock_div.style.display = 'none';
            } else {
                serviceItemHidden.value = '0';
                opning_stock_div.style.display = 'block';
            }

            serviceItemCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    serviceItemHidden.value = '1';
                    opning_stock_div.style.display = 'none';
                    low_stock_div.style.display = 'none';
                    inner_pack_div.style.display = 'none';
                    loose_pack_div.style.display = 'none';
                    variant_location_div.style.display = 'none';
                } else {
                    serviceItemHidden.value = '0';
                    opning_stock_div.style.display = 'block';
                    low_stock_div.style.display = 'block';
                    inner_pack_div.style.display = 'block';
                    loose_pack_div.style.display = 'block';
                    variant_location_div.style.display = 'block';
                }
            });



            if (manage_deal_Checkbox.checked) {
                manage_deal_Hidden.value = '1';
            } else {
                manage_deal_Hidden.value = '0';
            }
            manage_deal_Checkbox.addEventListener('change', function() {
                if (this.checked) {
                    manage_deal_Hidden.value = '1';
                } else {
                    manage_deal_Hidden.value = '0';
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const isFixedAssetCheckbox = $('#is_fixed_asset');
            const productSelect = $('#product_id');

            function fetchProducts(isChecked) {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching products...',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('product-variant.fixed.asset.products') }}",
                    method: 'GET',
                    data: {
                        is_fixed_asset: isChecked ? 1 : 0
                    },
                    success: function(data) {
                        productSelect.empty();
                        productSelect.append(
                            '<option value="" selected disabled>Select Product</option>');

                        $.each(data.products, function(index, product) {
                            productSelect.append('<option value="' + product.id + '" ' + (
                                    oldProductId == product.id ? 'selected' : '') + '>' +
                                product.code + ' - ' + product.product_name + '</option>');
                        });

                        Swal.close();
                    },
                    error: function(error) {
                        console.error('Error fetching products:', error);

                        Swal.close();
                    }
                });
            }

            const oldProductId = '{{ old('product_id') }}';

            fetchProducts(isFixedAssetCheckbox.prop('checked'));

            isFixedAssetCheckbox.change(function() {
                fetchProducts($(this).prop('checked'));
            });
        });
    </script>

    <script>
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
@endsection
<!-- container -->
