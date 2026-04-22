<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AccountReportsController;
use App\Http\Controllers\Account\CashDepositController;
use App\Http\Controllers\Account\DayBookController;
use App\Http\Controllers\Account\ExpenseController;
use App\Http\Controllers\Account\MakePaymentController;
use App\Http\Controllers\Account\PaymentReportController;
use App\Http\Controllers\Account\PosClosingController;
use App\Http\Controllers\Account\ReceivedPaymentController;
use App\Http\Controllers\Backup\BackupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\Inward\InwardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\OrderReportsController;
use App\Http\Controllers\PartyReportsController;
use App\Http\Controllers\SummaryReportController;
use App\Http\Controllers\Sales\SaleInvoiceController;
use App\Http\Controllers\Product\Brand\ProductBrandController;
use App\Http\Controllers\Product\Category\ProductCategoryController;
use App\Http\Controllers\Product\Location\ProductLocationController;
use App\Http\Controllers\Product\MeasuringUnit\MeasuringUnitController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductImport\ProductImportController;
use App\Http\Controllers\Product\ProductOption\ProductSizeController;
use App\Http\Controllers\Product\StockAdjustment\StockAdjustmentController;
use App\Http\Controllers\Product\Variant\ProductVariantController;
use App\Http\Controllers\Product\Variant\VariantBarcode\ProductVariantBarcodeController;
use App\Http\Controllers\productreportController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\StockManagment\StockTransferController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SessionController;
use PhpMyAdmin\Controllers\TableController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard (no specific permission, just auth)
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware('auth')->name('dashboard');

// Orders
Route::middleware(['can:orders', 'auth'])->group(function () {
    Route::post('/save-order', [OrderController::class, 'saveOrder']);
    Route::get('/create-order', [OrderController::class, 'creatOrder']);
    Route::get('/order-list', [OrderController::class, 'orderList']);
    Route::get('/order-update/{order}', [OrderController::class, 'update']);
    Route::post('/order-update/{order}', [OrderController::class, 'orderUpdate']);
    Route::get('/order-delete/{order}', [OrderController::class, 'delete']);
});

