<?php

namespace App\Http\Controllers;

use App\Actions\DeleteOrder;
use App\Actions\SavePartyLedger;
use App\Actions\UpdateOrder;
use App\Actions\UpdatePartyBalance;
use App\Models\Order;
use App\Models\Party;
use App\Models\ProductType;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{


    public function creatOrder()
    {
        $suppliers = Supplier::all();
        $productTypes = ProductType::all();
        $parties = Party::all();
        $order = Order::latest()->first();

        return view('adminPanel.orders.createOrder', ['order' => $order, 'parties' => $parties, 'suppliers' => $suppliers, 'productTypes' => $productTypes]);
    }

    public function update(Order $order)
    {
        $suppliers = Supplier::all();
        $productTypes = ProductType::all();
        $parties = Party::all();
        return view('adminPanel.orders.updateOrder', ['order' => $order, 'parties' => $parties, 'suppliers' => $suppliers, 'productTypes' => $productTypes]);
    }

    public function orderUpdate(Request $request, Order $order, UpdateOrder $updateOrder)
    {
        $result =  $updateOrder->execute($request->all(), $order);
        if ($result) {
            return redirect()->back()->with(['success' => 'Order Updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    public function delete(Order $order, DeleteOrder $deleteOrder)
    {
        $result =  $deleteOrder->execute($order);
        if ($result) {
            return redirect()->back()->with(['success' => 'Order Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
        }
    }

    private function reverseMarkaBalance(array $requestData, $orderId)
    {

        $partyData = [
            'partyId' => $requestData['marka'],
            'amount' => $requestData['totalAmount'],
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $orderId,
            'remarks' => $requestData['remarks'],
            'date' => $requestData['date'],
        ];

        $this->updatePartyBalance($partyData);
    }



    public function saveOrder(Request $request)
    {
        // dd($request->all());
        $request->validate([
            "date" => ['required', 'date'],
            "marka" => ['required', 'integer'],
            "product" => ['required', 'integer'],
            "purchaseQty" => ['required', 'numeric'],
            "purRate" => ['required', 'numeric'],
            "totalAmount" => ['required', 'numeric'],
            "driver" => ['required', 'integer'],
            "carAmount" => ['required', 'numeric'],
            "totalCarriage" => ['required', 'numeric'],
            "grandTotal" => ['required', 'numeric'],
            "supplier" => ['required', 'integer'],
            "customer" => ['required', 'integer'],
            "saleRate" => ['required', 'numeric'],
            "saleAmount" => ['required', 'numeric'],
            "profit" => ['required', 'numeric'],
            "remarks" => ['string', 'nullable'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $order = $this->saveOrderData($request->all());
                $this->updateMarkaBalance($request->all(), $order);
                $this->updateDriverBalance($request->all(), $order);
                $this->updateCustomerBalance($request->all(), $order);
            });
// dd($request->all());
            return redirect()->back()->with(['success' => 'Order Created Successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again','error' => $e->getMessage()]);
        }
    }

    private function updateDriverBalance(array $requestData, Order $order)
    {
        $remarks = "<h6>Qty: " . number_format($order->purchase_qty) . "</h6>
        <h6>Carriage Amount: " . number_format($order->carriage_amount) . "</h6>
        <h6>Total Carriage: " . number_format($order->total_carriage) . "</h6>";
        $requestData['remarks'] .= $remarks;

        $partyData = [
            'partyId' => $requestData['driver'],
            'amount' => $requestData['totalCarriage'],
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => $requestData['remarks'],
            'date' => $requestData['date'],
        ];

        $this->updatePartyBalance($partyData);
    }

    private function updateCustomerBalance(array $requestData, Order $order)
    {
        $remarks = "<h6>Qty: " . number_format($order->purchase_qty) . "</h6>
        <h6>Sale Amount: " . number_format($order->sale_rate) . "</h6>
        <h6>Total Sale: " . number_format($order->total_sale_amount) . "</h6>";
        $requestData['remarks'] .= $remarks;

        $partyData = [
            'partyId' => $requestData['customer'],
            'amount' => $requestData['saleAmount'],
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => $requestData['remarks'],
            'date' => $requestData['date'],
        ];

        $this->updatePartyBalance($partyData);
    }

    private function updateMarkaBalance(array $requestData, Order $order)
    {

        $remarks = "<h6>Qty: " . number_format($order->purchase_qty) . "</h6>
                <h6>Purchase Rate: " . number_format($order->purchase_rate) . "</h6>
                <h6>Total Purchase: " . number_format($order->total_purchase) . "</h6>";

        $requestData['remarks'] .= $remarks;

        $partyData = [
            'partyId' => $requestData['marka'],
            'amount' => $requestData['totalAmount'],
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => $requestData['remarks'],
            'date' => $requestData['date'],
        ];

        $this->updatePartyBalance($partyData);
    }

    private function saveOrderData($requestData)
    {
        return Order::create([
            'date' => $requestData['date'],
            'marka_id' => $requestData['marka'],
            'product_type' => $requestData['product'],
            'purchase_qty' => $requestData['purchaseQty'],
            'purchase_rate' => $requestData['purRate'],
            'total_purchase' => $requestData['totalAmount'],
            'driver_id' => $requestData['driver'],
            'carriage_amount' => $requestData['carAmount'],
            'total_carriage' => $requestData['totalCarriage'],
            'grand_purchase_amount' => $requestData['grandTotal'],
            'supplier_id' => $requestData['supplier'],
            'customer_id' => $requestData['customer'],
            'sale_rate' => $requestData['saleRate'],
            'total_sale_amount' => $requestData['saleAmount'],
            'profit' => $requestData['profit'],
            'remarks' => $requestData['remarks'],
        ]);
    }

    private function updatePartyBalance(array $partyData)
    {
        // Update Party Balance
        $updatePartyBalance = app(UpdatePartyBalance::class);
        $updatePartyBalance->execute($partyData['partyId'], $partyData['amount'], $partyData['incrementType']);

        // Insert Party Ledger
        $SavePartyLedger = app(SavePartyLedger::class);
        $SavePartyLedger->execute($partyData['partyId'], $partyData['amount'], $partyData['dbFeildId'], $partyData['dbFeild'], $partyData['orderId'], $partyData['remarks'], date: $partyData['date']);
    }

    public function orderList()
    {
        $allOrders = $this->orderListWithPagination(5);
        return view('adminPanel.orders.orderList', ['allOrders' => $allOrders]);
    }
    public function orderListWithPagination(int $items)
    {
        $allOrders = Order::OrderBy('id', 'desc')->paginate($items);
        return $allOrders;
    }
}
