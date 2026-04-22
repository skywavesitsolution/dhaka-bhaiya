@extends('adminPanel.master')

@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    {{-- <h4 class="mb-0">Recipe Details - {{ $recipe->productVariant->product_variant_name ?? 'N/A' }}</h4> --}}
                    {{-- <div class="d-flex align-items-center">
                        <div class="input-group me-2">
                            <input type="text" class="form-control" id="dash-daterange" placeholder="Select date range">
                            <span class="input-group-text bg-primary text-white">
                                <i class="mdi mdi-calendar-range"></i>
                            </span>
                        </div>
                        <button type="button" class="btn btn-primary me-2">
                            <i class="mdi mdi-autorenew"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="mdi mdi-filter-variant"></i> Filter
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h4 class="mb-0">Recipe Details - {{ $recipe->productVariant->product_variant_name ?? 'N/A' }}
                                ( Used Ingredients )
                            </h4>
                            {{-- <h5 class="card-title mb-0">Ingredients Used</h5> --}}

                            <a href="{{ route('recipes.index') }}" class="btn btn-primary">Back to List</a>
                        </div>
                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Sr#</th>
                                        <th>Ingredient Name</th>
                                        <th class="text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recipe->items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->productVariant->product_variant_name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ number_format($item->qty, 2) }}</td>
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
@endsection
