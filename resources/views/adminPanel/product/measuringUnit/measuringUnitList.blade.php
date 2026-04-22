@extends('adminPanel/master')
@section('style')
<link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <h4 class="page-title">Measuring Unit List</h4>
                        </div>
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn" style="background-color: black; color:white;" data-bs-toggle="modal" data-bs-target="#standard-modal"><i class="mdi mdi-plus-circle me-2"></i>Add New Unit</button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                            <thead style="background-color: black; color:white;">
                                <tr>
                                    <th>Sr#</th>
                                    <th>Unit Name</th>
                                    <th>Symbol</th>
                                    <th>Qty</th>
                                    <th>Description</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($measuringUnits)
                                        @foreach($measuringUnits as $measuringUnit)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $measuringUnit->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $measuringUnit->symbol ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $measuringUnit->quantity ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $measuringUnit->description ?? '--' }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" class="action-icon text-dark"  data-id="{{ $measuringUnit->id }}"  data-bs-toggle="modal" data-bs-target="#edit-modal"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                            </tbody>
                        </table>
                        {!! $measuringUnits->links() !!}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <!-- end row -->

</div>

<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Measuring Unit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('measuring-unit.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Measuring Unit Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Measuring Unit Name">
                                @error('name')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="symbol" class="form-label">Measuring Unit Symbol</label>
                                <input type="text" id="symbol" name="symbol" class="form-control" placeholder="Measuring Unit Symbol">
                                @error('symbol')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="qty" class="form-label">Qty</label>
                                <input type="number" id="qty" name="qty" class="form-control" placeholder="Measuring Unit QTY">
                                @error('qty')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="1" rows="2" placeholder="Measuring Unit Description"></textarea>
                                @error('description')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: black; color:white;">Save changes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- edit modal -->
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-modalLabel">Edit Color</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ URL::to('/measuring-unit/update-measuring-unit') }}" method="post">
                @csrf
                <input type="hidden" name="measuringUnitId" id="measuring-unit-id-field">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_measuring_unit_name" class="form-label">Measuring Unit Name</label>
                                <input type="text" id="edit_measuring_unit_name" name="name" class="form-control">
                                @error('name')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_measuring_unit_symbol" class="form-label">Measuring Unit Symbol</label>
                                <input type="text" id="edit_measuring_unit_symbol" name="symbol" class="form-control">
                                @error('symbol')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_measuring_unit_qty" class="form-label">Qty</label>
                                <input type="number" id="edit_measuring_unit_qty" name="qty" class="form-control" >
                                @error('qty')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_measuring_unit_description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_measuring_unit_description" cols="1" rows="2"></textarea>
                                @error('description')
                                <p class="text-danger mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: black; color:white;">Update</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('scripts')

<script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>


<script>
    @if(session('success'))
    $(document).ready(function() {
        $("#success-alert-modal").modal('show');
    })
    @endif

    @if(session('error'))
    $(document).ready(function() {
        $("#error-alert-modal").modal('show');
    })
    @endif

    var submit_form = true;

    function disabledSubmitButton(form) {
        console.log(form);
        console.log('Form is submit now ');
        if (submit_form) {
            submit_form = false;
            $('#expense_form').submit();
        }

    }

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
    $('#edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $.ajax({
            url: 'measuring-unit/get-measuring-unit/' + id,
            method: 'GET',
            success: function (data) {
                console.log(data);
                if (data && data.data) {
                    $('#measuring-unit-id-field').val((data.data.id));
                    $('#edit_measuring_unit_name').val(data.data.name);
                    $('#edit_measuring_unit_symbol').val(data.data.symbol);
                    $('#edit_measuring_unit_qty').val(data.data.quantity);
                    $('#edit_measuring_unit_description').val(data.data.description);
                } else {
                    console.error('No data received');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + error);
            }
        });
    });
</script>
@endsection
<!-- container -->
