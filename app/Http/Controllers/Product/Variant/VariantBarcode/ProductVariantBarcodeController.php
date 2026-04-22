<?php

namespace App\Http\Controllers\Product\Variant\VariantBarcode;

use App\Http\Controllers\Controller;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductVariantBarcodeController extends Controller
{
    public function index()
    {
        $productVariants = ProductVariant::with('product', 'size', 'productVariantLocation.ProductLocation', 'measuringUnit', 'rates', 'stock')->paginate(10);

        return view('adminPanel.product.product.productVariant.VariantBarcode.printProductVariantBarcode', compact(
            'productVariants'
        ));
    }

    public function searchProductVariants(Request $request)
    {
        $query = $request->input('search');

        $variants = ProductVariant::with('rates')
            ->where('code', 'like', "%{$query}%")
            ->orWhere('product_variant_name', 'like', "%{$query}%")
            ->get(['id', 'product_variant_name', 'code']);

        return response()->json($variants);
    }

    public function generateBarcodes(Request $request)
    {
        $products = $request->input('products');
        $generator = new BarcodeGeneratorPNG();
        $barcodes = [];

        foreach ($products as $product) {
            $barcode = $generator->getBarcode($product['product_code'], $generator::TYPE_CODE_128, 2, 25);
            $barcodes[] = [
                'company_name' => "RMS",
                'product_name' => $product['product_name'],
                'product_code' => $product['product_code'],
                'product_retailPrice' => $product['product_retailPrice'],
                'print_qty' => $product['quantity'],
                'barcode' => base64_encode($barcode)
            ];
        }

        return response()->json([
            'success' => true,
            'barcodes' => $barcodes
        ]);
    }
}
