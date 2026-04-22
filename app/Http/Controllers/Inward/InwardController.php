<?php

namespace App\Http\Controllers\Inward;

use App\Enums\InwardStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Inward\Inward;
use App\Models\Inward\InwardDetail;
use App\Models\Party;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InwardController extends Controller
{
    public function index()
    {
        $inwards = Inward::with('supplier')->paginate(10);
        $statuses = InwardStatusEnum::cases();

        return view(
            'adminPanel.inward.inwardList',
            [
                'inwards' => $inwards,
                'statuses' => $statuses
            ]
        );
    }

    public function create()
    {
        $allproductVariants = ProductVariant::all();
        $allSuppliers = Party::where('type', 'Supplier')->get();



        return view(
            'adminPanel.inward.createInward',
            [
                'allproductVariants' => $allproductVariants,
                'allSuppliers' => $allSuppliers,
            ]
        );
    }

    public function getProductVariants(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([], 200);
        }

        $variants = ProductVariant::where('product_variant_name', 'LIKE', "%{$query}%")
            ->select('product_variant_name', 'code')
            ->get();


        return response()->json($variants);
    }

    public function store(Request $request)
    {
        $request->validate([
            'inward_date' => 'required|date',
            'inward_time' => 'required|date_format:H:i',
            'supplier_id' => 'required|exists:parties,id',
            'inward_total_qty' => 'required|numeric|min:1',
            'product_variant_name' => 'required|array',
            'product_variant_name.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $quantities = array_map(function ($qty) {
                    return (int) ltrim($qty, '0');
                }, $request->qty);

                $totalQtyFromArray = array_sum($quantities);

                if ($totalQtyFromArray !== (int) $request->inward_total_qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The sum of quantities does not match the inward total quantity.'
                    ], 400);
                }

                $normalizedProductNames = array_map('strtolower', $request->product_variant_name);

                $products = [];
                foreach ($normalizedProductNames as $index => $productName) {
                    $qty = $quantities[$index];
                    if (isset($products[$productName])) {
                        $products[$productName] += $qty;
                    } else {
                        $products[$productName] = $qty;
                    }
                }

                $inward = Inward::create([
                    'date' => $request->inward_date,
                    'time' => $request->inward_time,
                    'supplier_id' => $request->supplier_id,
                    'qty' => $request->inward_total_qty,
                ]);

                foreach ($products as $productName => $totalQty) {
                    InwardDetail::create([
                        'inward_id' => $inward->id,
                        'product_name' => ucfirst($productName),
                        'qty' => $totalQty,
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Inward created successfully!'
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

    public function inwardDetail($id)
    {
        $inwardDetails = InwardDetail::where('inward_id', $id)->paginate(10);

        return view('adminPanel.inward.inwardDetailList', compact('inwardDetails'));
    }
    public function updateInwardStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:inwards,id',
            'status' => 'required|in:' . implode(',', array_column(InwardStatusEnum::cases(), 'value')),
        ]);

        try {
            DB::transaction(function () use ($request) {
                $inward = Inward::findOrFail($request->id);
                $inward->inward_status = $request->status;
                $inward->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Inward status updated successfully!'
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

    public function softDestroy($id)
    {
        $inward = Inward::find($id);

        if (!$inward) {
            return response()->json(['message' => 'Inward not found'], 404);
        }

        $inward->delete();

        return response()->json(['message' => 'Inward and its related details temporarely deleted successfully.'], 200);
    }

    public function trashed()
    {
        $inwards = Inward::onlyTrashed()
            ->with('supplier')
            ->paginate(10);
        $statuses = InwardStatusEnum::cases();


        return view(
            'adminPanel.inward.trashed.trashedInwardList',
            [
                'inwards' => $inwards,
                'statuses' => $statuses
            ]
        );
    }

    public function inwardTrashedDetail($id)
    {
        $inwardDetails = InwardDetail::onlyTrashed()->where('inward_id', $id)->paginate(10);

        return view('adminPanel.inward.trashed.trashedInwardDetailList', compact('inwardDetails'));
    }

    public function restoreInward($id)
    {
        $inward = Inward::onlyTrashed()->find($id);

        if (!$inward) {
            return response()->json(['message' => 'Inward not found in trash'], 404);
        }

        $inward->restore();

        return response()->json(['message' => 'Inward and its related detail restored successfully'], 200);
    }

    public function printInwardDetail($id)
    {
        $inward = Inward::where('id', $id)->with('supplier')->first();

        if (!$inward) {
            return redirect()->back()->with('error', 'Inward not found.');
        }

        $supplierName = $inward->supplier->name ?? '--';
        $inwardStatus = ucfirst($inward->inward_status->value) ?? '--';
        $inwardDate = \Carbon\Carbon::parse($inward->date)->format('d M Y');
        $inwardTime = \Carbon\Carbon::parse($inward->time)->format('h:i A');
        $totalQty = $inward->qty;

        $inwardDetails = InwardDetail::where('inward_id', $id)->get();

        return view('adminPanel.inward.report.printInwardDetail', compact('inwardDetails', 'supplierName', 'inwardStatus', 'inwardDate', 'inwardTime', 'totalQty'));
    }
}
