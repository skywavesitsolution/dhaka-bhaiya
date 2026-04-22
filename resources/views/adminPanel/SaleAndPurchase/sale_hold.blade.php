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
            @if(session('error'))
            <div id="error-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content modal-filled bg-danger">
                        <div class="modal-body p-4">
                            <div class="text-center">
                                <i class="dripicons-wrong h1"></i>
                                <h4 class="mt-2">Oh snap!</h4>
                                <p class="mt-3">{{ session('error') }}</p>
                                <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            @endif
            <div class="page-title-box">
                <div class="page-title-right">

                </div>
                <h4 class="page-title">Product Categories</h4>
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
                            <h4 class="page-title">Product Category List</h4>
                        </div>
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#standard-modal"><i class="mdi mdi-plus-circle me-2"></i>Add New Category</button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table id="scroll-horizontal-datatable" class="table table-centered w-100 nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Total Amount</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($holdInvoices)
                                        @foreach($holdInvoices as $holdInvoice)
                                            <tr>
                                                <td>
                                                    {{ $holdInvoice->id ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $holdInvoice->total_bill ?? '--' }}
                                                </td>
                                                <td>
                                                    {{ $holdInvoice->net_payable ?? '--' }}
                                                </td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0)" class="action-icon text-success"  data-id="{{ $holdInvoice->id }}"  data-bs-toggle="modal" data-bs-target="#edit-modal"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                            </tbody>
                        </table>
                        {{-- {!! $holdInvoices->links() !!} --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <!-- end row -->

</div>


<!-- edit modal -->
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-modalLabel">Edit Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ URL::to('/product-category/update-category') }}" method="post">
                @csrf
                <input type="hidden" name="productCategoryId" id="category-id-field">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="edit_category_name" class="form-label">Category Name</label>
                                <input type="text" id="edit_category_name" name="name" class="form-control">
                                @error('name')
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
            url: 'product-category/get-category/' + id,
            method: 'GET',
            success: function (data) {
                console.log(data);
                if (data && data.data) {
                    $('#category-id-field').val((data.data.id));
                    $('#edit_category_name').val(data.data.name);
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