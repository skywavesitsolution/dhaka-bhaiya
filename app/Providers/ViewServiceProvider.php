<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Models\Sales\SaleInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // // Fetch the count of purchases globally
        // $purchaseCount = Purchase::with('supplier')
        //     ->where('payment_type', 'credit')
        //     ->where('due_date', '>', Carbon::today()->addDays(2))
        //     ->whereHas('supplier', function ($query) {
        //         $query->where('balance', '>', DB::raw('purchases.supplier_balance'));
        //     })
        //     ->count();

        // // Share the purchase count globally
        // view()->share('purchaseCount', $purchaseCount);

        // // Fetch the count of sale invoices globally
        // $saleCount = SaleInvoice::with('party')
        //     ->where('payment_type', 'credit')
        //     ->where('due_date', '>', Carbon::today()->addDays(2))
        //     ->whereHas('party', function ($query) {
        //         $query->where('balance', '>', DB::raw('sale_invoices.customer_reciveable'));
        //     })
        //     ->count(); // Calculate count instead of getting the data

        // // Share the sale count globally
        // view()->share('saleCount', $saleCount);
    }
}
