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

                    <h4 class="page-title">Update Sale</h4>
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
                                Update Sales
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ URL::to('update-sale-product/' . $editSaleInvoice->id . '') }}"
                                    method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Date</label>
                                                <input type="date" name="bill_date" readonly
                                                    value="{{ $editSaleInvoice->bill_date }}" class="form-control">
                                                @error('bill_date')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Select Customer</label>
                                                <select name="party_id" class="form-control" id="">
                                                    <option value="">{{ $editSaleInvoice->party->name }}</option>
                                                    @isset($parties)
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->name }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                                @error('party_id')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Payment Type</label>
                                                <select name="payment_type" onchange="checkPaymentType()"
                                                    class="form-control">
                                                    <option value="">{{ $editSaleInvoice->payment_type }}</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Credit">Credit</option>
                                                </select>
                                                @error('paymentType')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Total Bill</label>
                                                <input type="text" readonly name="total_bill" id="total-bill"
                                                    value="{{ $editSaleInvoice->total_bill }}" class="form-control"
                                                    placeholder="Total Bill">
                                                @error('total_bill')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount-type"
                                                    class="form-control calculate-grand-total" id="">
                                                    <option value="">{{ $editSaleInvoice->discount_type }}</option>
                                                    <option value="Fixed">Flat</option>
                                                    <option value="Percentage">Percentage</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount Value</label>
                                                <input type="number" step="any" id="discount-value" value="0"
                                                    name="discount_value" value="{{ $editSaleInvoice->discount_value }}"
                                                    class="form-control calculate-grand-total">
                                                @error('discount_value')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount
                                                    Amount</label>
                                                <input type="text" readonly id="discount-amount" value="0"
                                                    name="discount_actual_value"
                                                    value="{{ $editSaleInvoice->discount_actual_value }}"
                                                    class="form-control">
                                                @error('discount_actual_value')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Adjustment</label>
                                                <input type="number" step="any" id="adjustment" value="0"
                                                    name="adjustment" value="{{ $editSaleInvoice->adjustment }}"
                                                    class="form-control calculate-grand-total">
                                                @error('adjustment')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Net Payable</label>
                                                <input type="number" readonly id="net-payable" step="any"
                                                    name="net_payable" value="{{ $editSaleInvoice->net_payable }}"
                                                    class="form-control">
                                                @error('net_payable')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-10">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Select
                                                    Product</label>
                                                <select name="" class="form-control" id="product_selected">
                                                    <option value="">Chose One</option>
                                                    @isset($products)
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <button class="btn btn-success" type="button"
                                                    style="margin-top: 1.8rem;" onclick="addToCart()">Add Product</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="" class="table table-centered w-100 nowrap">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th width="15%">Name</th>
                                                            <th>Sale Price</th>
                                                            <th>Qty</th>
                                                            <th>Update Qty</th>
                                                            <th>Discount Type</th>
                                                            <th>Discount</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableBody">
                                                        @foreach ($saleProducts as $saleProduct)
                                                            <tr id="{{ $saleProduct->product_id }}">
                                                                <td>
                                                                    <h5>{{ $saleProduct->product_id }}</h5>
                                                                    <input type="hidden" name="product_id[]" required
                                                                        value="{{ $saleProduct->product_id }}">
                                                                </td>
                                                                <td>
                                                                    <h5>{{ $saleProduct->product_name }}</h5>
                                                                    <input type="hidden" name="product_name[]" required
                                                                        value="{{ $saleProduct->product_name }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="retail_price[]" readonly
                                                                        id="retail-price{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control calculate-total"
                                                                        value="{{ $saleProduct->retail_price }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="qty[]" readonly
                                                                        id="qty{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control calculate-total"
                                                                        value="{{ $saleProduct->qty }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="update_qty[]"
                                                                        id="update-qty{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control calculate-total"
                                                                        value="0">
                                                                </td>
                                                                <td>
                                                                    <select name="product_discount_type[]"
                                                                        id="discountType{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control calculate-total">
                                                                        <option value="Fixed"
                                                                            {{ $saleProduct->discount_type == 'Fixed' ? 'selected' : '' }}>
                                                                            Flat</option>
                                                                        <option value="Percentage"
                                                                            {{ $saleProduct->discount_type == 'Percentage' ? 'selected' : '' }}>
                                                                            Percentage</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="product_discount_value[]"
                                                                        id="discount-value{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control calculate-total"
                                                                        value="{{ $saleProduct->product_discount_value }}">
                                                                    <input type="hidden"
                                                                        name="product_discount_actual_value[]"
                                                                        id="disc-actual-value{{ $saleProduct->product_id }}"
                                                                        product-id="{{ $saleProduct->product_id }}"
                                                                        class="form-control"
                                                                        value="{{ $saleProduct->discount_actual_value }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" readonly name="total[]" required
                                                                        id="total-price{{ $saleProduct->product_id }}"
                                                                        class="form-control"
                                                                        value="{{ $saleProduct->total }}">
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-danger btn-sm" type="button"
                                                                        onclick="removeProduct({{ $saleProduct->product_id }})">X</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </form>
                            </div>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>


        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

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

        var addToCartProducts = [];

        function addToCart() {
            var product = $('#product_selected').val();

            if (product !== '') {
                product = parseFloat(product);
                if (addToCartProducts.indexOf(product) == -1) {
                    addToCartProducts.push(product);
                    $.ajax({
                        url: "{{ URL::to('get-product') }}/" + product,
                        type: 'GET',
                        success: function(data) {
                            var product = data['data']['product'];

                            var tableRowHTML = `<tr id="${product['id']}">
                            <td>
                                <h5>${product['id']}</h5>
                                <input type="hidden" name="product_id[]" required value="${product['id']}">
                            </td>
                            <td>
                                <h5>${product['name']}</h5>
                                <input type="hidden" name="product_name[]" required value="${product['name']}">
                            </td>
                            <td>
                                <input type="text" name="retail_price[]" readonly id="retail-price${product['id']}" product-id="${product['id']}" class="form-control calculate-total" value="${product['retail_price']}">
                            </td>
                            <td>
                                <input type="text" name="qty[]" readonly id="qty${product['id']}" product-id="${product['id']}" class="form-control calculate-total" value="0">
                            </td>
                            <td>
                                <input type="text" name="update_qty[]" id="update-qty${product['id']}" product-id="${product['id']}" class="form-control calculate-total" value="0">
                             </td>
                            <td>
                                <select name="product_discount_type[]" id="discountType${product['id']}" product-id="${product['id']}" class="form-control calculate-total">
                                    <option value="Fixed">Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="product_discount_value[]" id="discount-value${product['id']}" product-id="${product['id']}" class="form-control calculate-total" value="0">
                                <input type="hidden" name="product_discount_actual_value[]" id="disc-actual-value${product['id']}" product-id="${product['id']}" class="form-control" value="">
                            </td>
                            <td>
                                <input type="text" readonly name="total[]" required id="total-price${product['id']}" class="form-control" value="">
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="button" onclick="removeProduct(${product['id']})">X</button>
                            </td>
                        </tr>`;

                            $('#tableBody').append(tableRowHTML);

                            // Attach event listeners to new elements
                            $('#tableBody').on('keyup change', '.calculate-total', function() {
                                var productId = $(this).attr('product-id');
                                calculateItemTotal(product['id']);
                            });
                        }
                    });
                } else {
                    $('#qty' + product + '').focus();
                }
            } else {
                $.toast({
                    heading: 'Information',
                    text: "Please Select Product",
                    icon: 'error',
                    loader: true,
                    loaderBg: 'error',
                    postion: "top - right"
                });
            }
        }

        $(document).ready(function() {
            // Initial calculation on page load
            calculateGrandTotal();

            // Event listener for dynamic field changes
            $('#tableBody').on('keyup change', '.calculate-total', function() {
                var productId = $(this).attr('product-id');
                calculateItemTotal(productId);
            });
        });

        // Calculate individual item totals
        function calculateItemTotal(productId) {
            var salePrice = parseFloat($('#retail-price' + productId).val()) || 0;
            var updateQty = parseFloat($('#update-qty' + productId).val()) || 0;
            var discountType = $('#discountType' + productId).val();
            var discountValue = parseFloat($('#discount-value' + productId).val()) || 0;

            var totalSalePrice = salePrice * updateQty;
            var discountApplied = 0;
            var netSale = totalSalePrice;

            // Calculate discount
            if (discountType === 'Fixed') {
                netSale = totalSalePrice - discountValue;
                discountApplied = discountValue;
            } else {
                discountApplied = (totalSalePrice * discountValue) / 100;
                netSale = totalSalePrice - discountApplied;
            }

            if (netSale < 0) {
                netSale = 0;
            }

            // Update fields
            $('#disc-actual-value' + productId).val(discountApplied.toFixed(2));
            $('#total-price' + productId).val(netSale.toFixed(2));

            // Recalculate the grand total
            calculateGrandTotal();
        }

        // Calculate the grand total across all products in the cart
        function calculateGrandTotal() {
            var grandTotal = 0;

            // Iterate over each total price field in the table to sum up the total
            $('.input[name="total[]"]').each(function() {
                var grandTotal = parseFloat($(this).val()) || 0;
                // totalAmount += itemTotal;
            });
            console.log(grandTotal);
            // Get discount and adjustment values (if any)
            var discountType = $('#discount-type').val();
            var discountValue = parseFloat($('#discount-value').val()) || 0;
            var adjustment = parseFloat($('#adjustment').val()) || 0;

            var existingTotalBill = parseFloat($('#total-bill').val()) || 0;

            // Add existing total bill to the calculated total
            totalAmount += existingTotalBill;

            var discountApplied = 0;
            var netPayable = totalAmount;

            // Apply discount logic (Fixed or Percentage)
            if (discountType === 'Fixed') {
                netPayable -= discountValue;
                discountApplied = discountValue;
            } else if (discountType === 'Percentage') {
                discountApplied = (totalAmount * discountValue) / 100;
                netPayable -= discountApplied;
            }

            // Apply any adjustment
            netPayable -= adjustment;

            // Ensure net payable is not negative
            if (netPayable < 0) {
                netPayable = 0;
            }
            console.log('Total Amount ', totalAmount);
            console.log('Total Net Payable ', netPayable);

            // Update Total Bill and Net Payable fields
            $('#discount-amount').val(discountApplied.toFixed(2));
            $('#total-bill').val(totalAmount.toFixed(2));
            $('#net-payable').val(netPayable.toFixed(2));
        }

        function removeProduct(productId) {
            $('#' + productId + '').remove();
            var productIndex = addToCartProducts.indexOf(productId)
            addToCartProducts.splice(productIndex, 1);
            calculateGrandTotal();
        }
    </script>
@endsection
<!-- container -->
