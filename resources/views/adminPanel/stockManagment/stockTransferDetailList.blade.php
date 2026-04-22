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
                    {{-- <h4 class="page-title">Transfer Stock Detail</h4> --}}
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
                                <h4 class="page-title">Transfer Stock Detail List</h4>

                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('stock-transfer.index') }}" class="btn btn-warning"><i
                                            class="mdi mdi-subdirectory-arrow-left me-2"></i>Back To Transfer Stock List</a>
                                    <a href="{{ route('stock-transfer.create') }}" class="btn"
                                        style="background-color: black; color:white;"><i
                                            class="mdi mdi-plus-circle me-2"></i>Transfer Stock</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead style="background-color: black; color:white;">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Product Variant Code</th>
                                        <th>Product Variant Name</th>
                                        <th>To Location</th>
                                        <th>Stock At Transfer Time</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($stockTransferDetails)
                                        @foreach ($stockTransferDetails as $stockTransferDetail)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $stockTransferDetail->productVariant->code ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $stockTransferDetail->productVariant->product_variant_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $stockTransferDetail->toLocation->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $stockTransferDetail->stock_at_time_of_transfer ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $stockTransferDetail->qty ?? '--' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $stockTransferDetails !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>
@endsection

<!-- container -->
