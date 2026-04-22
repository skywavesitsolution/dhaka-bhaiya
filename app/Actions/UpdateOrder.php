<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class UpdateOrder
{
    public function execute(array $requestData, Order $order)
    {
        try {
            DB::transaction(function () use ($order, $requestData) {
                $this->updateMarkaBalance($order, $requestData);
                $this->updateDriverBalance($order, $requestData);
                $this->updateCustomerBalance($order, $requestData);

                $order->update([
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
            });

            return true;
        } catch (\Exception $e) {
            dd($e);
            return false;
        }
    }

    private function updateMarkaBalance(Order $order, array $requestData)
    {
        if ($order->marka_id == $requestData['marka']) {
            $this->updateIfMarkaNotChange($order, $requestData);
        } else {
            $this->updateIfMarkaChange($order, $requestData);
        }
    }

    private function updateIfMarkaNotChange(Order $order, array $requestData)
    {
        $amountDifference = $requestData['totalAmount'] - $order->total_purchase;
        if ($amountDifference != 0) {

            $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Purchase Rate: " . number_format($requestData['purRate']) . "</h6>
                <h6>Total Purchase: " . number_format($requestData['totalAmount']) . "</h6>";

            $partyData = [
                'partyId' => $order->marka_id,
                'amount' => $amountDifference,
                'incrementType' => 'increment',
                'dbFeildId' => 'order_id',
                'dbFeild' => 'payment',
                'orderId' => $order->id,
                'remarks' => "Order Updated" . $remarks,
                'date' => date('Y-m-d'),
            ];

            $this->updatePartyBalance($partyData);
        }
    }

    private function updateIfMarkaChange(Order $order, array $requestData)
    {
        $this->removeFromPreviousMarka($order, $requestData);
        $this->addInNewMarka($order, $requestData);
    }

    private function removeFromPreviousMarka(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Purchase Rate: " . number_format($requestData['purRate']) . "</h6>
                <h6>Total Purchase: " . number_format($requestData['totalAmount']) . "</h6>";

        $partyData = [
            'partyId' => $order->marka_id,
            'amount' => $order->total_purchase,
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    private function addInNewMarka(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
        <h6>Purchase Rate: " . number_format($requestData['purRate']) . "</h6>
        <h6>Total Purchase: " . number_format($requestData['totalAmount']) . "</h6>";


        $partyData = [
            'partyId' => $requestData['marka'],
            'amount' => $requestData['totalAmount'],
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    // Driver Balances Update

    private function updateDriverBalance(Order $order, array $requestData)
    {
        if ($order->driver_id == $requestData['driver']) {
            $this->updateIfDriverNotChange($order, $requestData);
        } else {
            $this->updateIfDriverChange($order, $requestData);
        }
    }

    private function updateIfDriverNotChange(Order $order, array $requestData)
    {
        $amountDifference = $requestData['totalCarriage'] - $order->total_carriage;
        if ($amountDifference != 0) {
            $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Carriage Amount: " . number_format($requestData['carAmount']) . "</h6>
                <h6>Total Carriage: " . number_format($requestData['totalCarriage']) . "</h6>";

            $partyData = [
                'partyId' => $order->driver_id,
                'amount' => $amountDifference,
                'incrementType' => 'increment',
                'dbFeildId' => 'order_id',
                'dbFeild' => 'payment',
                'orderId' => $order->id,
                'remarks' => "Order Updated" . $remarks,
                'date' => date('Y-m-d'),
            ];

            $this->updatePartyBalance($partyData);
        }
    }

    private function updateIfDriverChange(Order $order, array $requestData)
    {
        $this->removeFromPreviousDriver($order, $requestData);
        $this->addInNewDriver($order, $requestData);
    }

    private function removeFromPreviousDriver(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Carriage Amount: " . number_format($requestData['carAmount']) . "</h6>
                <h6>Total Carriage: " . number_format($requestData['totalCarriage']) . "</h6>";

        $partyData = [
            'partyId' => $order->driver_id,
            'amount' => $order->total_carriage,
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    private function addInNewDriver(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Carriage Amount: " . number_format($requestData['carAmount']) . "</h6>
                <h6>Total Carriage: " . number_format($requestData['totalCarriage']) . "</h6>";

        $partyData = [
            'partyId' => $requestData['driver'],
            'amount' => $requestData['totalCarriage'],
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    // Customer Balance Update

    private function updateCustomerBalance(Order $order, array $requestData)
    {

        if ($order->customer_id == $requestData['customer']) {
            $this->updateIfCustomerNotChange($order, $requestData);
        } else {
            $this->updateIfCustomerChange($order, $requestData);
        }
    }

    private function updateIfCustomerNotChange(Order $order, array $requestData)
    {
        $amountDifference = $requestData['saleAmount'] - $order->total_sale_amount;
        if ($amountDifference != 0) {
            $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Sale Amount: " . number_format($requestData['saleRate']) . "</h6>
                <h6>Total Sale: " . number_format($requestData['saleAmount']) . "</h6>";

            $partyData = [
                'partyId' => $order->customer_id,
                'amount' => $amountDifference,
                'incrementType' => 'decrement',
                'dbFeildId' => 'order_id',
                'dbFeild' => 'received',
                'orderId' => $order->id,
                'remarks' => "Order Updated" . $remarks,
                'date' => date('Y-m-d'),
            ];

            $this->updatePartyBalance($partyData);
        }
    }

    private function updateIfCustomerChange(Order $order, array $requestData)
    {
        $this->removeFromPreviousCustomer($order, $requestData);
        $this->addInNewCustomer($order, $requestData);
    }

    private function removeFromPreviousCustomer(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
                <h6>Sale Amount: " . number_format($requestData['saleRate']) . "</h6>
                <h6>Total Sale: " . number_format($requestData['saleAmount']) . "</h6>";

        $partyData = [
            'partyId' => $order->customer_id,
            'amount' => $order->total_sale_amount,
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    private function addInNewCustomer(Order $order, array $requestData)
    {
        $remarks = "<h6>Qty: " . number_format($requestData['purchaseQty']) . "</h6>
        <h6>Sale Amount: " . number_format($requestData['saleRate']) . "</h6>
        <h6>Total Sale: " . number_format($requestData['saleAmount']) . "</h6>";

        $partyData = [
            'partyId' => $requestData['customer'],
            'amount' => $requestData['saleAmount'],
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => "Order Updated" . $remarks,
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
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
}
