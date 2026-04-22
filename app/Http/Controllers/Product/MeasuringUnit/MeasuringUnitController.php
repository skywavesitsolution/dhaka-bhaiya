<?php

namespace App\Http\Controllers\Product\MeasuringUnit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\MeasuringUnit\MeasuringUnit;

class MeasuringUnitController extends Controller
{

    public function index()
    {
        $measuringUnits = MeasuringUnit::paginate(10);
        return view(
            'adminPanel.product.measuringUnit.measuringUnitList',
            [
                'measuringUnits' => $measuringUnits,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'symbol' => ['required', 'string'],
            'qty' => ['required', 'string']
        ]);

        $result = MeasuringUnit::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'quantity' => $request->qty,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Measuring Unit added successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong try again']);
    }

    public function show(string $id)
    {
        $measuringUnit = MeasuringUnit::find($id);

        return response()->json(['data' => $measuringUnit]);
    }

    public function update(Request $request)
    {
        $measuringUnit = MeasuringUnit::find($request->measuringUnitId);
        $result = $measuringUnit->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'quantity' => $request->qty,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Measuring Unit Updated Successfully']);
        }

        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function destroy(string $id)
    {
        //
    }
}
