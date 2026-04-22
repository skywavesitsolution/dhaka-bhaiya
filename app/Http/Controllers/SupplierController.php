<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function addSupplier(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['email', 'nullable', 'unique:suppliers'],
        ]);

        $result = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Supplier Added Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function getSupplierList()
    {
        $allSuppliers = $this->getSupplierWithPagination(10);
        return view('adminPanel.Supplier.supplierList', ['suppliers' => $allSuppliers]);
    }


    public function getSupplier($id)
    {
        $supplier = Supplier::find($id);
        return response()->json(['data' => $supplier]);
    }

    public function updateSupplier(Request $request)
    {
        $request->validate([
            'supplierId' => 'required'
        ]);

        $result = Supplier::find($request->supplierId)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'address' => $request->address,
            ]);
        if ($result) {
            return redirect()->back()->with(['success' => 'Supplier Updated Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    static public function getAllSupplier(): Collection
    {
        return Supplier::all();
    }

    public function getSupplierWithPagination(int $items)
    {
        $parties = Supplier::paginate($items);
        return $parties;
    }
}
