@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <h4 class="page-title">Manage Tables</h4>
                        </div>
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn" style="background-color: black; color:white;"
                                    data-bs-toggle="modal" data-bs-target="#standard-modal">
                                    Add New Table
                                </button>
                            </div>
                        </div>
                    </div><!-- end col-->

                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                            <thead style="background-color: black; color:white;">
                                <tr>
                                    <th>Sr#</th>
                                    <th>Table Number</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tables as $table)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $table->table_number }}</td>
                                        <td>{{ $table->location->name }}</td>
                                        <td>
                                            <span
                                                class="badge
                                                        {{ $table->status == 'Free' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $table->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="action-icon text-dark"
                                                data-bs-toggle="modal" data-bs-target="#edit-modal"
                                                data-id="{{ $table->id }}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="standard-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="standard-modalLabel">Add New Table</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="form">
                                <form id="purchaseForm" action="{{ route('table.storeTable') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="row mb-2">

                                                <!-- Table Location -->
                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="location" class="form-label">Select Location</label>
                                                    <select name="table_location" id="table_location"
                                                        class="form-control @error('table_location') is-invalid @enderror">
                                                        <option selected disabled value="">Select Location</option>
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}"
                                                                {{ old('table_location') == $location->id ? 'selected' : '' }}>
                                                                {{ $location->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('table_location')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <!-- Table Number -->
                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="table_num" class="form-label">Table Number</label>
                                                    <input type="number"
                                                        class="form-control @error('table_number') is-invalid @enderror"
                                                        name="table_number" value="{{ old('table_number') }}">
                                                    @error('table_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <!-- Table Status -->
                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="tbl_status" class="form-label">Table Status</label>
                                                    <select name="table_status"
                                                        class="form-control @error('table_status') is-invalid @enderror">
                                                        <option value="Free"
                                                            {{ old('table_status', 'Free') == 'Free' ? 'selected' : '' }}>
                                                            Free</option>
                                                        {{-- <option value="Reserve" {{ old('table_status') == 'Reserve' ? 'selected' : '' }}>Reserve</option> --}}
                                                    </select>
                                                    @error('table_status')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div>
                                                <button type="submit" class="btn"
                                                    style="background-color: black; color:white;"
                                                    style="float: right;">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="standard-modalLabel">Edit Table</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="form">
                                <form id="purchaseForm" action="{{ route('table.update') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="table_id" id="product-id-field">

                                        <div class="row">
                                            <div class="row mb-2">

                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="date" class="form-label">Select Location</label>
                                                    <select name="table_location" id="edit_table_location"
                                                        class="form-control">
                                                        <option value="">Select Location</option>
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}">
                                                                {{ $location->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('table_location')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="date" class="form-label">Table number</label>
                                                    <input type="number" class="form-control" name="table_number"
                                                        id="edit_table_number">

                                                    @error('table_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="mt-2 col-sm-6" style="max-width: 50%;">
                                                    <label for="date" class="form-label">Table Status</label>
                                                    <select name="table_status" id="edit_table_status"
                                                        class="form-control">
                                                        <option value="Free">Free</option>
                                                        <option value="Reserve">Reserve</option>
                                                    </select>

                                                    @error('table_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn"
                                                    style="float: right; background-color: black; color:white;">Submit</button>
                                            </div>
                                        </div>
                                </form>

                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            @endsection

            @section('scripts')
                <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
                <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
                <script>
                    $('#edit-modal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget);
                        var tableId = button.data('id');
                        $(this).find('input[name="table_id"]').val(tableId);

                        $.ajax({
                            type: 'GET',
                            url: "{{ url('table/get-table') }}/" + tableId,
                        }).done(function(response) {
                            if (response.status === 'success') {
                                $('#edit_table_location').val(response.data
                                    .table_location);
                                $('#edit_table_number').val(response.data.table_number);
                                $('#edit_table_status').val(response.data.status);
                            } else {
                                alert(response.message);
                            }
                        }).fail(function(xhr) {
                            alert('Failed to fetch table data. Please try again.');
                        });
                    });
                </script>
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
            <!-- container -->
