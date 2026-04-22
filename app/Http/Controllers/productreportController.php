<?php

namespace App\Http\Controllers;

use App\Models\Models\Employee\Employee;
use App\Models\Party;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product_category;
use Database\Seeders\LocationSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\progress;

class productreportController extends Controller
{
    public function product_stock_reports()
    {
        $allProducts = ProductVariant::with('measuringUnit')->get();
        $allCategories = Product_category::all();
        $allBrands = ProductBrand::all();
        $alllocations = ProductLocation::all();
        $allSuppliers = Party::where('type', 'Supplier')->get();
        // dd($allBrands);
        return view(
            'adminPanel.orders.reports.stock.stock_report',
            [
                'allProducts' => $allProducts,
                'allCategories' => $allCategories,
                'allBrands' => $allBrands,
                'allSuppliers' => $allSuppliers,
                'alllocations' => $alllocations,
            ]
        );
    }


    public function all_product_stock_report(Request $request)
    {
        // Validate the product_id and date range (if provided)
        $validated = $request->validate([
            'product_id' => 'required|string',
            'start_date' => 'nullable|date',  // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be after or equal to start_date
        ]);

        // Start the query to fetch the stock data
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Add join with products table
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        // Apply product_id filter if not 'all_products'
        if ($validated['product_id'] !== 'all_products') {
            $query->where('product_variants.id', $validated['product_id']);
        }

        // // Apply date range filter if both start_date and end_date are provided
        // if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
        //     $query->whereBetween('product_variant_stocks.created_at', [
        //         $validated['start_date'],
        //         $validated['end_date']
        //     ]);
        // }
        // // If only start_date is provided, apply a filter for greater than or equal to the start_date
        // elseif (!empty($validated['start_date'])) {
        //     $query->where('product_variant_stocks.created_at', '>=', $validated['start_date']);
        // }
        // // If only end_date is provided, apply a filter for less than or equal to the end_date
        // elseif (!empty($validated['end_date'])) {
        //     $query->where('product_variant_stocks.created_at', '<=', $validated['end_date']);
        // }

        // Fetch the stock data with the necessary fields
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        // Return the view with stock data and validated request
        return view('adminPanel.orders.reports.product_stock_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
        ]);
    }
    public function category_wise_product_stock_report(Request $request)
    {
        // Validate the category_id and optional date range
        $validated = $request->validate([
            'category_id' => 'required|string', // category_id is required
            'start_date' => 'nullable|date',  // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch the stock data
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join with products table
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id') // Join with product_categories table
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        // Apply condition based on category_id
        if ($validated['category_id'] !== 'all_category') {
            // If category_id is not 'all_category', filter by the specific category_id
            $query->where('product_categories.id', $validated['category_id']);
        }

        // // Apply date filter if start_date and end_date are provided
        // if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
        //     $query->whereBetween('product_variant_stocks.created_at', [
        //         $validated['start_date'],
        //         $validated['end_date']
        //     ]);
        // }
        // // If only start_date is provided, filter records with date >= start_date
        // elseif (!empty($validated['start_date'])) {
        //     $query->where('product_variant_stocks.created_at', '>=', $validated['start_date']);
        // }
        // // If only end_date is provided, filter records with date <= end_date
        // elseif (!empty($validated['end_date'])) {
        //     $query->where('product_variant_stocks.created_at', '<=', $validated['end_date']);
        // }

        // Fetch the stock data with the necessary fields
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            'product_categories.name as category_name', // Added category name for clarity
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        // Return the view with stock data and validated request
        return view('adminPanel.orders.reports.stock.category_wise_stok_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
        ]);
    }
    public function brand_wise_product_stock_report(Request $request)
    {
        // Validate the category_id and optional date range
        $validated = $request->validate([
            'brand_id' => 'required|string', // category_id is required
            'start_date' => 'nullable|date',  // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        $reportType = 'Unknown Brand Stock';

        if ($request->has('brand_id')) {
            if ($request->brand_id == 'all_brands') {
                $reportType = 'All Brands Stock';
            } else {
                $brand = ProductBrand::find($request->brand_id);
                $reportType = $brand ? "{$brand->name} Stock" : 'Unknown Brand Stock';
            }
        }

        // Start the query to fetch the stock data
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join with products table
            ->join('product_brands', 'product_brands.id', '=', 'products.brand_id') // Join with product_categories table
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        // Apply condition based on category_id
        if ($validated['brand_id'] !== 'all_brands') {
            // If category_id is not 'all_category', filter by the specific category_id
            $query->where('product_brands.id', $validated['brand_id']);
        }


        // Apply date filter if start_date and end_date are provided
        // if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
        //     $query->whereBetween('product_variant_stocks.created_at', [
        //         $validated['start_date'],
        //         $validated['end_date']
        //     ]);
        // }
        // // If only start_date is provided, filter records with date >= start_date
        // elseif (!empty($validated['start_date'])) {
        //     $query->where('product_variant_stocks.created_at', '>=', $validated['start_date']);
        // }
        // // If only end_date is provided, filter records with date <= end_date
        // elseif (!empty($validated['end_date'])) {
        //     $query->where('product_variant_stocks.created_at', '<=', $validated['end_date']);
        // }

        // Fetch the stock data with the necessary fields
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            'product_brands.name as brand_name',
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        // Return the view with stock data and validated request
        return view('adminPanel.orders.reports.stock.brand_wise_stok_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
            'report_type' => $reportType,
        ]);
    }
    public function supplier_wise_product_stock_report(Request $request)
    {
        // Validate the category_id and optional date range
        $validated = $request->validate([
            'party_id' => 'required|string', // category_id is required
            'start_date' => 'nullable|date',  // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch the stock data
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join with products table
            ->join('parties', 'parties.id', '=', 'products.supplier_id') // Join with product_categories table
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        // Apply condition based on category_id
        if ($validated['party_id'] !== 'all_suppliers') {
            // If category_id is not 'all_category', filter by the specific category_id
            $query->where('parties.id', $validated['party_id']);
        }


        // Apply date filter if start_date and end_date are provided
        // if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
        //     $query->whereBetween('product_variant_stocks.created_at', [
        //         $validated['start_date'],
        //         $validated['end_date']
        //     ]);
        // }
        // // If only start_date is provided, filter records with date >= start_date
        // elseif (!empty($validated['start_date'])) {
        //     $query->where('product_variant_stocks.created_at', '>=', $validated['start_date']);
        // }
        // // If only end_date is provided, filter records with date <= end_date
        // elseif (!empty($validated['end_date'])) {
        //     $query->where('product_variant_stocks.created_at', '<=', $validated['end_date']);
        // }

        // Fetch the stock data with the necessary fields
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            'parties.name as supplier_name',
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        // Return the view with stock data and validated request
        return view('adminPanel.orders.reports.stock.supplier_wise_stok_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
        ]);
    }
    public function location_wise_product_stock_report(Request $request)
    {
        // Validate the category_id and optional date range
        $validated = $request->validate([
            'location_id' => 'required|string', // category_id is required
            'start_date' => 'nullable|date',  // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch the stock data
        $query = DB::table('product_variant_stocks')
            ->join('product_variants', 'product_variants.id', '=', 'product_variant_stocks.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join with products table
            ->join('product_locations', 'product_locations.id', '=', 'product_variants.location_id') // Join with product_categories table
            ->join('measuring_units', 'measuring_units.id', '=', 'product_variants.measuring_unit_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        // Apply condition based on category_id
        if ($validated['location_id'] !== 'all_locations') {
            // If category_id is not 'all_category', filter by the specific category_id
            $query->where('product_locations.id', $validated['location_id']);
        }


        // // Apply date filter if start_date and end_date are provided
        // if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
        //     $query->whereBetween('product_variant_stocks.created_at', [
        //         $validated['start_date'],
        //         $validated['end_date']
        //     ]);
        // }
        // // If only start_date is provided, filter records with date >= start_date
        // elseif (!empty($validated['start_date'])) {
        //     $query->where('product_variant_stocks.created_at', '>=', $validated['start_date']);
        // }
        // // If only end_date is provided, filter records with date <= end_date
        // elseif (!empty($validated['end_date'])) {
        //     $query->where('product_variant_stocks.created_at', '<=', $validated['end_date']);
        // }

        // Fetch the stock data with the necessary fields
        $stock_data = $query->select(
            'product_variant_stocks.*',
            'product_variants.product_variant_name as product_name',
            'measuring_units.name as measuring_unit_name',
            'product_locations.name as location_name',
            DB::raw('product_variant_stocks.stock * product_variant_rates.cost_price as total_cost'),
            DB::raw('product_variant_stocks.stock * product_variant_rates.retail_price as total_retail')
        )->get();

        // Return the view with stock data and validated request
        return view('adminPanel.orders.reports.stock.location_wise_stok_report', [
            'stock_data' => $stock_data,
            'request' => $validated,
        ]);
    }




    public function product_sale_reports()
    {
        $allProducts = ProductVariant::with('measuringUnit')
            ->where('manage_deal_items', 0) // Exclude variants with manage_deal_items > 0
            ->where(function ($query) {
                $query->where('service_item', true)
                    ->orWhere('raw_material', true)
                    ->orWhere('finish_goods', true); // Include finish_goods
            })
            ->get();
        $allCategories = Product_category::all();
        $allBrands = ProductBrand::all();
        $allCustomers = Party::where('type', 'Customer')->get();
        $alllocations = ProductLocation::all();
        $allemployees = Employee::all();
        // dd($alllocations);
        return view(
            'adminPanel.orders.reports.sale.sale_report',
            [
                'allProducts' => $allProducts,
                'allCategories' => $allCategories,
                'allBrands' => $allBrands,
                'allCustomers' => $allCustomers,
                'alllocations' => $alllocations,
                'allemployees' => $allemployees,
            ]
        );
    }


    public function date_product_sale_report(Request $request)
    {
        // Validate request inputs
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|string',
        ]);

        // Build the query
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id');

        // Apply date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('sale_invoices.bill_date', [$request->start_date, $request->end_date]);
        }

        // Apply product filter
        if ($request->product_id && $request->product_id != 'all_products') {
            $query->where('sale_products.product_id', $request->product_id);
        }

        // Calculate total bill discount with fallback
        $total_bill_discount = DB::table('sale_invoices')
            ->whereBetween('bill_date', [$request->start_date, $request->end_date])
            ->sum('discount_actual_value') ?? 0;

        // Fetch sale data with proper null handling
        $sale_data = $query->select(
            'sale_products.product_id',
            'product_variants.product_variant_name as product_name',
            'sale_invoices.bill_date',
            DB::raw('MAX(sale_products.retail_price) as retail_price'),
            DB::raw('SUM(sale_products.sale_qty) as total_sale_qty'),
            DB::raw('COALESCE(SUM(sale_products.sale_discount_actual_value), 0) as total_discount'), // Handle null
            DB::raw('(MAX(sale_products.retail_price) * SUM(sale_products.sale_qty)) - COALESCE(SUM(sale_products.sale_discount_actual_value), 0) as total_sale_amount') // Handle null
        )
            ->groupBy('sale_products.product_id', 'sale_invoices.bill_date', 'product_variants.product_variant_name')
            ->get();

        // Debug: Uncomment to check data
        // dd($sale_data);

        return view('adminPanel.orders.reports.sale.date_wise_sale_report', [
            'sale_data' => $sale_data,
            'total_bill_discount' => $total_bill_discount,
            'request' => $request
        ]);
    }


    public function invoice_wise_profit_margin(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id')
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id');

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }

        $sale_data = $query->select(
            'sale_products.invoice_id',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'sale_products.sale_qty',
            'product_variant_rates.cost_price as costprice',
            'sale_products.retail_price',
            'sale_products.sale_discount_actual_value as product_discount_actual_value',
            DB::raw('CAST(sale_products.retail_price AS DECIMAL(10,2)) - CAST(product_variant_rates.cost_price AS DECIMAL(10,2)) as profit'),
            DB::raw('(CAST(product_variant_rates.retail_price AS DECIMAL(10,2)) - CAST(product_variant_rates.cost_price AS DECIMAL(10,2))) / CAST(product_variant_rates.retail_price AS DECIMAL(10,2)) * 100 as profit_margin')
        )->get();

        return view('adminPanel.orders.reports.sale.invoice_wise_profit_margin', [
            'sale_data' => $sale_data,
            'request' => $validated,
        ]);
    }
    public function brand_wise_product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'brand_id' => 'required|string', // Ensure brand_id is provided
            'start_date' => 'nullable|date', // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id') // Correct the column name
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('product_brands', 'product_brands.id', '=', 'products.brand_id'); // Join product_brands table

        // Apply the brand filter if a specific brand is selected (not 'all_brands')
        if ($validated['brand_id'] != 'all_brands') {
            $query->where('product_brands.id', $validated['brand_id']);
        }

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }
        // If only start_date is provided, filter records with date >= start_date
        elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        }
        // If only end_date is provided, filter records with date <= end_date
        elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data with necessary fields
        $sale_data = $query->select(
            'sale_products.*',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'product_brands.name as brand_name' // Get brand name from product_brands
        )->get();


        // Return the view with sale data and validated request
        return view('adminPanel.orders.reports.sale.brand_wise_sale_report', [
            'sale_data' => $sale_data,
            'request' => $validated,  // Pass validated parameters to the view
        ]);
    }
    public function customer_wise_product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'customer_id' => 'required|string', // Ensure brand_id is provided
            'start_date' => 'nullable|date', // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id') // Correct the column name
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('parties', 'parties.id', '=', 'sale_invoices.party_id'); // Join product_brands table

        // Apply the brand filter if a specific brand is selected (not 'all_brands')
        if ($validated['customer_id'] != 'all_customers') {
            $query->where('parties.id', $validated['customer_id']);
        }

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }
        // If only start_date is provided, filter records with date >= start_date
        elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        }
        // If only end_date is provided, filter records with date <= end_date
        elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data with necessary fields
        $sale_data = $query->select(
            'sale_products.*',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'parties.name as customer_name' // Get brand name from product_brands
        )->get();


        // Return the view with sale data and validated request
        return view('adminPanel.orders.reports.sale.customer_wise_sale_report', [
            'sale_data' => $sale_data,
            'request' => $validated,  // Pass validated parameters to the view
        ]);
    }

    // public function category_wise_product_sale_report(Request $request)
    // {
    //     // Validate the request parameters
    //     $validated = $request->validate([
    //         'category_id' => 'required|string', // Ensure brand_id is provided
    //         'start_date' => 'nullable|date', // Optional start date
    //         'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
    //     ]);

    //     // Start the query to fetch sale data
    //     $query = DB::table('sale_products')
    //         ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
    //         ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id') // Correct the column name
    //         ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
    //         ->join('product_categories', 'product_categories.id', '=', 'products.category_id'); // Join product_brands table

    //     // Apply the brand filter if a specific brand is selected (not 'all_brands')
    //     if ($validated['category_id'] != 'all_category') {
    //         $query->where('product_categories.id', $validated['category_id']);
    //     }

    //     // Apply date range filter if start_date and end_date are provided
    //     if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
    //         $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
    //     }
    //     // If only start_date is provided, filter records with date >= start_date
    //     elseif (!empty($validated['start_date'])) {
    //         $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
    //     }
    //     // If only end_date is provided, filter records with date <= end_date
    //     elseif (!empty($validated['end_date'])) {
    //         $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
    //     }

    //     // Fetch the sale data with necessary fields
    //     $sale_data = $query->select(
    //         'sale_products.*',
    //         'sale_invoices.bill_date',
    //         'sale_invoices.net_payable',
    //         'product_variants.product_variant_name as product_name',
    //         'product_categories.name as category_name' // Get brand name from product_brands
    //     )->get();

    //     // Return the view with sale data and validated request
    //     return view('adminPanel.orders.reports.sale.category_wise_sale_report', [
    //         'sale_data' => $sale_data,
    //         'request' => $validated,  // Pass validated parameters to the view
    //     ]);
    // }

    public function category_wise_product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'category_id' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id');

        // Apply the category filter if a specific category is selected (not 'all_category')
        if ($validated['category_id'] != 'all_category') {
            $query->where('product_categories.id', $validated['category_id']);
        }

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }
        // If only start_date is provided, filter records with date >= start_date
        elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        }
        // If only end_date is provided, filter records with date <= end_date
        elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data with necessary fields
        $sale_data = $query->select(
            'sale_products.*',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'product_categories.name as category_name',
            'products.id as product_id'
        )->get();

        // Group data by product
        $grouped_data = [];
        foreach ($sale_data as $sale) {
            $product_id = $sale->product_id;

            if (!isset($grouped_data[$product_id])) {
                $grouped_data[$product_id] = [
                    'product_name' => $sale->product_name,
                    'category_name' => $sale->category_name,
                    'total_qty' => 0,
                    'retail_price' => $sale->retail_price,
                    'total_amount' => 0,
                    'total_discount' => 0
                ];
            }

            // Convert sale_qty to float and add to total
            $grouped_data[$product_id]['total_qty'] += floatval($sale->sale_qty);
            $grouped_data[$product_id]['total_amount'] += floatval($sale->sale_amount);

            // Calculate discount if available
            if ($sale->sale_discount_actual_value) {
                $grouped_data[$product_id]['total_discount'] += floatval($sale->sale_discount_actual_value);
            }
        }

        // Return the view with grouped sale data and validated request
        return view('adminPanel.orders.reports.sale.category_wise_sale_report', [
            'sale_data' => $grouped_data,
            'request' => $validated,
        ]);
    }

    public function location_wise_product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'location_id' => 'required|string', // Ensure brand_id is provided
            'start_date' => 'nullable|date', // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id') // Correct the column name
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('product_locations', 'product_locations.id', '=', 'product_variants.location_id'); // Join product_brands table

        // Apply the brand filter if a specific brand is selected (not 'all_brands')
        if ($validated['location_id'] != 'all_location') {
            $query->where('product_locations.id', $validated['location_id']);
        }

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }
        // If only start_date is provided, filter records with date >= start_date
        elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        }
        // If only end_date is provided, filter records with date <= end_date
        elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data with necessary fields
        $sale_data = $query->select(
            'sale_products.*',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'product_locations.name as location_name' // Get brand name from product_brands
        )->get();


        // Return the view with sale data and validated request
        return view('adminPanel.orders.reports.sale.location_wise_sale_report', [
            'sale_data' => $sale_data,
            'request' => $validated,  // Pass validated parameters to the view
        ]);
    }
    public function date_wise_product_sale_summary(Request $request)
    {
        // Define your query
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id')  // Fixed typo 'product_id' to 'product_id'
            ->select(
                'sale_invoices.bill_date',
                'product_variants.product_variant_name as product_name',
                DB::raw('SUM(sale_products.sale_qty) as sale_qty'),
                'sale_products.retail_price',
                DB::raw('SUM(sale_products.retail_price * sale_products.sale_qty) as net_payable'),
                DB::raw('SUM(sale_products.sale_discount_actual_value) as product_discount_actual_value')
            )
            ->groupBy('sale_invoices.bill_date', 'product_variants.product_variant_name', 'sale_products.retail_price')
            ->orderBy('sale_invoices.bill_date');

        // Apply date range filter if provided
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('sale_invoices.bill_date', [$request->start_date, $request->end_date]);
        }

        // Fetch the sale data
        $sale_data = $query->get();
        // Pass the sale data to the view
        return view('adminPanel.orders.reports.sale.date_wise_product_sale_summary', [
            'sale_data' => $sale_data,
            'request' => $request
        ]);
    }
    public function employee_wise_product_sale_report(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'employee_id' => 'required|string', // Ensure brand_id is provided
            'start_date' => 'nullable|date', // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id') // Correct the column name
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('employees', 'employees.id', '=', 'sale_invoices.employee_id'); // Join product_brands table

        // Apply the brand filter if a specific brand is selected (not 'all_brands')
        if ($validated['employee_id'] != 'all_employee') {
            $query->where('employees.id', $validated['employee_id']);
        }

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        }
        // If only start_date is provided, filter records with date >= start_date
        elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        }
        // If only end_date is provided, filter records with date <= end_date
        elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data with necessary fields
        $sale_data = $query->select(
            'sale_products.*',
            'sale_invoices.bill_date',
            'sale_invoices.net_payable',
            'product_variants.product_variant_name as product_name',
            'employees.name as employee_name' // Get brand name from product_brands
        )->get();


        // Return the view with sale data and validated request
        return view('adminPanel.orders.reports.sale.employee_wise_sale_report', [
            'sale_data' => $sale_data,
            'request' => $validated,  // Pass validated parameters to the view
        ]);
    }
    public function date_wise_profit_margin(Request $request)
    {
        // Validate the request parameters
        $validated = $request->validate([
            'start_date' => 'nullable|date', // Optional start date
            'end_date' => 'nullable|date|after_or_equal:start_date', // Optional end date, must be greater than or equal to start_date
        ]);

        // Start the query to fetch sale data
        $query = DB::table('sale_products')
            ->join('sale_invoices', 'sale_invoices.id', '=', 'sale_products.invoice_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_products.product_id')  // Corrected column name
            ->join('products', 'products.id', '=', 'product_variants.product_id')  // Join products table to get the brand_id
            ->join('employees', 'employees.id', '=', 'sale_invoices.employee_id')  // Join employees to get employee info
            ->join('product_variant_rates', 'product_variant_rates.product_variant_id', '=', 'product_variants.id') // Join for cost price
            ->leftJoin('expenses', 'expenses.date', '=', 'sale_invoices.bill_date'); // Join expenses table (assuming a date field)

        // Apply date range filter if start_date and end_date are provided
        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('sale_invoices.bill_date', [$validated['start_date'], $validated['end_date']]);
        } elseif (!empty($validated['start_date'])) {
            $query->where('sale_invoices.bill_date', '>=', $validated['start_date']);
        } elseif (!empty($validated['end_date'])) {
            $query->where('sale_invoices.bill_date', '<=', $validated['end_date']);
        }

        // Fetch the sale data and calculate profit, cost, and expenses
        $sale_data = $query->select(
            'sale_invoices.bill_date',
            'product_variants.product_variant_name as product_name',
            DB::raw('SUM(sale_products.sale_qty) as total_quantity'), // Total quantity sold
            DB::raw('SUM(sale_products.retail_price * sale_products.sale_qty) as total_sale'), // Total sale amount
            DB::raw('SUM(product_variant_rates.cost_price * sale_products.sale_qty) as total_cost'), // Total cost
            DB::raw('SUM(sale_products.retail_price * sale_products.sale_qty) - SUM(product_variant_rates.cost_price * sale_products.sale_qty) as gross_profit'), // Gross Profit
            DB::raw('SUM(expenses.total_amount) as total_expenses'), // Total expenses for the date (assuming `amount` column)
            DB::raw('
        CASE
            WHEN SUM(sale_products.retail_price * sale_products.sale_qty) - SUM(product_variant_rates.cost_price * sale_products.sale_qty) < 0
            THEN SUM(sale_products.retail_price * sale_products.sale_qty) - SUM(product_variant_rates.cost_price * sale_products.sale_qty)
            ELSE SUM(sale_products.retail_price * sale_products.sale_qty) - SUM(product_variant_rates.cost_price * sale_products.sale_qty) - SUM(expenses.total_amount)
        END as net_profit
    ') // Correct net profit calculation to show loss when gross profit is negative
        )
            ->groupBy('sale_invoices.bill_date', 'product_variants.product_variant_name') // Group by date and product variant
            ->orderBy('sale_invoices.bill_date', 'ASC')
            ->get();

        // Return the view with sale data and validated request
        return view('adminPanel.orders.reports.sale.date_wise_profit_margin_report', [
            'sale_data' => $sale_data,
            'request' => $validated,  // Pass validated parameters to the view
        ]);
    }















    public function product_purchase_reports()
    {
        $allProducts = ProductVariant::with('measuringUnit')->get();
        $allCategories = Product_category::all();
        $allBrands = ProductBrand::all();
        $allSuppliers = Party::where('type', 'Supplier')->get();
        $alllocations = ProductLocation::all();
        // dd($alllocations);
        return view(
            'adminPanel.orders.reports.purchase.purchase_report',
            [
                'allProducts' => $allProducts,
                'allCategories' => $allCategories,
                'allBrands' => $allBrands,
                'allSuppliers' => $allSuppliers,
                'alllocations' => $alllocations,
            ]
        );
    }


    public function product_wise_purchase_report(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'product_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.product_variant_id')
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id');
        // ->join('parties', 'parties.id', '=', 'purchases.supplier_id');

        // dd($query)

        if ($validated['product_id'] != 'all_products') {
            $query->where('purchase_details.product_variant_id', $validated['product_id']);
        }
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            // 'parties.name as supplier_name',
            'product_variants.product_variant_name as product_name'
        )->get();

        // dd($purchase_data);
        return view('adminPanel.orders.reports.product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated
        ]);
    }
    public function date_wise_purchase_report(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'start_date' => 'required|date',   // start_date is required
            'end_date' => 'required|date',     // end_date is required
        ]);

        // Build the query
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id') // Correct the join
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id'); // Join with product variants

        // Apply date filter based on the received date in the purchases table
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        // Fetch the data from the database
        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            // 'parties.name as supplier_name', // Uncomment if you need supplier name
            'product_variants.product_variant_name as product_name'
        )->get();

        // Return the data to the view
        return view('adminPanel.orders.reports.purchase.date_wise_product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated // Send validated data to the view
        ]);
    }
    public function brand_wise_purchase_report(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'brand_id' => 'required|string',
            'start_date' => 'required|date',   // start_date is required
            'end_date' => 'required|date',     // end_date is required
        ]);

        // Build the query
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id') // Correct the join
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('product_brands', 'product_brands.id', '=', 'products.brand_id');

        if ($validated['brand_id'] != 'all_brand') {
            $query->where('product_brands.id', $validated['brand_id']);
        }
        // Apply date filter based on the received date in the purchases table
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        // Fetch the data from the database
        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            'product_brands.name as brand_name', // Uncomment if you need supplier name
            'product_variants.product_variant_name as product_name'
        )->get();

        // Return the data to the view
        return view('adminPanel.orders.reports.purchase.brand_wise_product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated // Send validated data to the view
        ]);
    }
    public function supplier_wise_purchase_report(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'supplier_id' => 'required|string',
            'start_date' => 'required|date',   // start_date is required
            'end_date' => 'required|date',     // end_date is required
        ]);

        // Build the query
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id') // Correct the join
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('parties', 'parties.id', '=', 'purchases.supplier_id');

        if ($validated['supplier_id'] != 'all_supplier') {
            $query->where('parties.id', $validated['supplier_id']);
        }
        // Apply date filter based on the received date in the purchases table
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        // Fetch the data from the database
        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            'parties.name as supplier_name', // Uncomment if you need supplier name
            'product_variants.product_variant_name as product_name'
        )->get();

        // Return the data to the view
        return view('adminPanel.orders.reports.purchase.supplier_wise_product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated // Send validated data to the view
        ]);
    }
    public function category_wise_purchase_report(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'category_id' => 'required|string',
            'start_date' => 'required|date',   // start_date is required
            'end_date' => 'required|date',     // end_date is required
        ]);

        // Build the query
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id') // Correct the join
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id');

        if ($validated['category_id'] != 'all_category') {
            $query->where('product_categories.id', $validated['category_id']);
        }
        // Apply date filter based on the received date in the purchases table
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        // Fetch the data from the database
        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            'product_categories.name as category_name', // Uncomment if you need supplier name
            'product_variants.product_variant_name as product_name'
        )->get();

        // Return the data to the view
        return view('adminPanel.orders.reports.purchase.category_wise_product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated // Send validated data to the view
        ]);
    }
    public function location_wise_purchase_report(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'location_id' => 'required|string',
            'start_date' => 'required|date',   // start_date is required
            'end_date' => 'required|date',     // end_date is required
        ]);

        // Build the query
        $query = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id') // Correct the join
            ->join('product_variants', 'product_variants.id', '=', 'purchase_details.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id') // Join products table to get the brand_id
            ->join('product_locations', 'product_locations.id', '=', 'product_variants.location_id');

        if ($validated['location_id'] != 'all_location') {
            $query->where('product_locations.id', $validated['location_id']);
        }
        // Apply date filter based on the received date in the purchases table
        if ($validated['start_date'] && $validated['end_date']) {
            $query->whereBetween('purchases.received_date', [$validated['start_date'], $validated['end_date']]);
        }

        // Fetch the data from the database
        $purchase_data = $query->select(
            'purchase_details.*',
            'purchases.received_date',
            'purchases.total_bill',
            'purchases.net_payable',
            'product_locations.name as location_name', // Uncomment if you need supplier name
            'product_variants.product_variant_name as product_name'
        )->get();

        // Return the data to the view
        return view('adminPanel.orders.reports.purchase.location_wise_product_purchase_report', [
            'purchase_data' => $purchase_data,
            'request' => $validated // Send validated data to the view
        ]);
    }


























    public function productshoww()
    {

        return view('adminPanel.practice');
    }

    public function product($id)
    {
        // Retrieve the product data from the database using the product ID from the URL
        $product = Product::find($id);
        // dd($product);

        if ($product) {
            // Return the product data as a JSON response
            return response()->json([
                'status' => 'success',
                'product_name' => $product->product_name,
                'product_price' => $product->price,
                'product_description' => $product->description,
            ]);
        } else {
            // Return an error response if the product is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ]);
        }
    }
}
