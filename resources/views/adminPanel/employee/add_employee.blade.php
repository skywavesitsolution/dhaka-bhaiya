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

                    <h4 class="page-title">Add Employee</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->




        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-10">
                                        Employee List
                                    </div>
                                    <div class="text-sm-end">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#standard-modal">Add Employee</button>
                                    </div>
                                    <!-- end col-->
                                </div>
                                <div class="table-responsive">
                                    <table id="scroll-horizontal-datatable"
                                        class="table table-sm table-centered w-100 nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th> Name</th>
                                                <th>Email</th>
                                                <th>Position</th>
                                                <th>Salary</th>
                                                <th style="width: 85px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employees as $employee)
                                                <tr>
                                                    <td>{{ $employee->id }}</td>
                                                    <td>{{ $employee->name }}</td>
                                                    <td>{{ $employee->email }}</td>
                                                    <td>{{ $employee->position }}</td>
                                                    <td>{{ $employee->salary }}</td>
                                                    <td class="table-action">
                                                        <a href="javascript:void(0)"
                                                            class="action-icon text-success edit-employee"
                                                            data-employee="{{ json_encode($employee) }}"
                                                            data-bs-toggle="modal" data-bs-target="#edit-employee-modal">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="action-icon text-danger emp-del-btn"
                                                            data-id="{{ $employee->id }}"><i class="mdi mdi-delete"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No Employees Found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>


                                </div>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
            </div>

        </div>



        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add Employee</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/employee/add-employee') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="employee Name">
                                        @error('account_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Position</label>
                                        <input type="text" name="position" class="form-control" value=""
                                            placeholder="Enter Position">
                                        @error('position')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Salary</label>
                                        <input type="number" name="salary" class="form-control" value="0"
                                            placeholder="Enter Salary">
                                        @error('salary')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Contact</label>
                                        <input type="number" name="contact" class="form-control" value="0"
                                            placeholder="Enter Salary">
                                        @error('contact')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="loginCheckbox"
                                            name="has_login" {{ old('has_login') == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="loginCheckbox">
                                            Enable Login
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="passwordField" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Enter Password">
                                        @error('password')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div id="edit-employee-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="edit-employee-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-employee-modalLabel">Edit Employee</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form id="editEmployeeForm" action="" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Hidden Employee ID -->
                            <input type="hidden" id="employee_id" name="employee_id">

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label for="edit-name" class="form-label">Name</label>
                                    <input type="text" id="edit-name" name="name" class="form-control"
                                        placeholder="Employee Name" required>
                                    @error('name')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" id="edit-email" name="email" class="form-control"
                                        placeholder="Enter Email" required>
                                    @error('email')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="edit-position" class="form-label">Position</label>
                                    <input type="text" id="edit-position" name="position" class="form-control"
                                        placeholder="Enter Position">
                                    @error('position')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label for="edit-salary" class="form-label">Salary</label>
                                    <input type="number" id="edit-salary" name="salary" class="form-control"
                                        placeholder="Enter Salary" required>
                                    @error('salary')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="edit-contact" class="form-label">Contact</label>
                                    <input type="text" id="edit-contact" name="contact" class="form-control"
                                        placeholder="Enter Contact Number">
                                    @error('contact')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-loginCheckbox"
                                            name="has_login">
                                        <label class="form-check-label" for="edit-loginCheckbox">
                                            Enable Login
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="edit-passwordField" style="display: none;">
                                <div class="col-sm-12">
                                    <label for="edit-password" class="form-label">Password</label>
                                    <input type="password" id="edit-password" name="password" class="form-control"
                                        placeholder="Enter Password">
                                    @error('password')
                                        <p class="text-danger mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- cash deposit modal -->
        <div id="cash-deposit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="cash-depositLabel">Cash Deposit</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-cash-deposit') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Accounts Name</label>
                                        <select name="accountId" id="" class="form-control">
                                            @isset($accounts)
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('accountId')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Deposit By</label>
                                        <input type="text" name="depositBy" class="form-control"
                                            placeholder="Deposited By">
                                        @error('depositBy')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Amount</label>
                                        <input type="text" name="depositAmount" class="form-control"
                                            placeholder="Amount">
                                        @error('depositAmount')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- end row -->

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
            $('body').on('click', '.emp-del-btn', function() {
                var empId = $(this).data('id');
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

                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while we process your request.',
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: "{{ route('employee.delete', ':id') }}".replace(':id',
                                empId),
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

    <script>
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
        console.log('page is load now');
    </script>

    <script>
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            $.ajax({
                url: 'account/' + id,
                method: 'GET',
                success: function(data) {
                    $('#account-id-field').val((data.data.id));
                    $('#account_name').val(data.data.account_name);
                    $('#accountNumber').val(data.data.account_number);
                    $('#openingBalance').val(data.data.opening_balance);
                }
            });
        });

        document.getElementById('loginCheckbox').addEventListener('change', function() {
            var passwordField = document.getElementById('passwordField');
            if (this.checked) {
                passwordField.style.display = 'block';
            } else {
                passwordField.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const editEmployeeModal = document.getElementById('edit-employee-modal');
            const editForm = document.getElementById('editEmployeeForm');

            // Handle "edit" button click
            document.querySelectorAll('.edit-employee').forEach(button => {
                button.addEventListener('click', () => {
                    const employee = JSON.parse(button.getAttribute('data-employee'));

                    // Populate form fields
                    editForm.action = `employee/update/${employee.id}`;
                    document.getElementById('employee_id').value = employee.id;
                    document.getElementById('edit-name').value = employee.name;
                    document.getElementById('edit-email').value = employee.email;
                    document.getElementById('edit-position').value = employee.position;
                    document.getElementById('edit-salary').value = employee.salary;
                    document.getElementById('edit-contact').value = employee.contact;

                    // Handle "Enable Login" and password field
                    const hasLoginCheckbox = document.getElementById('edit-loginCheckbox');
                    const passwordField = document.getElementById('edit-passwordField');
                    hasLoginCheckbox.checked = employee.has_login;
                    passwordField.style.display = employee.has_login ? 'block' : 'none';

                    hasLoginCheckbox.addEventListener('change', () => {
                        passwordField.style.display = hasLoginCheckbox.checked ? 'block' :
                            'none';
                    });

                    // Show modal
                    new bootstrap.Modal(editEmployeeModal).show();
                });
            });

        });
    </script>
@endsection
<!-- container -->
