@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                Purchase List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">
                                        <a href="{{ route('purchase.form') }}">Add New</a>
                                    </button>
                                </div>
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Date</th>
                                        <th>Supplier Name</th>
                                        <th>Total bill</th>
                                        <th>Status</th>
                                        <th>Received Date</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($purchases)
                                        {{-- @if ($purchases->isEmpty())
                                            <tr>
                                                <td colspan="5">No data found</td>
                                                <!-- Adjust colspan to match your table -->
                                            </tr>
                                        @else --}}
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $purchase->created_at }}
                                                </td>
                                                <td>
                                                    {{ $purchase->supplier->name }}
                                                </td>

                                                <td>
                                                    {{ $purchase->total_bill }}
                                                </td>

                                                <td>
                                                    <select class="form-control" name="order_status"
                                                        id="order_status_{{ $purchase->id }}"
                                                        data-purchase-id="{{ $purchase->id }}">
                                                        <option value="pending"
                                                            {{ $purchase->order_status == 'pending' ? 'selected' : '' }}>
                                                            Pending</option>
                                                        <option value="received"
                                                            {{ $purchase->order_status == 'received' ? 'selected' : '' }}>
                                                            Received</option>
                                                    </select>
                                                </td>
                                                <script>
                                                    document.getElementById('order_status_{{ $purchase->id }}').addEventListener('change', function() {
                                                        var status = this.value;
                                                        var purchaseId = this.getAttribute('data-purchase-id');

                                                        // If status is 'received', redirect to the route
                                                        if (status === 'received') {
                                                            window.location.href = '{{ URL::to('purchase/recevied-purchase-invoice/') }}/' + purchaseId;
                                                        }
                                                    });
                                                </script>
                                                <script>
                                                    // Check when the page loads if the status is 'received', then disable the select
                                                    window.addEventListener('DOMContentLoaded', function() {
                                                        var orderStatusSelect = document.getElementById('order_status_{{ $purchase->id }}');
                                                        var selectedStatus = orderStatusSelect.value;

                                                        // If the status is 'received', disable the select field
                                                        if (selectedStatus === 'received') {
                                                            orderStatusSelect.disabled = true;
                                                        }

                                                        // Add event listener to disable the select field if 'received' is selected
                                                        orderStatusSelect.addEventListener('change', function() {
                                                            if (this.value === 'received') {
                                                                this.disabled = true;
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <td>
                                                    {{ $purchase->order_received_date ?? 'null' }}
                                                </td>

                                                <td class="table-action">
                                                    <a href="{{ route('purchase.print', $purchase->id) }}" target="_blank"><i
                                                            class="fas fa-print text-success"></i></a>
                                                    <a
                                                        href='{{ URL::to("purchase/delete-purchase-invoice/{$purchase->id}") }}'><i
                                                            class="fas fa-trash text-danger"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- @endif --}}
                                    @endisset
                                </tbody>
                            </table>
                            {{-- {{ $parties->links() }} --}}
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>



        <!-- edit modal -->
        <!-- edit modal -->
        <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modalLabel">Edit Purchase</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    {{-- <form action="{{ route('purchase.update') }}" method="post"> --}}
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="purchase-id-field" name="purchaseId" value="" />
                        <!-- Hidden field for purchaseId -->

                        <div id="purchase-details-container">
                            <!-- Dynamic rows for purchase details will be appended here by JavaScript -->
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12 text-end">
                                <label for="grandTotal" class="mb-2"><strong>Grand Total</strong></label>
                                <input type="number" name="grandTotal" id="grandTotal" value="0.00" class="form-control"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
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
    </script>

    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var purchase = button.data('id');
            $(this).find('#purchase-id-field').val(purchase);

            $.ajax({
                type: 'GET',
                url: 'get-Purchase/' + purchase, // Adjust the route as necessary
            }).done(function(data) {
                console.log("AJAX response:", data);
                var purchaseDetails = data.data.purchase_details;
                console.log("Purchase details:", purchaseDetails);

                if (purchaseDetails && purchaseDetails.length > 0) {
                    $('#purchase-details-container').empty();

                    purchaseDetails.forEach(function(detail) {
                        var row = `<div class="row mb-2 purchase-detail-row">
                                <div class="col-lg-2">
                                    <input type="hidden" name="product_id[]" value="${detail.product.id}" />
                                    <label class="mb-2">Product Name</label>
                                    <input type="text" name="product_name[]" value="${detail.product.name}" class="form-control" readonly />
                                </div>
                                <div class="col-lg-1">
                                    <label class="mb-2">Stock</label>
                                    <input type="number" name="stock[]" value="${detail.stock}" class="form-control" readonly />
                                </div>
                                <div class="col-lg-2">
                                    <label class="mb-2">Cost Price</label>
                                    <input type="number" name="cost_price[]" value="${detail.cost_price}" class="form-control cost-price" step="0.01" />
                                </div>
                                <div class="col-lg-1">
                                    <label class="mb-2">Qty</label>
                                    <input type="number" name="qty[]" value="${detail.qty}" class="form-control" readonly />
                                </div>
                                <div class="col-lg-2">
                                    <label class="mb-2">Update Qty</label>
                                    <input type="number" name="update_qty[]" value="" class="form-control update-qty" min="0" />
                                </div>
                                <div class="col-lg-2">
                                    <label class="mb-2">Total</label>
                                    <input type="number" name="total[]" value="${detail.total}" class="form-control total" readonly />
                                </div>

                            </div>`;
                        $('#purchase-details-container').append(row);
                    });
                } else {
                    alert('No purchase details found for this purchase.');
                }

                $('#edit-modal').modal('show');
            }).fail(function(xhr, status, error) {
                console.log("Error fetching purchase details:", error);
                alert("Error fetching data.");
            });
        });


        $('#purchase-details-container').on('input', '.cost-price, .update-qty', function() {
            var row = $(this).closest('.purchase-detail-row');
            var costPrice = parseFloat(row.find('input[name="cost_price[]"]').val()) || 0;
            var updateQty = parseFloat(row.find('input[name="update_qty[]"]').val()) || 0;
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var total = 0;

            if ($(this).hasClass('cost-price')) {
                // If cost price changed, multiply by existing qty
                total = costPrice * qty;
            } else if ($(this).hasClass('update-qty')) {
                // If update qty changed, multiply by cost price
                total = costPrice * updateQty;
            }

            row.find('input[name="total[]"]').val(total.toFixed(2));
            calculateGrandTotal();
        });

        // Function to calculate the grand total by summing all product totals
        function calculateGrandTotal() {
            let grandTotal = 0;

            // Iterate over each total field and sum their values
            $('input[name="total[]"]').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });

            // Set the grand total in the grandTotal field
            $('input[name="grandTotal"]').val(grandTotal.toFixed(2));
        }


        // Calculate grand total on page load (if needed)
        $(document).ready(function() {
            calculateGrandTotal();
        });
    </script>
@endsection
<!-- container -->
