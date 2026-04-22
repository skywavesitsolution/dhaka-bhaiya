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

                    <h4 class="page-title">Ingredient</h4>
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
                                <h5>Ingredients Purchase Details</h5>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Purchase Id</th>
                                        <th>Ingredient</th>
                                        <th>Cost Price</th>
                                        <th>Qty</th>
                                        <th>Discount Type</th>
                                        <th>Discount Amount</th>
                                        <th>Discount Value</th>
                                        <th>Net Payable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($IngredientPurchaseItem)
                                        @foreach ($IngredientPurchaseItem as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->ingredient_purchase_id }}
                                                </td>

                                                <td>
                                                    {{ $item->ingredient->name }}
                                                </td>
                                                <td>
                                                    {{ $item->cost_price }}
                                                </td>
                                                <td>
                                                    {{ $item->qty }}
                                                </td>
                                                <td>
                                                    {{ $item->discount_type }}
                                                </td>
                                                <td>
                                                    {{ $item->discount_value }}
                                                </td>
                                                <td>
                                                    {{ $item->discount_actual_value }}
                                                </td>
                                                <td>
                                                    {{ $item->total }}
                                                </td>

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

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add New Ingredient</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-crop-ingredients') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label"> Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Party Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Cost Price</label>
                                        <input type="text" name="costPrice" class="form-control"
                                            placeholder="Cost Price">
                                        @error('costPrice')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Opening Stock</label>
                                        <input type="text" name="openingStock" class="form-control"
                                            placeholder="Opening Stock">
                                        @error('openingStock')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
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
    </script>
@endsection
<!-- container -->
