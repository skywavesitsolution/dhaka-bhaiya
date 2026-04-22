@extends('adminPanel/master')
@section('style')
    <style>
        .card:hover {
            background-color: #f8f9fa;
            /* Light gray background on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add shadow effect */
            transform: translateY(-5px);
            /* Slight lift effect */
            transition: all 0.3s ease;
            /* Smooth transition for hover effects */
        }
    </style>
@endsection
@section('content')
    @php
        $settingsData = \App\Models\Setting::pluck('value', 'key')->toArray();
        $companyName = $settingsData['company_name'] ?? 'TechPOS RMS';
    @endphp
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="number" value="{{ date('Y') }}" id="reporting-year" placeholder="YYYY"
                                    class="form-control">
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="mdi mdi-calendar-range font-13"></i>
                                </span>
                            </div>
                            <button type="button" class="ml-2 btn btn-sm btn-success" onclick="loadDashboardData()"><i
                                    class="mdi mdi-autorenew"></i></button>

                        </form>
                    </div>
                    <h4 class="page-title">{{ $companyName }} Analytics</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        @php
            $user = Auth::user();
        @endphp
        @if ($user->roles->first()->name == 'Admin' || 'user')
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #4caf50; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                            </div>
                            <h5 class="fw-normal mt-0" title="Number of Products">Today Sale</h5>
                            <h3 class="mt-3 mb-3" id="today_sale_and_return">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Cash Sale</h5>
                                    <p class="card-text mb-0" id="todayCashSale">0.00</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Credit Sale</h5>
                                    <p class="card-text mb-0" id="todayCreditSale">0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #2196f3; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-currency-rupee widget-icon"></i>
                            </div>
                            <h5 class="fw-normal mt-0" title="Payments and Receiving">Today Purchase & Return</h5>
                            <h3 class="mt-3 mb-3" id="today_purchase_amount">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Cash Purchase</h5>
                                    <p class="card-text mb-0" id="todayCashPurchaseAmount">0.00</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Credit Purchase</h5>
                                    <p class="card-text mb-0" id="todayCreditPurchaseAmount">0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #ff9800; color: white;">
                        <div class="card-body">
                            <h5 class="fw-normal mt-0" title="Orders">Today Orders</h5>
                            <h3 class="mt-3 mb-3" id="today_orders">0</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Running Orders</h5>
                                    <p class="card-text mb-0" id="running_orders_count">0</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Completed Orders</h5>
                                    <p class="card-text mb-0" id="completed_orders_count">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #212121; color: white;">
                        <div class="card-body">
                            <h5 class="fw-normal mt-0" title="Transactions">Today transactions</h5>
                            <h3 class="mt-3 mb-3" id="paymentandreceiving">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Payment</h5>
                                    <p class="card-text mb-0" id="todayPayments">0.00</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Receiving</h5>
                                    <p class="card-text mb-0" id="todayReceivedPayments">0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #4caf50; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                            </div>
                            <h5 class="fw-normal mt-0" title="Number of Products">Total Orders</h5>
                            <h3 class="mt-3 mb-3" id="total_orders">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Today's Orders</h5>
                                    <p class="card-text mb-0" id="today_orders"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #2196f3; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                            </div>
                            <h5 class="fw-normal mt-0" title="Payments and Receiving">Total Dine In Orders</h5>
                            <h3 class="mt-3 mb-3" id="total_dine_in_orders">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Today's Dine In Orders</h5>
                                    <p class="card-text mb-0" id="today_dine_in_orders"></p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #ff9800; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                            </div>
                            <h5 class="fw-normal mt-0" title="Expanse">Total Take Away Orders</h5>
                            <h3 class="mt-3 mb-3" id="total_take_away_orders">0.00</h3>
                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Today's Take Away Orders</h5>
                                    <p class="card-text mb-0" id="today_take_away_orders"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card widget-flat" style="background-color: #121212; color: white;">
                        <div class="card-body">
                            <div class="float-end">
                            </div>
                            <h5 class="fw-normal mt-0" title="Expanse">Total Delivery Orders</h5>
                            <h3 class="mt-3 mb-3" id="total_delivery_orders">0.00</h3>

                            <div class="card my-0 float-end"
                                style="background-color: #fff; color: #000; padding: 10px; border-radius: 8px; margin-top: 10px; width:100%;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-1">Today's Delivery Orders</h5>
                                    <p class="card-text mb-0" id="today_delivery_orders"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <a href="{{ URL::to('add-make-payment') }}" class="text-decoration-none">
                        <div class="card widget-flat text-center">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="mdi mdi-currency-rupee" style="font-size: 2rem; color:#4caf50;"></i>
                                </h5>
                                <p class="card-text" style="color:#4caf50;">Payments & Receiving</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ URL::to('product-sale-reports') }}" class="text-decoration-none">
                        <div class="card widget-flat text-center">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="mdi mdi-file-chart" style="font-size: 2rem; color:#2196f3;"></i>
                                </h5>
                                <p class="card-text" style="color:#2196f3;">All Reports</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ URL::to('product-variant-barcode') }}" class="text-decoration-none">
                        <div class="card widget-flat text-center">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="mdi mdi-barcode-scan" style="font-size: 2rem; color:#ff9800;"></i>
                                </h5>
                                <p class="card-text" style="color:#ff9800;">Generate Barcode</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ URL::to('todayInvoices') }}" class="text-decoration-none">
                        <div class="card widget-flat text-center">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="mdi mdi-file-document " style="font-size: 2rem; color:black;"></i>
                                </h5>
                                <p class="card-text" style="color:black;">Check Inv Details</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="card card-h-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="header-title">Category Wise Expense</h4>
                            </div>

                            <div dir="ltr">
                                <div id="expense-column" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->

                </div> <!-- end col -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card card-h-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="header-title">Months wise Expanse</h4>
                            </div>

                            <div dir="ltr">
                                <div id="basic-column" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->

                </div> <!-- end col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="card card-h-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="header-title">Months Wise Sales</h4>
                            </div>
                            <div dir="ltr">
                                <div id="month-wise-sale" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
                {{-- <div class="col-xl-6 col-lg-6">
            <div class="card card-h-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title">Months Wise Profits</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="month-wise-profit" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div>  --}}
                <!-- end col -->
            </div>
        @endif
        <!-- end row -->



        <!-- end row -->

    </div>
