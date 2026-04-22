<?php

namespace App\Http\Controllers\Product\ProductImport;

use App\Actions\Product\GenerateProductVariantSKU;
use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\MeasuringUnit\MeasuringUnit;
use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\Product\Variant\ProductVariantRate;
use App\Models\Product\Variant\ProductVariantRateLedger;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\StockManagment\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ProductImportController extends Controller
{


    public function index()
    {
        $productTable = 'products';
        $variantTable = 'product_variants';
        $rateTable = 'product_variant_rates';
        $stockTable = 'product_variant_stocks';

        $productColumns = Schema::getColumnListing($productTable);
        $variantColumns = Schema::getColumnListing($variantTable);
        $rateColumns = Schema::getColumnListing($rateTable);
        $stockColumns = Schema::getColumnListing($stockTable);


        $allowedProductColumns = array_keys($this->getProductColumnDescriptions());
        $allowedVariantColumns = array_keys($this->getVariantColumnDescriptions());
        $allowedRateColumns = array_keys($this->getRateColumnDescriptions());
        $allowedStockColumns = array_keys($this->getStockColumnDescriptions());



        $filteredProductColumns = collect($productColumns)->filter(function ($column) use ($allowedProductColumns) {
            return in_array($column, $allowedProductColumns);
        });

        $filteredVariantColumns = collect($variantColumns)->filter(function ($column) use ($allowedVariantColumns) {
            return in_array($column, $allowedVariantColumns);
        });

        $filteredRateColumns = collect($rateColumns)->filter(function ($column) use ($allowedRateColumns) {
            return in_array($column, $allowedRateColumns);
        });

        $filteredStockColumns = collect($stockColumns)->filter(function ($column) use ($allowedStockColumns) {
            return in_array($column, $allowedStockColumns);
        });

        $productColumnsWithDetails = $filteredProductColumns->map(function ($column) {
            return [
                'name' => $this->formatColumnName($column),
                'description' => $this->getProductColumnDescription($column),
                'status' => $this->getColumnStatus($column),
            ];
        });

        $variantColumnsWithDetails = $filteredVariantColumns->map(function ($column) {
            return [
                'name' => $this->formatVariantColumnName($column),
                'description' => $this->getVariantColumnDescription($column),
                'status' => $this->getColumnStatus($column),
            ];
        });

        $rateColumnsWithDetails = $filteredRateColumns->map(function ($column) {
            return [
                'name' => $this->formatRateColumnName($column),
                'description' => $this->getRateColumnDescription($column),
                'status' => $this->getColumnStatus($column),
            ];
        });

        $stockColumnsWithDetails = $filteredStockColumns->map(function ($column) {
            return [
                'name' => $this->formatStockColumnName($column),
                'description' => $this->getStockColumnDescription($column),
                'status' => $this->getColumnStatus($column),
            ];
        });

        $allVariantColumnsWithDetails = $variantColumnsWithDetails
            ->concat($rateColumnsWithDetails)
            ->concat($stockColumnsWithDetails);

        return view('adminPanel.product.product.productImport.productImport', compact(
            'productColumnsWithDetails',
            'allVariantColumnsWithDetails',
        ));
    }

    protected function getProductColumnDescription($column)
    {
        return $this->getProductColumnDescriptions()[$column] ?? 'No description available.';
    }

    protected function getProductColumnDescriptions()
    {
        return [
            'product_name' => 'The name of the product in English.',
            'product_urdu_name' => 'The name of the product in Urdu (optional).',
            'code' => 'A unique product code.',
            'description' => 'Details about the product.',
            'category_id' => 'References the category of the product.',
            'brand_id' => 'References the brand of the product.',
            'supplier_id' => 'References the supplier of the product.',
            'is_manage_variants' => 'If you want to manage variant enter 1, or if u dont want to store manage variant then enter the variant data as  well ',
        ];
    }

    protected function getVariantColumnDescription($column)
    {
        return $this->getVariantColumnDescriptions()[$column] ?? 'No description available.';
    }

    protected function getVariantColumnDescriptions()
    {
        return [
            'code' => 'A unique code for the product variant.',
            'location_id' => 'Location where the product variant is stored.',
            'measuring_unit_id' => 'Measuring unit for the product variant (e.g., kg, piece, etc.). Write "No" for numbres. write "kg" for Kilogram.',
            'description' => 'A detailed description of the product variant, including features and specifications.',
        ];
    }

    protected function getColumnStatus($column)
    {
        $optionalColumns = ['description'];
        return in_array($column, $optionalColumns) ? 'Optional' : 'Required';
    }

    protected function formatColumnName($column)
    {
        $customNames = [
            'category_id' => 'Category Name',
            'brand_id' => 'Brand Name',
            'supplier_id' => 'Supplier Name',
            'is_manage_variant' => 'Manage Variant',
        ];

        return $customNames[$column] ?? ucwords(str_replace('_', ' ', $column));
    }

    protected function formatVariantColumnName($column)
    {
        $customVariantNames = [
            'code' => 'Variant Code',
            'location_id' => 'Variant Location',
            'measuring_unit_id' => 'Variant Measuring Unit',
            'description' => 'Variant Description',
        ];

        return $customVariantNames[$column] ?? ucwords(str_replace('_', ' ', $column));
    }

    protected function getRateColumnDescription($column)
    {
        return $this->getRateColumnDescriptions()[$column] ?? 'No description available.';
    }

    protected function getRateColumnDescriptions()
    {
        return [
            'cost_price' => 'The cost price of the product variant.',
            'retail_price' => 'The retail price of the product variant.',
            'wholesale_price' => 'The wholesale price of the product variant.',
        ];
    }

    protected function getStockColumnDescription($column)
    {
        return $this->getStockColumnDescriptions()[$column] ?? 'No description available.';
    }

    protected function getStockColumnDescriptions()
    {
        return [
            'low_stock' => 'The low stock threshold for the product variant.',
            'inner_pack' => 'The quantity in one inner pack of the product variant.',
            'loose_pack' => 'The quantity in one loose pack of the product variant.',
        ];
    }

    protected function formatRateColumnName($column)
    {
        $customRateNames = [
            'cost_price' => 'Variant Cost Price',
            'retail_price' => 'Variant Retail Price',
            'wholesale_price' => 'Variant Wholesale Price',
        ];

        return $customRateNames[$column] ?? ucwords(str_replace('_', ' ', $column));
    }

    protected function formatStockColumnName($column)
    {
        $customStockNames = [
            'low_stock' => 'Variant Low Stock',
            'inner_pack' => 'Variant Inner Pack',
            'loose_pack' => 'Variant Loose Pack',
        ];

        return $customStockNames[$column] ?? ucwords(str_replace('_', ' ', $column));
    }

    public function downloadExampleFormat()
    {
        $headers = [
            'Code',
            'Product Name',
            'Product Urdu Name',
            'Category Name',
            'Brand Name',
            'Supplier Name',
            'Description',
            'Manage Variant',

            'Variant Code',
            'Variant Location',
            'Variant Measuring Unit',

            'Variant Cost Price',
            'Variant Retail Price',
            'Variant Wholesale Price',

            'Variant Low Stock',
            'Variant Inner Pack',
            'Variant Loose Pack',
        ];

        $fileName = 'product_import_example.csv';

        $output = fopen('php://temp', 'w');
        fputcsv($output, $headers);

        for ($i = 0; $i < 10; $i++) {
            fputcsv($output, array_fill(0, count($headers), ''));
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }


    public function importProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file format or size!, Select only CSV file',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('product_file');
        $filePath = $file->getRealPath();
        $data = array_map('str_getcsv', file($filePath));
        try {
            DB::transaction(function () use ($request, $data) {
                foreach ($data as $key => $row) {
                    if ($key == 0) continue;


                    $productData = [
                        'code' => $row[0],
                        'product_name' => $row[1],
                        'product_urdu_name' => $row[2],
                        'description' => $row[6],
                        'is_manage_variants' => (int) $row[7]
                    ];

                    $category = ProductCategory::firstOrCreate(
                        ['name' => strtolower(trim($row[3]))],
                        [
                            'name' => ucfirst(trim($row[3])),
                            'user_id' => Auth::user()->id
                        ]
                    );

                    $brand = ProductBrand::firstOrCreate(
                        ['name' => strtolower(trim($row[4]))],
                        [
                            'name' => ucfirst(trim($row[4])),
                            'user_id' => Auth::user()->id
                        ]
                    );

                    $supplier = Party::firstOrCreate(
                        ['name' => strtolower(trim($row[5]))],
                        [
                            'name' => ucfirst(trim($row[5])),

                            'user_id' => Auth::user()->id,
                            'type' => 'Supplier',
                            'opening_balance' => 0,
                        ]
                    );

                    $product = Product::create([
                        'code' => $productData['code'],
                        'product_name' => $productData['product_name'],
                        'product_urdu_name' => $productData['product_urdu_name'],
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'supplier_id' => $supplier->id,
                        'description' => $productData['description'],
                        'is_manage_variants' => $productData['is_manage_variants'],
                        'user_id' => Auth::user()->id,
                    ]);

                    $skuGenerator = new GenerateProductVariantSKU();
                    $sku = $skuGenerator->execute($category, $brand, $request->size, $request->color, $productData['code']);

                    if ($productData['is_manage_variants'] === 0) {
                        $location = ProductLocation::firstOrCreate(
                            ['name' => strtolower(trim($row[9]))],
                            [
                                'name' => ucfirst(trim($row[9])),
                                'user_id' => Auth::user()->id
                            ]
                        );

                        $measuringUnit = MeasuringUnit::firstOrCreate(
                            ['name' => strtolower(trim($row[10]))],
                            [
                                'name' => ucfirst(trim($row[10])),
                                'user_id' => Auth::user()->id
                            ]
                        );

                        $productVariant = ProductVariant::create([
                            'product_variant_name' => $productData['product_name'],
                            'product_variant_urdu_name' => $productData['product_urdu_name'],
                            'code' => $row[8],
                            'SKU' => $sku,
                            'product_id' => $product->id,
                            'measuring_unit_id' => $measuringUnit->id,
                            'description' => $productData['description'],
                            'min_order_qty' => '1',
                            'user_id' => Auth::user()->id,
                        ]);

                        ProductVariantRate::create([
                            'product_variant_id' => $productVariant->id,
                            'cost_price' => $row[11],
                            'retail_price' => $row[12],
                            'wholesale_price' => $row[13],
                            'user_id' => Auth::user()->id,
                        ]);

                        ProductVariantRateLedger::create([
                            'product_variant_id' => $productVariant->id,
                            'old_retail_price' => null,
                            'new_retail_price' => $row[12],
                            'change_date' => now(),
                            'user_id' => Auth::user()->id,
                        ]);

                        ProductVariantStock::create([
                            'product_variant_id' => $productVariant->id,
                            'low_stock' => $row[14],
                            'inner_pack' => $row[15],
                            'loose_pack' => $row[16],
                            'user_id' => Auth::user()->id,
                        ]);

                        ProductVariantLocation::create([
                            'product_variant_id' => $productVariant->id,
                            'location_id' => $location->id,
                            'stock_qty' => '0',
                            'user_id' => Auth::user()->id,
                        ]);

                        StockLedger::create([
                            'product_variant_id' => $productVariant->id,
                            'location_id' => $location->id,
                            'stock_after_transaction' => '0',
                            'qty' => '0',
                            'transfer_type' => 'import',
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Products imported successfully!',
            ]);
        } catch (\Exception $e) {

            Log::error('Import Products Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
