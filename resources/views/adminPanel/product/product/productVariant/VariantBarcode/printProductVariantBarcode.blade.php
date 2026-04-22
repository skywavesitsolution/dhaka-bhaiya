@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #product-suggestions {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background: #fff;
        }

        #product-suggestions .dropdown-item {
            padding: 5px 10px;
            cursor: pointer;
        }

        #product-suggestions .dropdown-item:hover {
            background: #f0f0f0;
        }
    </style>
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
            </div>
        </div>
        <!-- end page title -->

        <div class="container mt-3">
            <h2 class="text-center mb-4">Print Product Barcode</h2>
            <div class="row mb-3">
                <div class="col-md-12">
                    <input type="text" id="product-search" class="form-control"
                        placeholder="Product Code / Product Name">
                    <ul id="product-suggestions" class="dropdown-menu"
                        style="display: none; position: absolute; width: 100%; z-index: 1000;">
                    </ul>
                </div>
            </div>

            <!-- Barcode Table -->
            <div class="row" style="max-height: 400px; overflow-y:auto;">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Retail Price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            <tr id="no-items-row">
                                <td colspan="4" class="text-center">No Items Added</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p>Total Labels: <span id="totalLabels">0</span></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button id="previewButton" class="btn btn-primary">Preview</button>
                    <button id="bulkPrintButton" style="display: none;" class="btn btn-success">Bulk Print</button>
                </div>
            </div>
            <div id="barcodePreview" style="margin-top: 20px; max-height: 400px; overflow-y:auto;">

            </div>
        </div>

        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#product-search").on("keyup", function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "{{ url('/product-variant-barcode/search-variants') }}",
                        method: "GET",
                        data: {
                            search: query
                        },
                        success: function(response) {
                            let suggestions = response;
                            let dropdown = $("#product-suggestions");
                            dropdown.empty().show();

                            if (suggestions.length > 0) {
                                suggestions.forEach(function(item) {
                                    dropdown.append(`
                                        <li class="dropdown-item"
                                            data-id="${item.id}"
                                            data-code="${item.code}"
                                            data-name="${item.product_variant_name}"
                                            data-retail_price="${item.rates.retail_price}">
                                            ${item.product_variant_name} (${item.code})
                                        </li>
                                    `);
                                });
                            } else {
                                dropdown.append(
                                    '<li class="dropdown-item disabled">No products found</li>'
                                    );
                            }
                        },
                        error: function() {
                            console.error("Error fetching product variants");
                        }
                    });
                } else {
                    $("#product-suggestions").hide();
                }
            });

            $(document).on("click", "#product-suggestions .dropdown-item", function() {
                let productId = $(this).data("id");
                let productCode = $(this).data("code");
                let productName = $(this).data("name");
                let productRetailPrice = $(this).data("retail_price");

                $("#product-search").val('');
                $("#product-suggestions").hide();

                if ($(`#product-table-body tr[data-id="${productId}"]`).length > 0) {
                    let existingRow = $(`#product-table-body tr[data-id="${productId}"]`);
                    let currentQuantity = parseInt(existingRow.find('.quantity').val());
                    existingRow.find('.quantity').val(currentQuantity + 1);
                    return;
                }

                $("#no-items-row").remove();

                $("#product-table-body").append(`
                    <tr data-id="${productId}">
                        <td>${productCode}</td>
                        <td>${productName}</td>
                        <td>${productRetailPrice}</td>
                        <td>
                            <button class="btn btn-sm btn-danger decrement">-</button>
                            <input type="number" class="form-control d-inline-block quantity" value="1" style="width: 60px;" readonly>
                            <button class="btn btn-sm btn-success increment">+</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                `);

                updateTotalLabels();
            });

            $(document).on("click", ".increment", function() {
                let input = $(this).siblings(".quantity");
                let currentVal = parseInt(input.val());
                input.val(currentVal + 1);
            });

            $(document).on("click", ".decrement", function() {
                let input = $(this).siblings(".quantity");
                let currentVal = parseInt(input.val());
                if (currentVal > 1) {
                    input.val(currentVal - 1);
                }
            });

            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();

                if ($("#product-table-body tr").length === 0) {
                    $("#product-table-body").append(`
                        <tr id="no-items-row">
                            <td colspan="4" class="text-center">No Items Added</td>
                        </tr>
                    `);
                }

                updateTotalLabels();
            });

            function updateTotalLabels() {
                let totalLabels = $("#product-table-body tr").length;
                if ($("#no-items-row").length > 0) {
                    totalLabels = 0;
                }
                $("#totalLabels").text(totalLabels);
            }
        });
    </script>

    <script>
        $(document).on("click", "#previewButton", function() {
            let products = [];
            $("#product-table-body tr").each(function() {
                let product = {
                    product_name: $(this).find('td:nth-child(2)').text(),
                    product_code: $(this).find('td:nth-child(1)').text(),
                    product_retailPrice: $(this).find('td:nth-child(3)').text(),
                    quantity: $(this).find('.quantity').val()
                };
                products.push(product);
            });

            $.ajax({
                url: "{{ url('/product-variant-barcode/generate-barcodes') }}",
                method: "POST",
                data: {
                    products: products,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        displayPreview(response.barcodes);
                        $("#bulkPrintButton").show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to generate barcodes.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while generating barcodes.',
                    });
                }
            });
        });

        function displayPreview(barcodes) {
            const barcodePreview = document.getElementById("barcodePreview");
            barcodePreview.innerHTML = "";

            barcodes.forEach((barcodeData) => {
                const {
                    company_name,
                    product_name,
                    product_retailPrice,
                    product_code,
                    barcode,
                    print_qty
                } = barcodeData;

                for (let i = 0; i < print_qty; i++) {
                    const barcodeCard = document.createElement("div");
                    barcodeCard.style.margin = "unset";
                    barcodeCard.style.padding = "3px";
                    barcodeCard.style.textAlign = "center";
                    barcodeCard.style.display = "inline-block";
                    barcodeCard.style.width = "unset";

                    barcodeCard.innerHTML = `
                        <div style="font-weight: bold; margin:0; line-height:12px; font-size: 10px;">${company_name}</div>
                        <div style="font-size: 10px; margin-bottom:0px; line-height:10px; width:90%; margin-bottom:1px; ">${product_name}</div>
                        <img src="data:image/png;base64,${barcode}" alt="Barcode" height: auto;" />
                        <div style="font-size: 8px; line-height:10px; margin-top:2px; margin-bottom:unset;"><strong>${product_code}</strong></div>
                        <div style="font-size: 11px; margin:0; padding:0; line-height:12px;">Price: <strong style="margin:0; padding:0;">${product_retailPrice}</strong></div>
                    `;

                    barcodePreview.appendChild(barcodeCard);
                }
            });
        }

        function printSingleBarcode(button) {
            const barcodeDiv = button.parentNode;
            const printWindow = window.open("", "_blank");
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Barcode</title>
                        <style>
                            body { text-align: center; font-family: Arial, sans-serif; }
                            div { margin: 5px 0; }
                            img { width: 80%; }
                            @page { size: landscape; }
                        </style>
                    </head>
                    <body>${barcodeDiv.innerHTML}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        $(document).on("click", "#bulkPrintButton", function() {
            const barcodePreview = document.getElementById("barcodePreview");
            const printWindow = window.open("", "_blank");

            let bulkContent = barcodePreview.innerHTML.replace(
                /<button.*?<\/button>/g, ""
            );

            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Barcodes</title>
                        <style>
                            body { text-align: center; font-family: Arial, sans-serif; }
                            div { margin: 5px 0; }
                            img { width: 80%; }
                            @page { size: landscape; }
                        </style>
                    </head>
                    <body>${bulkContent}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        });
    </script>
@endsection
<!-- container -->
