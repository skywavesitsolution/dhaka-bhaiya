@extends('adminPanel/master')
@section('style')
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="">
        <h3>Pos closing</h3>
    </div>

    <div class="container mt-2">
        <div class="card card-body border-left-3 border-left-primary navbar-shadow mb-4">
            <form action="{{ route('save.posClosing') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $activeBatch->id ?? '' }}" name="batch_id" id="batch_id"
                    {{ $activeBatch ? '' : 'disabled' }}>
                <div class="row">
                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" value="{{ $batchDate ?? '' }}" id="date_input" class="form-control"
                            name="date" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Select Operator</label>
                        <select class="form-control" name="operator_id" id="operator_id">
                            <option selected disabled>Select one</option>
                            @isset($employees)
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="button" class="btn btn-primary" onclick="select_operator()">Search</button>
                    </div>
                </div>
                <hr class="border-top">
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <br>
                    </div>
                    <!-- Column 2 -->
                    <div class="col-md-5">
                        <label>Physical Cash</label>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="5000"
                                    id="n_5000" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_5000" id="num_5000" value="" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_5000" id="result_5000" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="1000"
                                    id="n_1000" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_1000" id="num_1000" value="" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_1000" id="result_1000" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="500"
                                    id="n_500" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_500" id="num_500" value="" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_500" id="result_500" value="" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="100"
                                    id="n_100" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_100" id="num_100" value="" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_100" id="result_100" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="50"
                                    id="n_50" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_50" id="num_50" value=""
                                    class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_50" id="result_50" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="20"
                                    id="n_20" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_20" id="num_20" value=""
                                    class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_20" id="result_20" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" value="10"
                                    id="n_10" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="num_10" id="num_10" value=""
                                    class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="result_10" id="result_10" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="border: none; background-color: inherit;" type="text" id="n_0"
                                    value="Others" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="other_num" id="other_num" value=""
                                    class="form-control">
                            </div>
                            <div class="col-md-1">
                                <label>=</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="other_result" id="other_result" value=""
                                    class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <!-- Column 3 -->
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-10">
                                <label>System Cash</label>
                                <input type="text" readonly name="system_cash" id="system_cash" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Customer Payments</label>
                                <input type="text" readonly name="customer_payments" id="payments" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Total Cash</label>
                                <input type="text" readonly name="total_cash" id="total_cash" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Physical Cash</label>
                                <input type="text" readonly name="phisical_cash" id="total_phys" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Cash Differ</label>
                                <input type="text" readonly name="differ_cash" id="difference" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <input type="submit" class="form-control btn btn-primary" value="Save"
                                    name="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="border-top">
            <div class="row">
                <div class="form-group col-md-12">
                    <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Physical Cash</th>
                                <th scope="col">System Cash</th>
                                <th scope="col">Diff</th>
                            </tr>
                        </thead>
                        <tbody id="tbl_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include jQuery (already present) -->
    <script src="{{ asset('public/adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Set up AJAX to automatically include CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('form').on('submit', function(e) {
            e.preventDefault(); // Default form submit rok do

            var form = $(this);
            var formData = form.serialize();
            var submitBtn = form.find('input[type="submit"]');
            var originalBtnText = submitBtn.val();

            // Button disable karo loading ke liye
            submitBtn.prop('disabled', true).val('Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    submitBtn.prop('disabled', false).val(originalBtnText);

                    // Success SweetAlert show karo
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we save POS closing.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();

                            // 1 second baad reload
                            setTimeout(() => {
                                Swal.close();
                                window.location.reload();
                            }, 1000);
                        }
                    });
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).val(originalBtnText);

                    // Error SweetAlert show karo
                    var errorMessage = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseText) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.error) errorMessage = response.error;
                        } catch (e) {
                            errorMessage = xhr.responseText;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        function GetTotal() {
            var result_5000 = $('#result_5000');
            var result_1000 = $('#result_1000');
            var result_500 = $('#result_500');
            var result_100 = $('#result_100');
            var result_50 = $('#result_50');
            var result_20 = $('#result_20');
            var result_10 = $('#result_10');
            var other_result = $('#other_result');

            var total = Number(result_5000.val()) + Number(result_1000.val()) + Number(result_500.val()) +
                Number(result_100.val()) + Number(result_50.val()) + Number(result_20.val()) +
                Number(result_10.val()) + Number(other_result.val());
            $('#total_phys').val(total);
            cal_difference();
        }

        $(document).ready(function() {
            $('#num_5000').on('keyup keydown change', multiVal);
            $('#num_1000').on('keyup keydown change', multiVal);
            $('#num_500').on('keyup keydown change', multiVal);
            $('#num_100').on('keyup keydown change', multiVal);
            $('#num_50').on('keyup keydown change', multiVal);
            $('#num_20').on('keyup keydown change', multiVal);
            $('#num_10').on('keyup keydown change', multiVal);
            $('#other_num').on('keyup keydown change', multiVal);
        });

        function multiVal() {
            $('#result_5000').val(Number($('#n_5000').val()) * Number($('#num_5000').val()) || 0);
            $('#result_1000').val(Number($('#n_1000').val()) * Number($('#num_1000').val()) || 0);
            $('#result_500').val(Number($('#n_500').val()) * Number($('#num_500').val()) || 0);
            $('#result_100').val(Number($('#n_100').val()) * Number($('#num_100').val()) || 0);
            $('#result_50').val(Number($('#n_50').val()) * Number($('#num_50').val()) || 0);
            $('#result_20').val(Number($('#n_20').val()) * Number($('#num_20').val()) || 0);
            $('#result_10').val(Number($('#n_10').val()) * Number($('#num_10').val()) || 0);
            $('#other_result').val(Number($('#other_num').val()) || 0);
            GetTotal();
        }

        function updateTable() {
            var date = $('#date_input').val();
            var physicalCash = $('#total_phys').val() || 0;
            var systemCash = $('#system_cash').val() || 0;
            var diff = $('#difference').val() || 0;

            var tableRow = `
        <tr>
            <td>${date || 'N/A'}</td>
            <td>${physicalCash}</td>
            <td>${systemCash}</td>
            <td>${diff}</td>
        </tr>
    `;
            $('#tbl_data').html(tableRow);
        }

        function select_operator() {
            var id = $('#operator_id').val();
            var date = $('#date_input').val();
            var batchId = $('#batch_id').val();

            if (!id || !date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Input',
                    text: 'Please select an operator and ensure the date is set.',
                });
                return;
            }

            $.ajax({
                url: "{{ url('/closing/fetch_sale') }}",
                type: "POST",
                data: {
                    Id: id,
                    date: date,
                    batch_id: batchId
                },
                success: function(data) {
                    $('#system_cash').val(data.system_cash || 0);
                    $('#payments').val(data.customer_payments || 0);
                    var totalCash = (Number(data.system_cash) || 0) + (Number(data.customer_payments) || 0);
                    $('#total_cash').val(totalCash);

                    // Update table after fetching data
                    cal_difference();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch data. Check console for details.',
                    });
                }
            });
        }

        function cal_difference() {
            var totalCash = Number($('#total_cash').val()) || 0;
            var physicalCash = Number($('#total_phys').val()) || 0;
            var diff = totalCash - physicalCash;
            $('#difference').val(diff);

            // Update the table whenever the difference is calculated
            updateTable();
        }
    </script>
@endsection
