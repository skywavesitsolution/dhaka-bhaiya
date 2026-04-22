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
                @if (session('error'))
                    <div id="error-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content modal-filled bg-danger">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="dripicons-wrong h1"></i>
                                        <h4 class="mt-2">Oh snap!</h4>
                                        <p class="mt-3">{{ session('error') }}</p>
                                        <button type="button" class="btn btn-light my-2"
                                            data-bs-dismiss="modal">Continue</button>
                                    </div>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                @endif
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Inwards</h4>
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
                                <h4 class="page-title">Inward List</h4>

                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
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
                                        <th>Supplier Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Total Qty</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($inwards)
                                        @foreach ($inwards as $inward)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $inward->supplier->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($inward->date)->format('d M Y') ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($inward->time)->format('h:i A') ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $inward->qty ?? '--' }}
                                                </td>
                                                <td>
                                                    @if ($inward->inward_status->value === 'approved')
                                                        <span class="badge badge-outline-success">
                                                            {{ ucfirst($inward->inward_status->value) }}
                                                        </span>
                                                    @else
                                                        <select name="inward_status" id="inward_status_{{ $inward->id }}"
                                                            class="form-control">
                                                            @foreach ($statuses as $status)
                                                                <option value="{{ $status->value }}">
                                                                    {{ ucfirst($status->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($inward->inward_status->value === 'approved')
                                                        <a href="{{ route('inward.details', $inward->id) }}"
                                                            class="action-icon text-success">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('inward.print.details', $inward->id) }}"
                                                            target="_blank" class="action-icon text-warning">
                                                            <i class="mdi mdi-printer"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('inward.details', $inward->id) }}"
                                                            class="action-icon text-success">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('inward.print.details', $inward->id) }}"
                                                            target="_blank" class="action-icon text-warning">
                                                            <i class="mdi mdi-printer"></i>
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="action-icon text-danger inward-del-btn"
                                                            data-id="{{ $inward->id }}"><i class="mdi mdi-delete"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $inwards !!}
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('change', '[id^=inward_status_]', function() {
            let selectedStatus = $(this).val();
            let inwardId = $(this).attr('id').split('_')[2];

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to change the status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('inward.updateStatus') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: inwardId,
                            status: selectedStatus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Updated!',
                                'The status has been updated successfully.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });;
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was an error updating the status.',
                                'error'
                            );
                        }
                    });
                } else {}
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.inward-del-btn', function() {
                var inwardId = $(this).data('id');
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
                            url: "{{ route('inward.soft.destroy', ':id') }}".replace(':id',
                                inwardId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire(
                                        'Soft Deleted!',
                                        response.message,
                                        'success'
                                    )
                                    .then(() => {
                                        row.remove();
                                    });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message ||
                                    'An error occurred while deleting the product.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
<!-- container -->
