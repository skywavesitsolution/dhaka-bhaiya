<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DeleteOrder
{
    public function execute(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $this->updateMarkaBalance($order);
                $this->updateDriverBalance($order);
                $this->updateCustomerBalance($order);

                $order->delete();
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function updateMarkaBalance(Order $order)
    {

        $partyData = [
            'partyId' => $order->marka_id,
            'amount' => $order->total_purchase,
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => "Order Deleted",
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    private function updateDriverBalance(Order $order)
    {

        $partyData = [
            'partyId' => $order->driver_id,
            'amount' => $order->total_carriage,
            'incrementType' => 'decrement',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'received',
            'orderId' => $order->id,
            'remarks' => "Order Deleted",
            'date' => date('Y-m-d'),
        ];

        $this->updatePartyBalance($partyData);
    }

    private function updateCustomerBalance(Order $order)
    {

        $partyData = [
            'partyId' => $order->customer_id,
            'amount' => $order->total_sale_amount,
            'incrementType' => 'increment',
            'dbFeildId' => 'order_id',
            'dbFeild' => 'payment',
            'orderId' => $order->id,
            'remarks' => "Order Deleted",
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
