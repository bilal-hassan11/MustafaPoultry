<?php

use Illuminate\Http\Request;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Administrator\AccountTypeController;
use App\Http\Controllers\Administrator\CategoryController;
use App\Http\Controllers\SaleMurghiController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\Administrator\HomeController;
use App\Http\Controllers\Administrator\StaffController;
use App\Http\Controllers\Administrator\ItemController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\ReportController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CronJobController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Administrator\PaymentController;

use App\Http\Controllers\MedicineInvoiceController;
use App\Http\Controllers\FeedInvoiceController;
use App\Http\Controllers\ChickInvoiceController;
use App\Http\Controllers\MurghiInvoiceController;
use App\Http\Controllers\OtherInvoiceController;

use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => false, 'register' => true]);

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});


Route::middleware('auth:admin')->name('admin.')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    //Artisan Commands
    Route::prefix('artisan/command')->controller(ArtisanController::class)->name('artisan.command.')->group(function () {
        Route::get('/config_cache', 'config_cache')->name('config_cache');
        Route::get('/config_cache_clear', 'config_cache_clear')->name('config_cache_clear');
        Route::get('/route_cache', 'route_cache')->name('route_cache');
        Route::get('/route_cache_clear', 'route_cache_clear')->name('route_cache_clear');
        Route::get('/cache_clear', 'cache_clear')->name('cache_clear');
        Route::get('/route_list', 'route_list')->name('route_list');
        Route::get('/migrate', 'migrate')->name('migrate');
    });

    //Staffs routes
    Route::controller(StaffController::class)->prefix('staffs')->name('staffs.')->group(function () {
        Route::get('/', 'index')->name('all');
        Route::get('/add', 'add')->name('add');
        Route::get('/edit/{staff_id}', 'edit')->name('edit');
        Route::post('/save', 'save')->name('save');
        Route::post('/update_password', 'update_password')->name('update_password');
        Route::get('/update-status/{staff_id}', 'updateStatus')->name('update_status');
        Route::delete('/delete/{staff_id}', 'delete')->name('delete');

        //profile pages
        Route::get('/update-profile', 'update_profile')->name('update_profile');
        Route::post('/save-profile', 'save_profile')->name('save_profile');
        Route::post('/change-password', 'change_password')->name('change_password');
    });

    //permission routes
    Route::controller(PermissionController::class)->prefix('permission')->name('permissions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/save', 'save')->name('save');
        Route::get('/delete/{permission_id}', 'delete')->name('delete');
    });

    //account type routes
    Route::controller(AccountTypeController::class)->prefix('account-type')->name('account_types.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //account routes
    Route::controller(AccountController::class)->prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add/{grand_parent_id}/{parent_id}', 'add')->name('add');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //Payment
    Route::controller(PaymentController::class)->prefix('paymentbook')->name('paymentbooks.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //category routes
    Route::controller(CategoryController::class)->prefix('category')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //Item routes
    Route::controller(ItemController::class)->prefix('items')->name('items.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    //Cash Book routes
    Route::controller(CashController::class)->prefix('cash')->name('cash.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/get-parent-accounts/{id}', 'getParentAccounts')->name('get_parent_accounts');
    });

    //report routes
    Route::controller(ReportController::class)->prefix('report')->name('reports.')->group(function () {

        Route::get('/Reports/{id}', 'all_report')->name('all_report');

        Route::get('/AllReports', 'all_reports_request')->name('all_reports_request');
        Route::get('/AllReportsPdf', 'all_reports_pdf')->name('all_reports_pdf');

        Route::get('/item_stock_report', 'item_stock_report')->name('item_stock_report');
        Route::get('/Feed-Stock-Report', 'feed_item_wise_stock_report')->name('feed_item_wise_stock_report');
        Route::get('/Chick-Stock-Report', 'chick_item_wise_stock_report')->name('chick_item_wise_stock_report');
        Route::get('/Murghi-Stock-Report', 'murghi_item_wise_stock_report')->name('murghi_item_wise_stock_report');
        Route::get('/Medicine-Stock-Report', 'medicine_item_wise_stock_report')->name('medicine_item_wise_stock_report');


        Route::get('/CashFlow', 'cashflowReport')->name('cashflowreport');
        Route::get('/CashFlowPdf', 'cashflowReportPdf')->name('cashflowreportpdf');

        Route::get('/DayBook', 'DayBookReport')->name('daybook_report');
        Route::get('/DayBookPdf', 'DayBookPdf')->name('DayBookPdf');

        Route::get('/All_Accounts_Report', 'all_accounts_report_request')->name('all_accounts_report_request');

        Route::get('/Arti-Ledger', 'arti_accounts_report')->name('arti_accounts_report');

        Route::get('/Feed_P', 'detailpp')->name('feedsreport');

        Route::get('/Feed_Purchase', 'feedPurchaseReport')->name('feed_purchase_report');
        Route::get('/Feed_Sale', 'feedSaleReport')->name('feed_sale_report');

        Route::get('/Chick_Purchase', 'chickPurchaseReport')->name('chick_purchase_report');
        Route::get('/Chick_Sale', 'chickSaleReport')->name('chick_sale_report');

        Route::get('/Medicine_Purchase', 'MedicinePurchaseReport')->name('medicine_purchase_report');
        Route::get('/Medicine_Sale', 'MedicineSaleReport')->name('medicine_sale_report');
        Route::get('/Medicine_Sale_Pdf', 'MedicineSaleReportPdf')->name('medicine_sale_pdf');

        Route::get('/Medicine_Return', 'MedicineReturnReport')->name('medicine_return_report');

        Route::get('/Medicine_Item_Report', 'medicine_item_report')->name('medicine_item_report');
        Route::get('/Medicine_Expire', 'MedicineExpireReport')->name('medicine_expire_report');

        Route::get('/DayBook', 'DayBookReport')->name('daybook_report');

        Route::get('/All_Account-Ledger', 'accounts_head_report')->name('accounts_head_report');

        Route::get('/item', 'itemReport')->name('item');
        Route::get('/item-pdf', 'itemReportPdf')->name('item_pdf');
        Route::get('/item-print', 'itemReportPrint')->name('item_print');

        Route::get('/account', 'accountReport')->name('account');
        Route::get('/account-pdf', 'accountReportPdf')->name('account_pdf');
        Route::get('/purchase-pdf', 'PurchaseReportPdf')->name('purchase_pdf');
        Route::get('/sale-pdf', 'SaleReportPdf')->name('sale_pdf');
        Route::get('/sale-print', 'SaleReportPrint')->name('sale_print');

        Route::get('/Medicine_Accounts', 'medicine_account_report')->name('medicine_account_report');

        Route::get('/purchase-book', 'purchaseBookReport')->name('purchase_book');
        Route::get('/sale-book', 'saleBookReport')->name('sale_book');
        Route::get('/General_Medicine_Stock_Report', 'general_medicine_item_report')->name('general_medicine_item_report');
    });

    //common functions routes
    Route::controller(CommonController::class)->name('common.')->group(function () {
        Route::get('/get-parent-accounts/{id}', 'getParentAccounts')->name('get_parent_account');
        Route::get('/companies/{id}', 'get_companies')->name('companies');
        Route::get('/items/{id}', 'get_items')->name('items');
        Route::get('/get_items', 'get_all_items')->name('get_items');

        Route::get('/latest-sale/{category}', 'getLatestSale')->name('latest.sale');

        Route::get('/flocks/{id}', 'get_flocks')->name('flocks');
    });

    //Company 
    Route::controller(CompanyController::class)->prefix('company')->name('companys.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/delete/{id}', 'delete')->name('delete');
        Route::get('/type', 'type')->name('type');
        Route::post('/typestore', 'storetype')->name('store_type');
        Route::get('/typeedit/{id}', 'typeedit')->name('edit_type');
        Route::get('/typedelete/{id}', 'typedelete')->name('typedelete');
    });


    Route::controller(MedicineInvoiceController::class)->prefix('medicine-invoices')->name('medicine-invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sale', 'createSale')->name('sale');
        Route::get('/purchase', 'createPurchase')->name('purchase');
        Route::get('/purchase/edit/{invoice_no}', 'editPurchase')->name('edit.purchase');
        Route::get('/purchase/{invoice_no}', 'show')->name('purchase.show');
        Route::get('/sale/{invoice_no}', 'show')->name('sale.show');
        Route::get('/sale/edit/{invoice_no}', 'editSale')->name('edit.sale');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-sale', 'storeSale')->name('store-sale');
        Route::post('/return', 'singleReturn')->name('single-return');
    });

    Route::controller(StockController::class)->prefix('stock')->name('stock.')->group(function () {
        Route::get('/available-stock', [StockController::class, 'index'])->name('index');
        Route::get('/stock/filter', [StockController::class, 'filter'])->name('filter');
        Route::get('/items/by-category', [StockController::class, 'getItemsByCategory'])->name('items.byCategory');
        Route::get('expiry-stock', [StockController::class, 'expiryStockReport'])->name('expiry_stock_report');
        Route::get('/low-stock-report', [StockController::class, 'lowStockReport'])->name('low_stock_report');
        Route::get('/max-selling-report', [StockController::class, 'maxSellingReport'])->name('max_selling_report');
        Route::get('low-selling-report', [StockController::class, 'lowSellingReport'])->name('low_selling_report');
    });



    Route::controller(FeedInvoiceController::class)->prefix('feed-invoices')->name('feed-invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sale', 'createSale')->name('sale');
        Route::get('/purchase', 'createPurchase')->name('purchase');
        Route::get('/purchase/edit/{invoice_no}', 'editPurchase')->name('edit.purchase');
        Route::get('/purchase/{invoice_no}', 'show')->name('purchase.show');
        Route::get('/sale/{invoice_no}', 'show')->name('sale.show');
        Route::get('/sale/edit/{invoice_no}', 'editSale')->name('edit.sale');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-sale', 'storeSale')->name('store-sale');
        Route::post('/return', 'singleReturn')->name('single-return');
    });

    Route::controller(ChickInvoiceController::class)->prefix('chick-invoices')->name('chick-invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sale', 'createSale')->name('sale');
        Route::get('/purchase', 'createPurchase')->name('purchase');
        Route::get('/purchase/edit/{invoice_no}', 'editPurchase')->name('edit.purchase');
        Route::get('/purchase/{invoice_no}', 'show')->name('purchase.show');
        Route::get('/sale/{invoice_no}', 'show')->name('sale.show');
        Route::get('/sale/edit/{invoice_no}', 'editSale')->name('edit.sale');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-sale', 'storeSale')->name('store-sale');
        Route::post('/return', 'singleReturn')->name('single-return');
    });

    Route::controller(MurghiInvoiceController::class)->prefix('murghi-invoices')->name('murghi-invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sale', 'createSale')->name('sale');
        Route::get('/purchase', 'createPurchase')->name('purchase');
        Route::get('/purchase/edit/{invoice_no}', 'editPurchase')->name('edit.purchase');
        Route::get('/purchase/{invoice_no}', 'show')->name('purchase.show');
        Route::get('/sale/{invoice_no}', 'show')->name('sale.show');
        Route::get('/sale/edit/{invoice_no}', 'editSale')->name('edit.sale');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-sale', 'storeSale')->name('store-sale');
        Route::post('/return', 'singleReturn')->name('single-return');
    });

    Route::controller(OtherInvoiceController::class)->prefix('other-invoices')->name('other-invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sale', 'createSale')->name('sale');
        Route::get('/purchase', 'createPurchase')->name('purchase');
        Route::get('/purchase/edit/{invoice_no}', 'editPurchase')->name('edit.purchase');
        Route::get('/purchase/{invoice_no}', 'show')->name('purchase.show');
        Route::get('/sale/{invoice_no}', 'show')->name('sale.show');
        Route::get('/sale/edit/{invoice_no}', 'editSale')->name('edit.sale');
        Route::post('/store', 'store')->name('store');
        Route::post('/store-sale', 'storeSale')->name('store-sale');
        Route::post('/return', 'singleReturn')->name('single-return');
    });


});


Route::prefix('cronjobs')->group(function () {
    Route::get('/{method}', [CronJobController::class, 'index']);
});
