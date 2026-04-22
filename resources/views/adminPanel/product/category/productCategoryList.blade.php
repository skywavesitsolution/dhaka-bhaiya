@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <h4 class="page-title">Product Category List</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn" style="background-color: black; color:white;"
                                        data-bs-toggle="modal" data-bs-target="#standard-modal"><i
                                            class="mdi mdi-plus-circle me-2"></i>Add New Category</button>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#cat-transfer-modal"><i class="mdi mdi-transfer me-2"></i>Transfer
                                        Products</button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead style="background-color: black; color:white;">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Category Image</th>
                                        <th>Category Name</th>
                                        <th>Created By</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($productCategories)
                                        @foreach ($productCategories as $productCategory)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration ?? '--' }}
                                                </td>
                                                <td>
                                                    @if ($productCategory->hasMedia('category_images'))
                                                        @php
                                                            $media = $productCategory->getFirstMedia('category_images');
                                                            // $imageUrl = str_replace('storage/', 'public/storage/', $media->getFullUrl());
                                                            $imageUrl = $media->getFullUrl();
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="Category Image" width="60"
                                                            height="60" style="object-fit: cover; border-radius:6px;">
                                                    @else
                                                        <p>No Image Available</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $productCategory->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productCategory->user->name ?? '--' }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" class="action-icon text-dark"
                                                        data-id="{{ $productCategory->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal"> <i
                                                            class="mdi mdi-square-edit-outline"></i></a>
                                                    <a href="javascript:void(0)" class="action-icon text-danger cat-del-btn"
                                                        data-id="{{ $productCategory->id }}"><i class="mdi mdi-delete"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $productCategories->links() !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>

    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Product Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ URL::to('product-category/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Category Name">
                                    @error('name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- Image Upload Field -->
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Category Image</label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/*"
                                    onchange="previewImage(event)">
                                @error('image')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Image Preview -->
                            <div class="col-md-12 mb-3">
                                <div>
                                    <img id="imagePreview" src="#" alt="Image Preview"
                                        style="max-width: 30%; height: auto; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn" style="background-color: black; color:white;">Save
                            changes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- edit modal -->
    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-modalLabel">Edit Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ URL::to('/product-category/update-category') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="productCategoryId" id="category-id-field">
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="edit_category_name" class="form-label">Category Name</label>
                                    <input type="text" id="edit_category_name" name="name" class="form-control">
                                    @error('name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Old Image Display -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Current Category Image</label>
                                <div>
                                    <img id="oldImagePreview" src="#" alt="Current Image"
                                        style="max-width: 30%; height: auto;">
                                </div>
                            </div>

                            <!-- New Image Upload Field -->
                            <div class="col-md-12 mb-3">
                                <label for="new_image" class="form-label">Change Category Image</label>
                                <input type="file" id="new_image" name="image" class="form-control"
                                    accept="image/*" onchange="previewNewImage(event)">
                                @error('image')
                                    <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Image Preview -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">New Image Preview</label>
                                <div>
                                    <img id="newImagePreview" src="#" alt="New Image Preview"
                                        style="max-width: 30%; height: auto; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn"
                            style="background-color: black; color:white;">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div id="cat-transfer-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="cat-transfer-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cat-transfer-modalLabel">Transfer Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ URL::to('product-category/change-product-category') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <label for="from_category_id" class="form-label">From Category<span class="text-danger">
                                        *</span></label>
                                <select id="from_category_id" name="from_category_id" class="form-control" required>
                                    @foreach ($allproductCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <label for="to_category_id" class="form-label">To Category<span class="text-danger">
                                        *</span></label>
                                <select id="to_category_id" name="to_category_id" class="form-control select2"
                                    data-toggle="select2" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($allproductCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn" style="background-color: black; color:white;">Save
                            changes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.cat-del-btn', function() {
                var categoryId = $(this).data('id');
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
                            url: "{{ route('product-category.destroy', ':id') }}".replace(
                                ':id', categoryId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire(
                                        'Deleted!',
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
                                    'An error occurred while deleting the category.',
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
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            $.ajax({
                url: 'product-category/get-category/' + id,
                method: 'GET',
                success: function(data) {
                    console.log(data);

                    if (data && data.data) {
                        $('#category-id-field').val(data.data.id);
                        $('#edit_category_name').val(data.data.name);

                        const oldImagePreview = document.getElementById('oldImagePreview');
                        if (data.image_url) {
                            oldImagePreview.src = data.image_url;
                            oldImagePreview.style.display = 'block';
                        } else {
                            oldImagePreview.style.display = 'none';
                        }
                    } else {
                        console.error('No data received');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                }
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

        // Preview new image
        function previewNewImage(event) {
            const newImagePreview = document.getElementById('newImagePreview');
            const file = event.target.files[0];

            if (file) {
                newImagePreview.src = URL.createObjectURL(file);
                newImagePreview.style.display = 'block';
            } else {
                newImagePreview.style.display = 'none';
            }
        }
    </script>
@endsection
<!-- container -->
