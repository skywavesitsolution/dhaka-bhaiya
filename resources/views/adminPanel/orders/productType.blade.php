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
                <div class="page-title-box">

                    <h4 class="page-title">Product</h4>
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
                                Product List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">Add New Product</button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive ">
                            <table id="scroll-horizontal-datatable" class="table table-sm table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>ID</th>
                                        <th>code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Opening stock</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($productTypes)
                                        @foreach ($productTypes as $productType)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $productType->product_code ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productType->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productType->category->category_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productType->measuringUnit->unit_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productType->opening_stock ?? '--' }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" data-id="{{ $productType->id }}"
                                                        class="action-icon text-success" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal"> <i
                                                            class="mdi mdi-square-edit-outline"></i></a>
                                                    <a href="{{ route('product.delete', $productType->id) }}"><i
                                                            class="mdi mdi-trash-can-outline"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-product-type') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="product_code" class="mb-2">Product Code</label>
                                    <input type="text" name="product_code" id="product_code"
                                        class="form-control @error('product_code') is-invalid @enderror"
                                        placeholder="Add product" value="{{ $nextProductCode }}" readonly>
                                    @error('product_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="email" class="mb-2">Product Name</label>
                                    <input type="text" name="product_name" id="product_name"
                                        class="form-control @error('product_name') is-invalid @enderror"
                                        placeholder="Add product"value="{{ old('product_name') }}">
                                    @error('product_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="item_type" class="mb-2">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="" disabled selected>Select a category</option>
                                        @foreach ($categoies as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-lg-6">
                                    <label for="measuring_unit" class="mb-2">Measuring Unit</label>
                                    <select name="measuring_unit_id" id="measuring_unit_id" class="form-control">
                                        <option value="" disabled selected>Select unit</option>
                                        @foreach ($measuring_units as $measuring_unit)
                                            <option value="{{ $measuring_unit->id }}">{{ $measuring_unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('measuring_unit_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-lg-4">
                                    <label for="item_type" class="mb-2">Opening Stock</label>
                                    <input type="number" name="opening_stock" id="opening_stock"
                                        class="form-control @error('opening_stock') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('opening_stock') }}">
                                    @error('opening_stock')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label for="cost_price" class="mb-2">cost price</label>
                                    <input type="number" name="cost_price" id="cost_price"
                                        class="form-control @error('cost_price') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('cost_price') }}">
                                    @error('cost_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-4">
                                    <label for="retail_price" class="mb-2">Retail Price</label>
                                    <input type="number" name="retail_price" id="retail_price"
                                        class="form-control @error('retail_price') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('retail_price') }}">
                                    @error('retail_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
        </div><!-- /.modal -->




        <!-- edit modal -->
        <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modalLabel">Edit Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ route('update.product') }}" method="post">
                        @csrf
                        <input type="hidden" name="productId" id="product-id-field">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label for="product_code" class="mb-2">Product Code</label>
                                    <input type="text" name="product_code" id="code"
                                        class="form-control @error('product_code') is-invalid @enderror"
                                        placeholder="Add product" readonly>
                                    @error('product_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="name" class="mb-2">Product Name</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Add product">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="item_type" class="mb-2">Category</label>
                                    <select name="category" id="cat" class="form-control">
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach ($categoies as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label for="edit_measuring_unit" class="mb-2">Unit</label>
                                    <select name="measuring_unit_id" id="edit_measuring_unit" class="form-control">
                                        <option value="" disabled selected>Select unit</option>
                                        @foreach ($measuring_units as $measuring_unit)
                                            <option value="{{ $measuring_unit->id }}">{{ $measuring_unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">

                                <div class="mb-3 col-lg-4">
                                    <label for="item_type" class="mb-2">Opening Stock</label>
                                    <input type="number" name="opening_stock" id="stock"
                                        class="form-control @error('opening_stock') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('opening_stock') }}">
                                    @error('opening_stock')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label for="cost_price" class="mb-2">cost price</label>
                                    <input type="number" name="cost_price" id="cost"
                                        class="form-control @error('cost_price') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('cost_price') }}">
                                    @error('cost_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-4">
                                    <label for="retail_price" class="mb-2">Retail Price</label>
                                    <input type="number" name="retail_price" id="retail"
                                        class="form-control @error('retail_price') is-invalid @enderror"
                                        placeholder="Enter type" value="{{ old('retail_price') }}">
                                    @error('retail_price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        console.log('page is load now');
    </script>


    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var product = button.data('id');
            $(this).find('#product-id-field').val(product);
            $.ajax({
                type: 'GET',
                url: 'get-products/' + product,
            }).done(function(data) {
                $('#code').val(data.data.product_code);
                $('#name').val(data.data.name);
                $('#cat').val(data.data.category_id);
                $('#edit_measuring_unit').val(data.data.measuring_unit_id);
                $('#stock').val(data.data.opening_stock);
                $('#cost').val(data.data.cost_price);
                $('#retail').val(data.data.retail_price);
                $('#edit-modal').modal('show');
            });
        });
    </script>
@endsection
<!-- container -->
