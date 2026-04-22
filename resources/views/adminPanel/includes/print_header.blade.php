<?php

use Illuminate\Support\Facades\Auth;

$agent_data = Auth::user()->img;

?>
<!DOCTYPE html>
<html>

<head>
    @php
        $settingsData = \App\Models\Setting::pluck('value', 'key')->toArray();
        $companyName = $settingsData['company_name'] ?? 'TechPOS RMS';
        
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
    @endphp
    <title>{{ $companyName }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
    <style>
        @media print {
            #buttonRow {
                display: none;
                /* Hide the button row when printing */
            }
        }
    </style>

</head>

<body>
    <section style="text-align: center;">

        <img src="{{ $logoUrl }}" alt="{{ $companyName }}"
            style="width: 220px;margin-bottom:-4rem;">