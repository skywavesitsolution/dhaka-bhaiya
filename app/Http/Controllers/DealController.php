<?php

namespace App\Http\Controllers;

use App\Models\Deals\DealItem;
use App\Models\Deals\DealTable;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantRate; // Add this use statement
use App\Models\Table\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DealController extends Controller
{
    public function index()
    {
        $deals_products = ProductVariant::where('manage_deal_items', '1')->get();
        $deals = DealTable::with('products', 'deal_item.products')->get() ?? 'null';

        return view('adminPanel.product.product.create_deals', compact('deals_products', 'deals'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'deal_id' => 'required|exists:product_variants,id',
            'deal_total' => 'required|numeric|min:0',
            'pro_id' => 'required|array',
            'pro_id.*' => 'exists:product_variants,id',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:1',
            'retail_price' => 'required|array',
            'retail_price.*' => 'numeric|min:0',
            'total' => 'required|array',
            'total.*' => 'numeric|min:0',
        ]);

        Log::info('Storing new deal. Request data:', $request->all());

        // Check if a deal with the same product_variant_deal_id already exists
        if (DealTable::where('product_variant_deal_id', $request->deal_id)->exists()) {
            return redirect()->back()->with('error', 'A Deal With This Product ID Already Exists!');
        }

        try {
            DB::beginTransaction();

            // Create the deal
            $deal = DealTable::create([
                'product_variant_deal_id' => $request->deal_id,
                'deal_total' => $request->deal_total,
                'status' => 'active',
                'user_id' => Auth::id(),
            ]);

            // Create deal items
            foreach ($request->pro_id as $index => $productId) {
                DealItem::create([
                    'deal_id' => $deal->id,
                    'product_variant_id' => $productId,
                    'product_variant_qty' => $request->qty[$index],
                    'retail_price' => $request->retail_price[$index],
                    'total_price' => $request->total[$index],
                    'user_id' => Auth::id(),
                ]);
            }

            // Update the retail price in ProductVariantRates
            $productVariantId = $request->deal_id;
            Log::info("Attempting To update Retail_price For ProductVariant ID: $productVariantId with value: " . $request->deal_total);

            $productVariant = ProductVariant::findOrFail($productVariantId);
            $rate = $productVariant->rates()->firstOrFail(); // Get the first rate or fail

            $rate->retail_price = $request->deal_total;
            $rate->save();

            Log::info("Retail Price Updated Successfully In ProductVariantRates For ProductVariant ID: $productVariantId. New value: " . $rate->retail_price);

            DB::commit();

            return redirect()->back()->with('success', 'Deal Added Successfully, And Product Retail Price Updated In Rates.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create deal: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An Error Occurred While Creating The Deal. Please Try Again. Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'edit_deal_id' => 'required|exists:deal_tables,id',
            'deal_id' => 'required|exists:product_variants,id',
            'edit_deal_total' => 'required|numeric|min:0', // Add this to validate deal_total from form
            'pro_id' => 'required|array',
            'pro_id.*' => 'exists:product_variants,id',
            'qty' => 'required|array',
            'qty.*' => 'numeric|min:1',
            'total' => 'required|array',
            'total.*' => 'numeric|min:0',
        ]);

        $dealId = $request->edit_deal_id;

        try {
            DB::beginTransaction();

            $deal = DealTable::with('deal_item')->findOrFail($dealId);

            // Check for duplicate deals
            $existingDeal = DealTable::where('product_variant_deal_id', $request->deal_id)
                ->where('id', '!=', $dealId)
                ->exists();

            if ($existingDeal) {
                return redirect()->back()->with('error', 'A Different Deal With This ID Already Exists!');
            }

            // Calculate new deal total from the form input
            $newDealTotal = $request->edit_deal_total;

            // Update the deal with both product_variant_deal_id and deal_total
            $deal->update([
                'product_variant_deal_id' => $request->deal_id,
                'deal_total' => $newDealTotal, // Add this to update deal_total
                'status' => 'active',
                'user_id' => Auth::user()->id,
            ]);

            // Handle deal items
            $currentProductIds = $deal->deal_item->pluck('product_variant_id')->toArray();
            $newProductIds = $request->pro_id;

            $productsToRemove = array_diff($currentProductIds, $newProductIds);
            $productsToAdd = array_diff($newProductIds, $currentProductIds);

            if (!empty($productsToRemove)) {
                DealItem::where('deal_id', $deal->id)
                    ->whereIn('product_variant_id', $productsToRemove)
                    ->delete();
            }

            foreach ($request->pro_id as $index => $productId) {
                $quantity = $request->qty[$index] ?? 0;
                $totalPrice = $request->total[$index] ?? 0;

                $dealItem = DealItem::where('deal_id', $deal->id)
                    ->where('product_variant_id', $productId)
                    ->first();

                if ($dealItem) {
                    $dealItem->update([
                        'product_variant_qty' => $quantity,
                        'total_price' => $totalPrice,
                    ]);
                } else {
                    DealItem::create([
                        'deal_id' => $deal->id,
                        'product_variant_id' => $productId,
                        'product_variant_qty' => $quantity,
                        'total_price' => $totalPrice,
                        'retail_price' => 0,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            // Update retail price in ProductVariantRates
            $productVariant = ProductVariant::findOrFail($request->deal_id);
            $rate = $productVariant->rates()->firstOrFail();

            $rate->retail_price = $newDealTotal;
            $rate->save();

            Log::info("Deal Total Updated in DealTable for Deal ID: " . $deal->id . " to: " . $newDealTotal);
            Log::info("Retail Price Updated in ProductVariantRates for ProductVariant ID: " . $request->deal_id . " to: " . $newDealTotal);

            DB::commit();

            return redirect()->back()->with('success', 'Deal Updated Successfully, Deal Total and Product Retail Price Updated in Rates.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update deal: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An Error Occurred While Updating The Deal. Please Try Again. Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $deal = DealTable::find($id);

            if (!$deal) {
                return redirect()->back()->with('error', 'Deal Not Found!');
            }

            $deal->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Deal Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete deal: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An Error Occurred While Deleting The Deal. Please Try Again. Error: ' . $e->getMessage());
        }
    }

    public function showTablePage()
    {
        $tables = Table::with('location')->get();
        $locations = ProductLocation::get();

        return view('adminPanel.product.product.create_table', compact('tables', 'locations'));
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'table_number' => [
                'required',
                'integer',
                Rule::unique('tables')->where('table_location', $request->table_location),
            ],
            'table_location' => 'required|exists:product_locations,id',
        ]);

        $table = Table::create([
            'table_number' => $request->table_number,
            'table_location' => $request->table_location,
            'status' => 'Free',
            'user_id' => Auth::id(),
        ]);

        if ($table->wasRecentlyCreated) {
            return back()->with('success', 'Table Added Successfully!');
        }

        return back()->with('error', 'Failed to add table. Please try again.')
            ->withInput();
    }

    public function show($id)
    {
        $deal = DealTable::with('deal_item.products', 'products')->find($id);

        if (!$deal) {
            return response()->json(['message' => 'Deal Not Found'], 404);
        }

        $products = $deal->deal_item->map(function ($item) {
            return [
                'id' => $item->products->id ?? null,
                'name' => $item->products->product_variant_name ?? '',
                'quantity' => $item->product_variant_qty ?? 0,
                'retail' => $item->retail_price ?? 0,
                'total' => $item->total_price ?? 0,
            ];
        });

        return response()->json([
            'data' => [
                'id' => $deal->id,
                'deal_id' => $deal->product_variant_deal_id,
                'deal_total' => $deal->deal_total, // Ensure this is included
                'product_name' => $deal->products->product_variant_name ?? '',
                'products' => $products,
            ],
        ]);
    }

    public function getTable($id)
    {
        $table = Table::find($id);

        if ($table) {
            return response()->json([
                'status' => 'success',
                'data' => $table,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Table not found!',
        ], 404);
    }

    public function updateTable(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'table_number' => [
                'required',
                'integer',
                Rule::unique('tables')->where('table_location', $request->table_location)->ignore($request->table_id),
            ],
            'table_location' => 'required|exists:product_locations,id',
            'table_status' => 'required|in:Free,Reserve',
        ]);

        $updated = Table::where('id', $request->table_id)->update([
            'table_number' => $request->table_number,
            'table_location' => $request->table_location,
            'status' => $request->table_status,
        ]);

        if ($updated) {
            return back()->with('success', 'Table Updated Successfully!');
        }

        return back()->with('error', 'No changes were made or an error occurred. Please try again.');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->query('query');
        $cacheKey = 'products_' . md5($query);

        $products = Cache::remember($cacheKey, 60, function () use ($query) {
            return ProductVariant::with(['rates' => function ($q) {
                $q->select('product_variant_id', 'retail_price');
            }])
                ->where('manage_deal_items', 0)
                ->where(function ($q) use ($query) {
                    $q->where('code', 'LIKE', "$query%")
                        ->orWhere('product_variant_name', 'LIKE', "$query%");
                })
                ->select('id', 'code', 'product_variant_name')
                ->limit(20)
                ->get();
        });

        return response()->json($products);
    }
}
