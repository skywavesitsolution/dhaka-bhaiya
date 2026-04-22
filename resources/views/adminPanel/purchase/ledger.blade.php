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
                    <h4 class="page-title">Sale/Purchase Ledger</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Purchase Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="product-table" class="table table-sm table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product id</th>
                                        <th>Sale id</th>
                                        <th>Purchase id</th>
                                        <th>Sale stock</th>
                                        <th>Purchase stock</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="product-table-body">
                                    @foreach ($ledgers as $pro)
                                        <tr>
                                            <td>{{ $pro->id }}</td>
                                            <td>{{ $pro->product->product_name }}</td>
                                            <td>{{ $pro->sale_id }}</td>
                                            <td>{{ $pro->purchase_id }}</td>
                                            <td>{{ $pro->sale_stock }}</td>
                                            <td>{{ $pro->purchase_stock }}</td>
                                            <td>{{ $pro->balance }}</td>
                                            <td>{{ $pro->created_at }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Purchase Table -->

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
@endsection
