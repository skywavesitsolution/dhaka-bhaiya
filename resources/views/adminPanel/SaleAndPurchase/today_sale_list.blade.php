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

                    <h4 class="page-title">Today Sales</h4>
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
                                All Today Sales
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Add New</button> -->
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>Sr#</th>
                                        <th>Date</th>
                                        <th>Payment Type</th>
                                        <th>Total Bill</th>
                                        <th>Adjustment</th>
                                        <th>Net Payable</th>
                                        <th style="width: 85px;">Action</th>
                                        {{-- <th style="width: 85px;">add Bilty</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($getSaleInvoice)
                                        @foreach ($getSaleInvoice as $saleInvoice)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $saleInvoice->bill_date }}
                                                </td>
                                                <td>
                                                    {{ $saleInvoice->payment_type }}
                                                </td>
                                                <td>
                                                    {{ $saleInvoice->total_bill }}
                                                </td>
                                                <td>
                                                    {{ $saleInvoice->adjustment }}
                                                </td>
                                                <td>
                                                    {{ $saleInvoice->net_payable }}
                                                </td>
                                                <td class="table-action">
                                                    <a href='{{ URL::to("printInvoice/{$saleInvoice->id}") }}'
                                                        class="btn btn-info btn-sm mdi mdi-print text-white"> <i
                                                            class="mdi mdi-printer"></i></a>
                                                    {{-- <a href='{{ URL::to("delete-invoice/{$saleInvoice->id}") }}'
                                                        class="btn btn-info btn-sm action-icon text-white"> <i
                                                            class="mdi mdi-trash-can-outline"></i></a> --}}
                                                    {{-- <a href='{{ URL::to("edit-sale-inovice/{$saleInvoice->id}") }}' class="btn btn-info btn-sm action-icon text-white"> <i class="mdi mdi-square-edit-outline"></i></a> --}}
                                                </td>
                                                {{-- <td><button type="button" class="btn btn-success col endCashButton"
                                                        data-bs-toggle="modal" data-bs-target="#standard-modal"
                                                        data-sale-id="{{ $saleInvoice->id }}">
                                                        <i class="mdi mdi-plus-circle me-2"></i>
                                                    </button>

                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Modal Form inside main form -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Modal Title: Bilty -->
                        <h4 class="modal-title" id="standard-modalLabel">Add Bilty</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="row">
                                    <form id="biltyForm" action="{{ route('save-bilty-data') }}" method="POST">
                                        @csrf
                                        <!-- Hidden field for sale_id -->
                                        <input type="hidden" name="sale_id" id="sale_id">

                                        <div class="row">
                                            <div class="col-4">
                                                <label for="date" class="mb-2">Date</label>
                                                <input type="date" name="date" id="date" class="form-control">
                                                @error('date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4">
                                                <label for="bilty_number" class="mb-2">Bilty Number</label>
                                                <input type="number" name="bilty_number" id="bilty_number"
                                                    class="form-control">
                                                @error('bilty_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4">
                                                <label for="number_of_corton" class="mb-2">Number of Corton</label>
                                                <input type="number" name="number_of_corton" id="number_of_corton"
                                                    class="form-control">
                                                @error('number_of_corton')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                <label for="cargo_name" class="mb-2">Cargo Name</label>
                                                <input type="text" name="cargo_name" id="cargo_name"
                                                    class="form-control">
                                                @error('cargo_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4">
                                                <label for="vahical_number" class="mb-2">Vehicle Number</label>
                                                <input type="number" name="vahical_number" id="vahical_number"
                                                    class="form-control">
                                                @error('vahical_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4">
                                                <label for="remarks" class="mb-2">remarks</label>
                                                <input type="text" name="remarks" id="remarks" class="form-control">
                                                @error('remarks')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saleButton">Save Bilty</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


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
    </script>

    <script>
        $(document).ready(function() {
            // When any of the "Add Bilty" buttons is clicked
            $('.endCashButton').on('click', function() {
                // Get the sale_id from the button's data attribute
                var saleId = $(this).data('sale-id');
                console.log(saleId); // Debugging log

                // Set the sale_id in the hidden input field inside the modal
                $('#sale_id').val(saleId);
            });
        });
    </script>
@endsection
<!-- container -->
