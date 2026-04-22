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
                    <h4 class="page-title">Import Products</h4>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Import Product CSV</h5>
                    </div>
                    <div class="card-body">
                        <form id="importProductForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 justify-content-center">
                                <div class="col-md-8">
                                    <label for="productFile" class="form-label fw-bold">Upload CSV File <span
                                            class="text-danger">( Only CSV File Excepted )</span></label>
                                    <input type="file" class="form-control" id="productFile" name="product_file"
                                        required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4 py-2">
                                    <i class="mdi mdi-cloud-upload-outline me-2"></i>Import Products
                                </button>
                            </div>
                        </form>
                    </div>
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
                                <h4 class="page-title">Import Product Instructions</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('product-import.downloadExampleFormat') }}" class="btn btn-warning"><i
                                            class="mdi mdi-cloud-download-outline me-2"></i>Download Example Format</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Column Name</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($productColumnsWithDetails)
                                        @foreach ($productColumnsWithDetails as $column)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $column['name'] }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $column['status'] == 'Optional' ? 'bg-danger' : 'bg-success' }}">
                                                        {{ $column['status'] }}
                                                    </span>
                                                </td>
                                                <td class="text-wrap">{{ $column['description'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <h4 class="page-title">Import Product Variant Instructions</h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Column Name</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($allVariantColumnsWithDetails)
                                        @foreach ($allVariantColumnsWithDetails as $column)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $column['name'] }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $column['status'] == 'Optional' ? 'bg-danger' : 'bg-success' }}">
                                                        {{ $column['status'] }}
                                                    </span>
                                                </td>
                                                <td class="text-wrap">{{ $column['description'] }}</td>
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

        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#importProductForm').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to import these products?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, import it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Importing products...',
                            icon: 'info',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        $.ajax({
                            url: '{{ route('product-import.import') }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire(
                                    'Imported!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload(); // Reload the page
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message ||
                                    'An error occurred while importing the products.',
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
@endsection
<!-- container -->
