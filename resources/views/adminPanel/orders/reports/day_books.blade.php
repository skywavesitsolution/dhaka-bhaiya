@extends('adminPanel/master')
@section('style')
    <link href="{{ asset('adminPanel/assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row ml-1">

        <div class="col-9 card card-body border-left-3 border-left-primary navbar-shadow ">



            <div class="tab-content" id="v-pills-tabContent">
                <!-- Daybook Tab -->
                <div class="tab-pane fade active show" id="v-pills-daybook" role="tabpanel"
                    aria-labelledby="v-pills-daybook-tab">
                    <div class="row">
                        <div class="col-md-11">
                            <h4>Day Book As on {{ now() }}</h4>
                        </div>
                        <div class="col-md-1">
                            <label><a href="daybook_pdf.php?userid=21" target="_blank" class="badge badge-danger">PDF
                                    Report</a></label>
                        </div>
                    </div>

                    <hr>

                    <!-- Daybook Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Balance Summary</h4>
                            <h5 class="text-right">Opening Balance : {{ $openingBalance }}</h5>
                            <table class="table table-bordered" style="width: 100%;" id="balanceSummaryTable">
                                <thead style="color:rgb(67, 65, 65);">
                                    <tr style="color:rgb(75, 72, 72);">
                                        <th scope="col" style="width: 100px;">Sr</th>
                                        <th scope="col" style="width: 100px;">Account Name</th>
                                        <th scope="col" style="width: 150px;">Narration</th>
                                        <th scope="col" style="width: 57px;">Recipt</th>
                                        <th scope="col" style="width: 74px;">Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentsRecevingData as $paymentsReceving)
                                        <tr style="background-color: rgb(178, 167, 110); color: white;">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $paymentsReceving->account->account_name }}</td>
                                            <td>receviedId : {{ $paymentsReceving->id }}</td>
                                            <td></td>
                                            <td>{{ $paymentsReceving->total_payments }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($expences as $expence)
                                        <tr style="background-color: rgb(178, 167, 110); color: white;">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $expence->account->account_name }}</td>
                                            <td>expenseId : {{ $expence->id }}</td>
                                            <td></td>
                                            <td>{{ $expence->total_amount }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach ($cashPurchases as $cashPurchase)
                                        <tr style="background-color: rgb(178, 167, 110); color: white;">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cashPurchase->account->account_name }}</td>
                                            <td>PurchaseId : {{ $cashPurchase->id }}</td>
                                            <td></td>
                                            <td>{{ $cashPurchase->net_payable }}</td>
                                        </tr>
                                    @endforeach
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
                                        <td>Cash WithDraw</td>
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
                                        <td>Cash Purchase</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $cashPurchaseTotal }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Expense</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $expencestotal }}</td>
                                    </tr>
                                    <tr>
                                        <td>Recevied Amount</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $paymentsRecevingtotal }}</td>
                                        <td></td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Totals</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $totalSale }}</td>
                                        <td></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                            {{-- <h5 class="text-right mt-3">Today Balance : 0</h5>
                            <h5 class="text-right">Closing Balance : 644919</h5> --}}

                            <!-- Summary Table -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h4>Summary</h4>
                                    <table class="table table-bordered" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Debit</th>
                                                <th scope="col">Credit</th>
                                                <th scope="col">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $receiptsTotal = $cashSaleTotal + $paymentsRecevingtotal + $depositeAmount }}
                                                </td>
                                                <td>{{ $paymentsTotal = $expencestotal + $creditSaleTotal + $cashPurchaseTotal }}
                                                </td>
                                                <td>{{ $balance = $openingBalance + $receiptsTotal - $paymentsTotal }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Filter Tab -->


            </div>
        </div>

    </div>
@endsection

@section('script')
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
