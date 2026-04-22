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
                    {{-- <h4 class="page-title">Transfer Stock</h4> --}}
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
                                <h4 class="page-title">Transfer Stock List</h4>

                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
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
                                        <th>From Location</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Total Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($stockTransfers)
                                        @foreach ($stockTransfers as $stockTransfer)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $stockTransfer->fromLocation->name ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($stockTransfer->date)->format('d M Y') ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($stockTransfer->time)->format('h:i A') ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $stockTransfer->qty ?? '--' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('stock-transfer.details', $stockTransfer->id) }}"
                                                        class="action-icon text-success">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('stock-transfer.print.details', $stockTransfer->id) }}"
                                                        target="_blank" class="action-icon text-warning">
                                                        <i class="mdi mdi-printer"></i>
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        class="action-icon text-danger stock-transfer-del-btn"
                                                        data-id="{{ $stockTransfer->id }}"><i class="mdi mdi-delete"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            {!! $stockTransfers !!}
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
        $(document).ready(function() {
            $('body').on('click', '.stock-transfer-del-btn', function() {
                var stockTransferId = $(this).data('id');
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
                            url: "{{ route('stock-transfer.soft.destroy', ':id') }}"
                                .replace(':id', stockTransferId),
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
