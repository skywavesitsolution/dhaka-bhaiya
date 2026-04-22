@extends('adminPanel/master')
@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <h2>Day Closing</h2>


        <!-- Check for success message -->
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif --}}

        <!-- Button for Modal -->
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dayClosingModal">
                Day Closing
            </button>
        </div>

        <!-- DataTable -->
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Date</th>
                    <th>Opening Balance</th>
                    <th>Physical Balance</th>
                    <th>Expence</th>
                    <th>Closing Balance</th>
                </tr>
            </thead>
            <tbody>
                @isset($dayClosingData)
                    @foreach ($dayClosingData as $dayClosing)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dayClosing->date }}</td>
                            <td>{{ $dayClosing->opening_balance }}</td>
                            <td>{{ $dayClosing->physical_balance }}</td>
                            <td>{{ $dayClosing->expence }}</td>
                            <td>{{ $dayClosing->closing_balance }}</td>
                        </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>

        <!-- Modal for Day Closing -->
        <div class="modal fade" id="dayClosingModal" tabindex="-1" aria-labelledby="dayClosingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dayClosingModalLabel">Day Closing</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="dayClosingForm" action="{{ route('save.dayClosing') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="openingBalance" class="form-label">Opening Balance</label>
                                <input type="number" name="opening_balance" class="form-control"
                                    value="{{ $lastClosingBalance }}" readonly id="openingBalance"
                                    placeholder="Enter Opening Balance">
                            </div>
                            <div class="mb-3">
                                <label for="physical_balance" class="form-label">Physical Balance</label>
                                <input type="number" name="physical_balance" class="form-control" id="physical_balance"
                                    placeholder="" value="{{ $physicalCash }}">
                            </div>
                            <div class="mb-3">
                                <label for="expence" class="form-label">Expence</label>
                                <input type="number" name="expence" class="form-control" id="expence"
                                    placeholder="Enter Expences">
                            </div>
                            <div class="mb-3">
                                <label for="closing_balance" class="form-label">Closing Balance</label>
                                <input type="number" name="closing_balance" class="form-control" id="closing_balance"
                                    placeholder="Enter Card Sales">
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitDayClosing">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#example').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
            });

            // Function to calculate closing balance
            function calculateClosingBalance() {
                const openingBalance = parseFloat($('#openingBalance').val()) || 0;
                const physicalCash = parseFloat($('#physical_balance').val()) || 0;
                const expense = parseFloat($('#expence').val()) || 0;

                // Calculate Closing Balance
                const closingBalance = (openingBalance + physicalCash) - expense;

                // Update Closing Balance Field
                $('#closing_balance').val(closingBalance.toFixed(2));
            }

            // Attach change event listeners to inputs
            $('#openingBalance, #physical_balance, #expence').on('input', function() {
                calculateClosingBalance();
            });

            // Day Closing Form Submission
            // $('#submitDayClosing').on('click', function () {
            //     const openingBalance = $('#openingBalance').val();
            //     const physicalCash = $('#physical_balance').val();
            //     const expense = $('#expence').val();
            //     const closingBalance = $('#closing_balance').val();

            //     if (!openingBalance || !physicalCash || !expense || !closingBalance) {
            //         alert("Please fill in all the fields.");
            //     } else {
            //         alert(`Day closing submitted:
        //             Opening Balance: ${openingBalance}
        //             Physical Cash: ${physicalCash}
        //             Expense: ${expense}
        //             Closing Balance: ${closingBalance}`);
            //         $('#dayClosingModal').modal('hide');
            //     }
            // });
        });
    </script>
@endsection
