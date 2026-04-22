@extends('adminPanel/master')

@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .list-group-item.active {
            background-color: #007bff;
            color: white;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Page Title -->

        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-5">
                                <h4 class="page-title">Manage Deals</h4>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn" style="background-color: black; color:white;"
                                        data-bs-toggle="modal" data-bs-target="#standerd-modal">
                                        Add New Deal
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Deal Modal -->
                        <div id="standerd-modal" class="modal fade" tabindex="-1" role="dialog"
                            aria-labelledby="standard-modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="standard-modalLabel">Add New Deal</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-hidden="true"></button>
                                    </div>
                                    <form id="addDealForm" action="{{ route('deal.store') }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <label class="form-label">Select Deal Product</label>
                                                    <select name="deal_id" id="deal_ids" class="form-control" required>
                                                        <option value="">Select Deal</option>
                                                        @foreach ($deals_products as $deal)
                                                            <option value="{{ $deal->id }}">
                                                                {{ $deal->product_variant_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('deal_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label">Search Products</label>
                                                    <input type="text" id="product_search" class="form-control"
                                                        placeholder="Search by name or code..." onkeyup="searchProduct()"
                                                        autofocus>
                                                    <ul id="product_list" class="list-group"
                                                        style="max-height: 150px; overflow-y: auto; display: none; position: absolute; z-index: 1000; width: 40%; background: #fff; border: 1px solid #ccc;">
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="product-table" class="table  table-sm w-100">
                                                    <thead style="background-color: black; color:white;">
                                                        <tr>
                                                            <th class="d-none"></th>
                                                            <th class="col-3">Name</th>
                                                            <th class="col-2">Retail Price</th>
                                                            <th class="col-2">Qty</th>
                                                            <th class="col-2">Total</th>
                                                            <th class="col-2 text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="product-table-body"></tbody>
                                                    <tfoot>
                                                        <tr id="final-total-row">
                                                            <td colspan="4" class="text-end"><strong>Deal Total:</strong>
                                                            </td>
                                                            <td colspan="2">
                                                                <input type="number" id="final-total-value"
                                                                    name="deal_total" class="form-control w-100 text-end"
                                                                    value="0.00" readonly />
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <button type="submit" class="btn float-end mb-3"
                                                style="background-color: black; color:white;">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Deals List Table -->
                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table w-100">
                                <thead style="background-color: black; color:white;">
                                    <tr>
                                        <th class="col-1">Sr#</th>
                                        <th class="col-2">Deal Name</th>
                                        <th class="col-2">Products</th>
                                        <th class="col-2">Quantity</th>
                                        <th class="col-2">Product Price</th>
                                        <th class="col-2">Total Price</th>
                                        <th class="col-1 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deals as $deal)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $deal->products->product_variant_name ?? 'N/A' }}</td>
                                            <td>
                                                @foreach ($deal->deal_item as $item)
                                                    {{ $item->products->product_variant_name ?? 'N/A' }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($deal->deal_item as $item)
                                                    {{ $item->product_variant_qty }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($deal->deal_item as $item)
                                                    {{ $item->total_price }}<br>
                                                @endforeach
                                            </td>
                                            <td>{{ $deal->deal_total ?? '0.00' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('deal.delete', $deal->id) }}"
                                                    class="action-icon text-danger delete-deal"
                                                    data-id="{{ $deal->id }}">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="action-icon text-dark"
                                                    data-bs-toggle="modal" data-bs-target="#edit-modal"
                                                    data-id="{{ $deal->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Edit Deal Modal -->
                    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="edit-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="edit-modalLabel">Edit Deal</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-hidden="true"></button>
                                </div>
                                <form id="editDealForm" action="{{ route('deal.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" id="deal_id" name="edit_deal_id">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label">Select Deal Product</label>
                                                <select name="deal_id" id="edit_deal_id" class="form-control" required>
                                                    <option value="">Select Deal</option>
                                                    @foreach ($deals_products as $deal)
                                                        <option value="{{ $deal->id }}">
                                                            {{ $deal->product_variant_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('deal_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Search Products</label>
                                                <input type="text" id="edit_product_search" class="form-control"
                                                    placeholder="Search by name or code..." onkeyup="searchEditProduct()"
                                                    autofocus>
                                                <ul id="edit_product_list" class="list-group"
                                                    style="max-height: 150px; overflow-y: auto; display: none; position: absolute; z-index: 1000; width: 40%; background: #fff; border: 1px solid #ccc;">
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table id="product-table-edit" class="table table-sm w-100">
                                                <thead style="background-color: black; color:white;">
                                                    <tr>
                                                        <th class="d-none"></th>
                                                        <th class="col-3">Name</th>
                                                        <th class="col-2">Retail Price</th>
                                                        <th class="col-2">Qty</th>
                                                        <th class="col-2">Total</th>
                                                        <th class="col-2 text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-table-body-edit"></tbody>
                                                <tfoot>
                                                    <tr id="edit-final-total-row">
                                                        <td colspan="4" class="text-end"><strong>Deal Total:</strong>
                                                        </td>
                                                        <td colspan="2">
                                                            <input type="number" id="edit-final-total-value"
                                                                name="edit_deal_total" class="form-control w-100 text-end"
                                                                value="0.00" readonly />
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <button type="submit" class="btn float-end mb-3"
                                            style="background-color: black; color:white;">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <!-- DataTable Initialization -->
    <script>
        $(document).ready(function() {
            $("#scroll-horizontal-datatable").DataTable({
                scrollX: true,
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                }
            });

            // Delete confirmation
            $('.delete-deal').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

    <!-- Edit Modal Script -->
    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            $.ajax({
                url: "{{ url('deal/deals') }}/" + id,
                method: 'GET',
                success: function(response) {
                    if (response.data) {
                        $('#edit_deal_id').val(response.data.deal_id);
                        $('#deal_id').val(response.data.id);
                        $('#edit-final-total-value').val(parseFloat(response.data.deal_total || 0)
                            .toFixed(2));

                        var productTableBody = $('#product-table-body-edit');
                        productTableBody.empty();

                        response.data.products.forEach(function(product) {
                            productTableBody.append(`
                                <tr>
                                    <td class="d-none"><input type="number" name="pro_id[]" value="${product.id}" readonly class="form-control" /></td>
                                    <td class="name-column"><input type="text" name="pro_name[]" value="${product.name}" readonly class="form-control" /></td>
                                    <td class="name-column"><input type="number" name="retail_price[]" value="${product.retail}" readonly class="form-control retail-price" /></td>
                                    <td class="small-column"><input type="number" name="qty[]" id="quantity_${product.id}" value="${product.quantity}" min="1" class="form-control qty-input" oninput="calculateEditTotal(${product.id})" required /></td>
                                    <td class="name-column"><input type="number" name="total[]" id="total_${product.id}" value="${product.total}" class="form-control total-input" oninput="updateEditFinalTotal()" required /></td>
                                    <td class="action-btn"><button type="button" class="btn btn-danger remove-btn ms-4"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                </tr>
                            `);
                        });

                        updateEditFinalTotal();

                        $('.remove-btn').off('click').on('click', function() {
                            $(this).closest('tr').remove();
                            updateEditFinalTotal();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to load deal details.', 'error');
                }
            });
        });

        function calculateEditTotal(productId) {
            let qtyInput = $(`#quantity_${productId}`);
            let qty = parseFloat(qtyInput.val()) || 0;
            let retailPrice = parseFloat(qtyInput.closest('tr').find('.retail-price').val()) || 0;

            if (qty < 1) {
                Swal.fire('Error', 'Quantity must be at least 1.', 'error');
                qtyInput.val(1);
                qty = 1;
            }

            let total = qty * retailPrice;
            $(`#total_${productId}`).val(total.toFixed(2));
            updateEditFinalTotal();
        }

        function updateEditFinalTotal() {
            let finalTotal = 0;
            $('#product-table-body-edit .total-input').each(function() {
                finalTotal += parseFloat($(this).val()) || 0;
            });
            $('#edit-final-total-value').val(finalTotal.toFixed(2));
        }
    </script>

    <!-- Add Modal Script -->
    <script>
        function addToCart(productId) {
            if ($(`#product-table-body input[name="pro_id[]"][value="${productId}"]`).length > 0) {
                Swal.fire('Error', 'This product has already been added.', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('purchase.fetch.product.details', ':id') }}".replace(':id', productId),
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const product = response.data;
                        const initialQty = 1;
                        const initialTotal = (initialQty * product.retail).toFixed(2);

                        $('#product-table-body').append(`
                            <tr data-product-id="${product.id}">
                                <td class="d-none"><input type="number" name="pro_id[]" value="${product.id}" readonly class="form-control" /></td>
                                <td class="name-column"><input type="text" name="pro_name[]" value="${product.name}" readonly class="form-control" /></td>
                                <td class="name-column"><input type="number" name="retail_price[]" value="${product.retail}" readonly class="form-control retail-price" /></td>
                                <td class="small-column"><input type="number" name="qty[]" id="quantity_${product.id}" value="${initialQty}" min="1" class="form-control qty-input" oninput="calculateTotal(${product.id})" required /></td>
                                <td class="small-column"><input type="number" name="total[]" id="total_${product.id}" value="${initialTotal}" class="form-control total-input" oninput="updateFinalTotal()" required /></td>
                                <td class="action-btn"><button type="button" class="btn btn-danger remove-btn ms-4"><i class="mdi mdi-trash-can-outline"></i></button></td>
                            </tr>
                        `);

                        updateFinalTotal();

                        $('.remove-btn').off('click').on('click', function() {
                            $(this).closest('tr').remove();
                            updateFinalTotal();
                        });
                    } else {
                        Swal.fire('Error', 'Product not found.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'An error occurred while fetching product details.', 'error');
                }
            });
        }

        function calculateTotal(productId) {
            let qtyInput = $(`#quantity_${productId}`);
            let qty = parseFloat(qtyInput.val()) || 0;
            let retailPrice = parseFloat(qtyInput.closest('tr').find('.retail-price').val()) || 0;

            if (qty < 1) {
                Swal.fire('Error', 'Quantity must be at least 1.', 'error');
                qtyInput.val(1);
                qty = 1;
            }

            let total = qty * retailPrice;
            $(`#total_${productId}`).val(total.toFixed(2));
            updateFinalTotal();
        }

        function updateFinalTotal() {
            let finalTotal = 0;
            $('#product-table-body .total-input').each(function() {
                finalTotal += parseFloat($(this).val()) || 0;
            });
            $('#final-total-value').val(finalTotal.toFixed(2));
        }
    </script>

    <!-- Edit Add to Cart -->
    <script>
        function editaddToCart(productId) {
            if ($(`#product-table-body-edit input[name="pro_id[]"][value="${productId}"]`).length > 0) {
                Swal.fire('Error', 'This product has already been added.', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('purchase.fetch.product.details', ':id') }}".replace(':id', productId),
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const product = response.data;
                        const initialQty = 1;
                        const initialTotal = (initialQty * product.retail).toFixed(2);

                        $('#product-table-body-edit').append(`
                            <tr>
                                <td class="d-none"><input type="number" name="pro_id[]" value="${product.id}" readonly class="form-control" /></td>
                                <td class="name-column"><input type="text" name="pro_name[]" value="${product.name}" readonly class="form-control" /></td>
                                <td class="name-column"><input type="number" name="retail_price[]" value="${product.retail}" readonly class="form-control retail-price" /></td>
                                <td class="small-column"><input type="number" name="qty[]" id="quantity_${product.id}" value="${initialQty}" min="1" class="form-control qty-input" oninput="calculateEditTotal(${product.id})" required /></td>
                                <td class="name-column"><input type="number" name="total[]" id="total_${product.id}" value="${initialTotal}" class="form-control total-input" oninput="updateEditFinalTotal()" required /></td>
                                <td class="action-btn"><button type="button" class="btn btn-danger remove-btn ms-4"><i class="mdi mdi-trash-can-outline"></i></button></td>
                            </tr>
                        `);

                        updateEditFinalTotal();

                        $('.remove-btn').off('click').on('click', function() {
                            $(this).closest('tr').remove();
                            updateEditFinalTotal();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'An error occurred while fetching product details.', 'error');
                }
            });
        }
    </script>

    <!-- Product Search for Add Modal -->
    <script>
        let currentIndex = -1;
        let cache = {};

        function searchProduct() {
            const query = $('#product_search').val().trim();
            const productList = $('#product_list');

            if (query.length < 2) {
                productList.hide().html('');
                currentIndex = -1;
                return;
            }

            if (cache[query]) {
                displayProductList(cache[query], productList, 'selectProduct');
                return;
            }

            $.ajax({
                url: "{{ url('/deal/fetch-products-for-deal') }}",
                type: "GET",
                data: {
                    query
                },
                success: function(data) {
                    if (data.length > 0) {
                        cache[query] = data;
                        displayProductList(data, productList, 'selectProduct');
                    } else {
                        productList.html('<li class="list-group-item text-muted">No products found</li>')
                            .show();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to search products.', 'error');
                }
            });
        }

        function selectProduct(id) {
            addToCart(id);
            $('#product_search').val('');
            $('#product_list').hide();
            currentIndex = -1;
        }
    </script>

    <!-- Product Search for Edit Modal -->
    <script>
        let editCurrentIndex = -1;
        let editCache = {};

        function searchEditProduct() {
            const query = $('#edit_product_search').val().trim();
            const productList = $('#edit_product_list');

            if (query.length < 2) {
                productList.hide().html('');
                editCurrentIndex = -1;
                return;
            }

            if (editCache[query]) {
                displayProductList(editCache[query], productList, 'selectEditProduct');
                return;
            }

            $.ajax({
                url: "{{ url('/deal/fetch-products-for-deal') }}",
                type: "GET",
                data: {
                    query
                },
                success: function(data) {
                    if (data.length > 0) {
                        editCache[query] = data;
                        displayProductList(data, productList, 'selectEditProduct');
                    } else {
                        productList.html('<li class="list-group-item text-muted">No products found</li>')
                            .show();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to search products.', 'error');
                }
            });
        }

        function selectEditProduct(id) {
            editaddToCart(id);
            $('#edit_product_search').val('');
            $('#edit_product_list').hide();
            editCurrentIndex = -1;
        }

        function displayProductList(data, productList, selectFunction) {
            let listItems = '';
            data.forEach((product) => {
                listItems +=
                    `<li class="list-group-item" style="cursor: pointer;" onclick="${selectFunction}('${product.id}')">${product.code} / ${product.product_variant_name}</li>`;
            });
            productList.html(listItems).show();
        }

        // Unified Keydown Handler
        ['#product_search', '#edit_product_search'].forEach(selector => {
            $(selector).on('keydown', function(e) {
                const productList = $(this).next('.list-group');
                const items = productList.find('.list-group-item');
                const isEdit = selector === '#edit_product_search';
                let currentIdx = isEdit ? editCurrentIndex : currentIndex;

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentIdx >= 0 && items.length > 0) {
                        items.eq(currentIdx).click();
                    }
                } else if (e.key === 'ArrowDown') {
                    if (items.length > 0) {
                        currentIdx = (currentIdx + 1) % items.length;
                        highlightItem(items, currentIdx);
                        if (isEdit) editCurrentIndex = currentIdx;
                        else currentIndex = currentIdx;
                    }
                } else if (e.key === 'ArrowUp') {
                    if (items.length > 0) {
                        currentIdx = (currentIdx - 1 + items.length) % items.length;
                        highlightItem(items, currentIdx);
                        if (isEdit) editCurrentIndex = currentIdx;
                        else currentIndex = currentIdx;
                    }
                }
            });
        });

        function highlightItem(items, index) {
            items.removeClass('active');
            if (index >= 0 && items.length > 0) {
                items.eq(index).addClass('active');
            }
        }
    </script>

    <!-- Form Validation -->
    <script>
        ['#addDealForm', '#editDealForm'].forEach(formId => {
            $(formId).on('submit', function(e) {
                const dealId = $(this).find('[name="deal_id"]').val();
                const products = $(this).find('[name="pro_id[]"]').length;

                if (!dealId) {
                    e.preventDefault();
                    Swal.fire('Error', 'Please select a deal product.', 'error');
                } else if (products === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Please add at least one product.', 'error');
                }
            });
        });
    </script>
@endsection
