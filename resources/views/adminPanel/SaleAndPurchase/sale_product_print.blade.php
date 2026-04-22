<!DOCTYPE html>
<html>
<head>
    <title>Print Sale Invoice</title>
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
            justify-content: center; /* Center the items */
            margin-top: 10px; /* Space above the supplier info */
            justify-content: space-between
        }
        .supplier-info div {
            margin-right: 20px; /* Space between info items */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2GNINwsGpS5OEmLxrayXn9ApBGkPkbJrcjqpYp_c3x80oynQ9JU8pAauKNSDOfivIr0I&usqp=CAU" alt="Logo"> <!-- Replace with your logo path -->
            <div class="supplier-info">
                <div>
                    <strong>Invoice Id:</strong> {{ $editSaleInvoice->id }}<br>
                    <strong>Bill Date:</strong> {{ $editSaleInvoice->bill_date }}<br>
                    <strong>Customer Name:</strong> {{ $editSaleInvoice->party->name ?? 'null' }}


                
                </div> <!-- Replace with actual supplier name -->
                <div>
                    <strong>User name:</strong> {{ $editSaleInvoice->user->name }}<br>
                    <strong>Reporting Date:</strong> {{ date('Y-m-d') }}
                </div> <!-- Display current date -->
            </div>
        </div>
        
        <h1 class="text-center">Sale Invoice</h1>
        
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Retail Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                    @php
                            $grandTotal = 0; // Accumulate the grand total
                        @endphp
                @isset($saleProducts)
                    @foreach($saleProducts as $product)

                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->getProduct->product_variant_name }}</td>
                        <td>{{ $product->sale_qty }}</td>
                        <td>{{ $product->retail_price }}</td>
                        <td>{{ $product->sale_amount }}</td>
                    </tr>

                        @php
                            $grandTotal += $product->sale_amount; // Accumulate the grand total
                        @endphp
                    @endforeach
                    @endisset
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-bold-800" colspan="3">Invoice Total Amount</td>
                    <td class="text-bold-800 text-end">{{ $grandTotal }}</td> <!-- Display grand total here -->
                </tr>
            </tfoot>
        </table>
        <div class="row row-cols-2">
    <div class="col"></div>
    <div class="col">
        <div class="table-responsive">
            <h3>Bilty Details</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>bilty Number </th>
                        <th>Vahical Number </th>
                        <th>Cargo Name </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$editSaleInvoice->bilty->bilty_number ?? 'null'}}</td>
                        <td>{{$editSaleInvoice->bilty->vahical_number ?? 'null'}}</td>
                        <td>{{$editSaleInvoice->bilty->cargo_name ?? 'null'}}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

        <!-- <button onclick="window.print()" class="btn btn-primary mt-3">Print</button> -->
    </div>
</body>
<script>
     window.onafterprint = function () {
           window.location.href = "{{ url('sale-inovice-list') }}"; // Change this route to your POS page route
        };
</script>
</html>
