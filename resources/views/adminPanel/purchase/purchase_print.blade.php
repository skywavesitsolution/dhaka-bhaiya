<!DOCTYPE html>
<html>
<head>
    <title>Print Purchase Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        @media print {
            button {
                display: none; /* Hide the print button during printing */
            }
        }
        .header {
            text-align: center; /* Center align the header content */
            margin-bottom: 20px; /* Space below the header */
        }
        .header img {
            max-width: 150px; /* Set a maximum width for the logo */
        }
        .supplier-info {
            display: flex; /* Use flexbox to align items side by side */
            justify-content: space-between; /* Space between items */
            margin-top: 10px; /* Space above the supplier info */
        }
        .supplier-info div {
            margin-right: 20px; /* Space between info items */
        }
        table {
            width: 100%; /* Full width table */
            border-collapse: collapse; /* Collapse borders for better appearance */
        }
        th, td {
            border: 1px solid #dee2e6; /* Define border for table cells */
            padding: 8px; /* Add padding for better spacing */
            text-align: left; /* Align text to the left */
        }
        th {
            background-color: #f8f9fa; /* Background color for the header */
        }
        .additional-table {
            margin-top: 20px; /* Space above the additional table */
            float: right; /* Align to the right */
            width: 45%; /* Set width for the additional table */
        }
    </style>
    <script>
        window.onload = function() {
            window.print(); // Trigger print dialog once the page loads
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2GNINwsGpS5OEmLxrayXn9ApBGkPkbJrcjqpYp_c3x80oynQ9JU8pAauKNSDOfivIr0I&usqp=CAU" alt="Logo"> <!-- Replace with your logo path --> --}}
            <h2>RMS</h2>
            <div class="supplier-info">
                <div>
                    <strong>Invoice Id:</strong>  {{$purchase->id}}<br>
                    <strong>Bill Date:</strong>  {{$purchase->received_date}}<br>
                    <strong>Supplier Name:</strong> {{$purchase->supplier->name}}<br>
                </div>
                <div>
                    <strong>Reporting Date:</strong> {{ date('Y-m-d') }}<br>
                    <strong>User Name:</strong> {{auth()->user()->name}} <!-- Assuming $user is passed to the view -->
                </div>
            </div>
        </div>
        
        <h1 class="text-center">Purchase Invoice</h1>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Product Name</th>
                    <th>Qty Received</th>
                    <th>Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->productVarient->code }}</td>
                    <td>{{ $detail->productVarient->product_variant_name }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>{{ $detail->cost_price }}</td>
                    <td>{{ $detail->actual_discount_value }}</td>
                    <td>{{ $detail->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Additional Table -->
        <div class="additional-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        
                        <th>Invoice Amount</th>
                        <td>{{ $purchaseDetails->sum('total') }}</td> <!-- Direct calculation of total -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                       
                        <th>Adjustment</th>
                        <td>{{ $purchase->adjustment }}</td> <!-- Update with actual other expense -->
                    </tr>
                    <tr>
                        
                        <th>Amount Payable</th>
                        <td>
                            {{ $purchase->net_payable}} <!-- Total calculation -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        
    </div>
</body>
</html>
