<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Consumption;
use App\Models\SaleBook;
use App\Models\PurchaseBook;
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
            'sale_bags' => $sale_bag_array,
            'consumption' => $consumption_array,
            'consumption_qty' =>   $consumption_qty,
            'labels' => $labels,
            'prices' => $price,
            'total_sales' => SaleBook::with(['item', 'account'])->whereMonth('created_at', '=', $month)->orderBy('created_at', 'desc')->take(10)->latest()->get(),
            'total_purchases' => PurchaseBook::with(['item', 'account'])->whereMonth('created_at', '=', $month)->orderBy('created_at', 'desc')->take(10)->latest()->get(),
            'active_item'  => Item::where('status', '1')->latest()->get()->count(),
            'item_consumption' => Consumption::sum('qunantity'),
            'inward_vehicle_daily' => Inward::where('created_at',date('Y-m-d'))->get()->count(),
            'inward_vehicle_monthly' => Inward::where('created_at',date('m'))->get()->count(),
            'outward_vehicle_daily' => Outward::where('created_at',date('Y-m-d'))->get()->count(),
            'outward_vehicle_monthly' => Outward::where('created_at',date('m'))->get()->count(),
            'waiting_list'  => Outward::where('vehicle_status', 'pending')->latest()->get()->count(),
            'item_formulas'  => Formulation::latest()->get()->count(),
            'item_purchase' => PurchaseBook::sum('net_weight'),
            'item_purchase_ammount' => PurchaseBook::sum('net_ammount'),
            'active_accounts'  => Account::where('status', '1')->latest()->get()->count(),
            'active_users'  => Staff::where('is_active', '1')->latest()->get()->count(),

            


        );
       //dd($data['labels']);
        return view('admin.home')->with($data);
    }

    public function web(){
        return view('admin.web');
    }

}