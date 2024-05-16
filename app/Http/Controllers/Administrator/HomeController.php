<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Consumption;
use App\Models\SaleBook;
use App\Models\SaleFeed;
use App\Models\PurchaseFeed;
use App\Models\ReturnFeed;

use App\Models\SaleMedicine;
use App\Models\SaleChick;
use App\Models\PurchaseChick;
use App\Models\PurchaseMurghi;
use App\Models\SaleMurghi;
use App\Models\PurchaseBook;
use App\Models\PurchaseMedicine;
use App\Models\Item;
use App\Models\Inward;
use App\Models\Outward;
use App\Models\Formulation;
use App\Models\Account;
use App\Models\Staff;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



class HomeController extends AdminController
{
    public function index()
    {   
            //  $accounts = Account::where('account_nature','credit')->orderBy("id", "asc")->latest()->get();
            
        //dd("dfds");
            
        $current_month = date('Y-m-d');
        
        $tot_sale_feed_begs = SaleFeed::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_sale_feed_ammount = SaleFeed::where('date', $current_month)->latest()->get()->sum('net_ammount');
        
        $tot_Return_feed_begs = ReturnFeed::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_Return_feed_ammount = ReturnFeed::where('date', $current_month)->latest()->get()->sum('net_ammount');
        

        $tot_purchase_feed_begs = PurchaseFeed::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_purchase_feed_ammount = PurchaseFeed::where('date', $current_month)->latest()->get()->sum('net_ammount');
        
        //Medicine
        $tot_sale_medicine_qty = SaleMedicine::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_sale_medicine_ammount = SaleMedicine::where('date', $current_month)->latest()->get()->sum('net_ammount');
        
        $tot_purchase_medicine_qty = PurchaseMedicine::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_purchase_medicine_ammount = PurchaseMedicine::where('date', $current_month)->latest()->get()->sum('net_ammount');


        //Chicks 
        $tot_sale_chick_qty = SaleChick::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_sale_chick_ammount = SaleChick::where('date', $current_month)->latest()->get()->sum('net_ammount');
        
        $tot_purchase_chick_qty = PurchaseChick::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_purchase_chick_ammount = PurchaseChick::where('date', $current_month)->latest()->get()->sum('net_ammount');


        //Murghi 
        $tot_sale_murghi_qty = SaleMurghi::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_sale_murghi_ammount = SaleMurghi::where('date', $current_month)->latest()->get()->sum('net_ammount');
        
        $tot_purchase_murghi_qty = PurchaseMurghi::where('date', $current_month)->latest()->get()->sum('quantity');
        $tot_purchase_murghi_ammount = PurchaseMurghi::where('date', $current_month)->latest()->get()->sum('net_ammount');




        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();
        //dd($expire_medicine);
        $sale = SaleBook::select(DB::raw("COUNT(*) as count, Month(date) as month, SUM(no_of_bags) as bag"))
                ->whereYear('date', date('Y'))
                ->groupBy(DB::raw("Month(date)"))
                ->get();


        $Item  = Item::where('status','1')->latest()->get();
        foreach($Item as $i){
            $available_item[] = $i['name'];
            $available_stock[] = $i['stock_qty'];
        }
        $labels = [$available_item];
        $price = [$available_stock];
        //dd($available_item);
        
        // //return view('showMap',['labels' => $label, 'prices' => $price]);
        // $get = Consumption::with(['item'])->select(DB::raw("SUM(qunantity) as qty"))
        //         ->groupBy('item_id')
        //        ->get();
        //    ;
        // //dd($get);
        
        $consumption = Consumption::select(DB::raw("COUNT(*) as count, Month(date) as month, SUM(qunantity) as qty"))
        ->whereYear('date', date('Y'))
        ->groupBy(DB::raw("Month(date)"))
        ->get();
        
        $sale_array     = [0,0,0,0,0,0,0,0,0,0,0,0];
        $sale_bag_array = [0,0,0,0,0,0,0,0,0,0,0,0];
        $consumption_array     = [0,0,0,0,0,0,0,0,0,0,0,0];
        $consumption_qty = [0,0,0,0,0,0,0,0,0,0,0,0];
        

        foreach($sale->pluck('month') AS $index=>$month){
            $sale_array[$month-1]     = $sale->pluck('count')[$index];
            $sale_bag_array[$month-1] = intVal($sale->pluck('bag')[$index]);
        }

        foreach($consumption->pluck('month') AS $index=>$month){
            $consumption_array[$month-1]     = $consumption->pluck('count')[$index];
            $consumption_qty[$month-1] = intVal($consumption->pluck('qty')[$index]);
        }
        $month = date('m');
        $data = array(
            "title"     => "Dashboad",
            'sale'      => $sale_array,
            // 'cr'        =>$accounts,
            // 'dr'        =>$dr_accounts,
            'sale_bags' => $sale_bag_array,
            'tot_sale_feed_begs' => $tot_sale_feed_begs,
            'tot_sale_feed_ammount' => $tot_sale_feed_ammount,
            'tot_Return_feed_begs' => $tot_Return_feed_begs,
            'tot_Return_feed_ammount' => $tot_Return_feed_ammount,
            
            'tot_purchase_feed_begs' => $tot_purchase_feed_begs,
            'tot_purchase_feed_ammount' => $tot_purchase_feed_ammount,
            
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
            

            'consumption' => $consumption_array,
            'consumption_qty' =>   $consumption_qty,
            'labels' => $labels,
            'prices' => $price,
            'expire_medicine' => $expire_medicine, 
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