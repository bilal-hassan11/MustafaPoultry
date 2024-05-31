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

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



class HomeController extends AdminController
{
    public function index()
    {   
        $current_month = date('m');
        
        // Sale Feed
        $tot_sale_feed_begs = FeedInvoice::where('type','Sale')->whereMonth('date', $current_month)->sum('quantity');
        $tot_sale_feed_ammount = FeedInvoice::where('type','Sale')->whereMonth('date', $current_month)->sum('net_amount');
        
        //Purchase Feed
        $tot_purchase_feed_begs = FeedInvoice::where('type','Purchase')->whereMonth('date', $current_month)->sum('quantity');
        $tot_purchase_feed_ammount = FeedInvoice::where('type','Purchase')->whereMonth('date', $current_month)->sum('net_amount');
        //dd($tot_purchase_feed_ammount);
         // Sale Return Feed
         $tot_sale_return_feed_begs = FeedInvoice::where('type','Sale Return')->whereMonth('date', $current_month)->sum('quantity');
         $tot_sale_return_feed_ammount = FeedInvoice::where('type','Sale Return')->whereMonth('date', $current_month)->sum('net_amount');
         
         //Purchase Return Feed
         $tot_purchase_return_feed_begs = FeedInvoice::where('type','Purchase Return')->whereMonth('date', $current_month)->sum('quantity');
         $tot_purchase_return_feed_ammount = FeedInvoice::where('type','Purchase Return')->whereMonth('date', $current_month)->sum('net_amount');
        
        //Medicine
        $tot_sale_medicine_qty = MedicineInvoice::where('type','Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_medicine_ammount = MedicineInvoice::where('type','Sale')->where('date', $current_month)->sum('net_amount');
        
        $tot_purchase_medicine_qty = MedicineInvoice::where('type','Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_medicine_ammount = MedicineInvoice::where('type','Purchase')->where('date', $current_month)->sum('net_amount');


        //Chicks 
        $tot_sale_chick_qty = ChickInvoice::where('type','Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_chick_ammount = ChickInvoice::where('type','Sale')->where('date', $current_month)->sum('net_amount');
        
        $tot_purchase_chick_qty = ChickInvoice::where('type','Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_chick_ammount = ChickInvoice::where('type','Purchase')->where('date', $current_month)->sum('net_amount');


        //Murghi 
        $tot_sale_murghi_qty = MurghiInvoice::where('type','Sale')->where('date', $current_month)->sum('quantity');
        $tot_sale_murghi_ammount = MurghiInvoice::where('type','Sale')->where('date', $current_month)->sum('net_amount');
        
        $tot_purchase_murghi_qty = MurghiInvoice::where('type','Purchase')->where('date', $current_month)->sum('quantity');
        $tot_purchase_murghi_ammount = MurghiInvoice::where('type','Purchase')->where('date', $current_month)->sum('net_amount');

        //Expense
        $tot_expense = Expense::where('date', $current_month)->sum('ammount');

        //CashBook
        $tot_credit = CashBook::where('entry_date', $current_month)->sum('receipt_ammount');
        $tot_debit = CashBook::where('entry_date', $current_month)->sum('payment_ammount');
        $tot_cash_in_hand = $tot_debit - $tot_credit ;


        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        
        // $Item  = Item::where('status','1')->latest()->get();
        // foreach($Item as $i){
        //     $available_item[] = $i['name'];
        //     $available_stock[] = $i['stock_qty'];
        // }
        // $labels = [$available_item];
        // $price = [$available_stock];
        //dd($available_item);
        
        // //return view('showMap',['labels' => $label, 'prices' => $price]);
        // $get = Consumption::with(['item'])->select(DB::raw("SUM(qunantity) as qty"))
        //         ->groupBy('item_id')
        //        ->get();
        //    ;
        // //dd($get);
        
       
        // $sale_array     = [0,0,0,0,0,0,0,0,0,0,0,0];
        // $sale_bag_array = [0,0,0,0,0,0,0,0,0,0,0,0];
        // $consumption_array     = [0,0,0,0,0,0,0,0,0,0,0,0];
        // $consumption_qty = [0,0,0,0,0,0,0,0,0,0,0,0];
        

        // foreach($sale->pluck('month') AS $index=>$month){
        //     $sale_array[$month-1]     = $sale->pluck('count')[$index];
        //     $sale_bag_array[$month-1] = intVal($sale->pluck('bag')[$index]);
        // }

        // foreach($consumption->pluck('month') AS $index=>$month){
        //     $consumption_array[$month-1]     = $consumption->pluck('count')[$index];
        //     $consumption_qty[$month-1] = intVal($consumption->pluck('qty')[$index]);
        // }
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
            

            'tot_purchase_medicine_qty' => $tot_sale_medicine_qty,
            'tot_purchase_medicine_ammount' => $tot_sale_medicine_ammount,
            'tot_purchase_medicine_qty' => $tot_purchase_medicine_qty,
            'tot_purchase_medicine_ammount' => $tot_purchase_medicine_ammount,
            
            'tot_sale_chick_qty' => $tot_sale_chick_qty,
            'tot_sale_chick_ammount' => $tot_sale_chick_ammount,
            'tot_purchase_chick_qty' => $tot_purchase_chick_qty,
            'tot_purchase_chick_ammount' => $tot_purchase_chick_ammount,

            'tot_sale_murghi_qty' => $tot_sale_murghi_qty,
            'tot_sale_murghi_ammount' => $tot_sale_murghi_ammount,
            'tot_purchase_murghi_qty' => $tot_purchase_murghi_qty,
            'tot_purchase_murghi_ammount' => $tot_purchase_murghi_ammount,
            

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

    public function web(){
        return view('admin.web');
    }

}