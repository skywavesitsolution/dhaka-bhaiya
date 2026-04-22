@extends('adminPanel/master')
@section('style')
    <!-- Include DataTables CSS -->
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row ml-1">
        <div class="col-9 card card-body border-left-3 border-left-primary navbar-shadow">
            <div class="tab-content" id="v-pills-tabContent">

                <!-- Daybook Tab -->
                <div class="tab-pane fade active show" id="v-pills-daybook" role="tabpanel"
                    aria-labelledby="v-pills-daybook-tab">
                    <div class="row">
                        <div class="col-md-11">
                            <h4>Day Book As on {{ $startDate }} to {{ $endDate }}</h4>
                        </div>
                        <div class="col-md-1">
                            <label>
                                <a href="daybook_pdf.php?userid=21" target="_blank" class="badge badge-danger">PDF
                                    Report</a>
                            </label>
                        </div>
                    </div>
                    <hr>
                </div>

                <!-- Date Filter Tab -->
                <form action="{{ route('daybook.datewise') }}" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date"
                                value="{{ $startDate ?? \Carbon\Carbon::today()->toDateString() }}" class="form-control"
                                required>
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date"
                                value="{{ $endDate ?? \Carbon\Carbon::today()->toDateString() }}" class="form-control"
                                required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <hr>

                <!-- Filtered Daybook Table -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Balance Summary for Selected Dates</h4>
                        <table class="table table-bordered" style="width: 100%;" id="filteredBalanceSummaryTable">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Account Name</th>
                                    <th>Narration</th>
                                    <th>Receipt</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentData as $payment)
                                    <tr style="background-color: rgb(178, 167, 110); color: white;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->account->account_name }}</td>
                                        <td>PaymentId : {{ $payment->id }}</td>
                                        <td></td>
                                        <td>{{ $payment->total_payments }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($getSaleData as $item)
                                    <tr style="background-color: rgb(189, 185, 193); color: rgb(9, 9, 9);">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->payment_type }}</td>
                                        <td>SaleInvoice Id : {{ $item->id }}</td>
                                        <td>{{ $item->net_payable }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Cash Deposit</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $depositeAmount }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Cash Withdraw</td>
                                    <td></td>
                                    <td></td>
                                    <td>0</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Credit Sale</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $creditSaleTotal }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Cash Sale</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $cashSaleTotal }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Totals</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $totalSale }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Balance</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Include DataTables JS -->
    <script src="{{ asset('adminPanel/assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminPanel/assets/js/vendor/dataTables.bootstrap5.js') }}"></script>

    <script>
        // JavaScript for filtering Daybook based on selected date range
        document.getElementById('filter_button').addEventListener('click', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Make sure both start and end dates are selected
            if (startDate && endDate) {
                // Fetch the filtered data based on the date range
                fetch(`{{ url('admin/daybook/filter') }}?start_date=${startDate}&end_date=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        let tableContent = '';
                        data.forEach((item, index) => {
                            tableContent += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.account_name}</td>
                                    <td>${item.narration}</td>
                                    <td>${item.receipt}</td>
                                    <td>${item.payment}</td>
                                </tr>
                            `;
                        });
                        document.querySelector('#filteredBalanceSummaryTable tbody').innerHTML = tableContent;
                    })
                    .catch(error => console.error('Error fetching filtered data:', error));
            } else {
                alert('Please select both start and end dates.');
            }
        });
    </script>
@endsection
