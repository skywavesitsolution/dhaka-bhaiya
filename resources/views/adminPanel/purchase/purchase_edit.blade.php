@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .form-control-sm {
            font-size: 12px;
            padding: 4px;
            height: auto;
        }


        #product-table {
            width: 100%;
            border-collapse: collapse;
        }

        #product-table th,
        #product-table td {
            text-align: center;
            padding: 8px;
            vertical-align: middle;
            box-sizing: border-box;
        }

        #product-table th {
            background-color: #f8f9fa;
            /* Light background for table header */
        }

        /* Adjust column widths */
        #product-table td:nth-child(1),
        /* ID */
        #product-table td:nth-child(2),
        /* Name */
        #product-table td:nth-child(3),
        /* Color */
        #product-table td:nth-child(4) {
            /* Stock */
            width: 12%;
            /* Slightly increased width */
        }

        #product-table td:nth-child(5),
        /* Cost Price */
        #product-table td:nth-child(6),
        /* Qty */
        #product-table td:nth-child(7) {
            /* Total */
            width: 18%;
            /* Adjusted larger width for input fields */
        }

        #product-table td:nth-child(8) {
            width: 90px;
            /* Fixed width for action buttons */
        }

        /* Style for input fields */
        #product-table input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ced4da;
            /* Visible border for clarity */
            border-radius: 4px;
            /* Rounded corners for better UI */
            outline: none;
            background: #fff;
            box-shadow: none;
            /* Remove default shadowing */
        }

        /* Hover effect for inputs */
        #product-table input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Ensure table responsiveness */
        .table-responsive {
            margin: 10px 0;
            overflow-x: auto;
        }

        .table-centered th,
        .table-centered td {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <h4 class="page-title">Edit Purchase</h4>
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
                                Edit Purchase
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('purchase.list') }}" type="button" class="btn btn-warning">Purchase
                                        list</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="form">
                            <form id="" action="{{ route('purchase.update') }}" method="post">
                                @csrf
                                <input type="hidden" id="purchase-id-field" name="purchaseId"
                                    value="{{ $purchase->id }}" />
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-3">
                                            <label for="date" class="mb-2">Received Date</label>
                                            <input type="date" name="received_date" id="received_date"
                                                class="form-control form-control-sm @error('received_date') is-invalid @enderror"
                                                placeholder="Add date" value="{{ $purchase->received_date }}">
                                            @error('received_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-sm-3">
                                            <label for="date" class="mb-2">Due Date</label>
                                            <input type="date" name="date" id="date"
                                                class="form-control form-control-sm @error('date') is-invalid @enderror"
                                                placeholder="Add date" value="{{ $purchase->due_date }}">
                                            @error('date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                        <div class="mb-3 col-sm-3">
                                            <label for="supplier_name" class="mb-2">Supplier name</label>
                                            <select name="supplier_name" id="supplier_name"
                                                class="form-control form-control-sm @error('supplier_name') is-invalid @enderror"
                                                value="{{ old('supplier_name') }}">
                                                <option value="">Select supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ isset($purchase) && $purchase->supplier->id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>

                                                    {{-- <option value="{{ $supplier->id }}">{{ $supplier->name }}</option> --}}
                                                @endforeach
                                            </select>

                                            @error('supplier_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-sm-3">
                                            <label for="supplier_balance" class="mb-2">Supplier Blance</label>
                                            <input type="number" name="supplier_balance" id="supplier_balance"
                                                class="form-control form-control-sm @error('supplier_balance') is-invalid @enderror"
                                                placeholder="0" value="{{ $purchase->supplier_balance }}" readonly>
                                            @error('supplier_balance')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="row">

                                            <div class="mb-3 col-sm-3">
                                                <label for="payment_type" class="mb-2">Payment Type</label>
                                                <select name="payment_type" id="payment_type"
                                                    class="form-control form-control-sm @error('payment_type') is-invalid @enderror">
                                                    <option value="">Select type</option>
                                                    <option value="cash"
                                                        {{ old('payment_type', $purchase->payment_type) == 'cash' ? 'selected' : '' }}>
                                                        cash</option>
                                                    <option value="credit"
                                                        {{ old('payment_type', $purchase->payment_type) == 'credit' ? 'selected' : '' }}>
                                                        credit</option>
                                                    <option value="cash+credit"
                                                        {{ old('payment_type', $purchase->payment_type) == 'cash+credit' ? 'selected' : '' }}>
                                                        cash+credit</option>
                                                </select>

                                                @error('payment_type')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>



                                            <div class="mb-3 col-sm-3 d-none" id="account_name_container">
                                                <label for="account_name" class="mb-2">Account Name</label>
                                                <select name="account_name" id="account_name"
                                                    class="form-control form-control-sm @error('account_name') is-invalid @enderror">
                                                    <option value="">Select account</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}"
                                                            {{ isset($purchase) && $purchase->account_id == $account->id ? 'selected' : '' }}>
                                                            {{ $account->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('account_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-3">
                                                <label for="payment_amount" class="mb-2">Payment Amount</label>
                                                <input type="number" name="payment_amount" id="payment_amount"
                                                    class="form-control form-control-sm @error('payment_amount') is-invalid @enderror"
                                                    placeholder="0" value="{{ $purchase->payment_amount }}">
                                                @error('payment_amount')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="mb-3 col-sm-3">
                                                <label for="total_bill" class="mb-2">Total bill</label>
                                                <input type="number" name="total_bill" id="total_bill"
                                                    class="form-control form-control-sm @error('total_bill') is-invalid @enderror"
                                                    placeholder="0" value="{{ $purchase->total_bill }}" readonly>
                                                @error('total_bill')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>



                                            <div class="mb-3 col-sm-3">
                                                <label for="adjustment" class="mb-2">Adjustment</label>
                                                <input type="number" name="adjustment" id="adjustment"
                                                    class="form-control form-control-sm @error('adjustment') is-invalid @enderror"
                                                    placeholder="0" value="{{ $purchase->adjustment }}">
                                                @error('adjustment')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-sm-3">
                                                <label for="net_payable" class="mb-2">Net Payable</label>
                                                <input type="number" name="net_payable" id="net_payable"
                                                    class="form-control form-control-sm @error('net_payable') is-invalid @enderror"
                                                    placeholder="0" value="{{ $purchase->net_payable }}" readonly>
                                                @error('net_payable')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="row">
                                            {{-- <div class="mb-3 col-lg-10">
                                                <label for="select_product" class="mb-2">Select Product</label>
                                                <select name="select_product" id="select_product"
                                                    class="form-control @error('select_product') is-invalid @enderror"
                                                    value="{{ old('select_product') }}">
                                                    <option value="">Select product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->product_variant_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('select_product')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 d-flex align-self-center justify-content-end">
                                                <button type="button" id="addProduct" class="btn btn-warning">Add
                                                    Product</button>
                                            </div> --}}


                                            <div class="mb-3 col-sm-3" style="max-width: 50%;">
                                                <input type="text" id="product_search"
                                                    class="form-control form-control-sm"
                                                    placeholder="Search product by name or code..."
                                                    onkeyup="searchProduct()" autofocus>
                                                <ul id="product_list" class="list-group"
                                                    style="max-height: 150px;  overflow-y: auto; display: none; position: absolute; z-index: 1000; width: 50%; background: #fff; border: 1px solid #ccc;">
                                                    <!-- Search results will appear here -->
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table id="product-table" class="table table-sm table-centered w-100 nowrap">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        {{-- <th>Color</th> --}}
                                                        <th>Stock</th>
                                                        <th>Cost Price</th>
                                                        <th>Qty</th>
                                                        <th>update Qty</th>
                                                        <th>Total</th>
                                                        <th style="width: 85px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-table-body">
                                                    <!-- Dynamically added products will appear here -->
                                                    @if ($purchase->purchase_details)
                                                        @foreach ($purchase->purchase_details as $detail)
                                                            <tr>
                                                                <td>
                                                                    <input type="number" name="pro_id[]"
                                                                        value="{{ $detail->productVarient->id }}" readonly
                                                                        style="outline: none; border: none; background: transparent; pointer-events: none;" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="pro_name[]"
                                                                        value="{{ $detail->productVarient->product_variant_name }}"
                                                                        readonly
                                                                        style="outline: none; border: none; background: transparent; pointer-events: none;" />

                                                                <td>
                                                                    <input type="number" name="stock[]"
                                                                        value="{{ $detail->stock }}" readonly
                                                                        style="outline: none; border: none; background: transparent; pointer-events: none;" />
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="cost_price[]"
                                                                        value="{{ $detail->cost_price }}"
                                                                        class="form-control cost-price"
                                                                        oninput="calculateTotal({{ $detail->productVarient->id }})"
                                                                        style="outline: none; box-shadow: none; opacity: 0.9;"
                                                                        id="edit_cost_price" />
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="qty[]"
                                                                        value="{{ $detail->qty }}" class="form-control"
                                                                        oninput="calculateTotal({{ $detail->productVarient->id }})"
                                                                        style="outline: none; box-shadow: none; opacity: 0.9;"
                                                                        required readonly />
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="update_qty[]"
                                                                        value="" class="form-control update-qty"
                                                                        oninput="calculateTotal({{ $detail->productVarient->id }})"
                                                                        style="outline: none; box-shadow: none; opacity: 0.9;" />
                                                                </td>

                                                                <td>
                                                                    <input type="number" name="total[]"
                                                                        value="{{ $detail->total }}" class="form-control"
                                                                        readonly
                                                                        style="outline: none; box-shadow: none; opacity: 0.9;" />
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-product"
                                                                        data-id="{{ $detail->productVarient->id }}">Remove</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="hidden-product-data">
                                            <!-- Hidden inputs will be appended here -->
                                        </div>

                                        <div>
                                            <button type="submit" class="btn btn-warning"
                                                style="float: right;">Submit</button>
                                        </div>
                                    </div>
                            </form>






                            <!-- Standard modal for edit product -->
                            <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog"
                                aria-labelledby="edit-modalLabel" aria-hidden="true">
                                <div class="modal-dialog "> <!-- Use modal-xl for larger modal width -->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="edit-modalLabel">Edit Product</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-hidden="true"></button>
                                        </div>
                                        <form action="" method="post">
                                            @csrf
                                            <input type="hidden" name="productId" id="product-id-field">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-lg-4">
                                                        <label for="product_code" class="mb-2">Product Code</label>
                                                        <input type="number" name="product_code" id="product_code"
                                                            class="form-control @error('product_code') is-invalid @enderror"
                                                            placeholder="Add product">
                                                        @error('product_code')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-lg-4">
                                                        <label for="email" class="mb-2">Product Name</label>
                                                        <input type="text" name="product_name" id="product_name"
                                                            class="form-control @error('product_name') is-invalid @enderror"
                                                            placeholder="Add product">
                                                        @error('product_name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-lg-4">
                                                        <label for="item_type" class="mb-2">Opening Stock</label>
                                                        <input type="number" name="opening_stock" id="opening_stock"
                                                            class="form-control @error('opening_stock') is-invalid @enderror"
                                                            placeholder="Enter type">
                                                        @error('opening_stock')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

















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
                                    // console.log('Button clicked!');
                                    var button = $(event.relatedTarget);
                                    var product = button.data('id');
                                    $(this).find('#product-id-field').val(product);
                                    $.ajax({
                                        type: 'GET',
                                        url: 'get-product/' + product,
                                    }).done(function(data) {
                                        $('#product_code').val(data.data.product_code);
                                        $('#product_name').val(data.data.product_name);
                                        $('#opening_stock').val(data.data.opening_stock);
                                        $('#edit-modal').modal('show');
                                    });
                                });
                            </script>

                            {{-- <script>
    // Set today's date as the value for the date input
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        dateInput.value = today;
    });
</script> --}}

                            <script>
                                function addToCart(productId) {
                                    // Check if the product is already in the table
                                    if ($(`#product-table-body input[name="pro_id[]"][value="${productId}"]`).length > 0) {
                                        alert('This product has already been added.');
                                        return; // Exit if the product already exists
                                    }

                                    // AJAX request to fetch product details
                                    $.ajax({
                                        url: "{{ route('purchase.fetch.product.details', '') }}/" +
                                            productId, // Dynamically add the product ID to the URL
                                        method: 'GET', // Use GET method
                                        data: {
                                            _token: '{{ csrf_token() }}' // CSRF token for security
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                const product = response.data;

                                                // Append a new row with product details
                                                $('#product-table-body').append(`
                    <tr>
                        <td><input type="number" name="pro_id[]" value="${product.id}" readonly style="outline: none; border: none; background: transparent; pointer-events: none;" /></td>
                        <td><input type="text" name="pro_name[]" value="${product.name}" readonly style="outline: none; border: none; background: transparent; pointer-events: none;" /></td>

                        <td><input type="number" name="stock[]" value="${product.stock}" readonly style="outline: none; border: none; background: transparent; pointer-events: none;" /></td>
                        <td><input type="number" name="cost_price[]" id="cost_price_${product.id}" value="${product.cost_price}" class="form-control" oninput="calculateTotal(${product.id})" style="outline: none; box-shadow: none; opacity: 0.9;" /></td>
                        <td><input type="number" name="qty[]" id="quantity_${product.id}" value="" class="form-control" oninput="calculateTotal(${product.id})" style="outline: none; box-shadow: none; opacity: 0.9;" required /></td>
                        <td><input type="number" name="total[]" id="total_${product.id}" value="" class="form-control" readonly style="outline: none; box-shadow: none; opacity: 0.9;" /></td>
                        <td><button type="button" class="btn btn-danger remove-btn">Remove</button></td>
                    </tr>
                `);
                                            } else {
                                                alert('Product not found.');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('AJAX Error:', error);
                                            alert('An error occurred. Please try again.');
                                        }
                                    });
                                }






                                $('#product-table-body').on('input', '.cost-price, .update-qty', function() {
                                    var row = $(this).closest('tr');
                                    var costPrice = parseFloat(row.find('input[name="cost_price[]"]').val()) || 0;
                                    var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
                                    var updateQty = parseFloat(row.find('input[name="update_qty[]"]').val()) || 0;
                                    var total = 0;

                                    if ($(this).hasClass('cost-price')) {
                                        // If cost price changed, multiply by qty
                                        total = costPrice * qty;
                                    } else if ($(this).hasClass('update-qty')) {
                                        // If update qty changed, multiply by cost price
                                        total = costPrice * updateQty;
                                    }

                                    row.find('input[name="total[]"]').val(total.toFixed(2));
                                    calculateGrandTotal();
                                });

                                function calculateTotal() {
                                    $('#product-table-body tr').each(function() {
                                        const costPrice = parseFloat($(this).find('input[name="cost_price[]"]').val()) || 0;
                                        const qty = parseFloat($(this).find('input[name="qty[]"]').val()) || 0;
                                        const total = costPrice * qty;



                                        $(this).find('input[name="total[]"]').val(total);
                                        calculateGrandTotal();
                                    });
                                }

                                // function calculateTotal(productId) {
                                //      const costPrice = parseFloat($(`#cost_price_${productId}`).val()) || 0;
                                //     const quantity = parseFloat($(`#quantity_${productId}`).val()) || 0;
                                //     const total = costPrice * quantity;

                                //     $(`#total_${productId}`).val(total.toFixed(2));

                                //     calculateGrandTotal();
                                // }

                                function calculateGrandTotal() {
                                    let grandTotal = 0;

                                    $('input[name="total[]"]').each(function() {
                                        grandTotal += parseFloat($(this).val()) || 0;
                                    });

                                    $('#total_bill').val(grandTotal.toFixed(2));
                                    $('#net_payable').val(grandTotal.toFixed(2));
                                }



                                $(document).ready(function() {
                                    // Event listener for total_bill and adjustment input changes
                                    $('#total_bill, #adjustment').on('input', function() {
                                        calculateNetPayable();
                                    });

                                    function calculateNetPayable() {
                                        // Get the values of total_bill and adjustment
                                        const totalBill = parseFloat($('#total_bill').val()) || 0;
                                        const adjustment = parseFloat($('#adjustment').val()) || 0;

                                        // Calculate net payable
                                        const netPayable = totalBill - adjustment;

                                        // Set the value of the net payable field
                                        $('#net_payable').val(netPayable.toFixed(2));
                                    }
                                });



                                $(document).on('click', '.remove-btn', function() {
                                    $(this).closest('tr').remove();
                                    calculateGrandTotal();
                                });

                                $(document).on('click', '.remove-product', function() {
                                    $(this).closest('tr').remove();
                                    calculateGrandTotal(); // Recalculate totals if needed
                                });
                            </script>



                            <!-- JavaScript to toggle visibility -->
                            <script>
                                function toggleAccountField() {
                                    const paymentType = document.getElementById('payment_type').value;
                                    const accountNameContainer = document.getElementById('account_name_container');

                                    if (paymentType === 'cash' || paymentType === 'cash+credit') {
                                        accountNameContainer.classList.remove('d-none'); // Show the account name field
                                    } else {
                                        accountNameContainer.classList.add('d-none'); // Hide the account name field
                                    }
                                }

                                // Run the toggle function on page load
                                document.addEventListener('DOMContentLoaded', toggleAccountField);

                                // Attach the event listener to the payment type dropdown
                                document.getElementById('payment_type').addEventListener('change', toggleAccountField);
                            </script>

                            <script>
                                let currentIndex = -1;

                                function searchProduct() {
                                    const query = document.getElementById('product_search').value.trim();
                                    console.log(query);
                                    const productList = document.getElementById('product_list');

                                    if (query.length < 2) {
                                        productList.style.display = "none";
                                        productList.innerHTML = "";
                                        currentIndex = -1; // Reset the index
                                        return;
                                    }

                                    fetch(`/LatifTraders/fetch-products?query=${encodeURIComponent(query)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.length > 0) {
                                                let listItems = '';
                                                data.forEach((product, index) => {
                                                    listItems += `
                                                <li class="list-group-item" style="cursor: pointer; width: 100%;"
                                                    onclick="selectProduct('${product.id}', '${product.code}', '${product.product_variant_name}')"
                                                    data-index="${index}">
                                                    ${product.code} / ${product.product_variant_name}
                                                </li>`;
                                                });
                                                productList.innerHTML = listItems;
                                                productList.style.display = "block";
                                            } else {
                                                productList.innerHTML = '<li class="list-group-item text-muted">No products found</li>';
                                                productList.style.display = "block";
                                            }
                                        })
                                        .catch(error => console.error('Error fetching products:', error));
                                }

                                function selectProduct(id, code, name) {
                                    const qtyInput = document.querySelector(`#qty${id}`);
                                    if (qtyInput) {
                                        let currentQty = parseInt(qtyInput.value || 0);
                                        if (isReturnMode) {
                                            qtyInput.value = currentQty - 1 > 0 ? currentQty - 1 : 0;
                                        } else {
                                            qtyInput.value = currentQty + 1; // Increment
                                        }

                                        calculateItemTotals(id);
                                    } else {
                                        addToCart(id);

                                    }
                                    document.getElementById('product_search').value = '';
                                    document.getElementById('product_list').style.display = "none";
                                    currentIndex = -1;
                                }



                                document.getElementById('product_search').addEventListener('keydown', (e) => {
                                    const productList = document.getElementById('product_list');
                                    const items = productList.querySelectorAll('.list-group-item');

                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        if (currentIndex >= 0) {
                                            const selectedItem = items[currentIndex];
                                            const id = selectedItem.getAttribute('onclick').match(/'(.*?)'/g)[0].replace(/'/g, '');
                                            const code = selectedItem.getAttribute('onclick').match(/'(.*?)'/g)[1].replace(/'/g, '');
                                            const name = selectedItem.getAttribute('onclick').match(/'(.*?)'/g)[2].replace(/'/g, '');
                                            selectProduct(id, code, name);
                                            document.getElementById('product_list').style.display = "none";

                                        }
                                    } else if (e.key === 'ArrowDown') {
                                        // Move selection down
                                        currentIndex = (currentIndex + 1) % items.length;
                                        highlightItem(items);
                                    } else if (e.key === 'ArrowUp') {
                                        // Move selection up
                                        currentIndex = (currentIndex - 1 + items.length) % items.length;
                                        highlightItem(items);
                                    }
                                });


                                function highlightItem(items) {
                                    // Clear the active class from all items except the current one
                                    items.forEach((item, index) => {
                                        if (index !== currentIndex) {
                                            item.classList.remove('active');
                                        }
                                    });

                                    // Add the active class to the current item
                                    if (currentIndex >= 0) {
                                        items[currentIndex].classList.add('active');
                                    }
                                }
                            </script>
                        @endsection
                        <!-- container -->
