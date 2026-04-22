@extends('adminPanel.master')

@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <!-- Recipe Creation Modal -->
                    <div class="modal fade" id="standerd-modal" tabindex="-1" aria-labelledby="standard-modalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title" id="standard-modalLabel">Add New Recipe</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="purchaseForm" action="{{ route('recipes.store') }}" method="post">
                                        @csrf
                                        <div id="ingredients-container" style="display: none;"></div>

                                        <div class="row g-3">
                                            <div class="col-md-7">
                                                <label for="serviceItem" class="form-label">Select Service Item</label>
                                                <div class="input-group">
                                                    <select name="service_item_id" id="serviceItem" class="form-select">
                                                        <option value="">Select Service Item</option>
                                                        @foreach ($serviceItems as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-cost="{{ $item->rates->cost_price ?? 0 }}"
                                                                data-retail="{{ $item->rates->retail_price ?? 0 }}">
                                                                {{ $item->product_variant_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" id="servicePriceBtn" class="btn ms-1"
                                                        style="display: none; background-color: black; color:white;">
                                                        Cost: <span id="serviceCost">0.00</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="ingredientItem" class="form-label">Select Ingredient
                                                    Item</label>
                                                <select id="ingredientItem" class="form-select">
                                                    <option value="">Select Ingredient Item</option>
                                                    @foreach ($ingredients as $ingredient)
                                                        <option value="{{ $ingredient->id }}"
                                                            data-cost="{{ $ingredient->rates->cost_price ?? 0 }}">
                                                            {{ $ingredient->product_variant_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="table-responsive mt-3">
                                            <table id="product-table" class="table table-sm">
                                                <thead style="background-color: black; color:white;">
                                                    <tr>
                                                        <th class="text-center">SR#</th>
                                                        <th>Ingredient Name</th>
                                                        <th class="text-center">Cost</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Total Cost</th>
                                                        <th class="text-center" style="width: 85px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-table-body"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold py-2">Total:</td>
                                                        <td>
                                                            <input type="number" id="final-total-value" name="deal_total"
                                                                class="form-control" value="0.00" readonly />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="mt-3 text-end">
                                            <button type="submit" class="btn"
                                                style="background-color: black; color:white;">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Recipe Modal -->
                    <div class="modal fade" id="edit-recipe-modal" tabindex="-1" aria-labelledby="edit-recipe-modalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title" id="edit-recipe-modalLabel">Edit Recipe</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editRecipeForm" action="" method="post">
                                        @method('put')
                                        @csrf
                                        <input type="hidden" name="recipe_id" id="recipe_id">
                                        <div id="edit-ingredients-container" style="display: none;"></div>
                                        <div class="row g-3">
                                            <div class="col-md-7">
                                                <label for="editServiceItem" class="form-label">Service Item</label>
                                                <div class="input-group">
                                                    <select name="service_item_id" id="editServiceItem"
                                                        class="form-select" disabled>
                                                        <!-- Populated dynamically -->
                                                    </select>
                                                    <button type="button" id="editServicePriceBtn" class="btn ms-1"
                                                        style="display: none; background-color: black; color:white;">
                                                        Cost: <span id="editServiceCost">0.00</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="editIngredientItem" class="form-label">Select Ingredient
                                                    Item</label>
                                                <select id="editIngredientItem" class="form-select">
                                                    <option value="">Select Ingredient Item</option>
                                                    @foreach ($ingredients as $ingredient)
                                                        <option value="{{ $ingredient->id }}"
                                                            data-cost="{{ $ingredient->rates->cost_price ?? 0 }}">
                                                            {{ $ingredient->product_variant_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive mt-3">
                                            <table id="edit-product-table" class="table table-sm">
                                                <thead style="background-color: black; color:white;">
                                                    <tr>
                                                        <th class="text-center">SR#</th>
                                                        <th>Ingredient Name</th>
                                                        <th class="text-center">Cost</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Total Cost</th>
                                                        <th class="text-center" style="width: 85px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="edit-product-table-body"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold py-2">Total:</td>
                                                        <td>
                                                            <input type="number" id="edit-final-total-value"
                                                                name="deal_total" class="form-control" value="0.00"
                                                                readonly />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button type="submit" class="btn"
                                                style="background-color: black; color:white;">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Integrated Recipe List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Recipes List</h5>
                        <button type="button" class="btn" style="background-color: black; color:white;"
                            data-bs-toggle="modal" data-bs-target="#standerd-modal">
                            Add New Recipe
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                            <thead style="background-color: black; color:white;">
                                <tr>
                                    <th class="text-center">Sr#</th>
                                    <th>Service Item Name</th>
                                    <th class="text-center">Total Cost</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recipes as $index => $recipe)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $recipe->productVariant->product_variant_name ?? 'N/A' }}</td>
                                        <td class="text-center">{{ number_format($recipe->ingredient_total_cost, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('recipes.details', $recipe->id) }}"
                                                class="text-warning me-2">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="#" class="text-dark edit-recipe-button"
                                                data-id="{{ $recipe->id }}" data-bs-toggle="modal"
                                                data-bs-target="#edit-recipe-modal">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>


    <script>
        var submit_form = true;
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
    </script>

    <script>
        $(document).ready(function() {
            let createRowCount = 0;
            let createTotalCost = 0;
            let createIngredientsData = [];
            let createServiceCost = 0;

            let editRowCount = 0;
            let editTotalCost = 0;
            let editIngredientsData = [];
            let editServiceCost = 0;

            // Handle Service Item Selection for Creation
            $('#serviceItem').on('change', function() {
                const selectedOption = $(this).find(':selected');
                createServiceCost = parseFloat(selectedOption.data('cost')) || 0;
                const retailPrice = parseFloat(selectedOption.data('retail')) || 0;

                if (createServiceCost > 0 || retailPrice > 0) {
                    $('#serviceCost').text(createServiceCost.toFixed(2));
                    $('#servicePriceBtn').show();
                } else {
                    $('#servicePriceBtn').hide();
                }
            });

            // Add Ingredient to Creation Table
            $('#ingredientItem').on('change', function() {
                const ingredientId = $(this).val();
                if (!ingredientId) return;

                const ingredientName = $(this).find(':selected').text().trim();
                const costPrice = parseFloat($(this).find(':selected').data('cost')) || 0;

                if (createIngredientsData.some(item => item.id === ingredientId)) {
                    Swal.fire('Warning', 'This ingredient is already added!', 'warning');
                    $(this).val('');
                    return;
                }

                createRowCount++;
                const qty = 1;
                const rowTotal = costPrice * qty;

                const newRow = `
                    <tr data-ingredient-id="${ingredientId}">
                        <td class="text-center">${createRowCount}</td>
                        <td>${ingredientName}</td>
                        <td class="text-center">${costPrice.toFixed(2)}</td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm qty-input"
                                   data-cost="${costPrice}" value="${qty}" min="0" step="any" style="width: 80px;">
                        </td>
                        <td class="text-center"><span class="total-cost">${rowTotal.toFixed(2)}</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#product-table-body').append(newRow);
                createTotalCost += rowTotal;
                updateCreateTotal();

                createIngredientsData.push({
                    id: ingredientId,
                    quantity: qty,
                    cost: costPrice
                });
                updateCreateIngredientsInputs();
                bindCreateRowEvents();
                $(this).val('');
            });

            function bindCreateRowEvents() {
                $('#product-table .qty-input').off('change').on('change', function() {
                    const $this = $(this);
                    const cost = parseFloat($this.data('cost'));
                    const qty = parseFloat($this.val()) || 0;
                    if (qty < 0) {
                        $this.val(0);
                        return;
                    }
                    const rowTotal = cost * qty;
                    const $row = $this.closest('tr');
                    $row.find('.total-cost').text(rowTotal.toFixed(2));
                    const index = $row.index();
                    createIngredientsData[index].quantity = qty;
                    updateCreateTotal();
                    updateCreateIngredientsInputs();
                });

                $('#product-table .remove-row').off('click').on('click', function() {
                    const $row = $(this).closest('tr');
                    const index = $row.index();
                    createTotalCost -= parseFloat($row.find('.total-cost').text()) || 0;
                    createIngredientsData.splice(index, 1);
                    $row.remove();
                    createRowCount--;
                    renumberCreateRows();
                    updateCreateTotal();
                    updateCreateIngredientsInputs();
                });
            }


            // Edit Modal Functionality

            $(document).on('click', '.edit-recipe-button', function() {
                const recipeId = $(this).data('id');
                $('#recipe_id').val(recipeId);

                // Use Blade route with a placeholder, then replace in JS
                const url = "{{ route('recipes.update', ':id') }}".replace(':id', recipeId);
                $('#editRecipeForm').attr('action', url);

                fetchEditData(recipeId);
            });


            function renumberCreateRows() {
                $('#product-table-body tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }


            function updateCreateTotal() {
                createTotalCost = 0;
                $('#product-table .total-cost').each(function() {
                    createTotalCost += parseFloat($(this).text()) || 0;
                });
                $('#final-total-value').val(createTotalCost.toFixed(2));
            }

            function updateCreateIngredientsInputs() {
                const $container = $('#ingredients-container');
                $container.empty();

                createIngredientsData.forEach((item, index) => {
                    $container.append(
                        `<input type="hidden" name="ingredient_id[${index}]" value="${item.id}">`);
                    $container.append(
                        `<input type="hidden" name="quantity[${index}]" value="${item.quantity}">`);
                    $container.append(
                        `<input type="hidden" name="cost_price[${index}]" value="${item.cost}">`);
                });
            }

            // Handle Form Submission with Cost Validation (Create)
            $('#purchaseForm').on('submit', function(e) {
                e.preventDefault();

                if (Math.abs(createTotalCost - createServiceCost) < 0.01) {
                    this.submit();
                } else {
                    Swal.fire({
                        title: 'Cost Mismatch',
                        text: `The total ingredient cost (${createTotalCost.toFixed(2)}) does not match the service item cost (${createServiceCost.toFixed(2)}). Update cost price?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update cost',
                        cancelButtonText: 'No, save anyway',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#ingredients-container').append(
                                '<input type="hidden" name="update_cost" value="1">');
                            this.submit();
                        } else {
                            $('#ingredients-container').append(
                                '<input type="hidden" name="update_cost" value="0">');
                            this.submit();
                        }
                    });
                }
            });

            // Edit Modal Functionality
            $(document).on('click', '.edit-recipe-button', function() {
                const recipeId = $(this).data('id');
                $('#recipe_id').val(recipeId);

                // ✅ Correct Blade route syntax (no spaces, proper quotes)
                const url = "{{ route('recipes.update', ':id') }}".replace(':id', recipeId);
                $('#editRecipeForm').attr('action', url);

                fetchEditData(recipeId);
            });


            function fetchEditData(recipeId) {
                $.get(`{{ route('recipes.get_edit_data', 'recipe_id') }}`.replace('recipe_id', recipeId), function(
                    data) {
                    populateEditForm(data);
                    $('#edit-recipe-modal').modal('show');
                }).fail(function(xhr) {
                    console.error('AJAX error:', xhr.responseText);
                    Swal.fire('Error', 'Failed to load recipe data.', 'error');
                });
            }

            function populateEditForm(data) {
                console.log('Edit data received:', data);
                const serviceItem = data.serviceItem;
                const ingredients = data.ingredients || [];

                $('#editServiceItem').empty().append(
                    `<option value="${serviceItem.id}">${serviceItem.product_variant_name}</option>`).val(
                    serviceItem.id);
                editServiceCost = parseFloat(serviceItem.rates.cost_price ?? 0);
                $('#editServiceCost').text(editServiceCost.toFixed(2));
                if (editServiceCost > 0) $('#editServicePriceBtn').show();
                else $('#editServicePriceBtn').hide();

                const tableBody = $('#edit-product-table-body');
                tableBody.empty();
                editRowCount = 0;
                editTotalCost = 0;
                editIngredientsData = [];
                ingredients.forEach(ingredient => {
                    editRowCount++;
                    const costPrice = parseFloat(ingredient.cost);
                    const qty = parseFloat(ingredient.quantity);
                    const rowTotal = costPrice * qty;
                    editTotalCost += rowTotal;
                    const newRow = `
                        <tr data-ingredient-id="${ingredient.id}">
                            <td class="text-center">${editRowCount}</td>
                            <td>${ingredient.name}</td>
                            <td class="text-center">${costPrice.toFixed(2)}</td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm qty-input"
                                       data-cost="${costPrice}" value="${qty}" min="0" step="any" style="width: 80px;">
                            </td>
                            <td class="text-center"><span class="total-cost">${rowTotal.toFixed(2)}</span></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(newRow);
                    editIngredientsData.push({
                        id: ingredient.id,
                        quantity: qty,
                        cost: costPrice
                    });
                });
                updateEditTotal();
                bindEditRowEvents();
            }

            // Add Ingredient to Edit Table
            $('#editIngredientItem').on('change', function() {
                const ingredientId = $(this).val();
                if (!ingredientId) return;

                const ingredientName = $(this).find(':selected').text().trim();
                const costPrice = parseFloat($(this).find(':selected').data('cost')) || 0;

                if (editIngredientsData.some(item => item.id === ingredientId)) {
                    Swal.fire('Warning', 'This ingredient is already added!', 'warning');
                    $(this).val('');
                    return;
                }

                editRowCount++;
                const qty = 1;
                const rowTotal = costPrice * qty;

                const newRow = `
                    <tr data-ingredient-id="${ingredientId}">
                        <td class="text-center">${editRowCount}</td>
                        <td>${ingredientName}</td>
                        <td class="text-center">${costPrice.toFixed(2)}</td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm qty-input"
                                   data-cost="${costPrice}" value="${qty}" min="0" step="any" style="width: 80px;">
                        </td>
                        <td class="text-center"><span class="total-cost">${rowTotal.toFixed(2)}</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#edit-product-table-body').append(newRow);
                editTotalCost += rowTotal;
                updateEditTotal();

                editIngredientsData.push({
                    id: ingredientId,
                    quantity: qty,
                    cost: costPrice
                });
                updateEditIngredientsInputs();
                bindEditRowEvents();
                $(this).val('');
            });

            function bindEditRowEvents() {
                $('#edit-product-table .qty-input').off('change').on('change', function() {
                    const $this = $(this);
                    const cost = parseFloat($this.data('cost'));
                    const qty = parseFloat($this.val()) || 0;
                    if (qty < 0) {
                        $this.val(0);
                        return;
                    }
                    const rowTotal = cost * qty;
                    const $row = $this.closest('tr');
                    $row.find('.total-cost').text(rowTotal.toFixed(2));
                    const index = $row.index();
                    editIngredientsData[index].quantity = qty;
                    updateEditTotal();
                    updateEditIngredientsInputs();
                });

                $('#edit-product-table .remove-row').off('click').on('click', function() {
                    const $row = $(this).closest('tr');
                    const index = $row.index();
                    editTotalCost -= parseFloat($row.find('.total-cost').text()) || 0;
                    editIngredientsData.splice(index, 1);
                    $row.remove();
                    editRowCount--;
                    renumberEditRows();
                    updateEditTotal();
                    updateEditIngredientsInputs();
                });
            }

            function renumberEditRows() {
                $('#edit-product-table-body tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            function updateEditTotal() {
                editTotalCost = 0;
                $('#edit-product-table .total-cost').each(function() {
                    editTotalCost += parseFloat($(this).text()) || 0;
                });
                $('#edit-final-total-value').val(editTotalCost.toFixed(2));
            }

            function updateEditIngredientsInputs() {
                const $container = $('#edit-ingredients-container');
                $container.empty();
                editIngredientsData.forEach((item, index) => {
                    $container.append(
                        `<input type="hidden" name="ingredient_id[${index}]" value="${item.id}">`);
                    $container.append(
                        `<input type="hidden" name="quantity[${index}]" value="${item.quantity}">`);
                    $container.append(
                        `<input type="hidden" name="cost_price[${index}]" value="${item.cost}">`);
                });
                // Add service_item_id explicitly if not already present
                $container.append(
                    `<input type="hidden" name="service_item_id" value="${$('#editServiceItem').val()}">`);
            }

            // Handle Form Submission with Cost Validation (Edit)
            $('#editRecipeForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submitting with data:', {
                    service_item_id: $('#editServiceItem').val(),
                    ingredient_id: editIngredientsData.map(item => item.id),
                    quantity: editIngredientsData.map(item => item.quantity),
                    cost_price: editIngredientsData.map(item => item.cost),
                    deal_total: editTotalCost,
                    update_cost: $('#edit-ingredients-container input[name="update_cost"]').val()
                });

                if (Math.abs(editTotalCost - editServiceCost) < 0.01) {
                    this.submit();
                } else {
                    Swal.fire({
                        title: 'Cost Mismatch',
                        text: `The total ingredient cost (${editTotalCost.toFixed(2)}) does not match the service item cost (${editServiceCost.toFixed(2)}). Update cost price?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update cost',
                        cancelButtonText: 'No, save anyway',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#edit-ingredients-container').append(
                                '<input type="hidden" name="update_cost" value="1">');
                            this.submit();
                        } else {
                            $('#edit-ingredients-container').append(
                                '<input type="hidden" name="update_cost" value="0">');
                            this.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
