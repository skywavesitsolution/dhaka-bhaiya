<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settingsQuery = Setting::all();
        $settings = $settingsQuery->pluck('value', 'key')->toArray();
        
        $appLogoSetting = $settingsQuery->where('key', 'app_logo')->first();
        $invoiceLogoSetting = $settingsQuery->where('key', 'invoice_logo')->first();

        $appLogoUrl = $appLogoSetting ? $appLogoSetting->getFirstMediaUrl('logo') : null;
        $invoiceLogoUrl = $invoiceLogoSetting ? $invoiceLogoSetting->getFirstMediaUrl('invoice_logo') : null;

        return view('adminPanel.settings.index', compact('settings', 'appLogoUrl', 'invoiceLogoUrl'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'logo' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (((int) (strlen($value) * (3 / 4))) > 2097152) {
                    $fail('The App Logo must not be greater than 2MB.');
                }
            }],
            'invoice_logo' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (((int) (strlen($value) * (3 / 4))) > 2097152) {
                    $fail('The Invoice Logo must not be greater than 2MB.');
                }
            }],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $contacts = $request->input('contacts', []);
        Setting::updateOrCreate(
            ['key' => 'contacts'],
            ['value' => json_encode($contacts), 'type' => 'json']
        );

        $data = $request->except(['_token', 'logo', 'invoice_logo', 'same_as_logo', 'contacts']);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => 'text']
                );
            }
        }

        if ($request->filled('logo')) {
            $logoSetting = Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => 'media', 'type' => 'image']
            );
            $logoSetting->clearMediaCollection('logo');
            $logoSetting->addMediaFromBase64($request->logo)
                        ->usingFileName('logo_' . time() . '.png')
                        ->toMediaCollection('logo');
        }

        if ($request->has('same_as_logo') && $request->same_as_logo == '1') {
            $logoSetting = Setting::where('key', 'app_logo')->first();
            if ($logoSetting && $logoSetting->hasMedia('logo')) {
                $invoiceSetting = Setting::updateOrCreate(
                    ['key' => 'invoice_logo'],
                    ['value' => 'media', 'type' => 'image']
                );
                $invoiceSetting->clearMediaCollection('invoice_logo');
                $logoSetting->getFirstMedia('logo')->copy($invoiceSetting, 'invoice_logo');
            }
        } else {
            if ($request->filled('invoice_logo')) {
                $invoiceSetting = Setting::updateOrCreate(
                    ['key' => 'invoice_logo'],
                    ['value' => 'media', 'type' => 'image']
                );
                $invoiceSetting->clearMediaCollection('invoice_logo');
                $invoiceSetting->addMediaFromBase64($request->invoice_logo)
                               ->usingFileName('invoice_logo_' . time() . '.png')
                               ->toMediaCollection('invoice_logo');
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully.'
        ]);
    }
}
