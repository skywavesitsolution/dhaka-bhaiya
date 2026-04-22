@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 css -->
    <link href="assets/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <h4 class="page-title">Payments and Receiving</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a href="#home" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                    <i class="mdi mdi-home-variant d-md-none d-block"></i>
                    <span class="d-none d-md-block">Payments</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#profile" data-bs-toggle="tab" aria-expanded="true" class="nav-link ">
                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                    <span class="d-none d-md-block">Receiving</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <form action="{{ URL::to('add-make-payment') }}" id="make-payment" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-5">
                                            payments
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="text-sm-end">
                                                <a href="{{ URL::to('make-payment-list') }}" class="btn btn-info">Payment
                                                    List</a>
                                            </div>
                                        </div><!-- end col-->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Date</label>
                                                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                                            class="form-control" placeholder="Bill Date">
                                                        @error('date')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label"> Select
                                                            Account</label>
                                                        <select name="accountId" required
                                                            onchange="fetchId('to-account-id','to-account-prev-balance')"
                                                            class="form-control select2" id="to-account-id">
                                                            <option value="">Choose Account</option>
                                                            @isset($accounts)
                                                                @foreach ($accounts as $account_id)
                                                                    <option value="{{ $account_id->id }}">
                                                                        {{ $account_id->account_name }}</option>
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                        @error('accountId')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Previous
                                                            Balance</label>
                                                        <input type="text" readonly name="previousBalance"
                                                            id="to-account-prev-balance"
                                                            value="{{ old('previousBalance') }}" class="form-control">
                                                        @error('previousBalance')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Updated
                                                            Balance</label>
                                                        <input type="text" readonly name="updatedBalance" value="0"
                                                            id="updated-balance" class="form-control">
                                                        @error('updatedBalance')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Total
                                                            Payments</label>
                                                        <input type="text" readonly name="totalPayments"
                                                            id="total-payments" value="0" class="form-control"
                                                            placeholder="Total Payments">
                                                        @error('totalPayments')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Select
                                                            Particular</label>
                                                        <select name="particular" class="form-control"
                                                            onchange="fetchParticulars('particular','particularId','particular-balance')"
                                                            id="particular">
                                                            <option value="">Chose One</option>
                                                            <!-- {{-- <option value="Party" party-type="Marka">Marka</option>
                                                        <option value="Party" party-type="Driver">Driver</option> --}} -->
                                                            <option value="Party" party-type="Customer">Customer</option>
                                                            <option value="Party" party-type="Supplier">supplier</option>
                                                            <option value="Account" party-type="Account">Account</option>
                                                        </select>
                                                        <input type="text" class="d-none" name="party_name"
                                                            id="party_name">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-2" id="supplier-div-payment" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="example-input-normal" class="form-label">Select Supplier</label>
                                                    <select name="" onchange="fetchSupplierCustomers('supplier_id','particularId','particular-balance')" class="form-control select2" id="supplier_id">
                                                        <option value="-1">Select One</option>
                                                        @isset($suppliers)
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>

                                                        @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div> --}}
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Select
                                                            Account</label>
                                                        <select name="particularId"
                                                            onchange="fetchParticularBalnce('particular','particularId','particular-balance')"
                                                            class="form-control" id="particularId">
                                                            <option value="-1">Select One</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">
                                                            Balance</label>
                                                        <input type="number" readonly id="particular-balance"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal"
                                                            class="form-label">Payments</label>
                                                        <input type="number" id="payment" name="payment"
                                                            class="form-control" placeholder="Payments">
                                                        @error('payment')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal"
                                                            class="form-label">Remarks</label>
                                                        <input type="text" id="remarks" name="remarks"
                                                            class="form-control" placeholder="Remarks">
                                                        @error('remarks')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <button class="btn btn-success" type="button"
                                                            style="margin-top: 1.8rem;"
                                                            onclick="addToCart('particular','particularId','payment','remarks','tableBody')">Add
                                                            Payment</button>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-centered w-100 nowrap">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Particular</th>
                                                                    <th>Particular Id</th>
                                                                    <th>Particular Name</th>
                                                                    <th>Amount</th>
                                                                    <th>Remarks</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tableBody">



                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                        <div class="col-md-12">
                            <button type="button" onclick="disabledSubmitButton('make-payment')" id="sub_make_payment"
                                class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane show " id="profile">
                <form action="{{ URL::to('add-received-payment') }}" id="receive-payment" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-5">
                                            Payments Received
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="text-sm-end">
                                                <a href="{{ URL::to('receive-payment-list') }}"
                                                    class="btn btn-secondary">Received Payment List</a>
                                            </div>
                                        </div><!-- end col-->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Date</label>
                                                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                                            class="form-control" placeholder="Bill Date">
                                                        @error('date')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label"> Select
                                                            Account</label>
                                                        <select name="accountId" required
                                                            onchange="fetchId('from-account-id','from-account-prev-balance')"
                                                            class="form-control select2" id="from-account-id">
                                                            <option value="">Chose One</option>
                                                            @isset($accounts)
                                                                @foreach ($accounts as $account_id)
                                                                    <option value="{{ $account_id->id }}">
                                                                        {{ $account_id->account_name }}</option>
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                        @error('accountId')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Previous
                                                            Balance</label>
                                                        <input type="text" readonly name="previousBalance"
                                                            id="from-account-prev-balance"
                                                            value="{{ old('previousBalance') }}" class="form-control"
                                                            placeholder="Total Bill">
                                                        @error('previousBalance')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Updated
                                                            Balance</label>
                                                        <input type="text" readonly id="from-updated-balance"
                                                            name="updatedBalance" value="0" class="form-control"
                                                            placeholder="Total Bill">
                                                        @error('updatedBalance')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Total
                                                            Payments</label>
                                                        <input type="text" id="from-total-payments" readonly
                                                            name="totalPayments" value="0" class="form-control"
                                                            placeholder="Total Bill">
                                                        @error('totalPayments')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>



                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Select
                                                            Particular</label>
                                                        <select name="particular" class="form-control"
                                                            onchange="fetchParticulars('particularFrom','particularIdFrom','particular-balance-from')"
                                                            id="particularFrom">
                                                            <option value="">Chose One</option>
                                                            <!-- <option value="Party" party-type="Marka">Marka</option>
                                                                        <option value="Party" party-type="Driver">Driver</option> -->
                                                            <option value="Party" party-type="Customer">Customer</option>
                                                            <option value="Party" party-type="Supplier">supplier</option>
                                                            <option value="Account" party-type="Account">Account</option>
                                                        </select>
                                                        <input type="text" class="d-none" name="party_name"
                                                            id="party_name_customer">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-2" id="supplier-div-from" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="example-input-normal" class="form-label">Select Supplier</label>
                                                    <select name="" onchange="fetchSupplierCustomers('supplier_id_from','particularIdFrom','particular-balance-from')" class="form-control select2" id="supplier_id_from">
                                                        <option value="-1">Select One</option>
                                                        @isset($suppliers)
                                                        @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>

                                                        @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div> --}}
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal" class="form-label">Select
                                                            Account</label>
                                                        <select name="particularId"
                                                            onchange="fetchParticularBalnce('particularFrom','particularIdFrom','particular-balance-from')"
                                                            class="form-control" id="particularIdFrom">
                                                            <option value="-1">Select One</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal"
                                                            class="form-label">Balance</label>
                                                        <input type="number" readonly id="particular-balance-from"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal"
                                                            class="form-label">Payments</label>
                                                        <input type="number" id="paymentFrom" name="payment"
                                                            class="form-control" placeholder="Payments">
                                                        @error('payment')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <label for="example-input-normal"
                                                            class="form-label">Remarks</label>
                                                        <input type="text" id="remarksFrom" name="remarks"
                                                            class="form-control" placeholder="Remarks">
                                                        @error('remarks')
                                                            <p class="text-danger mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="mb-3">
                                                        <button class="btn btn-success" type="button"
                                                            style="margin-top: 1.8rem;"
                                                            onclick="addToCart('particularFrom','particularIdFrom','paymentFrom','remarksFrom','tableBodyFrom')">Add
                                                            Payment</button>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="" class="table table-centered w-100 nowrap">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Particular</th>
                                                                    <th>Particular Id</th>
                                                                    <th>Particular Name</th>
                                                                    <th>Amount</th>
                                                                    <th>Remarks</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tableBodyFrom">



                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                        <div class="col-md-12">
                            <button type="button" onclick="disabledSubmitButton('receive-payment')"
                                class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <div class="tab-pane" id="settings">
                        <p>...</p>
                    </div> -->
        </div>



        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="assets/vendor/select2/js/select2.min.js"></script>
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

        $('.select2').select2();

        var accounts = [];
        var parties = [];
        var counter = 1;

        var submitPaymentFrom = true;

        function disabledSubmitButton(formId) {
            console.log(formId);
            console.log('Form is submit now ');
            if (submitPaymentFrom) {
                submitPaymentFrom = false;
                $('#' + formId + '').submit();
            }

        }

        function getAllAccount() {
            $.ajax({
                url: "{{ URL::to('get-all-types-account') }}",
                type: 'GET',
                data: {},
                success: function(account) {
                    accounts = account['data']['accounts'];
                    parties = account['data']['parties'];
                }
            });
        }


        getAllAccount();

        function fetchId(id, displayId) {
            var accountId = $('#' + id + '').val();
            getAccountBalance(accountId, displayId)
        }

        function getAccountBalance(id, displayId) {
            accounts.forEach((account) => {
                if (account['id'] == id) {
                    $('#' + displayId + '').val(account['balance'])
                }
            })

            if (displayId == 'to-account-prev-balance') {
                calculateTotalPayment('tableBody', 'to-account-prev-balance', 'updated-balance', 'total-payments')
            } else {
                calculateTotalPayment('tableBodyFrom', 'from-account-prev-balance', 'from-updated-balance',
                    'from-total-payments')
            }
        }

        function fetchParticularBalnce(particular, particularId, balanceId) {
            var particular = $('#' + particular + '').val();
            var particularId = $('#' + particularId + '').val();

            if (particular == 'Party') {
                var balance = 0;
                parties.forEach((party) => {
                    if (party['id'] == particularId) {
                        balance = party['balance'];
                    }
                })
            }

            if (particular == 'Account') {
                accounts.forEach((account) => {
                    if (account['id'] == particularId) {
                        balance = account['balance'];
                    }
                })
            }

            $('#' + balanceId + '').val(balance);
        }

        function fetchParticulars(particularType, typeId, balanceId) {
            var type = $('#' + particularType + '').val();

            $('#' + typeId + '').html('<option value="-1">Select One</option>');
            var particularsList = `<option value="-1">Select One</option>`;

            if (type == 'Party') {
                var partyType = $('#' + particularType + '').find('option:selected').attr('party-type');
                console.log(partyType);
                $('#party_name').val(partyType);
                $('#party_name_customer').val(partyType);


                // Process for Party Types
                parties.forEach((party) => {
                    if (partyType == 'Marka' && party['type'] == 'Marka') {
                        particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                    }

                    if (partyType == 'Driver' && party['type'] == 'Driver') {
                        particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                    }

                    if (partyType == 'Customer' && party['type'] == 'Customer') {
                        console.log('particular' + particularType);
                        particularsList +=
                            `<option value="${party['id']}">${party['name']}</option>`; // Add customers to the list

                        if (particularType == 'particular') {
                            console.log('Enter in particular');
                            $('#supplier-div-payment').css('display', 'block');
                        } else {
                            $('#supplier-div-from').css('display', 'block');
                        }
                    }
                    if (partyType == 'Supplier' && party['type'] == 'Supplier') {
                        console.log('particular' + particularType);
                        particularsList +=
                            `<option value="${party['id']}">${party['name']}</option>`; // Add customers to the list

                        if (particularType == 'particular') {
                            console.log('Enter in particular');
                            $('#supplier-div-payment').css('display', 'block');
                        } else {
                            $('#supplier-div-from').css('display', 'block');
                        }
                    }
                });

                if (partyType !== 'Customer') {
                    $('#supplier-div-payment').css('display', 'none');
                    $('#supplier-div-from').css('display', 'none');
                }
            }

            if (type == 'Account') {
                $('#supplier-div-payment').css('display', 'none');
                $('#supplier-div-from').css('display', 'none');

                accounts.forEach((account) => {
                    particularsList += `<option value="${account['id']}">${account['account_name']}</option>`;
                });
            }




            $('#' + typeId + '').html(particularsList);
            $('#' + balanceId + '').val('');
            $('#' + typeId + '').select2();
        }


        function fetchSupplierCustomers(supplierId, displayId, balanceId) {
            $('#' + displayId + '').html('<option value="-1">Select One</option>');
            var supplierIdGet = $('#' + supplierId + '').val();
            var particularsList = `<option value="-1">Select One</option>`;
            parties.forEach((party) => {
                if (party['type'] == 'Customer' && party['supplier_id'] == supplierIdGet) {
                    particularsList += `<option value="${party['id']}">${party['name']}</option>`;
                }
            })

            $('#' + displayId + '').html(particularsList);
            $('#' + displayId + '').select2();
            $('#' + balanceId + '').val('');
        }

        function addToCart(particular, particularId, payment, remarks, tableBody) {
            var particular = $('#' + particular + '').val();
            var particularId = $('#' + particularId + '').val();
            var paymentAmount = $('#' + payment + '').val();
            var remarksValue = $('#' + remarks + '').val();

            var name = '';
            var type = '';
            if (particular == 'Party') {
                parties.forEach((party) => {
                    if (party['id'] == particularId) {
                        name = party['name'];
                        type = party['type'];
                    }
                })
            }

            if (particular == 'Account') {
                accounts.forEach((account) => {
                    if (account['id'] == particularId) {
                        name = account['account_name'];
                    }
                })
                type = 'Cash Account';
            }

            $payType = 'receivedPayment';
            if (tableBody == 'tableBody') {
                $payType = 'Payment';
            }

            if (particularId != -1) {
                var tableHtml = `<tr id="${counter}">
                            <td><p>${type}</p>
                                <input type="text" required hidden name="particular[]" readonly value="${particular}" class="form-control">
                            </td>
                            <td><input type="text" required name="particularId[]" readonly value="${particularId}" class="form-control"></td>
                            <td><input type="text" required name="particularName[]" readonly value="${name}" class="form-control"></td>
                            <td><input type="text" required name="payment[]" onkeyup="updateTotals('${$payType}')" value="${paymentAmount}" class="form-control"></td>
                            <td><input type="text" name="remarks[]" value="${remarksValue}" class="form-control"></td>
                            <td><button class="btn btn-danger btn-sm" type="button" onclick="removeItem(${counter++},'${$payType}')">X</button></td>
                        </tr>`;
                $('#' + tableBody + '').append(tableHtml);
                $('#' + payment + '').val('');
                $('#' + remarks + '').val('');

                if (tableBody == 'tableBody') {
                    calculateTotalPayment('tableBody', 'to-account-prev-balance', 'updated-balance', 'total-payments')
                } else {
                    calculateTotalPayment('tableBodyFrom', 'from-account-prev-balance', 'from-updated-balance',
                        'from-total-payments')
                }

            } else {
                $.toast({
                    heading: 'Information',
                    text: "Please Select Particular",
                    icon: 'error',
                    loader: true, // Change it to false to disable loader
                    loaderBg: 'error', // To change the background
                    postion: "top - right"
                })
            }

        }

        function removeItem(itemtId, payType) {
            $('#' + itemtId + '').remove();

            if (payType == 'receivedPayment') {
                calculateTotalPayment('tableBodyFrom', 'from-account-prev-balance', 'from-updated-balance',
                    'from-total-payments')
            } else {
                calculateTotalPayment('tableBody', 'to-account-prev-balance', 'updated-balance', 'total-payments')
            }

        }

        function updateTotals(type) {
            console.log(type);
            console.log('function is call now');
            if (type == 'Payment') {
                calculateTotalPayment('tableBody', 'to-account-prev-balance', 'updated-balance', 'total-payments')
            } else {
                calculateTotalPayment('tableBodyFrom', 'from-account-prev-balance', 'from-updated-balance',
                    'from-total-payments')
            }
        }

        calculateTotalPayment = (tableId, accuntBalanceId, previousBalId, totalPayID) => {
            // console.log('funciton is call ');

            var table = document.getElementById(tableId);

            // console.log(table);
            // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
            var totalPayments = 0;
            var balnc = 0;

            for (i = 0; i < table.rows.length; i++) {

                // GET THE CELLS COLLECTION OF THE CURRENT ROW.
                var objCells = table.rows.item(i).cells;
                console.log(objCells);
                console.log(objCells.item(3));
                var itemValue = objCells.item(3).children[0].value;
                console.log("new coming value" + itemValue)
                totalPayments = +totalPayments + +itemValue;
                // console.log(total_recv_payments);


            }
            console.log(totalPayments);
            var currrt_balance = $('#' + accuntBalanceId + '').val();
            // // console.log("prevoius balnc"+currrt_balance);
            if (tableId == 'tableBody') {
                balnc = currrt_balance - totalPayments;
            } else {
                balnc = +currrt_balance + +totalPayments;

            }


            $('#' + previousBalId + '').val(balnc);
            $('#' + totalPayID + '').val(totalPayments);


        }
    </script>
@endsection
<!-- container -->
