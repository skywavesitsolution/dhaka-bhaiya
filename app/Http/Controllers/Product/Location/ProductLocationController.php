<?php

namespace App\Http\Controllers\Product\Location;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariantLocation;

class ProductLocationController extends Controller
{
    public function index()
    {
        $productLocations = ProductLocation::with('user')->paginate(10);
        $allLocations = ProductLocation::all();
        return view(
            'adminPanel.product.location.productLocationList',
            [
                'productLocations' => $productLocations,
                'allLocations' => $allLocations,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string']
        ]);

        $result = ProductLocation::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Location added successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong try again']);
    }

    public function show(string $id)
    {
        $productLocation = ProductLocation::find($id);

        return response()->json(['data' => $productLocation]);
    }

    public function update(Request $request)
    {
        $productLocation = ProductLocation::find($request->productLocationId);
        $result = $productLocation->update([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Location Updated Successfully']);
        }

        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }


    public function changeProductLocation(Request $request)
    {
        $request->validate([
            'from_location_id' => 'required|exists:product_locations,id',
            'to_location_id' => 'required|exists:product_locations,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $productVariants = ProductVariant::where('location_id', $request->from_location_id)->get();
                if ($productVariants->isNotEmpty()) {
                    foreach ($productVariants as $productVariant) {
                        $productVariant->update([
                            'location_id' => $request->to_location_id,
                        ]);
                    }
                }
            });
            return redirect()->back()->with(['success' => 'Product Location Updated Successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating product location: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong. Check logs for details.']);
        }
    }

    public function destroy($id)
    {
        $productLocation = ProductLocation::find($id);

        if (!$productLocation) {
            return response()->json(['message' => 'Product Location not found'], 404);
        }

        $productVariantExists = ProductVariantLocation::where('location_id', $productLocation->id)->exists();

        if ($productVariantExists) {
            return response()->json(['message' => 'This location is assigned to one or more product variants and cannot be deleted.'], 400);
        }

        $productLocation->delete();

        return response()->json(['message' => 'Location deleted successfully.'], 200);
    }
}
