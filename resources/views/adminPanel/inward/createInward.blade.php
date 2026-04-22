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
                    <h4 class="page-title">Create Inward</h4>


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
                                <h4 class="page-title"><a href="{{ route('inward.index') }}" type="button"
                                        class="btn btn-danger"><i class="mdi  mdi-subdirectory-arrow-left"></i> Inward
                                        list</a></h4>

                            </div>
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>

                        <form id="inwardProductForm" action="{{ route('inward.store') }}" method="POST">
                            @csrf
                            <div class="row mt-4">
                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="inward_date" class="form-label">Inward Date</label>
                                        <input type="date" name="inward_date" id="inward_date" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="inward_time" class="form-label">Inward Time</label>
                                        <input type="time" name="inward_time" id="inward_time"class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label for="supplier_id" class="form-label">Supplier Name</label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select2"
                                            data-toggle="select2" onchange="saveTableToLocalStorage()">
                                            <option value="" disabled selected>Select Supplier</option>
                                            @foreach ($allSuppliers as $allSuppliers)
                                                <option value="{{ $allSuppliers->id }}">{{ $allSuppliers->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1 mt-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">
                                        +
                                    </button>
                                </div>

                                <div class="col-sm-2">
                                    <div class="mb-3">
                                        <label for="inward_total_qty" class="form-label">Inward Total Qty</label>
                                        <input type="number" value="0" name="inward_total_qty" id="inward_total_qty"
                                            readonly class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" name="product_varaint_name" placeholder="Enter Product Name Here"
                                        autocomplete="off" id="product_varaint_name" class="form-control">
                                    <ul id="product_suggestions" class="list-group" style="display: none;"></ul>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <div class="col-md-12 text-sm-end">
                                    <button id="clearTableFields" class="btn btn-danger mb-2 text-sm-end"
                                        style="display:none;">Clear Table Fields</button>
                                </div>
                                <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Product Name</th>
                                            <th>QTY</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product_table_body">
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" id="submit_form"
                                class="btn btn-success position-fixed bottom-0 end-0 m-3" style="display:none;">Save
                                Changes</button>
                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add New Supplier</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form id="addSupplierForm">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Party Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Type</label>
                                        <input type="text" class="form-control" name="type" readonly
                                            value="Supplier"></input>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Email</label>
                                        <input type="text" name="email" class="form-control"
                                            placeholder="Party Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="text" name="openingBalance" value="0" class="form-control"
                                            placeholder="Opening Balance">
                                        @error('openingBalance')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Company Name
                                            <span>Optional</span></label>
                                        <input type="text" name="company_name" class="form-control"
                                            placeholder="Company Name ">
                                        @error('company_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Party Address">
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateInput = document.getElementById('inward_date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            dateInput.max = today;

            const timeInput = document.getElementById('inward_time');
            const now = new Date();
            const currentTime = now.toTimeString().split(':').slice(0, 2).join(':');
            timeInput.value = currentTime;
            timeInput.addEventListener('input', function() {
                const selectedTime = timeInput.value;
                const currentFullTime = now.toTimeString().slice(0, 5);
                if (selectedTime > currentFullTime && now.toDateString() === new Date().toDateString()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Time',
                        text: 'Future time is not allowed!',
                    }).then(() => {
                        timeInput.value = currentTime;
                    });
                }
            });
        });
    </script>

    <script>
        let selectedIndex = -1;

        $('#product_varaint_name').on('input', function() {
            fetchProductSuggestions();
        });

        $('#product_varaint_name').on('keydown', function(e) {
            const suggestionBox = $('#product_suggestions');
            const suggestionItems = suggestionBox.find('li');
            if (suggestionItems.length === 0) return;

            if (e.key === 'ArrowDown') {
                selectedIndex = Math.min(selectedIndex + 1, suggestionItems.length - 1);
                updateSuggestionHighlight(suggestionItems);
                scrollToVisible(suggestionItems, selectedIndex);
                updateInputField(suggestionItems.eq(selectedIndex));
            } else if (e.key === 'ArrowUp') {
                selectedIndex = Math.max(selectedIndex - 1, 0);
                updateSuggestionHighlight(suggestionItems);
                scrollToVisible(suggestionItems, selectedIndex);
                updateInputField(suggestionItems.eq(selectedIndex));
            } else if (e.key === 'Enter') {
                e.preventDefault();

                if (selectedIndex === -1) {
                    addProductToTable($('#product_varaint_name').val().trim());
                } else {
                    if (selectedIndex >= 0 && selectedIndex < suggestionItems.length) {
                        const selectedProduct = suggestionItems.eq(selectedIndex);
                        selectProduct(selectedProduct.data('product-name'));
                    }
                }
            }
        });

        function fetchProductSuggestions() {
            const productNameInput = $('#product_varaint_name').val().trim();
            const suggestionBox = $('#product_suggestions');

            if (productNameInput === '') {
                suggestionBox.hide();
                return;
            }

            $.ajax({
                url: '{{ url('/inward/get-product-variants') }}',
                type: 'GET',
                data: {
                    query: productNameInput
                },
                success: function(response) {
                    if (response.length > 0) {
                        suggestionBox.empty();
                        response.forEach(product => {
                            suggestionBox.append(
                                `<li class="list-group-item" data-product-name="${product.product_variant_name}" onclick="selectProduct('${product.product_variant_name}')">
                                ${product.code} - ${product.product_variant_name}
                            </li>`
                            );
                        });

                        suggestionBox.css({
                            'max-height': '200px',
                            'overflow-y': 'auto'
                        });
                        suggestionBox.show();
                    } else {
                        suggestionBox.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        function selectProduct(productName) {
            $('#product_varaint_name').val(productName);
            addProductToTable(productName);
            $('#product_suggestions').hide();
            selectedIndex = -1;
        }

        function addProductToTable(productName) {
            if (!productName) return;

            const tableBody = $('#product_table_body');
            const newRow =
                `<tr>
                <td>${tableBody.children().length + 1}</td>
                <td>${productName}
                    <input type="hidden" name="product_variant_name[]" value="${productName}" class="form-control product-qty" required>
                </td>
                <td><input type="number" name="qty[]" value="1" oninput="saveTableToLocalStorage()" class="form-control product-qty" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)"><i class="mdi mdi-minus-thick"></i></button>
                </td>
            </tr>`;
            tableBody.append(newRow);
            $('#product_varaint_name').val('');
            $('#product_suggestions').hide();
            updateTotalQty();
            saveTableToLocalStorage(); // Save to local storage
            selectedIndex = -1;
        }


        function removeProductRow(button) {
            $(button).closest('tr').remove();
            updateTotalQty();
            updateRowNumbers();
            saveTableToLocalStorage();
        }

        function updateTotalQty() {
            let totalQty = 0;
            $('.product-qty').each(function() {
                totalQty += parseInt($(this).val()) || 0;
            });
            $('#inward_total_qty').val(totalQty);

            if ($('#product_table_body tr').length > 0) {
                $('#clearTableFields').show();
                $('#submit_form').show();
            } else {
                $('#clearTableFields').hide();
                $('#submit_form').hide();
            }
        }

        function saveTableToLocalStorage() {
            const supplier_id = $('#supplier_id').val();
            const tableData = [];
            $('#product_table_body tr').each(function() {
                const row = $(this);
                const productName = row.find('input[name="product_variant_name[]"]').val();
                const qty = row.find('input[name="qty[]"]').val();

                console.log(`Saving Row - Product: ${productName}, Qty: ${qty}`);
                tableData.push({
                    productName,
                    qty
                });
            });

            const dataToSave = {
                supplier_id: supplier_id,
                products: tableData
            };
            console.log('Supplier ID:', supplier_id, 'Type:', typeof supplier_id);
            console.log('Final Table Data to Save:', dataToSave);

            localStorage.setItem('inwardProducts', JSON.stringify(dataToSave));
        }
        $('#product_table_body').on('input', 'input[name="qty[]"]', function() {
            saveTableToLocalStorage();
        });


        function loadTableFromLocalStorage() {
            const dataToSave = JSON.parse(localStorage.getItem('inwardProducts')) || [];
            const tableData = dataToSave.products || [];
            const supplier_id = dataToSave.supplier_id || '';

            $('#supplier_id').val(supplier_id).trigger('change');
            console.log('Loaded Table Data:', dataToSave);
            const tableBody = $('#product_table_body');

            tableBody.empty();
            tableData.forEach((item, index) => {
                const newRow =
                    `<tr>
                    <td>${index + 1}</td>
                    <td>${item.productName}
                        <input type="hidden" name="product_variant_name[]" value="${item.productName}" class="form-control product-qty" required>
                    </td>
                    <td><input type="number" name="qty[]" value="${item.qty}" class="form-control product-qty" required></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)"><i class="mdi mdi-minus-thick"></i></button>
                    </td>
                </tr>`;
                tableBody.append(newRow);
            });

            updateTotalQty();
        }


        function clearTableData() {
            $('#product_table_body').empty();
            updateTotalQty();
            localStorage.removeItem('inwardProducts'); // Clear local storage
            $('#clearTableFields').hide();
            $('#submit_form').hide();
        }

        $(document).on('input', '.product-qty', function() {
            const qty = parseInt($(this).val());
            if (qty < 1 || isNaN(qty)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Quantity cannot be zero or negative!'
                });
                $(this).val(1);
            }
            updateTotalQty();
        });

        function updateRowNumbers() {
            $('#product_table_body tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#clearTableFields').on('click', function(e) {
            e.preventDefault();
            const isClearSelected = $(this).text().trim() === 'Clear Selected Fields';
            const message = isClearSelected ? 'Are you sure to clear the selected fields?' :
                'Are you sure to clear the entire table?';

            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (isClearSelected) {
                        $('input[name="select_row"]:checked').each(function() {
                            $(this).closest('tr').remove();
                        });
                        updateRowNumbers();
                        updateTotalQty();
                    } else {
                        clearTableData(); // Clear both table and local storage
                    }
                    $(this).text('Clear Table Fields');
                }
            });
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission on enter key
            }
        });

        $(document).ready(function() {
            loadTableFromLocalStorage();
        });


        $('head').append('<style>.highlight { background-color: #007bff; color: white; }</style>');

        $(document).on('keydown', '.product-qty', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                const currentRow = $(this).closest('tr');
                const nextRow = currentRow.next('tr');
                if (nextRow.length) {
                    nextRow.find('.product-qty').focus();
                } else {}
            }
        });


        $('#inwardProductForm').on('submit', function(e) {
            e.preventDefault();

            let supplierId = $('#supplier_id').val();
            if (!supplierId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a supplier before submitting the form.',
                });
                return;
            }

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
                            Swal.fire('Success', 'The products have been successfully added!',
                                'success').then(() => {
                                location.reload();
                            });
                            clearTableData();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Something went wrong. Please try again.',
                                'error');
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#addSupplierForm').on('submit', function(e) {
                e.preventDefault();



                let formData = $(this).serialize();

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('add-party-wi-ajax') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.close();

                        if (!response.error) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });

                            $('#standard-modal').modal('hide');
                            $('#addSupplierForm')[0].reset();

                            let supplierSelect = $('#supplier_id');
                            supplierSelect.append(
                                `<option value="${response.newSupplier.id}">${response.newSupplier.name}</option>`
                            );

                            supplierSelect.select2();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '\n';
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessage,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.',
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
<!-- container -->
