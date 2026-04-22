<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            /* margin: 20px; */
        }

        img {
            width: 100px;
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .invoice-container {
            padding: 10px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .head {
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            /* margin-bottom: 5px; */
        }

        .header p {
            margin: 0;
            font-size: 14px;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
            font-size: 12px;
            /* font-weight: bold; */
        }

        .section {
            display: flex;
            justify-content: space-between;
        }

        .table-foot {
            display: flex;
            justify-content: space-between;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
            border: 1px dashed #000;
            border-radius: 5px;
            padding: 2px;
            font-family: sans-serif;
        }

        .table th,
        .table td {
            font-size: 14px;
            padding: 4px;
            text-align: center;
            /* font-weight: ; */
        line-height:16px;
        }

        .clamp-text {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .print-btn {
            margin-top: 20px;
            text-align: center;
        }

        .qr-code {
            width: 100%;
            justify-content: center;
            display: flex;
        }

        .qr-code img {
            padding-bottom: 10px;
            width: 100px;
            margin: 0 auto;
        }

        @media print {
            .print-btn {
                display: none;
            }

          
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">
                <img src="../public/adminPanel/assets/images/rms_logo.png"
                    alt="" />
              
            </div>
            <p>Kitchen Inoice</p>
         
        </div>
        <div class="head">
            <div class="section">
                <p>Date: {{ $invoice->created_at }}</p>
                <p>inv.: {{ $invoice->id }}</p>
            </div>

            <div class="section">
                <p>Customer: {{ $invoice->customer_name ?? 'Walking Customer' }}</p>
                <p>Cashier ID: {{ Auth::user()->id }}</p>
            </div>
            <div class="section">
                <p>Order Type: {{ $invoice->order_type }}</p>
                <p>Table Number: {{ $invoice->table_number ?? 'null' }}</p>
                
            </div>
            {{-- <div>
            </div> --}}
            
        </div>

        <table class="table" id="salesTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 20px">Sr#</th>
                    <th class="text-start">Item</th>
                    <th>Qty</th>
                    
                </tr>
            </thead>
            <tbody>
    @foreach ($productsWithDeals as $key => $data)
        @php
            $product = $data['product'];
            $deals = $data['deals'];
        @endphp

        <!-- Product Row -->
        <tr >
            <td>{{ $key + 1 }}</td>
            <td class="text-start" >{{ $product->variant->product_variant_name }}</td>
          <td class="sale_qty">{{ $product->sale_qty }}</td>
          
        </tr>
    

        <!-- Deals for the Product -->
        @if ($deals->isNotEmpty())
            <tr>
                <td colspan="3" class="text-start"><strong>Deal Products:</strong></td>
            </tr>
            @foreach ($deals as $deal)
                @foreach ($deal->deal_item as $dealItem)
                    <tr >
                      
                        <td class="text-start" colspan="2" >{{ $dealItem->products->product_variant_name }}</td>
                        <td class="text-center">{{ $dealItem->product_variant_qty }}</td>
                        {{-- Additional fields can be added here if needed --}}
                    </tr>
                    
                    
                @endforeach
            @endforeach
        @endif
    @endforeach
</tbody>

            <tfoot class="table-light">
                {{-- <tr>
                    <td colspan="2" style="text-align: left">Subtotal:</td>
                    <td id="total_qty"></td>
                    <td></td>
                    <td></td>
                    <td id="total_amount"></td>
                </tr> --}}
            </tfoot>
        </table>

        {{-- <div class="table-foot">
            <div>
                <p><strong>Bill Discount:</strong></p>
                <p><strong>Net Payable:</strong></p>
                <p><strong>Amount Paid:</strong></p>
            </div>
            <div>
                <p id="bill_discount"><strong>0.00</strong></p>
                <p id="net_payable"><strong>0.00</strong></p>
                <p id="amount_paid"><strong>0.00</strong></p>
            </div>
        </div> --}}
        
        <p class="clamp-text"><b>Note:</b>
            <span> Feel Free to clarify!</span>
        </p>
        <div class="footer">
            <p>
                < www.skywavesit.tech>
            </p>
            <p>TechPOS Retail Solution by skywavesit.tech</p>
            <p>0331-3999315</p>
        </div>

      

        

        {{-- <div class="print-btn">
            <button class="btn btn-primary" onclick="window.print()">Print</button>
        </div> --}}
    </div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>


<script>
    window.onload = function () {
        // Generate QR Code
        var url = "http://localhost/resturent/printquotation/{{ $invoice->id }}";
      

        // Print the page
        window.print();
    };

    // Detect when print dialog is closed
    window.onafterprint = function () {
        window.location.href = "{{ url('get-sale-invoice') }}"; // Redirect to POS page
    };

    // Alternative: Detect print cancelation (works in modern browsers)
    var mediaQueryList = window.matchMedia('print');
    mediaQueryList.addEventListener('change', function (mql) {
        if (!mql.matches) {
            window.location.href = "{{ url('get-sale-invoice') }}"; // Redirect after print dialog closes
        }
    });
</script>



</body>

</html>
