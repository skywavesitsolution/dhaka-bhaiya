<?php

namespace App\Http\Controllers\StockManagment;

use App\Http\Controllers\Controller;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\StockManagment\StockLedger;
use App\Models\StockManagment\StockTransfer;
use App\Models\StockManagment\StockTransferDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StockTransferController extends Controller
{
    public function index()
    {

        $stockTransfers = StockTransfer::with('fromLocation')->paginate(10);

        return view(
            'adminPanel.stockManagment.stockTransferList',
            [
                'stockTransfers' => $stockTransfers
            ]
        );
    }

    public function stockTransfer()
    {
        $locations = ProductLocation::all();

        return view(
            'adminPanel.stockManagment.transferStock',
            [
                'locations' => $locations,
            ]
        );
    }

    public function getProductVariantsByLocation(Request $request)
    {
        $locationId = $request->input('location_id');

        $productVariants = ProductVariantLocation::where('location_id', $locationId)
            ->with('productVariant.stock')
            ->get();

        return response()->json([
            'product_variants' => $productVariants
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_transfer_date' => 'required|date',
            'stock_transfer_time' => 'required|date_format:H:i',
            'from_location_id' => 'required|exists:product_locations,id',
            'transfer_stock_total_qty' => 'required|numeric|min:0',
            'product_variant_id' => 'required|array',
            'product_variant_id.*' => 'required|exists:product_variants,id',
            'location_id' => 'required|array',
            'location_id.*' => 'required|exists:product_locations,id',
            'to_location' => 'required|array',
            'to_location.*' => 'required|exists:product_locations,id',
            'current_stock' => 'required|array',
            'current_stock.*' => 'required|numeric|min:0',
            'transfer_stock' => 'required|array',
            'transfer_stock.*' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transactionId = Str::uuid();
                $stockTransfer = StockTransfer::create([
                    'transaction_id' => $transactionId,
                    'date' => $request->stock_transfer_date,
                    'time' => $request->stock_transfer_time,
                    'from_location_id' => $request->from_location_id,
                    'qty' => $request->transfer_stock_total_qty,
                    'user_id' => Auth::user()->id
                ]);

                $sameLocation = false;
                $allZero = true;

                foreach ($request->product_variant_id as $index => $productVariantId) {

                    if ($request->transfer_stock[$index] > 0) {
                        $allZero = false;


                        if ($request->location_id[$index] == $request->to_location[$index]) {
                            $sameLocation = true;
                            continue;
                        }

                        $productVariantLocation = ProductVariantLocation::where('product_variant_id', $productVariantId)
                            ->where('location_id', $request->location_id[$index])
                            ->first();

                        if (!$productVariantLocation) {
                            throw new \Exception("Product variant location not found for product_variant_id: $productVariantId at location_id: " . $request->location_id[$index]);
                        }

                        if ($productVariantLocation->stock_qty < $request->current_stock[$index]) {
                            throw new \Exception("Not enough stock at location_id: " . $request->location_id[$index]);
                        }

                        $productVariantLocation->stock_qty -= $request->transfer_stock[$index];
                        $productVariantLocation->save();

                        $toLocationVariant = ProductVariantLocation::where('product_variant_id', $productVariantId)
                            ->where('location_id', $request->to_location[$index])
                            ->first();

                        if (!$toLocationVariant) {
                            $toLocationVariant = new ProductVariantLocation([
                                'product_variant_id' => $productVariantId,
                                'location_id' => $request->to_location[$index],
                                'stock_qty' => 0,
                                'user_id' => Auth::user()->id,
                            ]);
                            $toLocationVariant->save();
                        }

                        $toLocationVariant->stock_qty += $request->transfer_stock[$index];
                        $toLocationVariant->save();

                        StockTransferDetail::create([
                            'transaction_id' => $transactionId,
                            'stock_transfer_id' => $stockTransfer->id,
                            'product_variant_id' => $productVariantId,
                            'to_location_id' => $request->to_location[$index],
                            'stock_at_time_of_transfer' => $request->current_stock[$index],
                            'qty' => $request->transfer_stock[$index],
                        ]);

                        StockLedger::create([
                            'transaction_id' => $transactionId,
                            'product_variant_id' => $productVariantId,
                            'location_id' => $request->location_id[$index],
                            'qty' => -$request->transfer_stock[$index], // Deduct from source location
                            'stock_after_transaction' => $productVariantLocation->stock_qty,
                            'transfer_type' => 'out',
                            'user_id' => Auth::user()->id,
                        ]);

                        StockLedger::create([
                            'transaction_id' => $transactionId,
                            'product_variant_id' => $productVariantId,
                            'location_id' => $request->to_location[$index],
                            'qty' => $request->transfer_stock[$index], // Add to destination location
                            'stock_after_transaction' => $toLocationVariant->stock_qty,
                            'transfer_type' => 'in',
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
                if ($allZero) {
                    throw new \Exception("All transfer stock quantities are zero, no entries were made.");
                }
                if ($sameLocation) {
                    throw new \Exception("Cannot transfer stock to the same location.");
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Stock transferred successfully!'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('General error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function stockTransferDetail($id)
    {
        $stockTransferDetails = StockTransferDetail::where('stock_transfer_id', $id)->with('stockTransfer', 'productVariant', 'toLocation')->paginate(10);

        return view('adminPanel.stockManagment.stockTransferDetailList', compact('stockTransferDetails'));
    }

    public function printTransferStockDetail($id)
    {
        $stockTransfer = StockTransfer::where('id', $id)->with('fromLocation')->first();

        if (!$stockTransfer) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $fromLocation = $stockTransfer->fromLocation->name ?? '--';
        $stockTransfersDate = \Carbon\Carbon::parse($stockTransfer->date)->format('d M Y');
        $stockTransfersTime = \Carbon\Carbon::parse($stockTransfer->time)->format('h:i A');
        $totalQty = $stockTransfer->qty;

        $stockTransferDetails = StockTransferDetail::where('stock_transfer_id', $id)->with('stockTransfer', 'productVariant', 'toLocation')->get();

        return view('adminPanel.stockManagment.report.printStockTransferDetail', compact('fromLocation', 'stockTransfersDate', 'stockTransfersTime', 'totalQty', 'stockTransferDetails'));
    }

    public function softDestroy($id)
    {
        $stockTransfer = StockTransfer::find($id);

        if (!$stockTransfer) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $stockTransfer->delete();

        return response()->json(['message' => 'Stock Transfer and its related details temporarely deleted successfully.'], 200);
    }

    public function trashed()
    {

        $stockTransfers = StockTransfer::onlyTrashed()->with('fromLocation')->paginate(10);

        return view(
            'adminPanel.stockManagment.trashed.trashedStockTransferList',
            [
                'stockTransfers' => $stockTransfers
            ]
        );
    }
}
