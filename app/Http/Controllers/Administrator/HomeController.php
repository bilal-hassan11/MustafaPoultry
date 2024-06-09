<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Item;
use App\Models\Account;
use App\Models\Staff;
use App\Models\FeedInvoice;
use App\Models\ChickInvoice;
use App\Models\MurghiInvoice;
use App\Models\MedicineInvoice;
use App\Models\CashBook;
use App\Models\Expense;
use Carbon\Carbon;

class HomeController extends AdminController
{
    public function index()
    {
        $current_month = date('m');

        // Sale Feed
        $tot_sale_feed_begs = FeedInvoice::where('type', 'Sale')->whereMonth('date', $current_month)->sum('quantity');
        $tot_sale_feed_ammount = FeedInvoice::where('type', 'Sale')->whereMonth('date', $current_month)->sum('net_amount');

        //Purchase Feed
        $tot_purchase_feed_begs = FeedInvoice::where('type', 'Purchase')->whereMonth('date', $current_month)->sum('quantity');
        $tot_purchase_feed_ammount = FeedInvoice::where('type', 'Purchase')->whereMonth('date', $current_month)->sum('net_amount');

        // Sale Return Feed
        $tot_sale_return_feed_begs = FeedInvoice::where('type', 'Sale Return')->whereMonth('date', $current_month)->sum('quantity');
        $tot_sale_return_feed_ammount = FeedInvoice::where('type', 'Sale Return')->whereMonth('date', $current_month)->sum('net_amount');

        //Purchase Return Feed
        $tot_purchase_return_feed_begs = FeedInvoice::where('type', 'Purchase Return')->whereMonth('date', $current_month)->sum('quantity');
        $tot_purchase_return_feed_ammount = FeedInvoice::where('type', 'Purchase Return')->whereMonth('date', $current_month)->sum('net_amount');

        //Medicine
        $tot_sale_medicine_qty = MedicineInvoice::where('type', 'Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_medicine_ammount = MedicineInvoice::where('type', 'Sale')->where('date', $current_month)->sum('net_amount');

        $tot_purchase_medicine_qty = MedicineInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_medicine_ammount = MedicineInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('net_amount');
        // Sale Return Medicine
        $tot_sale_return_medicine_qty = MedicineInvoice::where('type', 'Sale Return')->whereMonth('date', $current_month)->sum('quantity');
        $tot_sale_return_medicine_ammount = MedicineInvoice::where('type', 'Sale Return')->whereMonth('date', $current_month)->sum('net_amount');

        //Purchase Return Medicine
        $tot_purchase_return_medicine_qty = MedicineInvoice::where('type', 'Purchase Return')->whereMonth('date', $current_month)->sum('quantity');
        $tot_purchase_return_medicine_ammount = MedicineInvoice::where('type', 'Purchase Return')->whereMonth('date', $current_month)->sum('net_amount');


        //Chicks 
        $tot_sale_chick_qty = ChickInvoice::where('type', 'Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_chick_ammount = ChickInvoice::where('type', 'Sale')->where('date', $current_month)->sum('net_amount');

        $tot_purchase_chick_qty = ChickInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_chick_ammount = ChickInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('net_amount');


        //Murghi 
        $tot_sale_murghi_qty = MurghiInvoice::where('type', 'Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_murghi_ammount = MurghiInvoice::where('type', 'Sale')->where('date', $current_month)->sum('net_amount');

        $tot_purchase_murghi_qty = MurghiInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_murghi_ammount = MurghiInvoice::where('type', 'Purchase')->where('date', $current_month)->sum('net_amount');

        //Expense
        $tot_expense = Expense::where('date', $current_month)->sum('ammount');

        //CashBook
        $tot_credit = CashBook::where('entry_date', $current_month)->sum('receipt_ammount');
        $tot_debit = CashBook::where('entry_date', $current_month)->sum('payment_ammount');
        $tot_cash_in_hand = $tot_debit - $tot_credit;


        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();

        $maxSellingProducts = MedicineInvoice::with('item')
            ->where('type', 'Sale')
            ->groupBy('item_id')
            ->selectRaw('item_id, sum(quantity) as total_quantity')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();


        $lowSellingProducts = MedicineInvoice::with('item')
            ->where('type', 'Sale')
            ->groupBy('item_id')
            ->selectRaw('item_id, sum(quantity) as total_quantity')
            ->orderBy('total_quantity')
            ->take(10)
            ->get();

        $medicineInvoice = new MedicineInvoice();
        $stockInfo = $medicineInvoice->getStockInfo();
        $lowStockAlertProducts = $medicineInvoice->filterLowStock($stockInfo);
        $expiredStock = $medicineInvoice->filterNearExpiryStock($stockInfo, 3);

        $month = date('m');
        $data = array(
            "title"     => "Dashboad",
            // 'sale'      => $sale_array,

            // 'sale_bags' => $sale_bag_array,

            'tot_sale_feed_begs' => $tot_sale_feed_begs,
            'tot_sale_feed_ammount' => $tot_sale_feed_ammount,

            'tot_purchase_feed_begs' => $tot_purchase_feed_begs,
            'tot_purchase_feed_ammount' => $tot_purchase_feed_ammount,

            'tot_sale_return_feed_begs' => $tot_sale_return_feed_begs,
            'tot_sale_return_feed_ammount' => $tot_sale_return_feed_ammount,

            'tot_purchase_return_feed_begs' => $tot_purchase_return_feed_begs,
            'tot_purchase_return_feed_ammount' => $tot_purchase_return_feed_ammount,


            'tot_sale_medicine_qty' => $tot_sale_medicine_qty,
            'tot_sale_medicine_ammount' => $tot_sale_medicine_ammount,
            'tot_purchase_medicine_qty' => $tot_purchase_medicine_qty,
            'tot_purchase_medicine_ammount' => $tot_purchase_medicine_ammount,

            'tot_sale_return_medicine_qty' => $tot_sale_return_medicine_qty,
            'tot_sale_return_medicine_ammount' => $tot_sale_return_medicine_ammount,
            'tot_purchase_return_medicine_qty' => $tot_purchase_return_medicine_qty,
            'tot_purchase_return_medicine_ammount' => $tot_purchase_return_medicine_ammount,

            'tot_sale_chick_qty' => $tot_sale_chick_qty,
            'tot_sale_chick_ammount' => $tot_sale_chick_ammount,
            'tot_purchase_chick_qty' => $tot_purchase_chick_qty,
            'tot_purchase_chick_ammount' => $tot_purchase_chick_ammount,

            'tot_sale_murghi_qty' => $tot_sale_murghi_qty,
            'tot_sale_murghi_ammount' => $tot_sale_murghi_ammount,
            'tot_purchase_murghi_qty' => $tot_purchase_murghi_qty,
            'tot_purchase_murghi_ammount' => $tot_purchase_murghi_ammount,

            'lowStockAlertProducts' => $lowStockAlertProducts,
            'expired_items' => $expiredStock,
            'maxSellingProducts' => $maxSellingProducts,
            'lowSellingProducts' => $lowSellingProducts,
            // 'consumption' => $consumption_array,
            // 'consumption_qty' =>   $consumption_qty,
            // 'labels' => $labels,
            // 'prices' => $price,
            // 'expire_medicine' => $expire_medicine, 
            'active_item'  => Item::where('status', '1')->latest()->get()->count(),
            'active_accounts'  => Account::where('status', '1')->latest()->get()->count(),
            'active_users'  => Staff::where('is_active', '1')->latest()->get()->count(),




        );
        //dd($data);
        return view('admin.home')->with($data);
    }

    public function web()
    {
        return view('admin.web');
    }
}
