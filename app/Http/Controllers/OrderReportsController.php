<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Supplier;
use Illuminate\Http\Request;

class OrderReportsController extends Controller
{
    public function orderReports()
    {
        $parties = Party::get();
        $suppliers = Supplier::all();

        return view('adminPanel.orders.orderReports.orderReports', ['suppliers' => $suppliers, 'parties' => $parties]);
    }

    public function printOrdersList(Request $request)
    {

        $order = 'App\Models\Order';
        $query = $order::query();

        if ($request->reportType != 'All') {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $order = $query->get();
        return view('adminPanel.orders.orderReports.orderList', ['orders' => $order, 'request' => $request->all()]);
    }

    public function partyWiseOrder(Request $request)
    {
        $order = 'App\Models\Order';
        $query = $order::query();
        $partyName = '';

        if ($request->particularType == 'Marka') {
            $query->where('marka_id', $request->partyId);
        }

        if ($request->particularType == 'Driver') {
            $query->where('driver_id', $request->partyId);
        }

        if ($request->particularType == 'Customer') {
            $query->where('customer_id', $request->partyId);
        }

        if ($request->reportType != 'All') {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $order = $query->get();
        $party = Party::find($request->partyId);
        $partyName = $party->name;

        return view('adminPanel.orders.orderReports.partyOrderList', ['orders' => $order, 'partyName' => $partyName, 'request' => $request->all()]);
    }

    public function supplierWiseOrder(Request $request)
    {

        $order = 'App\Models\Order';
        $query = $order::query();
        $query->where('supplier_id', $request->supplier_id);

        if ($request->reportType != 'All') {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $order = $query->get();
        $supplier = Supplier::find($request->supplier_id);
        return view('adminPanel.orders.orderReports.supplierOrderList', ['orders' => $order, 'supplier' => $supplier, 'request' => $request->all()]);
    }
}