// General Section
Route::middleware(['auth'])->group(function () {
    Route::prefix('product-category')
        ->middleware('can:general.category')
        ->name('product-category.')
        ->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
            Route::post('/store', [ProductCategoryController::class, 'store'])->name('store');
            Route::get('/get-category/{id}', [ProductCategoryController::class, 'show']);
            Route::post('/update-category', [ProductCategoryController::class, 'update'])->name('update');
            Route::post('/change-product-category', [ProductCategoryController::class, 'changeProductCategory'])->name('change');
            Route::delete('/delete-category/{id}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('product-brand')
        ->middleware('can:general.brand') // Assuming permission for consistency
        ->name('product-brand.')
        ->group(function () {
            Route::get('/', [ProductBrandController::class, 'index'])->name('index');
            Route::post('/store', [ProductBrandController::class, 'store'])->name('store');
            Route::get('/get-brand/{id}', [ProductBrandController::class, 'show']);
            Route::post('/update-brand', [ProductBrandController::class, 'update'])->name('update');
            Route::delete('/delete-brand/{id}', [ProductBrandController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('product-size')
        ->middleware('can:general.size') // Assuming permission for consistency
        ->name('product-size.')
        ->group(function () {
            Route::get('/', [ProductSizeController::class, 'index'])->name('index');
            Route::post('/store', [ProductSizeController::class, 'store'])->name('store');
            Route::get('/get-size/{id}', [ProductSizeController::class, 'show']);
            Route::post('/update-size', [ProductSizeController::class, 'update'])->name('update');
            Route::delete('/delete-color/{id}', [ProductSizeController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('product-location')
        ->middleware('can:general.location')
        ->name('product-location.')
        ->group(function () {
            Route::get('/', [ProductLocationController::class, 'index'])->name('index');
            Route::post('/store', [ProductLocationController::class, 'store'])->name('store');
            Route::get('/get-location/{id}', [ProductLocationController::class, 'show']);
            Route::post('/update-location', [ProductLocationController::class, 'update'])->name('update');
            Route::post('/change-product-location', [ProductLocationController::class, 'changeProductLocation'])->name('change');
            Route::delete('/delete-location/{id}', [ProductLocationController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('measuring-unit')
        ->middleware('can:general.measuring_unit')
        ->name('measuring-unit.')
        ->group(function () {
            Route::get('/', [MeasuringUnitController::class, 'index'])->name('index');
            Route::post('/store', [MeasuringUnitController::class, 'store'])->name('store');
            Route::get('/get-measuring-unit/{id}', [MeasuringUnitController::class, 'show']);
            Route::post('/update-measuring-unit', [MeasuringUnitController::class, 'update'])->name('update');
        });

    Route::prefix('table')
        ->middleware('can:general.manage_tables')
        ->name('table.')
        ->group(function () {
            Route::get('/', [DealController::class, 'showTablePage'])->name('showTablePage');
            Route::post('/storeTable', [DealController::class, 'storeTable'])->name('storeTable');
            Route::get('/get-table/{id}', [DealController::class, 'getTable'])->name('table.getTable');
            Route::post('/update', [DealController::class, 'updatetable'])->name('update');
        });

    Route::prefix('deal')
        ->middleware('can:general.manage_deals')
        ->name('deal.')
        ->group(function () {
            Route::get('/', [DealController::class, 'index'])->name('index');
            Route::post('/store', [DealController::class, 'store'])->name('store');
            Route::post('/update', [DealController::class, 'update'])->name('update');
            Route::get('/deals/{id}', [DealController::class, 'show'])->name('showdeal');
            Route::get('/fetch-products-for-deal', [DealController::class, 'searchProducts']);
            Route::get('/delete/{id}', [DealController::class, 'delete'])->name('delete');
        });

    Route::prefix('recipes')
        ->middleware('can:general.manage_recipes')
        ->name('recipes.')
        ->group(function () {
            Route::get('/', [RecipeController::class, 'index'])->name('index');
            Route::post('/', [RecipeController::class, 'store'])->name('store');
            Route::get('/{id}/details', [RecipeController::class, 'details'])->name('details');
            Route::get('/{id}/edit-data', [RecipeController::class, 'getEditData'])->name('get_edit_data');
            Route::put('/{id}', [RecipeController::class, 'update'])->name('update');
        });
});



// Product Section
Route::middleware(['auth'])->group(function () {
    Route::prefix('product')
        ->name('product.')
        ->group(function () {
            Route::get('/', [ProductController::class, 'index'])->middleware('can:product.product')->name('index');
            Route::get('/create', [ProductController::class, 'create'])->middleware('can:product.create_product')->name('create');
            Route::get('/get-product-suggestions', [ProductController::class, 'getProductSuggestions'])->middleware('can:product.product');
            Route::post('/store', [ProductController::class, 'store'])->middleware('can:product.create_product')->name('store');
            Route::get('/get-product/{id}', [ProductController::class, 'show']);
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->middleware('can:product.product_variant')->name('edit');
            Route::post('/update', [ProductController::class, 'update'])->middleware('can:product.product_variant')->name('update');
            Route::post('/update-status', [ProductController::class, 'updateStatus'])->middleware('can:product.product')->name('update.status');
            Route::post('/update-product', [ProductController::class, 'update'])->middleware('can:product.product')->name('update');
            Route::delete('/delete/{id}', [ProductController::class, 'softDestroy'])->middleware('can:product.product')->name('soft.destroy');
            Route::get('/trashed', [ProductController::class, 'trashed'])->name('trashed');
            Route::post('restore/{id}', [ProductController::class, 'restoreProduct'])->name('restore');
            Route::get('/low-stock', [ProductController::class, 'lowStock'])->middleware('can:product.low_stock_list')->name('low-stock');
            Route::get('/fixed-assets', [ProductController::class, 'fixedAssetProducts'])->middleware('can:product.product')->name('fixed-assets');
            Route::post('/crietaria-wise-report', [ProductController::class, 'criteriaWiseReport'])->middleware('can:product.product')->name('crietaria-wise-report');
            Route::get('/get-products-by-category/{category_id}', [ProductController::class, 'getProductsByCategory'])->middleware('can:product.product')->name('get.products.by.category');
            Route::get('/get-products-by-brand/{brand_id}', [ProductController::class, 'getProductsByBrand'])->middleware('can:product.product')->name('get.products.by.brand');
            Route::get('/get-products-by-supplier/{supplier_id}', [ProductController::class, 'getProductsBySupplier'])->middleware('can:product.product')->name('get.products.by.supplier');
        });

    Route::prefix('product-variant')
        ->name('product-variant.')
        ->group(function () {
            Route::get('/', [ProductVariantController::class, 'index'])->middleware('can:product.product_variant')->name('index');
            Route::get('/fixed-asset/products', [ProductVariantController::class, 'getFixedAssetProducts'])->middleware('can:product.product_variant')->name('fixed.asset.products');
            Route::get('/create', [ProductVariantController::class, 'create'])->middleware('can:product.product_variant')->name('create');
            Route::post('/store', [ProductVariantController::class, 'store'])->middleware('can:product.product_variant')->name('store');
            Route::get('/get-product-variant/{id}', [ProductVariantController::class, 'show']);
            Route::post('/update-product-variant', [ProductVariantController::class, 'update'])->middleware('can:product.product_variant')->name('update');
            Route::get('/get-product-variant-retail-price/{id}', [ProductVariantController::class, 'getProductVariantRetailPrice'])->middleware('can:product.product_variant');
            Route::post('/update-product-variant-retail-price', [ProductVariantController::class, 'updateProductVariantRetailPrice'])->middleware('can:product.product_variant');
            Route::get('/get-product-variant-code/{id}', [ProductVariantController::class, 'getProductVariantCode'])->middleware('can:product.product_variant');
            Route::post('/update-product-variant-code', [ProductVariantController::class, 'updateProductVariantCode'])->middleware('can:product.product_variant');
            Route::delete('/delete/{id}', [ProductVariantController::class, 'softDestroy'])->middleware('can:product.product_variant')->name('soft.destroy');
            Route::get('/trashed', [ProductVariantController::class, 'trashed'])->name('trashed');
            Route::post('restore/{id}', [ProductVariantController::class, 'restoreProductVariant'])->name('restore');
            Route::get('/fixed-assets', [ProductVariantController::class, 'fixedAssetProductVariants'])->middleware('can:product.product_variant')->name('fixed-assets');
            Route::post('/crietaria-wise-report', [ProductVariantController::class, 'criteriaWiseReport'])->middleware('can:product.product_variant')->name('crietaria-wise-report');
        });

    Route::prefix('product-stock-adjustment')
        ->middleware('can:stock_management.stock_adjustment')
        ->name('product-stock-adjustment.')
        ->group(function () {
            Route::get('/', [StockAdjustmentController::class, 'index'])->name('index');
            Route::get('/get-variant-stock', [StockAdjustmentController::class, 'getVariantStock'])->name('get.variant.stock');
            Route::post('/variant-stock-update', [StockAdjustmentController::class, 'updateVariantStock'])->name('update.variant.stock');
        });

    Route::prefix('product-variant-barcode')
        ->middleware('can:product.print_variants_barcode')
        ->name('product-variant-barcode.')
        ->group(function () {
            Route::get('/', [ProductVariantBarcodeController::class, 'index'])->name('index');
            Route::get('/search-variants', [ProductVariantBarcodeController::class, 'searchProductVariants']);
            Route::post('/generate-barcodes', [ProductVariantBarcodeController::class, 'generateBarcodes']);
        });

    Route::prefix('product-import')
        ->middleware('can:product.create_product')
        ->name('product-import.')
        ->group(function () {
            Route::get('/', [ProductImportController::class, 'index'])->name('index');
            Route::get('/download-example-format', [ProductImportController::class, 'downloadExampleFormat'])->name('downloadExampleFormat');
            Route::post('/store', [ProductImportController::class, 'importProducts'])->name('import');
        });

    Route::prefix('employee')
        ->name('employee.')
        ->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->middleware('can:user_management.employees')->name('index');
            Route::post('/add-employee', [EmployeeController::class, 'store'])->middleware('can:user_management.employees')->name('add');
            Route::put('/update/{id}', [EmployeeController::class, 'update'])->middleware('can:user_management.employees')->name('employee.update');
            Route::delete('/delete/{id}', [EmployeeController::class, 'softdestroy'])->middleware('can:user_management.employees')->name('delete');
            Route::post('/restore/{id}', [EmployeeController::class, 'restore'])->middleware('can:user_management.trashed_employees')->name('restore');
            Route::get('/trashed', [EmployeeController::class, 'trashedEmployees'])->middleware('can:user_management.trashed_employees')->name('trashed');
        });
});

// Inward Section
Route::middleware(['can:inward', 'auth'])->group(function () {
    Route::prefix('inward')
        ->name('inward.')
        ->group(function () {
            Route::get('/', [InwardController::class, 'index'])->name('index');
            Route::get('/create', [InwardController::class, 'create'])->name('create');
            Route::get('/get-product-variants', [InwardController::class, 'getProductVariants']);
            Route::post('/store', [InwardController::class, 'store'])->name('store');
            Route::get('/{id}/details', [InwardController::class, 'inwardDetail'])->name('details');
            Route::post('/update-status', [InwardController::class, 'updateInwardStatus'])->name('updateStatus');
            Route::delete('/delete/{id}', [InwardController::class, 'softDestroy'])->name('soft.destroy');
            Route::get('/trashed', [InwardController::class, 'trashed'])->name('trashed');
            Route::get('/{id}/trashed-details', [InwardController::class, 'inwardTrashedDetail'])->name('trashed.details');
            Route::post('restore/{id}', [InwardController::class, 'restoreInward'])->name('restore');
            Route::get('/{id}/print/details', [InwardController::class, 'printInwardDetail'])->name('print.details');
        });
});

// Stock Management Section
Route::middleware(['auth'])->group(function () {
    Route::prefix('stock-transfer')
        ->name('stock-transfer.')
        ->group(function () {
            Route::get('/list', [StockTransferController::class, 'index'])->middleware('can:stock_management.transfer_stock_list')->name('index');
            Route::get('/', [StockTransferController::class, 'stockTransfer'])->middleware('can:stock_management.transfer_stock')->name('create');
            Route::get('/get-product-variants-by-location', [StockTransferController::class, 'getProductVariantsByLocation'])->middleware('can:stock_management.transfer_stock')->name('get.product.variants.by.location');
            Route::post('/store', [StockTransferController::class, 'store'])->middleware('can:stock_management.transfer_stock')->name('store');
            Route::get('/{id}/details', [StockTransferController::class, 'stockTransferDetail'])->middleware('can:stock_management.transfer_stock_list')->name('details');
            Route::get('/{id}/print/details', [StockTransferController::class, 'printTransferStockDetail'])->middleware('can:stock_management.transfer_stock_list')->name('print.details');
            Route::delete('/delete/{id}', [StockTransferController::class, 'softDestroy'])->middleware('can:stock_management.transfer_stock_list')->name('soft.destroy');
            Route::get('/trashed', [StockTransferController::class, 'trashed'])->middleware('can:stock_management.trashed_transfer_stock_list')->name('trashed');
        });

    Route::prefix('purchase')
        ->name('purchase.')
        ->group(function () {
            Route::get('/', [PurchaseController::class, 'show_purchase_page'])->middleware('can:stock_management.purchase')->name('form');
            Route::post('/save-purchase', [PurchaseController::class, 'save_purchase'])->middleware('can:stock_management.purchase')->name('save');
            Route::get('/get-supplier-balance/{id}', [PurchaseController::class, 'getsupplierbalance'])->middleware('can:stock_management.purchase')->name('getsupplierbalance');
            Route::get('/purchase-list', [PurchaseController::class, 'purchase_list'])->middleware('can:stock_management.purchase_list')->name('list');
            Route::get('/fetch-product-details/{id}', [PurchaseController::class, 'fetchProductDetails'])->middleware('can:stock_management.purchase')->name('fetch.product.details');
            Route::get('/purchase/print/{id}', [PurchaseController::class, 'printPurchaseDetails'])->middleware('can:stock_management.purchase_list')->name('print');
            Route::get('/delete-purchase-invoice/{id}', [PurchaseController::class, 'deletePurchaseInvoice'])->middleware('can:stock_management.purchase_list')->name('delete');
            Route::get('/recevied-purchase-invoice/{id}', [PurchaseController::class, 'received'])->middleware('can:stock_management.purchase_list')->name('recevied');
            Route::get('/fetch-products-for-purchase', [PurchaseController::class, 'searchProducts'])->middleware('can:stock_management.purchase');
        });
    Route::get('/purchase-return-list', [PurchaseReturnController::class, 'list'])->middleware('can:stock_management.purchase_list')->name('purchase.return.list');
    Route::get('/return', [PurchaseReturnController::class, 'showpurchaseReturnpage'])->middleware('can:stock_management.purchase_return')->name('purchase.return');
    Route::post('/return-save', [PurchaseReturnController::class, 'savePurchaseReturn'])->middleware('can:stock_management.purchase_return')->name('purchase.return.save');
});

// Suppliers (not in navigation, retained for consistency)
Route::middleware(['can:suppliers', 'auth'])->group(function () {
    Route::post('/add-supplier', [SupplierController::class, 'addSupplier']);
    Route::get('/get-supplier-list', [SupplierController::class, 'getSupplierList']);
    Route::get('/get-supplier/{id}', [SupplierController::class, 'getSupplier']);
    Route::post('/update-supplier', [SupplierController::class, 'updateSupplier'])->name('update.supplier');
});

// Party Section
Route::middleware(['can:party', 'auth'])->group(function () {
    Route::post('/add-party', [PartyController::class, 'addParty']);
    Route::post('/add-party-wi-ajax', [PartyController::class, 'addPartyWithAjax'])->name('add-party-wi-ajax');
    Route::get('/get-suppliers', [PartyController::class, 'getSuppliers'])->name('get.suppliers');
    Route::get('/get-parties-list', [PartyController::class, 'getpartiesList']);
    Route::get('/get-party/{id}', [PartyController::class, 'getParty']);
    Route::post('/update-party', [PartyController::class, 'updateParty'])->name('update.party');
    Route::get('/fetch-parties-wi-types/{type}', [PartyController::class, 'fetchPartieswithTypes']);
});

// Sale Section (POS)
Route::middleware(['auth'])->group(function () {
    Route::get('/get-sale-invoice', [SaleInvoiceController::class, 'getSaleInvoice'])->middleware('can:sale.sale');
    Route::get('/get-product/{id}', [SaleInvoiceController::class, 'getProductById'])->name('product.get');
    Route::post('/store-sale-inovice', [SaleInvoiceController::class, 'storeSaleInvoice'])->middleware('can:sale.sale')->name('sale.invoice');
    Route::get('/sale-inovice-list', [SaleInvoiceController::class, 'SaleInvoiceList'])->middleware('can:sale.sale_list');
    Route::get('/today-sale-inovice-list', [SaleInvoiceController::class, 'todaySaleInvoiceList'])->middleware('can:sale.sale_list');
    Route::get('/check-table-status/{tableId}', [SaleInvoiceController::class, 'checkTableStatus'])->middleware('can:sale.sale')->name('check.table.status');
    Route::get('/edit-sale-inovice/{id}', [SaleInvoiceController::class, 'editSaleInvoice'])->middleware('can:sale.sale');
    Route::get('/sale-product-print/{id}', [SaleInvoiceController::class, 'SaleInvoicePrint'])->middleware('can:sale.sale_list');
    Route::get('/print-customer-invoice/{quotationId}', [SaleInvoiceController::class, 'printCustomerInvoice'])->middleware('can:sale.sale')->name('printCustomerInvoice');
    Route::post('/update-sale-product', [SaleInvoiceController::class, 'updateSaleProduct'])->middleware('can:sale.sale');
    Route::post('/update-sale-product/{id}', [SaleInvoiceController::class, 'updateSaleProduct'])->middleware('can:sale.sale');
    Route::get('/get-customer-balance/{id}', [SaleInvoiceController::class, 'getcustomerbalance'])->middleware('can:sale.sale')->name('getcustomerbalance');
    Route::get('/filter-products', [SaleInvoiceController::class, 'filterProducts'])->middleware('can:sale.sale')->name('filter.products');
    Route::post('/hold-invoices', [SaleInvoiceController::class, 'storeHoldInvoice'])->middleware('can:sale.sale')->name('hold.invoice');
    Route::get('/sale-holds', [SaleInvoiceController::class, 'getSaleHold'])->middleware('can:sale.sale')->name('sale.hold');
    Route::get('/get-held-invoice/{invoiceId}', [SaleInvoiceController::class, 'reloadInvoice'])->middleware('can:sale.sale');
    Route::post('/quotation-invoices', [SaleInvoiceController::class, 'storequotationInvoice'])->middleware('can:sale.sale')->name('quotaion.invoice');
    Route::get('/get-quotation/{quotationId}', [SaleInvoiceController::class, 'reloadQuotation'])->middleware('can:sale.sale');
    Route::get('/search-products', [SaleInvoiceController::class, 'searchProducts'])->middleware('can:sale.sale');
    Route::get('/delete-holdInvoice/{id}', [SaleInvoiceController::class, 'deleteHoldInvoice'])->middleware('can:sale.sale')->name('delete.holdInvoice');
    Route::get('/delete-quotation/{id}', [SaleInvoiceController::class, 'deleteQuotationInvoice'])->middleware('can:sale.sale')->name('delete.quotationInvoice');
    Route::get('/fetch-products-for-sale', [SaleInvoiceController::class, 'searchProducts'])->middleware('can:sale.sale');
    Route::get('/get-hold-count', [SaleInvoiceController::class, 'getHoldCount'])->middleware('can:sale.sale');
    Route::get('/get-quotation-count', [SaleInvoiceController::class, 'getquotationCount'])->middleware('can:sale.sale');
    Route::get('/printInvoice/{invoiceId}', [SaleInvoiceController::class, 'printInvoice'])->middleware('can:sale.sale')->name('printInvoice');
    Route::get('/printquotation/{quotationId}', [SaleInvoiceController::class, 'printquotation'])->middleware('can:sale.sale')->name('printquotation');
    Route::post('/save-bilty-data', [SaleInvoiceController::class, 'saveBiltyData'])->middleware('can:sale.sale')->name('save-bilty-data');
    Route::post('/update-status/{id}', [SaleInvoiceController::class, 'updateStatus'])->middleware('can:sale.sale');
    Route::get('/best-selling-products', [SaleInvoiceController::class, 'bestSellingProducts'])->middleware('can:sale.sale')->name('best.selling');
    Route::get('get-customer-discount/{customer_id}/{product_id}', [SaleInvoiceController::class, 'getCustomerDiscount'])->middleware('can:sale.sale');
    Route::post('/closing/fetch_sale', [SaleInvoiceController::class, 'fetchSaleData'])->middleware('can:sale.sale')->name('fetch.sale');
});

// Accounts Section
Route::middleware(['auth'])->group(function () {
    Route::middleware('can:accounts.accounts_list')->group(function () {
        Route::get('/get-all-types-account', [AccountController::class, 'fetchAllAcounts']);
        Route::get('/account/{id}', [AccountController::class, 'getAccount']);
        Route::get('/add-account', [AccountController::class, 'accountsList']);
        Route::post('/add-account', [AccountController::class, 'addAccount']);
        Route::post('/update-account', [AccountController::class, 'updateAccount'])->name('account.update');
    });

    Route::middleware('can:accounts.payments_receiving')->group(function () {
        Route::get('/add-make-payment', [MakePaymentController::class, 'getPaymentAddPage']);
        Route::post('/add-make-payment', [MakePaymentController::class, 'addMakePayment']);
        Route::get('/make-payment-list', [MakePaymentController::class, 'paymentsList']);
        Route::get('/fetch-make-payment/{payment}', [MakePaymentController::class, 'fetchMakePayment']);
        Route::get('/view-payment-details/{id}', [MakePaymentController::class, 'viewPaymentDetails']);
        Route::get('/get-payment-item/{makePaymentItem}', [MakePaymentController::class, 'getPaymentItem']);
        Route::post('/update-payment', [MakePaymentController::class, 'updatePayment']);
        Route::get('/delete-payment-item/{paymentItem}', [MakePaymentController::class, 'deletePaymentItem']);
        Route::post('/update-payment-item', [MakePaymentController::class, 'updatePaymentItem']);
        Route::get('/print-payment-voucher/{makePayment}', [MakePaymentController::class, 'printPaymentVoucher']);

        Route::post('/add-received-payment', [ReceivedPaymentController::class, 'addReceivedPayment']);
        Route::get('/receive-payment-list', [ReceivedPaymentController::class, 'receivePaymentsList']);
        Route::get('/view-receive-payment-details/{id}', [ReceivedPaymentController::class, 'viewReceivePaymentDetails']);
        Route::get('/fetch-received-payment/{payment}', [ReceivedPaymentController::class, 'fetchReceivedPayment']);
        Route::post('/update-received-payment', [ReceivedPaymentController::class, 'updatePayment']);
        Route::get('/get-recevied-payment-item/{receivedPaymentItem}', [ReceivedPaymentController::class, 'getPaymentReceivedItem']);
        Route::post('/update-payment-received-item', [ReceivedPaymentController::class, 'updatePaymentReceivedItem']);
        Route::get('/delete-payment-received-item/{paymentItem}', [ReceivedPaymentController::class, 'deletePaymentItem']);
        Route::get('/print-payment-received-voucher/{receivedPayment}', [ReceivedPaymentController::class, 'printPaymentVoucher']);

        Route::post('/add-cash-deposit', [CashDepositController::class, 'addCashDesposit']);
        Route::get('/cashdeposit_data/{id}', [CashDepositController::class, 'cashdeposit_print'])->name('cashdeposit_print');
    });

    Route::prefix('Capital')
        ->middleware('can:accounts.capital_management')
        ->name('Capital.')
        ->group(function () {
            Route::get('/', [AccountController::class, 'index']);
            Route::post('/store', [AccountController::class, 'storeCapital'])->name('store');
            Route::post('/withdraw', [AccountController::class, 'storeWithdrawal'])->name('withdrawal');
        });

    Route::prefix('Assets')
        ->name('Assets.')
        ->group(function () {
            Route::get('/', [AccountController::class, 'assetsPageView'])->middleware('can:accounts.accounts_list');
            Route::post('/store', [AccountController::class, 'storeCapital'])->middleware('can:accounts.accounts_list')->name('store');
            Route::post('/withdraw', [AccountController::class, 'storeWithdrawal'])->middleware('can:accounts.accounts_list')->name('withdrawal');
        });

    Route::get('/balance-sheet', [AccountController::class, 'balanceSheet'])->middleware('can:accounts.balance_sheet');
    Route::get('/show_profit_margin', [AccountController::class, 'show_profit_margin'])->middleware('can:accounts.date_wise_profit_margin');
    Route::get('/TrialbalanceSheet', [AccountController::class, 'trialbalanceSheet'])->middleware('can:accounts.trial_balance_sheet');
});

// Expense Section
Route::middleware(['auth'])->group(function () {
    Route::middleware('can:expense.expense_list')->group(function () {
        Route::get('/add-expense', [ExpenseController::class, 'create']);
        Route::get('/expense-list', [ExpenseController::class, 'index']);
        Route::post('/expense-sub', [ExpenseController::class, 'store']);
        Route::get('/expense_print/{id}', [ExpenseController::class, 'expense_print']);
    });

    Route::middleware('can:expense.categories')->group(function () {
        Route::get('/expense-categories', [ExpenseController::class, 'expense_categories']);
        Route::post('/expense-cat-submit', [ExpenseController::class, 'storeCategory']);
        Route::post('/expense-cat-update', [ExpenseController::class, 'update']);
    });

    Route::middleware('can:expense.sub_categories')->group(function () {
        Route::get('/expense-sub-categories', [ExpenseController::class, 'expense_sub_categories']);
        Route::post('/expense-sub-cat-submit', [ExpenseController::class, 'expense_sub_cat_submit']);
        Route::post('/fetch_sub_category', [ExpenseController::class, 'fetch_sub_category']);
        Route::post('/expense-sub-cat-update', [ExpenseController::class, 'sub_cat_update']);
    });
});

// POS Closing Section
Route::middleware(['auth'])->group(function () {
    Route::get('/pos-closing', [SaleInvoiceController::class, 'posClosing'])->middleware('can:pos_closing.pos_closing')->name('pos.closing');
    Route::post('/fetch_sale', [SaleInvoiceController::class, 'FetchSaleOfEmployee'])->middleware('can:pos_closing.pos_closing')->name('fetch.sale');
    Route::post('/save-posClosing', [PosClosingController::class, 'SavePosClosing'])->middleware('can:pos_closing.pos_closing')->name('save.posClosing');
    Route::get('/dayClosing', [PosClosingController::class, 'dayClosing'])->middleware('can:pos_closing.pos_closing')->name('dayClosing');
    Route::post('/save-dayClosing', [PosClosingController::class, 'dayClosingSave'])->middleware('can:pos_closing.pos_closing')->name('save.dayClosing');
    Route::get('/todayInvoices', [DayBookController::class, 'todayInvoices'])->middleware('can:pos_closing.today_invoices')->name('todayInvoices');
    Route::get('/day-bookss', [DayBookController::class, 'showDayBookForm'])->middleware('can:pos_closing.day_book')->name('day.book');
    Route::get('/day-book-datewise', [DayBookController::class, 'showDayBookdatewiseForm'])->middleware('can:pos_closing.datewise_day_book')->name('daybook.datewise');
});

// User Management Section
Route::middleware(['auth'])->group(function () {
    Route::middleware('can:user_management.user_list')->group(function () {
        Route::get('/users-list', [UserController::class, 'usersList']);
        Route::post('/add-user', [UserController::class, 'registerUser']);
        Route::post('/update-user', [UserController::class, 'updateUser']);
    });
});

// Reports Section
Route::middleware(['auth'])->group(function () {
    Route::middleware(['can:reports.order_reports'])->group(function () {
        Route::get('/order-reports', [OrderReportsController::class, 'orderReports']);
        Route::post('/print-order-list', [OrderReportsController::class, 'printOrdersList']);
        Route::post('/party-wise-order', [OrderReportsController::class, 'partyWiseOrder']);
        Route::post('/supplier-wise-order', [OrderReportsController::class, 'supplierWiseOrder']);
    });

    Route::middleware(['can:reports.party_reports'])->group(function () {
        Route::get('/party-reports', [PartyReportsController::class, 'partyReports']);
        Route::post('/party-wise-balance-list', [PartyReportsController::class, 'partyWiseBalanceList']);
        Route::get('/all-customer-balance-list', [PartyReportsController::class, 'allCustomerBalanceList']);
        Route::post('/party-last-transcation', [PartyReportsController::class, 'partyLastTranscation']);
    });

    Route::middleware(['can:reports.party_statements'])->group(function () {
        Route::get('/party-statemsent', [PartyReportsController::class, 'partyStatements']);
        Route::post('/generate-party-statement', [PartyReportsController::class, 'generatePartyStatement']);
        Route::post('/date-wise-party-statement', [PartyReportsController::class, 'partyStatementDateWise']);
        Route::post('/generate-account-statement', [PartyReportsController::class, 'generateAccountStatement']);
        Route::post('/generate-account-statement-datewise', [PartyReportsController::class, 'generateAccountStatementDateWise']);
    });

    Route::middleware(['can:reports.summary_reports'])->group(function () {
        Route::get('/summary-reports', [SummaryReportController::class, 'summaryReports']);
        Route::post('/day-summary-report', [SummaryReportController::class, 'daySummary']);
        Route::post('/date-wise-income-statement', [SummaryReportController::class, 'dateWiseIncomeStatement']);
        Route::post('/supplier-wise-summary-report', [SummaryReportController::class, 'supplierWiseSummary']);
    });

    Route::middleware(['can:reports.stock_report'])->group(function () {
        Route::get('/product-stock-reports', [productreportController::class, 'product_stock_reports']);
        Route::post('/all_product-stock-report', [productreportController::class, 'all_product_stock_report']);
        Route::post('/category_wise_product-stock-report', [productreportController::class, 'category_wise_product_stock_report']);
        Route::post('/brand_wise_product-stock-report', [productreportController::class, 'brand_wise_product_stock_report']);
        Route::post('/supplier_wise_product-stock-report', [productreportController::class, 'supplier_wise_product_stock_report']);
        Route::post('/location_wise_product-stock-report', [productreportController::class, 'location_wise_product_stock_report']);
    });

    Route::middleware(['can:reports.sale_report'])->group(function () {
        Route::get('/product-sale-reports', [productreportController::class, 'product_sale_reports']);
        Route::post('/all_product_sale_report', [ProductTypeController::class, 'product_sale_report']);
        Route::post('/date_product_sale_report', [productreportController::class, 'date_product_sale_report']);
        Route::post('/category_wise_product_sale_report', [productreportController::class, 'category_wise_product_sale_report'])->name('category.wise.sale.report');
        Route::post('/invoice_wise_profit_margin', [productreportController::class, 'invoice_wise_profit_margin']);
        Route::post('/date_wise_product_sale_summary', [productreportController::class, 'date_wise_product_sale_summary']);
        Route::post('/brand_wise_product_sale_report', [productreportController::class, 'brand_wise_product_sale_report']);
        Route::post('/customer_wise_product_sale_report', [productreportController::class, 'customer_wise_product_sale_report']);
        Route::post('/location_wise_product_sale_report', [productreportController::class, 'location_wise_product_sale_report']);
        Route::post('/employee_wise_product_sale_report', [productreportController::class, 'employee_wise_product_sale_report']);
        Route::post('/date_wise_profit_margin', [productreportController::class, 'date_wise_profit_margin']);
    });

    Route::middleware(['can:reports.purchase_report'])->group(function () {
        Route::get('/product-purchase-reports', [productreportController::class, 'product_purchase_reports']);
        Route::post('/product_wise_purchase_report', [productreportController::class, 'product_wise_purchase_report']);
        Route::post('/date_wise_purchase_report', [productreportController::class, 'date_wise_purchase_report']);
        Route::post('/brand_wise_purchase_report', [productreportController::class, 'brand_wise_purchase_report']);
        Route::post('/supplier_wise_purchase_report', [productreportController::class, 'supplier_wise_purchase_report']);
        Route::post('/category_wise_purchase_report', [productreportController::class, 'category_wise_purchase_report']);
        Route::post('/location_wise_purchase_report', [productreportController::class, 'location_wise_purchase_report']);
        Route::get('/productshoww', [productreportController::class, 'productshoww']);
        Route::get('/productajax/{id}', [productreportController::class, 'product']);
    });

    Route::middleware(['can:reports.expense_report'])->group(function () {
        Route::get('/product-reports', [ProductTypeController::class, 'product_reports']);
        Route::post('/product-sale-report', [ProductTypeController::class, 'product_sale_report']);
        Route::post('/product-stock-report', [ProductTypeController::class, 'product_stock_report']);
        Route::post('/product-ledger-report', [ProductTypeController::class, 'product_ledger_report']);
        Route::get('/expense-reports', [ExpenseController::class, 'expense_reports']);
        Route::post('/category-wise-expense', [ExpenseController::class, 'category_wise_expense']);
        Route::post('/sub-category-wise-expense', [ExpenseController::class, 'sub_category_wise_expense']);
        Route::get('/print-all-expense', [ExpenseController::class, 'print_all_expense']);
        Route::post('/date-wise-expense', [ExpenseController::class, 'date_wise_expense']);
        Route::post('/cash-account-wise-expense', [ExpenseController::class, 'cash_account_wise_expense']);
        Route::get('/day-book', [ExpenseController::class, 'day_book']);
        Route::post('/day-book', [ExpenseController::class, 'day_book_sub']);
    });

    Route::middleware(['can:reports.payments_recv_report'])->group(function () {
        Route::get('/payments-report', [PaymentReportController::class, 'paymentsReports']);
        Route::post('/date-wise-payment', [PaymentReportController::class, 'dateWisePayment']);
        Route::post('/date-wise-recveive-payments', [PaymentReportController::class, 'dateWiseReceivedPayment']);
    });

    Route::middleware(['can:reports.ledgers_reports'])->group(function () {
        Route::get('/reports-list', [AccountController::class, 'reports_list']);
        Route::get('/ledger-reports', [AccountReportsController::class, 'ledgersReports']);
        Route::post('/print-cash-account-ledeger', [AccountReportsController::class, 'cashAccountLedeger']);
        Route::post('/date-wise-cash-account-ledeger', [AccountReportsController::class, 'dateWiseCashAccountLedeger']);
        Route::post('/print-party-ledeger', [AccountReportsController::class, 'partyLedger']);
        Route::post('/date-wise-party-ledeger', [AccountReportsController::class, 'dateWisePartyledeger']);
        Route::post('/supplier-customer-list-print', [AccountReportsController::class, 'supplierCustomerList']);
        Route::get('/payable-receivable', [AccountReportsController::class, 'payableAndReceivableReport']);
    });
});

// Miscellaneous Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/get-dashboard-card', [DashboardController::class, 'getDashboardCard']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('sessions')
        ->name('session.')
        ->group(function () {
            Route::get('/manage', [SessionController::class, 'index'])->name('manage');
            Route::post('/', [SessionController::class, 'store'])->name('store');
            Route::patch('/{id}/end', [SessionController::class, 'endSession'])->name('end');
        });
    Route::post('/update-quotation-table/{quotationId}', [SaleInvoiceController::class, 'updateQuotationTable'])->name('update.quotation.table');
    Route::get('/get-tables', [SaleInvoiceController::class, 'getTables'])->name('get.tables');


    Route::prefix('backup-restore')
        ->name('backup-restore.')
        // ->middleware('can:backup-restore')
        ->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/download', [BackupController::class, 'download'])->name('download');
            Route::post('/upload', [BackupController::class, 'upload'])->name('upload');
            Route::post('/push-online', [BackupController::class, 'pushOnline'])->name('push-online');
            Route::post('/push-online-seperate', [BackupController::class, 'pushOnlineSeperate'])->name('push-online-seperate');
        });
});

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');
});

// Authentication Routes
require __DIR__ . '/auth.php';

// Settings Routes
require __DIR__ . '/settings.php';