@endsection

@section('scripts')
    <script>
        function loadDashboardData() {
            console.log('function is call');
            $('.card-text-top').css('display', 'block');
            $('#products-count').html('Products ');
            $('#todayPayments').html('PKR ');
            $('#todayReceivedPayments').html('PKR ');
            $('#todayOrderProfit').html('PKR ');
            $('#todayExpense').html('PKR ');
            $('#markaReceivable').html('PKR ');
            $('#markaPayable').html('PKR ');
            $('#driverReceivable').html('PKR ');
            $('#driverPayable').html('PKR ');
            $('#revenue-heading').html('PKR ');
            $('#accountReceivable').html('PKR ');
            $('#accountPayable').html('PKR ');
            $('#peroid-items').html('');
            $('#running_orders').html('0'); // Initialize new fields
            $('#running_orders_count').html('0');
            $('#completed_orders_count').html('0');

            var year = $('#reporting-year').val();

            getDashboardCards(year);
        }
        loadDashboardData();

        function getDashboardCards(year) {
            $.ajax({
                url: "{{ URL::to('get-dashboard-card') }}",
                type: 'POST',
                data: {
                    _token: '{{ CSRF_token() }}',
                    year: year
                },
                success: function(data) {
                    if (data.error) {
                        console.error('Error in response:', data);
                        return;
                    }

                    var data = data['data'];
                    console.log('Dashboard Data:', data);

                    // Safely handle each field with fallback values
                    $('#todayCashPurchaseAmount').html(data['todayCashPurchaseAmount'] ? parseFloat(data[
                        'todayCashPurchaseAmount'].replace(/,/g, '')).toFixed(2) : '0.00');
                    $('#todayCreditPurchaseAmount').html(data['todayCreditPurchaseAmount'] ? parseFloat(data[
                        'todayCreditPurchaseAmount'].replace(/,/g, '')).toFixed(2) : '0.00');
                    $('#todayCashSale').html(data['todayCashSale'] ? parseFloat(data['todayCashSale'].replace(
                        /,/g, '')).toFixed(2) : '0.00');
                    $('#todayCreditSale').html(data['todayCreditSale'] ? parseFloat(data['todayCreditSale']
                        .replace(/,/g, '')).toFixed(2) : '0.00');
                    $('#low_stock').html(data['lowStock'] ? parseInt(data['lowStock']) : 0);
                    $('#stock_status').html(data['stockStatus'] ? parseInt(data['stockStatus']) : 0);
                    $('#today_purchase_amount').html('PKR ' + (data['todayPurchaseAmount'] ? parseFloat(data[
                        'todayPurchaseAmount'].replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#today_sale_and_return').html('PKR ' + (data['todaySaleAmount'] ? parseFloat(data[
                        'todaySaleAmount'].replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#products-count').html('Products ' + (data['totalProductsCount'] ? parseInt(data[
                        'totalProductsCount']) : 0));
                    $('#todayPayments').html(data['todayPayments'] ? parseFloat(data['todayPayments'].replace(
                        /,/g, '')).toFixed(2) : '0.00');
                    $('#todayReceivedPayments').html(data['todayReceivedPayments'] ? parseFloat(data[
                        'todayReceivedPayments'].replace(/,/g, '')).toFixed(2) : '0.00');
                    $('#paymentandreceiving').html(data['todaypaymentandreceiving'] ? parseFloat(data[
                        'todaypaymentandreceiving'].replace(/,/g, '')).toFixed(2) : '0.00');
                    $('#monthlyexpense').html('PKR ' + (data['monthlyexpense'] ? parseFloat(data[
                        'monthlyexpense'].replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#total_orders').html(data['todayNetProfit'] ? parseFloat(data['todayNetProfit'].replace(
                        /,/g, '')) : 0);
                    $('#today_orders').html(data['todayGrossProfit'] ? parseFloat(data['todayGrossProfit']
                        .replace(/,/g, '')) : 0);
                    $('#total_take_away_orders').html(data['yearlyProfit'] ? parseFloat(data['yearlyProfit']
                        .replace(/,/g, '')) : 0);
                    $('#today_take_away_orders').html(data['YearlyGrossProfit'] ? parseFloat(data[
                        'YearlyGrossProfit'].replace(/,/g, '')) : 0);
                    $('#total_dine_in_orders').html(data['monthlyProfit'] ? parseFloat(data['monthlyProfit']
                        .replace(/,/g, '')) : 0);
                    $('#today_dine_in_orders').html(data['monthlyGrossProfit'] ? parseFloat(data[
                        'monthlyGrossProfit'].replace(/,/g, '')) : 0);
                    $('#total_delivery_orders').html(data['totalProfit'] ? parseFloat(data['totalProfit']
                        .replace(/,/g, '')) : 0);
                    $('#today_delivery_orders').html(data['totalGrossProfit'] ? parseFloat(data[
                        'totalGrossProfit'].replace(/,/g, '')) : 0);
                    $('#todayExpense').html('PKR ' + (data['todayExpense'] ? parseFloat(data['todayExpense']
                        .replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#markaReceivable').html('PKR ' + (data['markaReceivable'] ? parseFloat(data[
                        'markaReceivable']).toFixed(2) : '0.00'));
                    $('#markaPayable').html('PKR ' + (data['markaPayable'] ? parseFloat(data['markaPayable'])
                        .toFixed(2) : '0.00'));
                    $('#driverReceivable').html('PKR ' + (data['driverReceivable'] ? parseFloat(data[
                        'driverReceivable']).toFixed(2) : '0.00'));
                    $('#driverPayable').html('PKR ' + (data['driverPayable'] ? parseFloat(data['driverPayable'])
                        .toFixed(2) : '0.00'));
                    $('#revenue-heading').html('PKR ' + (data['todayRevenue'] ? parseFloat(data['todayRevenue']
                        .replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#accountReceivable').html('PKR ' + (data['accountReceivable'] ? parseFloat(data[
                        'accountReceivable'].replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#accountPayable').html('PKR ' + (data['accountPayable'] ? parseFloat(data[
                        'accountPayable'].replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#Yearlyexpense').html('PKR ' + (data['yearlyexpense'] ? parseFloat(data['yearlyexpense']
                        .replace(/,/g, '')).toFixed(2) : '0.00'));
                    $('#totalexpense').html('PKR ' + (data['totalExpense'] ? parseFloat(data['totalExpense']
                        .replace(/,/g, '')).toFixed(2) : '0.00'));

                    // New fields for orders
                    $('#today_orders').html(data['todayOrders'] ? parseInt(data['todayOrders']) : 0);
                    $('#running_orders_count').html(data['runningOrders'] ? parseInt(data['runningOrders']) :
                    0);
                    $('#completed_orders_count').html(data['completedOrders'] ? parseInt(data[
                        'completedOrders']) : 0);

                    $('.card-text-top').css('display', 'none');

                    // Charts
                    expenseChart(data['expenseGraph'][0], data['expenseGraph'][1]);
                    peroidsChart(data['expenseMonthyGraph'][0], data['expenseMonthyGraph'][1]);
                    monthWiseOrderChartChart(
                        data['monthWiseOrders'][0], // Categories (Month Labels)
                        data['monthWiseOrders'][1], // Sales Values
                        data['monthWisepurchases'][0], // Categories (Same Month Labels)
                        data['monthWisepurchases'][1] // Purchase Values
                    );
                    monthWiseProfitChartChart(data['monthWiseProfit'][0], data['monthWiseProfit'][1]);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                    $('.card-text-top').css('display', 'none');
                }
            });
        }

        function peroidsChart(categories, values) {
            $('#basic-column').html("");

            dataColors = $("#basic-column").data("colors");
            dataColors && (colors = dataColors.split(","));
            var options = {
                    chart: {
                        height: 396,
                        type: "bar",
                        toolbar: {
                            show: !1
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            endingShape: "rounded",
                            columnWidth: "55%"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    colors: ["#39afd1"],
                    series: [{
                        name: 'Values',
                        data: values
                    }],
                    xaxis: {
                        categories: categories
                    },
                    legend: {
                        offsetY: 7
                    },
                    yaxis: {
                        title: {
                            text: ""
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        row: {
                            colors: ["transparent", "transparent"],
                            opacity: .2
                        },
                        borderColor: "#f1f3fa",
                        padding: {
                            bottom: 5
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(o) {
                                return "" + o + ""
                            }
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#basic-column"), options);
            chart.render();
            // colors = ["#fa5c7c"];
        }

        function monthWiseOrderChartChart(saleCategories, saleValues, purchaseCategories, purchaseValues) {
            $('#month-wise-sale').html(""); // Clear the chart container

            // Data colors for the sales and purchase series
            dataColors = $("#month-wise-sale").data("colors");
            dataColors && (colors = dataColors.split(","));

            var options = {
                chart: {
                    height: 396,
                    type: "bar",
                    toolbar: {
                        show: !1
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        endingShape: "rounded",
                        columnWidth: "55%"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                // Multiple series for sales and purchases
                colors: ["#39afd1", "#fa5c7c"], // Blue for sales, Red for purchases
                series: [{
                        name: 'Sales',
                        data: saleValues // Sales data
                    },
                    {
                        name: 'Purchases',
                        data: purchaseValues // Purchases data
                    }
                ],
                xaxis: {
                    categories: saleCategories // Assuming sales and purchases have the same categories (months)
                },
                legend: {
                    offsetY: 7
                },
                yaxis: {
                    title: {
                        text: ""
                    }
                },
                fill: {
                    opacity: 1
                },
                grid: {
                    row: {
                        colors: ["transparent", "transparent"],
                        opacity: .2
                    },
                    borderColor: "#f1f3fa",
                    padding: {
                        bottom: 5
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(o) {
                            return "" + o + "";
                        }
                    }
                }
            };

            // Render the chart with the given options
            var chart = new ApexCharts(document.querySelector("#month-wise-sale"), options);
            chart.render();
        }

        function monthWiseProfitChartChart(categories, values) {
            $('#month-wise-profit').html("");

            dataColors = $("#month-wise-profit").data("colors");
            dataColors && (colors = dataColors.split(","));
            var options = {
                    chart: {
                        height: 396,
                        type: "bar",
                        toolbar: {
                            show: !1
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            endingShape: "rounded",
                            columnWidth: "55%"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    colors: ["#0acf97"],
                    series: [{
                        name: 'Values',
                        data: values
                    }],
                    xaxis: {
                        categories: categories
                    },
                    legend: {
                        offsetY: 7
                    },
                    yaxis: {
                        title: {
                            text: ""
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        row: {
                            colors: ["transparent", "transparent"],
                            opacity: .2
                        },
                        borderColor: "#f1f3fa",
                        padding: {
                            bottom: 5
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(o) {
                                return "" + o + ""
                            }
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#month-wise-profit"), options);
            chart.render();
            // colors = ["#fa5c7c"];
        }

        function expenseChart(categories, value) {
            $('#expense-column').html("");

            dataColors = $("#expense-column").data("colors");
            dataColors && (colors = dataColors.split(","));
            var options = {
                    chart: {
                        height: 396,
                        type: "bar",
                        toolbar: {
                            show: !1
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            endingShape: "rounded",
                            columnWidth: "55%"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    colors: ["#fa5c7c", "#0acf97", "#39afd1"],
                    series: value,
                    xaxis: {
                        categories: categories
                    },
                    legend: {
                        offsetY: 7
                    },
                    yaxis: {
                        title: {
                            text: ""
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        row: {
                            colors: ["transparent", "transparent"],
                            opacity: .2
                        },
                        borderColor: "#f1f3fa",
                        padding: {
                            bottom: 5
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(o) {
                                return "" + o + ""
                            }
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#expense-column"), options);
            chart.render();
            // colors = ["#fa5c7c"];
        }

        function expenseMonthWiseChart(months, values) {
            $('#expense-column').html("");
            console.log(values);
            dataColors = $("#expense-month-wise").data("colors");
            dataColors && (colors = dataColors.split(","));
            var options = {
                    chart: {
                        height: 396,
                        type: "bar",
                        toolbar: {
                            show: !1
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: !1,
                            endingShape: "rounded",
                            columnWidth: "55%"
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        show: !0,
                        width: 2,
                        colors: ["transparent"]
                    },
                    colors: ["#fa5c7c", "#0acf97", "#39afd1"],
                    series: values,
                    xaxis: {
                        months: months
                    },
                    legend: {
                        offsetY: 7
                    },
                    yaxis: {
                        title: {
                            text: ""
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        row: {
                            colors: ["transparent", "transparent"],
                            opacity: .2
                        },
                        borderColor: "#f1f3fa",
                        padding: {
                            bottom: 5
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(o) {
                                return "" + o + ""
                            }
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#expense-month-wise"), options);
            chart.render();
            // colors = ["#fa5c7c"];
        }
    </script>
@endsection
<!-- container -->
