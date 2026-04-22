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
                    {{-- <h4 class="page-title">Transfer Stock</h4> --}}
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
                                <h4 class="page-title"><a href="{{ route('stock-transfer.index') }}" type="button"
                                        class="btn btn-warning"><i class="mdi  mdi-subdirectory-arrow-left"></i> Tansferd
                                        Stock list</a></h4>

                            </div>
                            <div class="col-sm-7 text-end">
                                <button id="clearTableFields" class="btn btn-danger mb-2 text-sm-end">Refresh Page <i
                                        class="mdi mdi-close-box-multiple-outline"></i></button>
                            </div><!-- end col-->
                        </div>

                        <form id="stockTransferFrom" action="{{ route('stock-transfer.store') }}" method="POST">
                            @csrf
                            <div class="row mt-4">
                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="stock_transfer_date" class="form-label">Transfer Date</label>
                                        <input type="date" name="stock_transfer_date" id="stock_transfer_date"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="stock_transfer_time" class="form-label">Transfer Time</label>
                                        <input type="time" name="stock_transfer_time"
                                            id="stock_transfer_time"class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label for="from_location_id" class="form-label">From Location</label>
                                        <select id="from_location_id" name="from_location_id" class="form-control select2"
                                            data-toggle="select2">
                                            <option value="" disabled selected>Select Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" readonly name="from_location_id" id="set_from_location_id">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="mb-3">
                                        <label for="transfer_stock_total_qty" class="form-label">Total Stock Transfer
                                            QTY</label>
                                        <input type="number" value="0" name="transfer_stock_total_qty"
                                            id="transfer_stock_total_qty" readonly class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <div class="mb-3">
                                        <label for="product_variant_id" class="form-label">Product Variant</label>
                                        <select id="product_variant_id" name="product_variant_id"
                                            class="form-control select2" data-toggle="select2">
                                            <option value="" disabled selected>Choose Location First</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                    <thead style="background-color: black; color:white;">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Product Name</th>
                                            <th>To Location</th>
                                            <th>Current Stock</th>
                                            <th>Stock Transfer QTY</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product_table_body">
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" id="submit_transfer_stock_form"
                                class="btn position-fixed bottom-0 end-0 m-3"
                                style="background-color: black; color:white; display:none;">Save Changes</button>
                        </form>
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
        document.addEventListener("DOMContentLoaded", function() {
            const dateInput = document.getElementById('stock_transfer_date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            dateInput.max = today;

            const timeInput = document.getElementById('stock_transfer_time');
            const now = new Date();
            const currentTime = now.toTimeString().split(':').slice(0, 2).join(':');
            timeInput.value = currentTime;
            timeInput.addEventListener('input', function() {
                const selectedTime = timeInput.value;
                const currentFullTime = now.toTimeString().slice(0, 5);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#from_location_id').change(function() {
                var locationId = $(this).val();

                if (locationId) {
                    $('#set_from_location_id').val(locationId);

                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while the product variants are loading.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route('stock-transfer.get.product.variants.by.location') }}',
                        type: 'GET',
                        data: {
                            location_id: locationId
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.product_variants.length === 0) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'No Variants Found',
                                    text: 'No product variants found for the selected location.',
                                });
                            } else {
                                console.log("Variants fetched successfully:", response);

                                $('#product_variant_id').empty();

                                $('#product_variant_id').append(
                                    '<option value="" disabled selected>Select Product Variant</option>'
                                    );

                                $.each(response.product_variants, function(key, variant) {
                                    let optionText = variant.product_variant.code +
                                        ' - ' + variant.product_variant
                                        .product_variant_name;
                                    $('#product_variant_id').append('<option value="' +
                                        variant.product_variant.id +
                                        '" data-stock="' + variant.stock_qty +
                                        '" data-location-id="' + variant
                                        .location_id + '">' + optionText +
                                        '</option>');
                                });

                                $('#product_variant_id').trigger('change');
                                $('#from_location_id').prop('disabled', true);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            console.error("Error fetching variants:", error);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch product variants. Please try again later.',
                            });

                            $('#from_location_id').prop('disabled', false);
                        }
                    });

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Location Selected',
                        text: 'Please select a location to load product variants.',
                    });
                }
            });

            $('#product_variant_id').change(function() {
                var selectedVariant = $(this).find('option:selected');
                var variantStock = selectedVariant.data('stock');
                var locationId = selectedVariant.data('location-id');
                var selectedVariantId = selectedVariant.val();

                var existingVariant = $('#product_table_body').find('tr').filter(function() {
                    return $(this).data('variant-id') == selectedVariantId;
                });

                if (existingVariant.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Variant Already Selected',
                        text: 'This product variant has already been added to the table.',
                    });
                    return;
                }
                if (variantStock == 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Variant Stock is 0',
                        text: 'This product variant have 0 Stock in this location.',
                    });
                    return;
                }

                if (selectedVariant.val()) {
                    var row = `
                    <tr data-variant-id="${selectedVariantId}">
                        <td>1</td>
                        <td>${selectedVariant.text()}
                            <input type="hidden" readonly value="${selectedVariantId}" name="product_variant_id[]"/>
                            <input type="hidden" readonly value="${locationId}" name="location_id[]"/>
                            </td>
                        <td>
                            <select class="form-control select2" name="to_location[]" data-toggle="select2">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" ${locationId == {{ $location->id }} ? 'selected' : ''}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="current_stock[]" value="${variantStock}" readonly />
                             </td>
                        <td><input type="number" name="transfer_stock[]" class="form-control transfer-qty" value="0" min="0" /></td>
                        <td><button type="button" class="btn btn-danger remove-row">-</button></td>
                    </tr>
                `;

                    $('#product_table_body').append(row);

                    $('#submit_transfer_stock_form').show();

                    $('#clearTableFields').show();

                    updateTotalQuantity();
                }
            });

            $(document).on('input', '.transfer-qty', function() {
                var currentStock = $(this).closest('tr').find('input[type="text"]').val();
                var transferQty = $(this).val();

                if (parseInt(transferQty) > parseInt(currentStock)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Quantity',
                        text: 'Transfer quantity cannot exceed the current stock.',
                    });

                    $(this).val(0);
                }

                updateTotalQuantity();
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();

                if ($('#product_table_body tr').length === 0) {
                    $('#submit_transfer_stock_form').hide();
                    $('#clearTableFields').hide();
                }

                updateTotalQuantity();
            });

            $('form').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                }
            });

            $('#clearTableFields').click(function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'You are about to clear all data in this page.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, clear it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });

            function updateTotalQuantity() {
                var totalQty = 0;

                $('.transfer-qty').each(function() {
                    totalQty += parseInt($(this).val()) || 0;
                });

                $('#transfer_stock_total_qty').val(totalQty);
            }

            $('#stockTransferFrom').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to submit the form?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while we process your request.',
                            icon: 'info',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: $(this).attr('action'),
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.close();
                                if (response.success) {
                                    Swal.fire('Success', response.message, 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                                clearTableData();
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.responseJSON && xhr.responseJSON
                                    .message ? xhr.responseJSON.message :
                                    'Something went wrong. Please try again.';
                                Swal.fire('Error', errorMessage, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
<!-- container -->
