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

            .item {
                width: 50%;
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
            // Fallback to original path if no media exists
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
        
        $invoiceNote = !empty($settingsData['invoice_note']) ? $settingsData['invoice_note'] : 'Thanks For Your Kind Visit.';
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
        </div>
        <div class="head">
            <div class="section">
                <p>Date: {{ $invoice->created_at->format('d-m-Y H:i:s') }}</p>
                <p>Inv: {{ $invoice->id }}</p>
            </div>
            <div class="section">
                <p>Payment Type: {{ $invoice->payment_type }}</p>
                <p>Cashier ID: {{ $invoice->user_id }}</p>
            </div>
            <div>
                <p>Customer Name: {{ $invoice->customer_name ?? 'Walking Customer' }}</p>
            </div>
        </div>

        <table class="table" id="salesTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 20px">Sr#</th>
                    <th class="text-start">Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Disc.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->saleProduct as $key => $product)
                    <tr style="border-color: #fff;">
                        <td>{{ $key + 1 }}</td>
                        <td class="text-start" colspan="5">
                            @php
                                // Check if this product is a deal
                                $isDeal = $product->getProduct->deal()->exists();
                                $displayName = $isDeal 
                                    ? $product->getProduct->product_variant_name 
                                    : $product->getProduct->product_variant_name;
                            @endphp
                            {{ $displayName }}
                        </td>
                        <td class="d-none">1</td>
                        <td class="d-none">600</td>
                        <td class="d-none">0</td>
                        <td class="d-none">600</td>
                    </tr>
                    <tr style="line-height: 8px;">
                        <td></td>
                        <td class="item"></td>
                        <td class="sale_qty">{{ $product->sale_qty }}</td>
                        <td class="sale_price">{{ $product->retail_price }}</td>
                        <td class="sale_discount">{{ $product->sale_dicount ?? '0.00' }}</td>
                        <td class="sale_amount">{{ $product->sale_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" style="text-align: left">Subtotal:</th>
                    <th id="total_qty"></th>
                    <th></th>
                    <th id="bill_discount"></th>
                    <th id="total_amount"></th>
                </tr>
            </tfoot>
        </table>

        <div class="table-foot">
            <div>
                <p><strong>Invoice Discount:</strong></p>
                <p><strong>Products Discount:</strong></p>
                <p><strong>Service Charges:</strong></p>
                <p><strong>Net Payable:</strong></p>
            </div>
            <div>
                <p id="invoice_discount"><strong>{{ $invoice->discount_actual_value ?? 0.00 }}</strong></p>
                <p id="bill_discounts"><strong>0.00</strong></p>
                <p id="service_charges"><strong>{{ $invoice->service_charges ?? 0 }}</strong></p>
                <p id="net_payable"><strong>{{ $invoice->net_payable }}</strong></p>
            </div>
        </div>

        <p class="clamp-text"><b>Note:</b>
            <span>{{ $invoiceNote }}</span>
        </p>
        <div class="footer">
            <p>< www.skywavesit.tech></p>
            <p>TechPOS Retail Solution by Skywaves Network & IT Solutions</p>
            <p>0331-3999315</p>
        </div>
    </div>

    <script>
window.onload = function () {
    function calculateTotals() {
        let totalQty = 0;
        let totalAmount = 0;
        let totalDiscount = 0;

        const rows = document.querySelectorAll("#salesTable tbody tr");

        rows.forEach((row, index) => {
            if (index % 2 !== 0) {
                const qty = parseFloat(row.querySelector(".sale_qty")?.textContent) || 0;
                const amount = parseFloat(row.querySelector(".sale_amount")?.textContent) || 0;
                const discount = parseFloat(row.querySelector(".sale_discount")?.textContent) || 0;

                totalQty += qty;
                totalAmount += amount;
                totalDiscount += discount;
            }
        });

        const invoiceDiscount = parseFloat(document.getElementById("invoice_discount").textContent) || 0;
        const serviceCharges = parseFloat(document.getElementById("service_charges").textContent) || 0;
        const billDiscount = totalDiscount;
        const totalCombinedDiscount = invoiceDiscount;
        const netPayable = (totalAmount - totalCombinedDiscount) + serviceCharges;

        document.getElementById("total_qty").textContent = totalQty.toFixed(2);
        document.getElementById("total_amount").textContent = totalAmount.toFixed(2);
        document.getElementById("bill_discount").textContent = billDiscount.toFixed(2);
        document.getElementById("bill_discounts").querySelector("strong").textContent = billDiscount.toFixed(2);
        document.getElementById("net_payable").querySelector("strong").textContent = netPayable.toFixed(2);
        document.getElementById("service_charges").querySelector("strong").textContent = serviceCharges.toFixed(2);
    }

    calculateTotals();
    window.print();
    window.onafterprint = function () {
        window.location.href = "{{ url('get-sale-invoice') }}";
    };
};
    </script>
</body>

</html>