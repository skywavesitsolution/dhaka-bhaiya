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
                    <h4 class="page-title">Purchase</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-5">
                                Purchase List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('purchase.list') }}" type="button" class="btn btn-warning">Purchase
                                        list</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="form">
                            <form id="purchaseForm" action="{{ route('purchase.save') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div clas">
                                                <label for="received_date" class="form-label">Received Date</label>
                                                <input type="date" name="received_date" id="received_date"
                                                    class="form-control form-control-sm @error('received_date') is-invalid @enderror"
                                                    placeholder="Add date" value="{{ old('received_date') }}">
                                                @error('received_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="">
                                                <label for="date" class="form-label">Due Date</label>
                                                <input type="date" name="date" id="date"
                                                    class="form-control form-control-sm @error('date') is-invalid @enderror"
                                                    placeholder="Add date" value="{{ old('date') }}">
                                                @error('date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="">
                                                <label for="supplier_name" class="form-label">Supplier Name</label>
                                                <select name="supplier_name" id="supplier_name"
                                                    class="form-control form-control-sm @error('supplier_name') is-invalid @enderror">
                                                    <option value="">Select supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            {{ old('supplier_name') == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('supplier_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="">
                                                <label for="supplier_balance" class="form-label">Supplier Balance</label>
                                                <input type="number" name="supplier_balance" id="supplier_balance"
                                                    class="form-control form-control-sm @error('supplier_balance') is-invalid @enderror"
                                                    placeholder="0" value="{{ old('supplier_balance') }}" readonly>
                                                @error('supplier_balance')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class=" col-sm-3">
                                            <label for="payment_type" class="mb-2">Payment Type</label>
                                            <select name="payment_type" id="payment_type"
                                                class="form-control form-control-sm @error('payment_type') is-invalid @enderror">
                                                <option value="">Select type</option>
                                                <option value="cash"
                                                    {{ old('payment_type') == 'cash' ? 'selected' : '' }}>cash</option>
                                                <option value="credit"
                                                    {{ old('payment_type') == 'credit' ? 'selected' : '' }}>credit</option>
                                                <option value="cash+credit"
                                                    {{ old('payment_type') == 'cash+credit' ? 'selected' : '' }}>
                                                    cash+credit
                                                </option>
                                            </select>
                                            @error('payment_type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class=" col-sm-3 d-none" id="account_name_container">
                                            <label for="account_name" class="mb-2">Account Name</label>
                                            <select name="account_name" id="account_name"
                                                class="form-control form-control-sm @error('account_name') is-invalid @enderror">
                                                <option value="">Select account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}"
                                                        {{ $account->account_name == 'cash in hand' ? 'selected' : '' }}>
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

                                        <div class=" col-sm-3" id="payment_amount">
                                            <label for="payment_amount" class="mb-2">Payment Amount</label>
                                            <input type="number" name="payment_amount" id="payment_amount"
                                                class="form-control form-control-sm @error('payment_amount') is-invalid @enderror"
                                                placeholder="0" value="{{ old('payment_amount') }}">
                                            @error('payment_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class=" col-sm-3">
                                            <label for="total_bill" class="mb-2">Total bill</label>
                                            <input type="number" name="total_bill" id="total_bill"
                                                class="form-control form-control-sm @error('total_bill') is-invalid @enderror"
                                                placeholder="0" value="{{ old('total_bill') }}" readonly>
                                            @error('total_bill')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class=" col-sm-3">
                                            <label for="adjustment" class="mb-2">Adjustment</label>
                                            <input type="number" name="adjustment" id="adjustment"
                                                class="form-control form-control-sm @error('adjustment') is-invalid @enderror"
                                                placeholder="0" value="{{ old('adjustment') }}">
                                            @error('adjustment')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class=" col-sm-3">
                                            <label for="net_payable" class="mb-2">Net Payable</label>
                                            <input type="number" name="net_payable" id="net_payable"
                                                class="form-control form-control-sm @error('net_payable') is-invalid @enderror"
                                                placeholder="0" value="{{ old('net_payable') }}" readonly>
                                            @error('net_payable')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                            <input type="text" id="product_search" class="form-control"
                                                placeholder="Search product by name or code..." onkeyup="searchProduct()"
                                                autofocus value="{{ old('product_search') }}">
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
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Cost Price</th>
                                                    <th>retail Price</th>
                                                    <th>Wholesale Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                    <th style="width: 85px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-table-body">
                                                <!-- Dynamically added products will appear here -->
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
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                            <script>
                                // Set today's date as the value for the date input
                                document.addEventListener('DOMContentLoaded', function() {
                                    const dateInput = document.getElementById('received_date');
                                    const duedateInput = document.getElementById('date');
                                    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
                                    dateInput.value = today;
                                    duedateInput.value = today;
                                });
                            </script>

                            <script>
                                function addToCart(productId) {
                                    // Check if the product is already in the table
                                    console.log(productId);
                                    if ($(`#product-table-body input[name="pro_id[]"][value="${productId}"]`).length > 0) {
                                        alert('This product has already been added.');
                                        return; // Exit if the product already exists
                                    }

                                    // AJAX request to fetch product details
                                    $.ajax({
                                        url: "{{ route('purchase.fetch.product.details', ':id') }}".replace(':id',
                                            productId), // Dynamically add the product ID to the URL
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
    <td class="action-btn">
        <button type="button" class="btn btn-primary btn-sm" title="Add Discount" onclick="toggleDiscount(${product.id})">+</button>
    </td>
    <td class="d-none">
        <input type="number" name="pro_id[]" value="${product.id}" readonly class="form-control" />
    </td>
    <td class="name-column">
        <input type="text" name="pro_name[]" value="${product.name}" readonly class="form-control" />
    </td>
    <td class="d-none">
        <input type="number" name="stock[]" value="${product.stock}" readonly class="form-control-plaintext" />
    </td>
    <td class="small-column">
        <input type="number" name="cost_price[]" id="cost_price_${product.id}" value="${product.cost_price}" class="form-control" oninput="calculateTotal(${product.id})" />
    </td>
    <td class="small-column">
        <input type="number" name="retail[]" id="retail_${product.id}" value="${product.retail}" class="form-control" oninput="calculateTotal(${product.id})" required />
    </td>
    <td class="small-column">
        <input type="number" name="wholesale[]" id="wholesale_${product.id}" value="${product.wholesale}" class="form-control" oninput="calculateTotal(${product.id})"  />
    </td>
    <td class="small-column">
        <input type="text" name="qty[]" id="quantity_${product.id}" value="" class="form-control" oninput="calculateTotal(${product.id})" required />
    </td>
    <td class="total-column">
        <input type="number" name="total[]" id="total_${product.id}" value="" class="form-control" readonly />
    </td>
    <td class="action-btn">
        <button type="button" class="btn btn-danger remove-btn">Remove</button>
    </td>
</tr>



                    <tr id="discount-row-${product.id}" class="d-none">
                        <td colspan="12">
                            <div class="row g-1">
                                <div class="col-md-2">
                                    <select name="product_discount_type[]" id="discountType${product.id}" class="form-control form-control-sm">
                                        <option value="">select</option>
                                        <option value="Fixed">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="product_discount_value[]" id="discount-value${product.id}" class="form-control form-control-sm" value="0">
                                    <input type="text"  hidden name="product_discount_actual_value[]" id="disc-actual-value${product['id']}" product-id="${product['id']}" class="form-control form-control-sm small-input" value="0">
                                </div>
                                <div class="col-md-2">
                                                <input type="text" name="location[]" id="location-field${product['id']}" product-id="${product['id']}" class="form-control form-control-sm small-input" placeholder="Location Field" value="${product['location'] || ''}">
                                            </div>
                                <div class="col-md-6">
                                    <input type="text" name="remarks[]" id="remarks-field${product.id}" class="form-control form-control-sm" placeholder="Remarks">
                                </div>
                            </div>
                        </td>
                    </tr>
                `);

                                                // Add event listener to remove button
                                                $('.remove-btn').click(function() {
                                                    $(this).closest('tr').next('#discount-row-' + product.id)
                                                        .remove(); // Remove discount row
                                                    $(this).closest('tr').remove(); // Remove product row
                                                });

                                                calculateTotal(product.id);
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

                                // Toggle discount row visibility
                                function toggleDiscount(productId) {
                                    $(`#discount-row-${productId}`).toggleClass('d-none');
                                }

                                // // Event listener for adding a product using the new function
                                // $(document).ready(function() {
                                //     $('#addProduct').on('click', function(event) {
                                //         event.preventDefault();

                                //         const selectedProductId = $('#select_product').val();
                                //         console.log(selectedProductId);

                                //         // Call the reusable function
                                //         addToCart(selectedProductId);
                                //     });
                                // });








                                function calculateTotal(productId) {
                                    // Get product details from inputs
                                    const costPrice = parseFloat($(`#cost_price_${productId}`).val()) || 0;
                                    const quantity = parseFloat($(`#quantity_${productId}`).val()) || 0;
                                    const discountType = $(`#discountType${productId}`).val();
                                    let discountValue = parseFloat($(`#discount-value${productId}`).val()) || 0;

                                    // Calculate the total without discount
                                    let total = costPrice * quantity;

                                    // Calculate discount based on type
                                    if (discountType === 'Fixed' && discountValue > 0) {
                                        // Ensure the discount doesn't exceed the total
                                        discountValue = Math.min(discountValue, total);
                                    } else if (discountType === 'Percentage' && discountValue > 0) {
                                        // Calculate percentage discount
                                        discountValue = (total * discountValue) / 100;
                                    } else {
                                        // No discount applied
                                        discountValue = 0;
                                    }

                                    // Subtract discount from total
                                    total -= discountValue;

                                    // Ensure total doesn't go negative
                                    total = Math.max(total, 0);

                                    // Update the discount value and total fields
                                    $(`#disc-actual-value${productId}`).val(discountValue.toFixed(2));
                                    $(`#total_${productId}`).val(total.toFixed(2));

                                    // Recalculate grand total
                                    calculateGrandTotal();
                                }



                                $(document).on('change input', '[id^="discountType"], [id^="discount-value"]', function() {
                                    const productId = $(this).closest('tr').prev().find('input[name="pro_id[]"]').val();
                                    calculateTotal(productId);
                                });




                                function calculateGrandTotal() {
                                    let grandTotal = 0;

                                    $('input[name="total[]"]').each(function() {
                                        grandTotal += parseFloat($(this).val()) || 0;
                                    });

                                    $('#total_bill').val(grandTotal.toFixed(2));
                                    $('#net_payable').val(grandTotal.toFixed(2));
                                    // $('#payment_amount').val(grandTotal.toFixed(2));
                                }

                                $(document).on('click', '.remove-btn', function() {
                                    $(this).closest('tr').remove();
                                    calculateGrandTotal();
                                });

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
                                        // $('#payment_amount').val(netPayable.toFixed(2));
                                    }
                                });
                            </script>




                            <!-- JavaScript to toggle visibility -->
                            <<script>
                                document.getElementById('payment_type').addEventListener('change', function() {
                                    const accountNameContainer = document.getElementById('account_name_container');
                                    const paymentAmountContainer = document.getElementById('payment_amount');

                                    if (this.value === 'cash') {
                                        accountNameContainer.classList.remove('d-none'); // Show the account name field
                                        paymentAmountContainer.classList.remove('d-none'); // Hide the payment amount field
                                    } else if (this.value === 'cash+credit') {
                                        accountNameContainer.classList.remove('d-none'); // Show the account name field
                                        paymentAmountContainer.classList.remove('d-none'); // Show the payment amount field
                                    } else {
                                        accountNameContainer.classList.add('d-none'); // Hide the account name field
                                        paymentAmountContainer.classList.add('d-none'); // Hide the payment amount field
                                    }
                                });
                            </script>



                            <script>
                                document.getElementById('supplier_name').addEventListener('change', function() {
                                    const supplierName = this.value;
                                    // console.log(supplierName);

                                    $.ajax({
                                        type: 'GET', // Changed to a comma
                                        url: 'purchase/get-supplier-balance/' + supplierName,
                                    }).done(function(data) {
                                        $('#supplier_balance').val(data.data.supplierBalance
                                            .balance); // Adjusted to access supplierBalance correctly
                                    });
                                });
                            </script>


                            <script>
                                // Trigger the hidden button when Enter is pressed
                                document.getElementById('select_product').addEventListener('keydown', function(event) {
                                    if (event.key === 'Enter') {
                                        event.preventDefault(); // Prevent default form submission if inside a form
                                        document.getElementById('addProduct').click();
                                    }
                                });
                            </script>

                            <script>
                                let currentIndex = -1; // For tracking selected item in the list
                                let scanBuffer = ''; // Buffer to store scanned input
                                let scanTimeout; // Timer to detect end of scanning
                                let debounceTimer; // Timer for debouncing
                                const cache = {}; // Cache for storing search results

                                // Function to handle product search and display results
                                function searchProduct(query) {
                                    const productList = $('#product_list');

                                    if (query.length < 2) {
                                        productList.hide();
                                        productList.html("");
                                        currentIndex = -1; // Reset the index
                                        return;
                                    }

                                    // Check if the result is already cached
                                    if (cache[query]) {
                                        displayProductList(cache[query]);
                                        return;
                                    }

                                    // Use AJAX for fetching products
                                    $.ajax({
                                        url: "{{ url('/purchase/fetch-products-for-purchase') }}",
                                        type: "GET",
                                        data: {
                                            query: query
                                        },
                                        success: function(data) {
                                            if (data.length > 0) {
                                                cache[query] = data; // Cache the result
                                                displayProductList(data);
                                            } else {
                                                productList.html('<li class="list-group-item text-muted">No products found</li>');
                                                productList.show();
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error fetching products:', error);
                                        }
                                    });
                                }

                                // Function to display product list
                                function displayProductList(data) {
                                    const productList = $('#product_list');
                                    let listItems = '';
                                    data.forEach((product) => {
                                        listItems += `
                <li class="list-group-item" style="cursor: pointer; width: 100%;"
                    onclick="selectProduct('${product.id}', '${product.code}', '${product.product_variant_name}')">
                    ${product.code} / ${product.product_variant_name}
                </li>`;
                                    });
                                    productList.html(listItems);
                                    productList.show();
                                    currentIndex = -1; // Reset index for new list
                                }

                                // Function to select and add product to the cart
                                function selectProduct(id, code, name) {

                                    const supplierName = $('#supplier_name').val();
                                    if (!supplierName) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Supplier Required',
                                            text: 'Please select a supplier first.',
                                            confirmButtonText: 'OK'
                                        });
                                        return;
                                    }

                                    // Check if payment type is selected
                                    const paymentType = $('#payment_type').val();
                                    if (!paymentType) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Payment Type Required',
                                            text: 'Please select a payment type first.',
                                            confirmButtonText: 'OK'
                                        });
                                        return;
                                    }


                                    const qtyInput = $(`#qty${id}`);
                                    if (qtyInput.length > 0) {
                                        let currentQty = parseInt(qtyInput.val() || 0);

                                        if (isReturnMode) {
                                            qtyInput.val(currentQty - 1 > 0 ? currentQty - 1 : 0);
                                        } else {
                                            qtyInput.val(currentQty + 1); // Increment
                                        }

                                        calculateItemTotals(id);
                                    } else {
                                        addToCart(id); // Automatically add to cart if no quantity input field
                                    }

                                    $('#product_search').val('');
                                    $('#product_list').hide();
                                    currentIndex = -1; // Reset index after selection
                                }

                                // Listen for input events to automatically search for the product as it is scanned or typed
                                $('#product_search').on('input', function() {
                                    const query = $(this).val().trim();
                                    clearTimeout(debounceTimer);
                                    debounceTimer = setTimeout(() => {
                                        if (query.length >= 2) {
                                            searchProduct(query); // Trigger product search after debounce delay
                                        }
                                    }, 300); // Adjust debounce time for optimal performance
                                });

                                // Prevent form submission when scanner simulates an "Enter" key press
                                $('#product_search').on('keydown', function(e) {
                                    const productList = $('#product_list');
                                    const items = productList.find('.list-group-item');

                                    if (e.key === 'Enter') {
                                        e.preventDefault(); // Prevent form submission

                                        if (currentIndex >= 0 && items.length > 0) {
                                            $(items[currentIndex]).click(); // Trigger click on highlighted item
                                        } else {
                                            const query = $(this).val().trim();

                                            if (query.length > 0) {
                                                $.ajax({
                                                    url: "{{ url('/purchase/fetch-products-for-purchase') }}",
                                                    type: "GET",
                                                    data: {
                                                        query: query
                                                    },
                                                    success: function(data) {
                                                        if (data.length > 0) {
                                                            const product = data[0]; // Automatically select the first product
                                                            selectProduct(product.id, product.code, product
                                                                .product_variant_name);
                                                        }
                                                    },
                                                    error: function(xhr, status, error) {
                                                        console.error('Error adding scanned product:', error);
                                                    }
                                                });
                                            }
                                        }
                                    } else if (e.key === 'ArrowDown') {
                                        // Move selection down
                                        if (items.length > 0) {
                                            currentIndex = (currentIndex + 1) % items.length;
                                            highlightItem(items);
                                        }
                                    } else if (e.key === 'ArrowUp') {
                                        // Move selection up
                                        if (items.length > 0) {
                                            currentIndex = (currentIndex - 1 + items.length) % items.length;
                                            highlightItem(items);
                                        }
                                    }
                                });

                                // Highlight the selected item in the list
                                function highlightItem(items) {
                                    // Clear the active class from all items except the current one
                                    items.removeClass('active');

                                    // Add the active class to the current item
                                    if (currentIndex >= 0 && items.length > 0) {
                                        $(items[currentIndex]).addClass('active');
                                    }
                                }
                            </script>
                        @endsection
                        <!-- container -->
