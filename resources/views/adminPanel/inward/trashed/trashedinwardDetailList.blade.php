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
                    <h4 class="page-title">Trash Inward Detail</h4>
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
                                <h4 class="page-title">Trashed Inward Detail List</h4>

                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <a href="{{ route('inward.trashed') }}" class="btn btn-danger"><i
                                            class="mdi mdi-subdirectory-arrow-left me-2"></i>Back To Trash Inward List</a>
                                    <a href="{{ route('inward.create') }}" class="btn btn-success"><i
                                            class="mdi mdi-plus-circle me-2"></i>Add New Inward</a>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($inwardDetails)
                                        @foreach ($inwardDetails as $inwardDetail)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $inwardDetail->product_name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $inwardDetail->qty ?? '--' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $inwardDetails !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>
@endsection

<!-- container -->
