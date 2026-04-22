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
                    <div class="page-title-right">
                    </div>
                    {{-- <h4 class="page-title">Parties</h4> --}}
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
                                <h4 class="page-title">Parties List</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">
                                        Add New
                                    </button> --}}
                                    <button type="button" class="btn" style="background-color: black; color:white;"
                                        data-bs-toggle="modal" data-bs-target="#standard-modal"><i
                                            class="mdi mdi-plus-circle me-2"></i>Add New Party</button>
                                </div>
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered w-100 nowrap">
                                <thead style="background-color: black; color:white;">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Opening Balance</th>
                                        <th>Balance</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($parties)
                                        @foreach ($parties as $party)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $party->name }}
                                                </td>

                                                <td>
                                                    {{ $party->type }}
                                                </td>

                                                <td>
                                                    {{ $party->phone_number }}
                                                </td>

                                                <td>
                                                    {{ $party->email }}
                                                </td>

                                                <td>
                                                    {{ $party->opening_balance }}
                                                </td>
                                                <td>
                                                    {{ $party->balance }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" data-id="{{ $party->id }}"
                                                        class="action-icon text-success" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal"> <i
                                                            class="mdi mdi-square-edit-outline"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {{ $parties->links() }}
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add New Party</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-party') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Party Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Type</label>
                                        <select name="type" class="form-control" required id="particularType"
                                            onchange="toggleDiscountType()">
                                            <option selected disabled>Select One</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Customer">Customer</option>
                                            <option value="Both">Both</option>
                                        </select>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Phone#</label>
                                        <input type="text" name="phone_number" class="form-control"
                                            placeholder="Phone Number">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Email</label>
                                        <input type="text" name="email" class="form-control" placeholder="Party Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="number" name="openingBalance" value="0" class="form-control"
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

                                <div class="col-sm-6 d-none" id="discountType">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Discount Type</label>
                                        <select onchange="selectType()" class="form-control" name="overall_discount_type"
                                            id="overall_discount_type">
                                            <option value="">Select Type</option>
                                            <option value="1">Bill Discount</option>
                                            <option value="2">Product Discount</option>
                                        </select>
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-sm-6 d-none" id="discountFields">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Discount Tpe</label>
                                        <select class="form-control" name="discount_type" id="discount_type">
                                            <option value="">Select Type</option>
                                            <option value="Fixed">Fixed</option>
                                            <option value="Percentage">Percantage</option>
                                        </select>
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 d-none" id="discountValueField">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Discount Value</label>
                                        <input type="number" name="discount_value" class="form-control"
                                            placeholder="Discount value">
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 d-none" id="product_field">
                                    <label for="example-input-normal" class="form-label">Select Product</label>
                                    <select class="form-control" name="product_id" id="product_id">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $pro)
                                            <option value="{{ $pro->id }}">{{ $pro->product_variant_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-none" id="table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Product Name</th>
                                            <th>Cost Price</th>
                                            <th>Retail Price</th>
                                            <th>Discount type</th>
                                            <th>Discount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product_table_body">
                                        <!-- Product rows will be appended here -->
                                    </tbody>
                                </table>
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
        </div><!-- /.modal -->

        <!-- edit modal -->
        <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modalLabel">Edit Party</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ route('update.party') }}" method="post">
                        @csrf
                        <input type="hidden" name="partyId" id="party-id-field">
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Name</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Type</label>
                                        <select name="type" onchange="selectTypeEdit()" disabled="true"
                                            class="form-control particularType" required id="partiyType">
                                            <option value="Supplier">Supplier</option>
                                            <option value="Customer">Customer</option>
                                            <option value="Both">Both</option>
                                        </select>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12 suppliers-list-div" style="display:none" id="supplier-list-div">
                                    <div class="mb-3">

                                        <select name="supplier_id" class="form-control supplier_id" id="supplier-list"
                                            disabled="true">

                                            @isset($suppliers)
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('type')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Phone#</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control"
                                            placeholder="Phone Number">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Party Email</label>
                                        <input type="text" id="email" name="email" class="form-control">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Balance</label>
                                        <input type="text" readonly id="openingBalance" name="openingBalance"
                                            value="0" class="form-control">
                                        @error('openingBalance')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Company Name
                                            <span>Optional</span></label>
                                        <input type="text" id="company_name" name="company_name"
                                            class="form-control">
                                        @error('company_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Address</label>
                                        <input type="text" id="address" name="address" class="form-control">
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
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
        </div><!-- /.modal -->


        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>



    <script>
        console.log('page is load now');

        function selectType() {
            var type = $('#particularType').val();
            if (type == 'Customer') {
                // $('#suppliers-list-div').css('display', 'block');
                $('#suppliers-list').attr('required', true);
            } else {
                $('#suppliers-list-div').css('display', 'none');
                $('#suppliers-list').attr('required', false);
            }
        }
    </script>

    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var party = button.data('id');
            $(this).find('#party-id-field').val(party);
            $.ajax({
                type: 'GET',
                url: 'get-party/' + party,
            }).done(function(data) {
                $('#name').val(data.data.name);
                $('#email').val(data.data.email);
                $('#partiyType').val(data.data.type);
                $('.supplier_id').val(data.data.supplier_id);
                $('#openingBalance').val(data.data.opening_balance);
                $('#company_name').val(data.data.company_name);
                $('#address').val(data.data.address);
                $('#phone_number').val(data.data.phone_number);
                // Dynamically update the selects
                if ($('#partiyType').val() == 'Customer') {

                    $('.supplier-list').val(data.supplier_id); // Select the supplier
                } else {
                    $('#supplier-list-div').hide(); // Hide the supplier select
                }
                $('#edit-modal').modal('show');
            });
        });

        function selectTypeEdit() {
            var type = $('#partiyType').val();
            if (type == 'Customer') {
                // $('#supplier-list-div').css('display', 'block');
                $('#supplier-list').attr('required', true);
            } else {
                $('#supplier-list-div').css('display', 'none');
                $('#supplier-list').attr('required', false);
            }
        }
    </script>
    <script>
        function toggleDiscountType() {
            const particularType = document.getElementById('particularType');
            const discountType = document.getElementById('discountType');

            if (particularType.value === 'Customer' || particularType.value === 'Both') {
                discountType.classList.remove('d-none'); // Show the Discount Type section
            } else {
                discountType.classList.add('d-none'); // Hide the Discount Type section
            }
        }
        // JavaScript function to toggle visibility of discount fields
        function selectType() {
            var partyType = document.getElementById('overall_discount_type').value;
            console.log(partyType);

            // Check if the selected party type is '1' (bill Discount) or '2' (Product Discount)
            if (partyType === '1') {
                // Show the discount fields for bill discount
                document.getElementById('discountFields').classList.remove('d-none');
                document.getElementById('discountValueField').classList.remove('d-none');
                document.getElementById('product').classList.add('d-none'); // Hide product fields
                document.getElementById('table').classList.add('d-none'); // Hide table for product discount
            } else if (partyType === '2') {
                // Show the product selection and table for product discount
                document.getElementById('product_field').classList.remove('d-none');
                document.getElementById('table').classList.remove('d-none');
                document.getElementById('discountFields').classList.add('d-none'); // Hide discount fields
                document.getElementById('discountValueField').classList.add('d-none'); // Hide discount value field
            } else {
                // Hide all discount fields if no discount type is selected
                document.getElementById('discountFields').classList.add('d-none');
                document.getElementById('discountValueField').classList.add('d-none');
                document.getElementById('product_field').classList.add('d-none');
                document.getElementById('table').classList.add('d-none');
            }
        }
    </script>


    <script>
        // Event listener for product selection
        $('#product_id').on('change', function() {
            var productId = $(this).val(); // Get selected product ID

            if (productId) {
                // Make an AJAX request to fetch product details (cost, retail price, etc.)
                $.ajax({
                    type: 'GET',
                    url: '{{ route('product.get', ['id' => 0]) }}'.replace('0', productId),
                    success: function(data) {
                        console.log(data); // Debugging line to check the data structure

                        // Check if data is valid
                        if (data && data.data.product) {
                            var product = data.data.product; // Product data from the backend

                            // Prepare HTML with input fields to display in table
                            var productRow = `
                            <tr id="product_${product.id}">
                                <td><input type="number" name="product_id[]" class="form-control" value="${product.id}" ></td>
                                <td><input type="text" name="product_name[]" class="form-control" value="${product.name}" ></td>
                                <td><input type="number" name="cost_price[]" class="form-control" value="${product.cost_price}" id="cost_price_${product.id}" onchange="updateDiscount(${product.id})"></td>
                                <td><input type="number" name="retail_price[]" class="form-control" value="${product.retail_price}" id="retail_price_${product.id}" onchange="updateDiscount(${product.id})"></td>
                                <td>
                                <select name="discount_type[]" class="form-control" id="discount_${product.id}" onchange="updateDiscount(${product.id})">
                                    <option value="Fixed">Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                                </td>
                                <td><input type="number" name="discount[]" class="form-control" id="discount_${product.id}" onchange="updateDiscount(${product.id})"></td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="removeProduct(${product.id})">Remove</button>
                                </td>
                            </tr>
                        `;

                            // Append the product row to the table body (assuming the table body has an id of #product_table_body)
                            $('#product_table_body').append(productRow);
                        } else {
                            alert('Product details not found');
                        }
                    },
                    error: function() {
                        alert('Failed to load product details.');
                    }
                });
            } else {
                alert('Please select a product');
            }
        });

        // Function to remove a product from the table
        function removeProduct(productId) {
            $('#product_' + productId).remove(); // Remove product row from the table
        }
    </script>
@endsection
<!-- container -->
