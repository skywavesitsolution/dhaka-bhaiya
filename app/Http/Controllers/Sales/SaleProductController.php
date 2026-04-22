<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleProductController extends Controller
{
    public function getSaleProduct()
    {
        $getSaleProduct = SaleProduct::get();

        return view('adminPanel.', compact('getSaleProduct'));
    }

    public function storeSaleProduct(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'exists:sale_invoices,id'],
            'product_id' => ['required', 'exists:products,id'],
            'sale_qty' => ['required', 'string'],
            'sale_dicount' => ['nuallable', 'string'],
            'sale_amount' => ['required', 'string'],
        ]);

        $result = SaleProduct::create([
            'user_id' => Auth::user()->id,
            'invoice_id' => $request->saleInvoice->id,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'retail_price' => $request->retail_price,
            'qty' => $request->qty,
            'product_discount_type' => $request->product_discount_type,
            'product_discount_value' => $request->product_discount_value,
            'product_discount_actual_value' => $request->product_discount_actual_value,
            'total' => $request->total,
            'ip_address' => $request->ip(),
        ]);

        

        if ($result) {
            return redirect()->back()->with(['success' => 'Sale product added successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong try again']);
    }
}
