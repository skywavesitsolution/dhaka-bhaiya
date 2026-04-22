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

                    <h4 class="page-title">Supplier</h4>
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
                                Suppliers List
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#standard-modal">Add New Supplier</button>
                                </div>
                            </div><!-- end col-->
                        </div>

                        <div class="table-responsive">
                            <table id="scroll-horizontal-datatable" class="table table-sm table-centered w-100 nowrap">
                                <thead class="table-light">
                                    <tr>

                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($suppliers)
                                        @foreach ($suppliers as $supplier)
                                            <tr>
                                                <td>
                                                    {{ $supplier->id }}
                                                </td>

                                                <td>
                                                    {{ $supplier->name }}
                                                </td>
                                                <td>
                                                    {{ $supplier->email }}
                                                </td>
                                                <td>
                                                    {{ $supplier->phone }}
                                                </td>

                                                <td class="table-action">
                                                    <a href="javascript:void(0)" data-id="{{ $supplier->id }}"
                                                        class="action-icon text-success" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal"> <i
                                                            class="mdi mdi-square-edit-outline"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

        <!-- Standard modal -->
        <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add New Supplier</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ URL::to('/add-supplier') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Supplier Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Supplier Name">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Supplier Email</label>
                                        <input type="text" name="email" class="form-control"
                                            placeholder="Supplier Email">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                                        @error('phone')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Company Name
                                            <span>Optional</span></label>
                                        <input type="text" name="company_name" class="form-control"
                                            placeholder="Company Name ">
                                        @error('company_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Supplier Address">
                                        @error('address')
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



        <!-- Standard modal -->
        <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modalLabel">Edit Supplier</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <form action="{{ route('update.supplier') }}" method="post">
                        @csrf
                        <input type="hidden" name="supplierId" id="supplier-id-field">
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Supplier Name</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                        @error('name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Supplier Email</label>
                                        <input type="text" id="email" name="email" class="form-control">
                                        @error('email')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control">
                                        @error('phone')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Company Name
                                            <span>Optional</span></label>
                                        <input type="text" id="company_name" name="company_name"
                                            class="form-control">
                                        @error('company_name')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="example-input-normal" class="form-label">Address</label>
                                        <input type="text" id="address" name="address" class="form-control">
                                        @error('address')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
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
            // console.log('Button clicked!');
            var button = $(event.relatedTarget);
            var supplier = button.data('id');
            $(this).find('#supplier-id-field').val(supplier);
            $.ajax({
                type: 'GET',
                url: 'get-supplier/' + supplier,
            }).done(function(data) {
                $('#name').val(data.data.name);
                $('#phone').val(data.data.phone);
                $('#email').val(data.data.email);
                $('#company_name').val(data.data.company_name);
                $('#address').val(data.data.address);
                $('#edit-modal').modal('show');
            });
        });
    </script>
@endsection
<!-- container -->
