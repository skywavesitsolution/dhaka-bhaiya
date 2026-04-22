<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Invoice #{{ $invoice->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
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
            line-height:16px;
        }

        .clamp-text {
            display: -webkit-box;
            -webkit-line-clamp: 3;
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
    @php
        $settingsData = \App\Models\Setting::pluck('value', 'key')->toArray();
        
        $appLogoSetting = \App\Models\Setting::where('key', 'app_logo')->first();
        $invoiceLogoSetting = \App\Models\Setting::where('key', 'invoice_logo')->first();
        
        $logoUrl = '';
        if ($invoiceLogoSetting && $invoiceLogoSetting->hasMedia('invoice_logo')) {
            $logoUrl = asset($invoiceLogoSetting->getFirstMediaUrl('invoice_logo'));
        } elseif ($appLogoSetting && $appLogoSetting->hasMedia('logo')) {
            $logoUrl = asset($appLogoSetting->getFirstMediaUrl('logo'));
        } else {
            $logoUrl = asset('adminPanel/assets/images/rms_logo.png');
        }
        
        $address = $settingsData['address'] ?? '';
        
        $dynamicContacts = isset($settingsData['contacts']) ? json_decode($settingsData['contacts'], true) : [];
        if (!is_array($dynamicContacts)) $dynamicContacts = [];
        
        $invoiceContacts = [];
        foreach($dynamicContacts as $contact) {
            if(isset($contact['show_on_invoice']) && $contact['show_on_invoice'] == '1') {
                $text = '';
                if(isset($contact['show_label']) && $contact['show_label'] == '1') {
                    $text .= $contact['label'] . ': ';
                }
                $text .= $contact['value'];
                $invoiceContacts[] = $text;
            }
        }
        $contactString = implode('<br>', $invoiceContacts);
        
        $invoiceNote = !empty($settingsData['invoice_note']) ? $settingsData['invoice_note'] : 'Feel Free to clarify!';
    @endphp
    <div class="invoice-container">
        <div class="header">
            @if($logoUrl)
            <div class="logo">
                <img src="{{ $logoUrl }}" alt="Company Logo" />
            </div>
            @endif
            @if($address)
            <p>{{ $address }}</p>
            @endif
            @if($contactString)
            <p style="margin-top:2px;">{!! $contactString !!}</p>
            @endif
            <h1>Customer Invoice</h1>
            <p>Invoice #{{ $invoice->id }}</p>
        </div>

        <div class="head">
            <div class="section">
           <p>Date: {{ $invoice->created_at->format('d-m-Y H:i:s') }}</p>

                
            </div>
            <div class="section">
                <p>Customer: {{ $invoice->customer_name ?? $invoice->party->name ?? 'N/A' }}</p>
                <p>Number: {{ $invoice->customer_number ?? $invoice->party->contact_number ?? 'N/A' }}</p>
            </div>
               @if($invoice->order_type === 'delivery')
    <div class="section">
        <p>Address: {{ $invoice->customer_address ?? $invoice->party->customer_address ?? 'N/A' }}</p>
    </div>
@endif
            <div class="section">
                <p>Order Type: {{ ucfirst($invoice->order_type ?? 'N/A') }}</p>
                @if($invoice->order_type === 'dine-in' && $invoice->table)
                    <p>Table: {{ $invoice->table->table_number }} ({{ $invoice->table->location->name }})</p>
              
                @endif
            </div>
            @if($invoice->employee)
                <div class="section">
                    <p>Served By: {{ $invoice->employee->name ?? 'N/A' }}</p>
                </div>
            @endif
        </div>

        <table class="table">
            <thead class="table-light">
                <tr>
                    <th style="width: 20px">Sr#</th>
                    <th class="text-start">Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productsWithDeals as $key => $item)
                    @php
                        $product = $item['product'];
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="text-start">{{ $product->variant->product_variant_name }}</td>
                        <td>{{ $product->sale_qty }}</td>
                        <td>{{ number_format($product->retail_price, 2) }}</td>
                        <td>{{ number_format($product->sale_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="4">Subtotal:</td>
                    <td>{{ number_format($invoice->total_bill, 2) }}</td>
                </tr>
                @if($invoice->discount_actual_value > 0)
                    <tr>
                        <td colspan="4">Discount ({{ $invoice->discount_type }}):</td>
                        <td>{{ number_format($invoice->discount_actual_value, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">Net Payable:</td>
                        <td>{{ number_format($invoice->net_payable, 2) }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>

        <p class="clamp-text"><b>Note:</b>
            <span>{{ $invoiceNote }}</span>
        </p>
        <div class="footer">
            <p>
                < www.skywavesit.tech>
            </p>
            <p>TechPOS Retail Solution by skywavesit.tech</p>
            <p>0331-3999315</p>
        </div>

        <script>
            window.print();
            setTimeout(() => { window.close(); }, 1000); // Auto-close after printing (optional)

            window.onafterprint = function () {
        window.location.href = "{{ url('get-sale-invoice') }}";
    };
        </script>
    </div>
</body>
</html>