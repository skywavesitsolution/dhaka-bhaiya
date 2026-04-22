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
                            <h4 class="page-title">Product Location List</h4>
                        </div>
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn" style="background-color: black; color:white;" data-bs-toggle="modal" data-bs-target="#standard-modal"><i class="mdi mdi-plus-circle me-2"></i>Add New Location</button>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#location-transfer-modal"><i class="mdi mdi-transfer me-2"></i>Transfer Products</button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                            <thead style="background-color: black; color:white;">
                                <tr>
                                    <th>Sr#</th>
                                    <th>Location Name</th>
                                    <th>Created By</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($productLocations)
                                        @foreach($productLocations as $productLocation)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productLocation->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $productLocation->user->name ?? '--' }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" class="action-icon text-dark"  data-id="{{ $productLocation->id }}"  data-bs-toggle="modal" data-bs-target="#edit-modal"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                    <a href="javascript:void(0)" class="action-icon text-danger location-del-btn"  data-id="{{ $productLocation->id }}"><i class="mdi mdi-delete"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                            </tbody>
                        </table>
                        {!! $productLocations->links() !!}
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
                <h4 class="modal-title" id="standard-modalLabel">Add Product Location</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ URL::to('product-location/store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Location Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Location Name">
                                @error('name')
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
                <h4 class="modal-title" id="edit-modalLabel">Edit Location</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ URL::to('/product-location/update-location') }}" method="post">
                @csrf
                <input type="hidden" name="productLocationId" id="location-id-field">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_location_name" class="form-label">Location Name</label>
                                <input type="text" id="edit_location_name" name="name" class="form-control">
                                @error('name')
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


<div id="location-transfer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="location-transfer-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="location-transfer-modalLabel">Transfer Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ URL::to('product-location/change-product-location') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <label for="from_location_id" class="form-label">From Location<span class="text-danger"> *</span></label>
                            <select id="from_location_id" name="from_location_id" class="form-control" required>
                                @foreach($allLocations as $location)
                                    <option value="{{ $location->id }}" >{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <label for="to_location_id" class="form-label">To Location<span class="text-danger"> *</span></label>
                            <select id="to_location_id" name="to_location_id" class="form-control select2" data-toggle="select2" required>
                                <option value="" selected disabled>Select Location</option>
                                @foreach($allLocations as $location)
                                    <option value="{{ $location->id }}" >{{ $location->name }}</option>
                                @endforeach
                            </select>
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
@endsection

@section('scripts')

<script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('body').on('click', '.location-del-btn', function () {
            var locaionId = $(this).data('id');
            var row = $(this).closest('tr');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('product-location.destroy', ':id') }}".replace(':id', locaionId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            )
                            .then(() => {
                            row.remove();
                        });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message || 'An error occurred while deleting the brand.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

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
            url: 'product-location/get-location/' + id,
            method: 'GET',
            success: function (data) {
                console.log(data);
                if (data && data.data) {
                    $('#location-id-field').val((data.data.id));
                    $('#edit_location_name').val(data.data.name);
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
