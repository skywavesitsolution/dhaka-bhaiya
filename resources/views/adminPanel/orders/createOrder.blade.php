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

                    <h4 class="page-title">Create Order</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 d-flex flex-row flex-row-reverse">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#standard-modal">Add Party</button>
                                <a href="{{ URL::to('order-list') }}" target="_blank" class="btn btn-warning">Orders
                                    List</a>
                            </div>
                        </div>
                        <form action="{{ URL::to('save-order') }}" id="order_form" method="post">
                            @csrf
                            <div class="modal-body">

                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Date</label>
                                            <input type="date" name="date"
                                                value="{{ isset($order->date) ? $order->date : '' }}" class="form-control">
                                            @error('date')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Select Marka</label>
                                            <select name="marka" id="marka-id" class="form-control" required>
                                                <option value="">Select One</option>
                                                @isset($parties)
                                                    @foreach ($parties as $party)
                                                        @if ($party->type == 'Marka')
                                                            <option value="{{ $party->id }}"
                                                                @if (isset($order->marka_id) && $order->marka_id == $party->id) selected @endif>
                                                                {{ $party->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('marka')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Select Product</label>
                                            <select name="product" class="form-control" required>
                                                <option value="">Select One</option>
                                                @isset($productTypes)
                                                    @foreach ($productTypes as $productType)
                                                        <option value="{{ $productType->id }}"
                                                            @if (isset($order->product_type) && $order->product_type == $productType->id) selected @endif>
                                                            {{ $productType->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('product')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Purchase Qty</label>
                                            <input type="number" name="purchaseQty" step="any"
                                                value="{{ isset($order->purchase_qty) ? $order->purchase_qty : ' ' }}"
                                                id="purchaseQty" class="form-control calculateTotalAmount" required>
                                            @error('purchaseQty')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Purchase Rate</label>
                                            <input type="number" name="purRate" step="any"
                                                value="{{ isset($order->purchase_rate) ? $order->purchase_rate : ' ' }}"
                                                id="purchaseRate" class="form-control calculateTotalAmount" required>
                                            @error('purRate')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Total Amount</label>
                                            <input type="number" name="totalAmount" step="any"
                                                value="{{ isset($order->total_purchase) ? $order->total_purchase : ' ' }}"
                                                id="totalPurchaseAmount" class="form-control" readonly required>
                                            @error('totalAmount')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Select Driver</label>
                                            <select name="driver" id="driver-id" class="form-control" required>
                                                <option value="">Select One</option>
                                                @isset($parties)
                                                    @foreach ($parties as $party)
                                                        @if ($party->type == 'Driver')
                                                            <option value="{{ $party->id }}"
                                                                @if (isset($order->driver_id) && $order->driver_id == $party->id) selected @endif>
                                                                {{ $party->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('driver')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Carriage Amount</label>
                                            <input type="number" name="carAmount" step="any"
                                                value="{{ isset($order->carriage_amount) ? $order->carriage_amount : ' ' }}"
                                                id="carriageAmount" class="form-control calculateTotalAmount" required>
                                            @error('carAmount')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Total Carriage</label>
                                            <input type="number" name="totalCarriage" step="any"
                                                value="{{ isset($order->total_carriage) ? $order->total_carriage : ' ' }}"
                                                id="totalCarriage" readonly class="form-control" readonly required>
                                            @error('totalCarriage')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Grand Total</label>
                                            <input type="number" name="grandTotal" step="any"
                                                value="{{ isset($order->grand_purchase_amount) ? $order->grand_purchase_amount : ' ' }}"
                                                id="grandTotal" class="form-control" readonly required>
                                            @error('grandTotal')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Select Supplier</label>
                                            <select name="supplier" id="supplier-id"
                                                onchange="fetchPartListWithType('Customer', 'customer-id')"
                                                class="form-control" required>
                                                <option value="">Select One</option>
                                                @isset($suppliers)
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            @if (isset($order->supplier_id) && $order->supplier_id == $supplier->id) selected @endif>
                                                            {{ $supplier->name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('supplier')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Select Customer</label>
                                            <select name="customer" id="customer-id" class="form-control" required>
                                                <option value="">Select One</option>
                                                @isset($parties)
                                                    @foreach ($parties as $party)
                                                        @if ($party->type == 'Customer' && isset($order) && $party->supplier_id == $order->supplier_id)
                                                            <option value="{{ $party->id }}"
                                                                @if (isset($order->customer_id) && $order->customer_id == $party->id) selected @endif>
                                                                {{ $party->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('customer')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Sale Rate</label>
                                            <input type="number" name="saleRate" step="any"
                                                value="{{ isset($order->sale_rate) ? $order->sale_rate : ' ' }}"
                                                id="saleRate" class="form-control calculateTotalAmount" required>
                                            @error('saleRate')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Total Sale Amount</label>
                                            <input type="number" name="saleAmount" step="any"
                                                value="{{ isset($order->total_sale_amount) ? $order->total_sale_amount : ' ' }}"
                                                id="totalSaleAmount" class="form-control" readonly required>
                                            @error('saleAmount')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <?php
                                    $user = Auth::user();
                                    // dd($user->roles->first()->name);

                                    if($user->roles->first()->name == 'User' ){
                                       ?>
                                    <div class="col-md-4 d-none">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Profit</label>
                                            <input type="number" name="profit"
                                                value="{{ isset($order->profit) ? $order->profit : ' ' }}" id="profit"
                                                class="form-control" readonly required>
                                            @error('profit')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>



                                    <?php
                                    }
                                ?>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="example-input-normal" class="form-label">Remarks</label>
                                            <input type="text" name="remarks"
                                                value="{{ isset($order->remarks) ? $order->remarks : ' ' }}"
                                                class="form-control">
                                            @error('remarks')
                                                <p class="text-danger mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <!-- <div class="col"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button></div> -->
                                    <div class="col"><button type="submit" class="btn btn-primary">Save
                                            changes</button></div>

                                </div>
                            </div>

                        </form>


                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add New Party</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="" id="party-add-form" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Party Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Type</label>
                                        <select name="type" onchange="selectType()" class="form-control" required
                                            id="particularType">
                                            <option value="">Select One</option>
                                            <option value="Marka">Marka</option>
                                            <option value="Driver">Driver</option>
                                            <option value="Customer">Customer</option>
                                        </select>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12" style="display:none" id="suppliers-list-div">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Supplier</label>
                                        <select name="supplier_id" class="form-control" id="suppliers-list">
                                            <option value="">Select One</option>
                                            @isset($suppliers)
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Email</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Party Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="text" name="openingBalance" id="openingBalance" value="0"
                                            class="form-control" placeholder="Party Email">
                                        @error('openingBalance')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Company Name
                                            <span>Optional</span></label>
                                        <input type="text" name="company_name" id="company_name" class="form-control"
                                            placeholder="Company Name ">
                                        @error('company_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Address</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            placeholder="Party Email">
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" onclick="addNewParty()" class="btn btn-primary">Save changes</button>
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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> -->

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
        $('#supplier-id').select2();

        var submit_form = true;

        function disabledSubmitButton(form) {
            console.log(form);
            console.log('Form is submit now ');
            if (submit_form) {
                submit_form = false;
                $('#order_form').submit();
            }

        }

        function addNewParty() {
            var name = $('#name').val()
            var partyType = $('#particularType').val()
            if (name == '') {
                $.toast({
                    heading: 'error',
                    text: "Please Enter Particular Name",
                    icon: 'error',
                    loader: true, // Change it to false to disable loader
                    loaderBg: 'error', // To change the background
                    postion: "top - right"
                })
                return false;
            }

            if (partyType == '') {
                $.toast({
                    heading: 'error',
                    text: "Please Select Party Type",
                    icon: 'error',
                    loader: true, // Change it to false to disable loader
                    loaderBg: 'error', // To change the background
                    postion: "top - right"
                })
                return false;
            }

            $.ajax({
                url: "{{ URL::to('add-party-wi-ajax') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}', // Blade function corrected to lowercase
                    'name': name,
                    'type': partyType,
                    'supplier_id': $('#suppliers-list').val(),
                    'openingBalance': $('#openingBalance').val(),
                    'balance': $('#openingBalance').val(),
                    'email': $('#email').val(),
                    'company_name': $('#company_name').val(),
                    'address': $('#address').val(),
                },
                success: function(response) {
                    if (response['error'] == false) {
                        $.toast({
                            heading: 'success',
                            text: "Party Added Successfully",
                            icon: 'success',
                            loader: true, // Change it to false to disable loader
                            loaderBg: 'success', // To change the background
                            postion: "top - right"
                        })

                        if (partyType == 'Marka') {
                            fetchPartListWithType(partyType, 'marka-id')
                        }
                        if (partyType == 'Driver') {
                            fetchPartListWithType(partyType, 'driver-id')
                        }
                        if (partyType == 'Customer') {
                            fetchPartListWithType(partyType, 'customer-id')
                        }


                        return true;
                    }

                    $.toast({
                        heading: 'error',
                        text: "Something Went Wrong Try Again",
                        icon: 'error',
                        loader: true, // Change it to false to disable loader
                        loaderBg: 'error', // To change the background
                        postion: "top - right"
                    })
                    return false;

                }
            });

            $('#standard-modal').modal('hide');
        }

        function fetchPartListWithType(type, displayId) {
            $('#' + displayId + '').html('')
            $.ajax({
                url: "{{ URL::to('fetch-parties-wi-types') }}/" + type + "",
                type: 'GET',
                data: {},
                success: function(response) {
                    var parties = response['data']['parties'];
                    var options = `<option value="">Select One</option>`;
                    if (type == 'Customer') {
                        var supplierId = $('#supplier-id').val();
                        parties.forEach((party) => {
                            if (supplierId == party['supplier_id']) {
                                options += `<option value="${party['id']}">${party['name']}</option>`;
                            }
                        });
                    } else {
                        parties.forEach((party) => {
                            options += `<option value="${party['id']}">${party['name']}</option>`;
                        });
                    }

                    $('#' + displayId + '').html(options)
                    $('#' + displayId + '').select2();
                }
            });
        }

        // fetchPartListWithType('Marka', 'marka-id')
        // fetchPartListWithType('Driver', 'driver-id')
        // fetchPartListWithType('Customer', 'customer-id')


        function selectType() {
            var type = $('#particularType').val();
            if (type == 'Customer') {
                $('#suppliers-list-div').css('display', 'block');
                $('#suppliers-list').attr('required', true);
            } else {
                $('#suppliers-list-div').css('display', 'none');
                $('#suppliers-list').attr('required', false);
            }
        }

        $('.calculateTotalAmount').on('keyup change', function() {
            calculatePurchase();
            calculateCarriageTotal();
            calculateSalePrice();
        })

        function calculatePurchase() {
            var totalPurchaseAmount = $('#purchaseQty').val() * $('#purchaseRate').val();
            $('#totalPurchaseAmount').val(totalPurchaseAmount)

        }

        function calculateCarriageTotal() {
            var totalCarriage = $('#purchaseQty').val() * $('#carriageAmount').val()
            var totalPurchaseAmount = $('#totalPurchaseAmount').val();
            var totalExpense = parseFloat(totalCarriage) + parseFloat(totalPurchaseAmount);
            $('#totalCarriage').val(totalCarriage);
            $('#grandTotal').val(totalExpense);
        }

        function calculateSalePrice() {
            var totalSaleAmount = $('#purchaseQty').val() * $('#saleRate').val();
            var totalExpenseAmount = $('#grandTotal').val();
            var profit = totalSaleAmount - totalExpenseAmount;
            $('#totalSaleAmount').val(totalSaleAmount);
            $('#profit').val(profit);
        }
    </script>
@endsection
<!-- container -->
