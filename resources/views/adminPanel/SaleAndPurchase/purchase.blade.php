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

                    <h4 class="page-title">Ingridents Purchase</h4>
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
                                Purchase List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">Add New</button>
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
                                <form action="{{ URL::to('add-ingredients-purchase') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Date</label>
                                                <input type="date" name="bill_date" required value="{{ date('Y-m-d') }}"
                                                    class="form-control" placeholder="Bill Date">
                                                @error('bill_date')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Party</label>
                                                <select name="party_id" required class="form-control" id="">
                                                    <option value="">Chose One</option>
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

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Total Bill</label>
                                                <input type="text" readonly name="total_bill" id="total-bill"
                                                    value="{{ old('total_bill') }}" class="form-control"
                                                    placeholder="Total Bill">
                                                @error('total_bill')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount-type"
                                                    class="form-control calculate-grand-total" id="">
                                                    <option value="Fixed">Flat</option>
                                                    <option value="Percentage">Percentage</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount Value</label>
                                                <input type="number" step="any" id="discount-value" value="0"
                                                    name="discount_value" value="{{ old('discount_value') }}"
                                                    class="form-control calculate-grand-total" placeholder="Discount Value">
                                                @error('discount_value')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Discount Amount</label>
                                                <input type="text" readonly id="discount-amount" value="0"
                                                    name="discount_actual_value" value="{{ old('discount_actual_value') }}"
                                                    class="form-control" placeholder="Total Bill">
                                                @error('discount_actual_value')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Adjustment</label>
                                                <input type="number" step="any" id="adjustment" value="0"
                                                    name="adjustment" value="{{ old('adjustment') }}"
                                                    class="form-control calculate-grand-total"
                                                    placeholder="Total Adjustment">
                                                @error('adjustment')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Net Payable</label>
                                                <input type="number" readonly id="net-payable" step="any"
                                                    name="net_payable" value="{{ old('net_payable') }}"
                                                    class="form-control" placeholder="Net Payable">
                                                @error('net_payable')
                                                    <p class="text-danger mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-10">
                                            <div class="mb-3">
                                                <label for="example-input-normal" class="form-label">Select
                                                    Ingreident</label>
                                                <select name="" class="form-control" id="ingredient_selected">
                                                    <option value="">Chose One</option>
                                                    @isset($cropIngredient)
                                                        @foreach ($cropIngredient as $ingreident)
                                                            <option value="{{ $ingreident->id }}">{{ $ingreident->name }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="mb-3">
                                                <button class="btn btn-success" type="button"
                                                    style="margin-top: 1.8rem;" onclick="addToCart()">Add
                                                    Ingridient</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="" class="table table-centered w-100 nowrap">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th width="15%">Name</th>
                                                            <th>Stock</th>
                                                            <th>Cost Price</th>
                                                            <th>Qty</th>
                                                            <th>Discount Type</th>
                                                            <th>Discount</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableBody">



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

        var addToCartIngredients = [];


        function addToCart() {
            var ingredient = $('#ingredient_selected').val();

            // Get Ingrident Data

            if (ingredient !== '') {
                ingredient = parseFloat(ingredient);
                if (addToCartIngredients.indexOf(ingredient) == -1) {
                    addToCartIngredients.push(ingredient);
                    $.ajax({
                        url: "{{ URL::to('get-crop-ingredient') }}/" + ingredient + "",
                        type: 'GET',
                        data: {},
                        success: function(data) {
                            var ingreident = data['data']['ingredient'];

                            var tableRowHTML = `<tr id="${ingreident['id']}">
                                            <td>
                                                <h5>${ingreident['id']}</h5>
                                                <input type="text" hidden name="ingredient_id[]" required class="form-control" value="${ingreident['id']}">
                                            </td>
                                            <td>
                                                <h5>${ingreident['name']}</h5>
                                                <input type="text" hidden name="ingredient_name[]" required class="form-control" value="${ingreident['id']}">
                                            </td>
                                            <td>
                                                <h5>${ingreident['stock']}</h5>
                                            </td>
                                            <td>
                                                <input type="text" name="cost_price[]" required id="cost-price${ingreident['id']}" ingreident-id="${ingreident['id']}" class="form-control calculate-total" value="${ingreident['cost_price']}">
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" required id="qty${ingreident['id']}" ingreident-id="${ingreident['id']}" class="form-control calculate-total" value="">
                                            </td>
                                            <td>
                                                <select name="ingredient_discount_type[]" required id="discountType${ingreident['id']}" ingreident-id="${ingreident['id']}" class="form-control calculate-total" id="">
                                                    <option value="Fixed">Flat</option>
                                                    <option value="Percentage">Percentage</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="ingredient_discount_value[]" required id="discount-value${ingreident['id']}" ingreident-id="${ingreident['id']}" class="form-control calculate-total" value="">
                                                <input type="text" hidden name="ingredient_discount_actual_value[]" required id="disc-actual-value${ingreident['id']}" ingreident-id="${ingreident['id']}" class="form-control" value="">
                                            </td>
                                            <td>
                                                <input type="text" readonly name="total[]" required id="total-price${ingreident['id']}" class="form-control" value="">
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" type="button" onclick="removeIngredient(${ingreident['id']})">X</button>
                                            </td>
                                        </tr>`;

                            $('#tableBody').append(tableRowHTML);

                            $('.calculate-total').on('keyup change', function() {
                                var ingridentId = $(this).attr('ingreident-id');
                                calculateItemTotals(ingridentId);

                            })
                        }
                    });
                } else {
                    $('#qty' + ingredient + '').focus();
                }

            } else {
                $.toast({
                    heading: 'Information',
                    text: "Please Select Ingreident",
                    icon: 'error',
                    loader: true, // Change it to false to disable loader
                    loaderBg: 'error', // To change the background
                    postion: "top - right"
                })
            }


            console.log('ingredient' + ingredient);
        }

        function calculateItemTotals(ingridentId) {
            var costPrice = $('#cost-price' + ingridentId + '').val();
            var qty = $('#qty' + ingridentId + '').val();
            var discountType = $('#discountType' + ingridentId + '').val();
            var discountValue = $('#discount-value' + ingridentId + '').val();

            var totalCostPrice = costPrice * qty;

            // Calculate Discount
            if (discountType == 'Fixed') {
                var netCost = totalCostPrice - discountValue;
            } else {
                var discountValue = (totalCostPrice * discountValue) / 100;
                var netCost = totalCostPrice - discountValue;
            }

            $('#disc-actual-value' + ingridentId + '').val(discountValue);
            $('#total-price' + ingridentId + '').val(netCost);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            var totalAmount = 0;
            addToCartIngredients.forEach((ingredient) => {
                var itemTotal = $('#total-price' + ingredient + '').val();
                totalAmount = totalAmount + parseFloat(itemTotal);
            });

            var discountType = $('#discount-type').val();
            var discountValue = $('#discount-value').val();
            var adjustment = $('#adjustment').val();

            // Calculate Discount
            if (discountType == 'Fixed') {
                var netPayable = totalAmount - discountValue;
            } else {
                var discountValue = (totalAmount * discountValue) / 100;
                var netPayable = totalAmount - discountValue;
            }

            netPayable = netPayable - adjustment;

            $('#discount-amount').val(discountValue);
            $('#total-bill').val(totalAmount);
            $('#net-payable').val(netPayable);
        }

        $('.calculate-grand-total').on('keyup change', function() {
            calculateGrandTotal()

        })

        function removeIngredient(ingredientId) {
            $('#' + ingredientId + '').remove();
            var ingredientIndex = addToCartIngredients.indexOf(ingredientId)
            addToCartIngredients.splice(ingredientIndex, 1);
            calculateGrandTotal();
        }
    </script>
@endsection
<!-- container -->
