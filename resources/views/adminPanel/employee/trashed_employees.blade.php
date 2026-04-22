@extends('adminPanel/master')

@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <h4 class="page-title">Trashed Employee</h4>
                </div>
            </div>
        </div>

        <!-- Display the list of soft-deleted employees -->
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-10">
                                        Trashed Employees
                                    </div>
                                    <!-- end col-->
                                </div>
                                <div class="table-responsive">
                                    <table id="scroll-horizontal-datatable"
                                        class="table table-sm table-centered w-100 nowrap">
                                        <thead class="table-light">
                                            <tr class="text-center">
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Position</th>
                                                <th>Salary</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($employees->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="text-center">No Trashed Employees Found.</td>
                                                </tr>
                                            @else
                                                @foreach ($employees as $employee)
                                                    <tr class="text-center">
                                                        <td>{{ $employee->id }}</td>
                                                        <td>{{ $employee->name }}</td>
                                                        <td>{{ $employee->email }}</td>
                                                        <td>{{ $employee->position }}</td>
                                                        <td>{{ $employee->salary }}</td>
                                                        <td>
                                                            <a href="javascript:void(0)"
                                                                class="action-icon text-success emp-restore-btn"
                                                                data-id="{{ $employee->id }}"><i
                                                                    class="mdi mdi-restore"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.emp-restore-btn', function() {
                var empId = $(this).data('id');
                var row = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to restore this employee!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while we process your request.',
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "{{ route('employee.restore', ':id') }}".replace(':id',
                                empId),
                            type: 'post',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.fire(
                                        'Restored!',
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
                                    'An error occurred while restoring the product.',
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
