<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\PartyDiscount;
use App\Models\PartyDiscountProduct;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartyController extends Controller
{
    public function addParty(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => ['required'],
            'type' => ['required', 'in:Supplier,Both,Customer'],
            'opening_balance' => ['integer'],
            'balance' => ['integer'],
            'email' => ['nullable', 'email', 'unique:parties'],
        ]);

        DB::beginTransaction(); // Start the transaction

        try {
            // Create the new party record
            $result = Party::create([
                'name' => $request->name,
                'type' => $request->type,
                'opening_balance' => $request->openingBalance,
                'balance' => $request->openingBalance,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'user_id' => Auth::user()->id,
            ]);

            // Handle discounts only if 'overall_discount_type' is set
            if (!empty($request->overall_discount_type)) {
                // If the discount type is '1' (bill discount), create a party discount
                if ($request->overall_discount_type == '1') {
                    PartyDiscount::create([
                        'party_id' => $result->id,
                        'discount_type' => $request->discount_type, // Ensure this field is coming from the request
                        'discount_value' => $request->discount_value, // Ensure this field is coming from the request
                        'user_id' => Auth::user()->id,
                    ]);
                } else {
                    // Loop through each product and create the discount product entries if discount is for products
                    foreach ($request->product_id as $index => $productId) {
                        PartyDiscountProduct::create([
                            'product_variant_id' => $productId,
                            'cost_price' => $request->cost_price[$index],
                            'retail_price' => $request->retail_price[$index],
                            'product_discount' => $request->discount[$index],
                            'product_discount_type' => $request->discount_type[$index],
                            'party_id' => $result->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            }

            DB::commit(); // Commit the transaction if everything was successful

            // If party creation is successful, redirect back with success message
            return redirect()->back()->with(['success' => 'Party Added Successfully']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if any part fails

            // Catch any exception and log the error for debugging
            Log::error('Error in adding party: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with(['error' => 'Something Went Wrong. Please try again.']);
        }
    }

    public function addPartyWithAjax(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'type' => ['required', 'in:Supplier,Driver,Customer'],
            'opening_balance' => ['integer'],
            'balance' => ['integer'],
            'email' => ['nullable', 'email', 'unique:parties'],
        ]);

        $result = Party::create([
            'name' => $request->name,
            'type' => $request->type,
            'supplier_id' => $request->supplier_id,
            'opening_balance' => $request->openingBalance,
            'balance' => $request->openingBalance,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'address' => $request->address,
            // 'phone_number' => $request->phone_number,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Party Added Successfully',
                'newSupplier' => [
                    'id' => $result->id,
                    'name' => $result->name,
                ],
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'Something Went Wrong Try Again',
        ]);
    }

    public function getSuppliers()
    {
        $suppliers = Party::where('type', 'Supplier')->get(['id', 'name']);
        return response()->json($suppliers);
    }

    public function fetchPartieswithTypes($type)
    {
        $parties = Party::where('type', $type)->get();
        return response()->json([
            'error' => false,
            'data' => [
                'parties' => $parties
            ]
        ]);
    }

    public function getpartiesList()
    {
        $allParties = $this->getPartiesWithPagination(5);
        $suppliers = Supplier::all();
        $products = ProductVariant::all();
        return view('adminPanel.party.partyList', ['parties' => $allParties, 'suppliers' => $suppliers, 'products' => $products]);
    }

    public function getParty($id)
    {
        $party = Party::find($id);
        return response()->json(['data' => $party]);
    }

    public function updateParty(Party $party, Request $request)
    {
        $request->validate([
            'partyId' => 'required'
        ]);

        $result = Party::find($request->partyId)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
            ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Party Updated Successfully']);
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    static public function getAllParties(): Collection
    {
        return Party::all();
    }

    public function getPartiesWithPagination(int $items)
    {
        $parties = Party::OrderBy('id', 'desc')->paginate($items);
        return $parties;
    }
}
