<?php

namespace App\Http\Controllers;

use App\Models\MeasuringUnit;
use App\Models\Product\MeasuringUnit\MeasuringUnit as MeasuringUnitMeasuringUnit;
use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product_category;
use App\Models\Product_rate;
use App\Models\Product_stock;
use App\Models\ProductType;
use App\Models\Purchase_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductTypeController extends Controller
{
    public function productList()
    {
        $productTypes = ProductType::with('category', 'measuringUnit')->paginate(10);
        $categoies = Product_category::get();
        $measuring_units = MeasuringUnitMeasuringUnit::get();
        $lastProduct = ProductType::orderBy('id', 'desc')->first();

        if ($lastProduct) {
            $lastProductCode = intval(substr($lastProduct->product_code, 2));
            $nextProductCode = 'SN' . str_pad($lastProductCode + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextProductCode = 'SN001';
        }

        return view('adminPanel.orders.productType', [
            'productTypes' => $productTypes,
            'categoies' => $categoies,
            'measuring_units' => $measuring_units,
            'nextProductCode' => $nextProductCode,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'product_code' => 'required',
            'product_name' => 'required|string|max:255',
            'opening_stock' => 'nullable|integer',
            'measuring_unit_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $product = ProductType::create([
                'product_code' => $request->input('product_code'),
                'name' => $request->input('product_name'),
                'category_id' => $request->input('category'),
                'measuring_unit_id' => $request->input('measuring_unit_id'),
                'opening_stock' => $request->input('opening_stock'),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip(),
            ]);

            if ($product) {
                Product_rate::create([
                    'product_id' => $product->id,
                    'cost_price' => $request->input('cost_price'),
                    'retail_price' => $request->input('retail_price'),
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                ]);

                Product_stock::create([
                    'product_id' => $product->id,
                    'stock' => $request->input('opening_stock'),
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Product creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong, please try again.');
        }
    }

    public function getProduct($id)
    {
        $product = ProductType::find($id);

        $productRate = Product_rate::where('product_id', $id)->first();

        return response()->json([
            'data' => [
                'product_code' => $product->product_code,
                'name' => $product->name,
                'category_id' => $product->category->id,
                'measuring_unit_id' => $product->measuringUnit->id,
                'opening_stock' => $product->opening_stock,
                'cost_price' => $productRate ? $productRate->cost_price : null,
                'retail_price' => $productRate ? $productRate->retail_price : null
            ]
        ]);
    }

    public function getCategoryProduct(Request $request)
    {
        $categoryId = $request->input('category_id');

        if ($categoryId === 'all_category') {
            $products = ProductType::all();
        } else {
            $products = ProductType::where('category_id', $categoryId)->get();
        }

        return response()->json(['products' => $products]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'productId' => 'required',
            'category' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $product = ProductType::find($request->productId);

            if ($product) {
                $product->update([
                    'product_code' => $request->input('product_code'),
                    'name' => $request->input('name'),
                    'category_id' => $request->input('category'),
                    'measuring_unit_id' => $request->input('measuring_unit_id'),
                    'opening_stock' => $request->input('opening_stock'),
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                ]);

                Product_rate::where('product_id', $product->id)->update([
                    'cost_price' => $request->input('cost_price'),
                    'retail_price' => $request->input('retail_price'),
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                ]);

                Product_stock::where('product_id', $product->id)->update([
                    'stock' => $request->input('opening_stock'),
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Product updated successfully.');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Product not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Product update failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong, please try again.');
        }
    }

    public function delete_product($id)
    {
        $del_product = ProductType::find($id);

        if ($del_product) {
            $existsInPurchaseDetail = Purchase_detail::where('product_id', $id)->exists();

            if ($existsInPurchaseDetail) {
                return redirect()->back()->with(['error' => 'Product cannot be deleted as it is referenced in purchase details.']);
            }

            try {
                $del_product->delete();
                return redirect()->back()->with(['success' => 'Product deleted successfully']);
            } catch (\Exception $e) {
                return redirect()->back()->with(['error' => 'Something went wrong, please try again!']);
            }
        }
        return redirect()->back()->with(['error' => 'Product not found!']);
    }

    public function product_reports()
    {
        $allProducts = ProductVariant::with('measuringUnit')->get();
        // dd($allProducts);
        $allCategories = Product_category::all();
        // dd($allCategories);
        return view(
            'adminPanel.orders.reports.product_reports',
            [
                'allProducts' => $allProducts,
                'allCategories' => $allCategories,
            ]
        );
    }

    public function product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'product_id' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Fetch raw sales data for the selected period
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id');

        // Apply filters
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        } elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        } elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        if ($validated['product_id'] !== 'all_products') {
            // If filtering for a specific product, we'll filter in PHP later to include deal components
        }

        $sales = $query->select(
            'sale_products.invoice_id',
            'sale_invoices.bill_date',
            'sale_products.product_id',
            'product_variants.product_variant_name',
            'product_variants.service_item',
            'product_variants.raw_material',
            'product_variants.finish_goods',
            'product_variants.manage_deal_items',
            'sale_products.sale_qty',
            'sale_products.retail_price',
            'sale_products.sale_discount_actual_value'
        )->get();

        $processed_data = [];

        foreach ($sales as $sale) {
            if ($sale->manage_deal_items > 0) {
                // Deal Expansion
                $deal = \App\Models\Deals\DealTable::where('product_variant_deal_id', $sale->product_id)
                    ->with('deal_item.products')
                    ->first();

                if ($deal && $deal->deal_item->count() > 0) {
                    $isFirst = true;
                    foreach ($deal->deal_item as $item) {
                        $processed_data[] = (object)[
                            'invoice_id' => $sale->invoice_id,
                            'bill_date' => $sale->bill_date,
                            'product_id' => $item->product_variant_id,
                            'product_name' => $item->products->product_variant_name ?? 'Unknown',
                            'item_type' => 'Deal Item',
                            'sale_qty' => $sale->sale_qty * $item->product_variant_qty,
                            'retail_price' => $isFirst ? $sale->retail_price : 0,
                            'product_discount_actual_value' => $isFirst ? ($sale->sale_discount_actual_value ?? 0) : 0,
                            'net_payable' => $isFirst ? (($sale->retail_price * $sale->sale_qty) - ($sale->sale_discount_actual_value ?? 0)) : 0,
                        ];
                        $isFirst = false;
                    }
                } else {
                    // Fallback
                    $processed_data[] = (object)[
                        'invoice_id' => $sale->invoice_id,
                        'bill_date' => $sale->bill_date,
                        'product_id' => $sale->product_id,
                        'product_name' => $sale->product_variant_name,
                        'item_type' => 'Deal (No Items)',
                        'sale_qty' => $sale->sale_qty,
                        'retail_price' => $sale->retail_price,
                        'product_discount_actual_value' => $sale->sale_discount_actual_value ?? 0,
                        'net_payable' => ($sale->retail_price * $sale->sale_qty) - ($sale->sale_discount_actual_value ?? 0),
                    ];
                }
            } else {
                // Regular Item (Finish Goods or Service Item)
                $processed_data[] = (object)[
                    'invoice_id' => $sale->invoice_id,
                    'bill_date' => $sale->bill_date,
                    'product_id' => $sale->product_id,
                    'product_name' => $sale->product_variant_name,
                    'item_type' => $sale->service_item ? 'Service Item' : ($sale->raw_material ? 'Raw Material' : 'Finish Good'),
                    'sale_qty' => $sale->sale_qty,
                    'retail_price' => $sale->retail_price,
                    'product_discount_actual_value' => $sale->sale_discount_actual_value ?? 0,
                    'net_payable' => ($sale->retail_price * $sale->sale_qty) - ($sale->sale_discount_actual_value ?? 0),
                ];

                // If it's a service item, also include ingredients (old logic)
                if ($sale->service_item) {
                    $ingredients = \App\Models\Ingredient::where('product_variant_id', $sale->product_id)
                        ->with('ingredients_items.products')
                        ->first();
                    
                    if ($ingredients) {
                        foreach ($ingredients->ingredients_items as $ingItem) {
                            $processed_data[] = (object)[
                                'invoice_id' => $sale->invoice_id,
                                'bill_date' => $sale->bill_date,
                                'product_id' => $ingItem->product_variant_id,
                                'product_name' => $ingItem->products->product_variant_name ?? 'Ingredient',
                                'item_type' => 'Ingredient',
                                'sale_qty' => $sale->sale_qty * $ingItem->qty,
                                'retail_price' => 0,
                                'product_discount_actual_value' => 0,
                                'net_payable' => 0,
                            ];
                        }
                    }
                }
            }
        }

        // Apply final product filter
        if ($validated['product_id'] !== 'all_products') {
            $processed_data = array_filter($processed_data, function($item) use ($validated) {
                return $item->product_id == $validated['product_id'];
            });
        }

        $sale_data = collect($processed_data);

        return view('adminPanel.orders.reports.product_sale_report', [
            'sale_data' => $sale_data,
            'request' => $request
        ]);
    }

    // public function product_purchase_report(Request $request)
    // {
    //     // dd($request);
    //     $validated = $request->validate([
    //         'product_id' => 'required|string',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //     ]);
    //     $query = DB::table('purchase_details')
    //         ->join('purchases', 'purchases.id', '=', 'purchase_details.product_variant_id')
    //         ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id')
    //         ->join('parties', 'parties.id', '=', 'purchases.supplier_id');
    //     if ($validated['product_id'] != 'all_products') {
    //         $query->where('purchase_details.product_variant_id', $validated['product_id']);
    //     }
    //     if ($validated['start_date'] && $validated['end_date']) {
    //         $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
    //     }
    //     $purchase_data = $query->select(
    //         'purchase_details.*',
    //         'purchases.received_date',
    //         'purchases.total_bill',
    //         'purchases.net_payable',
    //         'parties.name as supplier_name',
    //         'product_variants.product_variant_name as product_name'
    //     )->get();
    //     return view('adminPanel.orders.reports.product_purchase_report', [
    //         'purchase_data' => $purchase_data,
    //         'request' => $validated
    //     ]);
    // }

    public function product_stock_report(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|string',
            'product_id' => 'required|string',
        ]);
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Add join with products table
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id') // Join the product_categories table based on category_id in products
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        if ($validated['category_id'] === 'all_category' && $validated['product_id'] === 'all_products') {
        } elseif ($validated['category_id'] === 'all_category') {
            $query->where('product_variant_stocks.product_variant_id', $validated['product_id']);
        } elseif ($validated['product_id'] === 'all_products') {
            $query->where('products.category_id', $validated['category_id']);
        } else {
            $query->where('products.category_id', $validated['category_id'])
                ->where('product_variant_stocks.product_variant_id', $validated['product_id']);
        }
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            'product_categories.name',
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        return view('adminPanel.orders.reports.product_stock_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
        ]);
    }

    // public function product_ledger_report(Request $request)
    // {
    //     $validated = $request->validate([
    //         'product_id' => 'required|string',
    //     ]);

    //     // $product = DB::table('product_variants')
    //     //     ->select('product_variant_name')
    //     //     ->where('id', $validated['product_id'])
    //     //     ->first();
    //     $product = DB::table('product_variant_stocks')
    //     ->select('opening_stock')
    //     ->where('product_variant_id', $validated['product_id'])
    //     ->first();
    //     // dd($product);

    //     $query = DB::table('product_variant_ledgers')
    //         ->join('product_variants', 'product_variants.id', '=', 'product_variant_ledgers.product_variant_id')
    //         ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id');

    //     if ($validated['product_id'] != 'all_products') {
    //         $query->where('product_variant_ledgers.product_variant_id', $validated['product_id']);
    //     }

    //     $ledger_data = $query->select(
    //         'product_variant_ledgers.*',
    //         'product_variants.product_variant_name as product_name',
    //         'measuring_units.name as measuring_unit_name',
    //         DB::raw("IF(product_variant_ledgers.product_variant_purchase_id  IS NOT NULL, 'Purchase', 'Sale') as particular"),
    //         'product_variant_ledgers.product_variant_purchase_stock as debit',
    //         'product_variant_ledgers.product_variant_sale_stock as credit'
    //     )->orderBy('product_variant_ledgers.id', 'asc')->get();

    //     return view('adminPanel.orders.reports.product_ledger_report', [
    //         'ledger_data' => $ledger_data,
    //         'opening_stock' => $product->opening_stock ?? 0, // Pass the opening stock to the view
    //         'request' => $validated,
    //         'product_name' => $product->product_variant_name ?? 'Unknown Product', // Pass product name to the view
    //     ]);
    // }

    public function product_ledger_report(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string',
        ]);

        // Initialize variables for opening stock and product name
        $opening_stock = 0;
        $product_name = 'Unknown Product';

        // Check if it's a specific product or 'all_products'
        if ($validated['product_id'] != 'all_products') {
            // If a specific product is selected, get its opening stock
            $product = DB::table('product_variant_stocks')
                ->select('opening_stock')
                ->where('product_variant_id', $validated['product_id'])
                ->first();

            if ($product) {
                $opening_stock = $product->opening_stock;
                // Set the product name for the specific product
                $product_variant = DB::table('product_variants')
                    ->where('id', $validated['product_id'])
                    ->first();
                $product_name = $product_variant->product_variant_name ?? 'Unknown Product';
            }
        } else {
            // If 'all_products' is selected, aggregate opening stock from all products
            $opening_stock = DB::table('product_variant_stocks')
                ->select(DB::raw('SUM(opening_stock) as total_opening_stock'))
                ->first()->total_opening_stock ?? 0; // Default to 0 if no data exists
        }

        // Construct the query for product variant ledgers
        $query = DB::table('product_variant_ledgers')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_ledgers.product_variant_id')
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id');

        // Apply the filter for specific product or all products
        if ($validated['product_id'] != 'all_products') {
            $query->where('product_variant_ledgers.product_variant_id', $validated['product_id']);
        }

        // Fetch ledger data
        $ledger_data = $query->select(
            'product_variant_ledgers.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            DB::raw("IF(product_variant_ledgers.product_variant_purchase_id IS NOT NULL, 'Purchase', 'Sale') as particular"),
            'product_variant_ledgers.product_variant_purchase_stock as debit',
            'product_variant_ledgers.product_variant_sale_stock as credit'
        )
            ->orderBy('product_variant_ledgers.id', 'asc')
            ->get();

        // Return the view with the data
        return view('adminPanel.orders.reports.product_ledger_report', [
            'ledger_data' => $ledger_data,
            'opening_stock' => $opening_stock, // Pass the opening stock (specific or total for all products)
            'request' => $validated,
            'product_name' => $product_name, // Pass the product name (or 'Unknown Product' for all products)
        ]);
    }
}
