<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\AccountLedger;
use App\Models\OutwardDetail;
use App\Models\PurchaseMedicine;
use App\Models\SaleMedicine;
use App\Models\ReturnMedicine;
use App\Models\ExpireMedicine;
use App\Models\PurchaseFeed;
use App\Models\SaleFeed;
use App\Models\ReturnFeed;
use App\Models\PurchaseChick;
use App\Models\SaleChick;
use App\Models\PurchaseMurghi;
use App\Models\SaleMurghi;
use App\Models\Expense;
use App\Models\SaleBook;
use App\Models\CashBook;
use App\Models\AccountType;
use App\Models\Inward;
use App\Models\Outward;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function feed_item_wise_stock_report(Request $req){
       
        if(isset($req->to_date)){
            //dd($req->all());
            
           $from_date = $req->from_date;
           $to_date = $req->to_date;

           $purchase_opening = PurchaseFeed::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $sale_opening = SaleFeed::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $return_opening = ReturnFeed::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');

           $opening_stock = $purchase_opening + $return_opening - $sale_opening ;
            $data = array(
                'title' => 'Item Report',
                'items' => Item::where('category_id',3)->latest()->get(), 
                'item_opening' =>  $opening_stock ,
                'is_update'=> true,
                'item_name'=> true,
                'item' => Item::where('id', hashids_decode($req->item_id))->latest()->get(), 
                'purchase_feed'   => PurchaseFeed::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);})->latest()->get(),
                'sale_feed'      => SaleFeed::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                                                                                    $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                                                                                })->latest()->get(),
                'return_feed' => ReturnFeed::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                    $query->where('item_id', hashids_decode($req->item_id));
                })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                        })->latest()->get(),
                
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );

        }else{
            
            $from_date = date('d-m-Y');
           $to_date = date('d-m-Y');
           
           $items = Item::withSum('sale_feed', 'quantity')->withSum('purchase_feed', 'quantity')->withSum('return_feed', 'quantity')
           ->where('category_id',3)->get();

           $data = array(
                'title' => 'Item Report',
                'items' => Item::where('category_id',3)->latest()->get(),
                'item_name'=> false,
                'is_update'=> false,
                'item_opening' =>  0 ,
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );
            
        }
       return view('admin.report.feed_stock_wise_report')->with($data);
    }

    public function chick_item_wise_stock_report(Request $req){
        
        if(isset($req->to_date)){
            //dd($req->all());
            
           $from_date = $req->from_date;
           $to_date = $req->to_date;

           $purchase_opening = PurchaseChick::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $sale_opening = SaleChick::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           
           $opening_stock = $purchase_opening  - $sale_opening ;
            $data = array(
                'title' => 'Chick Stock Report',
                'items' => Item::where('category_id',2)->latest()->get(), 
                'item_opening' =>  $opening_stock ,
                'is_update'=> true,
                'item_name'=> true,
                'item' => Item::where('id', hashids_decode($req->item_id))->latest()->get(), 
                'purchase_chick'   => PurchaseChick::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);})->latest()->get(),
                'sale_chick'      => SaleChick::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                                                                                    $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                                                                                })->latest()->get(),
                
                
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );

        }else{
            
            $from_date = date('d-m-Y');
           $to_date = date('d-m-Y');
           
           $items = Item::withSum('sale_feed', 'quantity')->withSum('purchase_feed', 'quantity')->withSum('return_feed', 'quantity')
           ->where('category_id',2)->get();

           $data = array(
                'title' => 'Chick Stock Report',
                'items' => Item::where('category_id',2)->latest()->get(),
                'item_name'=> false,
                'is_update'=> false,
                'item_opening' =>  0 ,
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );
            
        }

       return view('admin.report.chick_stock_wise_report')->with($data);
    }

    public function murghi_item_wise_stock_report(Request $req){
        
        if(isset($req->to_date)){
            
           $from_date = $req->from_date;
           $to_date = $req->to_date;

           $purchase_opening = PurchaseMurghi::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $sale_opening = SaleMurghi::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           
           $opening_stock = $purchase_opening  - $sale_opening ;
            $data = array(
                'title' => 'Murghi Stock Report',
                'items' => Item::where('category_id',8)->latest()->get(), 
                'item_opening' =>  $opening_stock ,
                'is_update'=> true,
                'item_name'=> true,
                'item' => Item::where('id', hashids_decode($req->item_id))->latest()->get(), 
                'purchase_murghi'   => PurchaseMurghi::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);})->latest()->get(),
                'sale_murghi'      => SaleMurghi::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                                                                                    $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                                                                                })->latest()->get(),
                
                
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );

        }else{
            
            $from_date = date('d-m-Y');
           $to_date = date('d-m-Y');
           
           $items = Item::withSum('sale_feed', 'quantity')->withSum('purchase_feed', 'quantity')->withSum('return_feed', 'quantity')
           ->where('category_id',2)->get();

           $data = array(
                'title' => 'Murghi Stock Report',
                'items' => Item::where('category_id',8)->latest()->get(),
                'item_name'=> false,
                'is_update'=> false,
                'item_opening' =>  0 ,
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );
            
        }

       return view('admin.report.murghi_stock_wise_report')->with($data);
    }

    public function medicine_item_wise_stock_report(Request $req){
        
        if(isset($req->to_date)){
            //dd($req->all());
            
           $from_date = $req->from_date;
           $to_date = $req->to_date;

           $purchase_opening = PurchaseMedicine::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $sale_opening = SaleMedicine::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');
           $return_opening = ReturnMedicine::with(['item','account'])->where('item_id', hashids_decode($req->item_id))->whereDate('date','<', $req->from_date)->sum('quantity');

           $opening_stock = $purchase_opening + $return_opening - $sale_opening ;
            $data = array(
                'title' => 'Medicine Item Stock',
                'items' => Item::where('category_id',4)->latest()->get(), 
                'item_opening' =>  $opening_stock ,
                'is_update'=> true,
                'item_name'=> true,
                'item' => Item::where('id', hashids_decode($req->item_id))->latest()->get(), 
                'purchase_medicine'   => PurchaseMedicine::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);})->latest()->get(),
                'sale_medicine'      => SaleMedicine::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                                                            $query->where('item_id', hashids_decode($req->item_id));
                                                                            })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                                                                                    $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                                                                                })->latest()->get(),
                'return_medicine' => ReturnMedicine::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                    $query->where('item_id', hashids_decode($req->item_id));
                })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                                        })->latest()->get(),
                
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );

        }else{
            
            $from_date = date('d-m-Y');
           $to_date = date('d-m-Y');
           
           $items = Item::withSum('sale_feed', 'quantity')->withSum('purchase_feed', 'quantity')->withSum('return_feed', 'quantity')
           ->where('category_id',3)->get();

           $data = array(
                'title' => 'Medicine Item Stock Report',
                'items' => Item::where('category_id',4)->latest()->get(),
                'item_name'=> false,
                'is_update'=> false,
                'item_opening' =>  0 ,
                'from_date' => $from_date,
                'to_date' => $to_date,
                

            );
            
        }
       return view('admin.report.medicine_stock_wise_report')->with($data);
    }
    
    public function item_stock_report(Request $req){
        
        if(isset($req->to_date)){

           $from_date = $req->from_date;
           $to_date = $req->to_date;
           
           $items = Item::select(
               'items.*',
               DB::raw("(SELECT SUM(sale_feed.quantity) FROM sale_feed WHERE items.id = sale_feed.item_id AND sale_feed.date BETWEEN ? AND ?) AS sale_feed_sum_quantity"),
               DB::raw("(SELECT SUM(purchase_feed.quantity) FROM purchase_feed WHERE items.id = purchase_feed.item_id AND purchase_feed.date BETWEEN ? AND ?) AS purchase_feed_sum_quantity"),
               DB::raw("(SELECT SUM(return_feed.quantity) FROM return_feed WHERE items.id = return_feed.item_id AND return_feed.date BETWEEN ? AND ?) AS return_feed_sum_quantity")
            
               )
               ->where('type', $type)
               ->addBinding($from_date, 'select')
               ->addBinding($to_date, 'select')
               ->addBinding($from_date, 'select')
               ->addBinding($to_date, 'select')
               ->where('category_id',3)
               ->get();

        }else{
            
           $from_date = date('Y-m-d');
           $to_date = date('Y-m-d');
           
           $items = Item::withSum('sale_feed', 'quantity')->withSum('purchase_feed', 'quantity')->withSum('return_feed', 'quantity')
           ->where('category_id',3)->get();
            
        }
        
        $data = array(
            'title' => 'Item Report',
            'items' => $items,
            'is_update'=> true,
            'from_date' => $from_date,
            'to_date' => $to_date,
            

        );
        
       return view('admin.report.item_stock_report')->with($data);
    
    }

    public function DayBookReport(Request $req){
        
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        

        if(isset($req->from_date)){
            
           
            
            //get Opening
            $c_cash_credit  = CashBook::whereDate('date', '<', $req->from_date)->sum('receipt_ammount');
            $c_cash_debit  = CashBook::whereDate('date', '<', $req->from_date)->sum('payment_ammount');
            $c_ex  = Expense::whereDate('date', '<', $req->from_date)->sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            //dd($c_net_c);
            //get Closing
            $cash_credit  = CashBook::whereDate('date', '<=', $req->from_date)->sum('receipt_ammount');
            $cash_debit  = CashBook::whereDate('date', '<=', $req->from_date)->sum('payment_ammount');
            $ex  = Expense::whereDate('date', '<=', $req->from_date)->sum('ammount');
            $day_exp = Expense::whereDate('date', '=', $req->from_date)->sum('ammount');
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $req->from_date)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
                                                            
            $data = array(
                'title' => 'DayBook Report',
                'expire_medicine'   => $expire_medicine,
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                'cashbook'  => $c,
                'is_update' => true,
                'from_date' => $req->from_date ,
                'purchase_medicine'  => PurchaseMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_medicine'     => SaleMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'return_medicine'   => ReturnMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),                 
                'purchase_murghi'   => PurchaseMurghi::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_murghi'       => SaleMurghi::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'purchase_chick'   => PurchaseChick::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_chick'       => SaleChick::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'purchase_feed'   => PurchaseFeed::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_feed'      => SaleFeed::with(['item',                                     'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'return_feed' => ReturnFeed::with(['item', 'account']                           )->whereDate('date', $req->from_date                        )->latest()->get(),
            
                'cash'          => CashBook::with(['account'])->whereDate                   ('date', $req->from_date)->latest()->get(),
                         
                                            
            );
           // dd($data['purchases']);
        }else{
            
            
            $current_month = date('Y-m-d');
            
            //Feed
            $tot_sale_feed = SaleFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_feed = PurchaseFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_return_feed = ReturnFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            
            //Medicine
            $tot_sale_medicine = SaleMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_medicine = PurchaseMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_return_medicine = ReturnMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            //dd($tot_sale_medicine);
            
            //Chicks 
            $tot_sale_chick = SaleChick::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_chick = PurchaseChick::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
    
    
            //Murghi 
            $tot_sale_murghi = SaleMurghi::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_murghi = PurchaseMurghi::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            //Cashflow 
            //get Opening
            $c_cash_credit  = CashBook::sum('receipt_ammount');
            $c_cash_debit  = CashBook::sum('payment_ammount');
            $c_ex  = Expense::sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            //dd($c_net_c);
            //get Closing
            $cash_credit  = CashBook::sum('receipt_ammount');
            $cash_debit  = CashBook::sum('payment_ammount');
            
            $ex  = Expense::sum('ammount');
            
            
            $day_exp = Expense::whereDate('date', '=', $current_month)->sum('ammount');
            
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $current_month)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
            
            $data = array(
                'title' => 'DayBook report',
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                'cashbook'  => $c,
                'date'      => $current_month,
                'expire_medicine'   => $expire_medicine,
                'purchase_medicine'  => $tot_purchase_medicine,
                'sale_medicine'     =>  $tot_sale_medicine,   
                'return_medicine'     =>  $tot_return_medicine,   
                
                'purchase_murghi'   => $tot_purchase_murghi,
                'sale_murghi'       => $tot_sale_murghi,   
                'purchase_chick'   => $tot_purchase_chick,
                'sale_chick'       => $tot_sale_chick,   
                'purchase_feed'   => $tot_purchase_feed,
                'sale_feed'       => $tot_sale_feed, 
                'return_feed'       => $tot_return_feed, 
                
            );
        }
        
        
        return view('admin.report.daybook_report')->with($data);
    }
    
    public function DayBookPdf(Request $req){
        
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        

        if(isset($req->from_date)){
            
            //get Opening
            $c_cash_credit  = CashBook::whereDate('date', '<', $req->from_date)->sum('receipt_ammount');
            $c_cash_debit  = CashBook::whereDate('date', '<', $req->from_date)->sum('payment_ammount');
            $c_ex  = Expense::whereDate('date', '<', $req->from_date)->sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            
            //get Closing
            $cash_credit  = CashBook::whereDate('date', '<=', $req->from_date)->sum('receipt_ammount');
            $cash_debit  = CashBook::whereDate('date', '<=', $req->from_date)->sum('payment_ammount');
            $ex  = Expense::whereDate('date', '<=', $req->from_date)->sum('ammount');
            $day_exp = Expense::whereDate('date', '=', $req->from_date)->sum('ammount');
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $req->from_date)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
                                                            
            $data = array(
                'title' => 'DayBook Report',
                'expire_medicine'   => $expire_medicine,
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                'cashbook'  => $c,
               
                'from_date' => $req->from_date ,
                'purchase_medicine'  => PurchaseMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_medicine'     => SaleMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'return_medicine'   => ReturnMedicine::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),                 
                'purchase_murghi'   => PurchaseMurghi::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_murghi'       => SaleMurghi::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'purchase_chick'   => PurchaseChick::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_chick'       => SaleChick::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'purchase_feed'   => PurchaseFeed::with(['item',                             'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'sale_feed'      => SaleFeed::with(['item',                                     'account'])->whereDate('date', $req                         ->from_date)->latest()->get(),
                'return_feed' => ReturnFeed::with(['item', 'account']                           )->whereDate('date', $req->from_date                        )->latest()->get(),
            
                'cash'          => CashBook::with(['account'])->whereDate                   ('date', $req->from_date)->latest()->get(),
                         
                                            
            );
           //dd($data['purchase_medicine']);
        }else{
            
            
            $current_month = date('Y-m-d');
            
            //Feed
            $tot_sale_feed = SaleFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_feed = PurchaseFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_return_feed = ReturnFeed::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            
            //Medicine
            $tot_sale_medicine = SaleMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_medicine = PurchaseMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_return_medicine = ReturnMedicine::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            //dd($tot_sale_medicine);
            
            //Chicks 
            $tot_sale_chick = SaleChick::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_chick = PurchaseChick::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
    
    
            //Murghi 
            $tot_sale_murghi = SaleMurghi::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            $tot_purchase_murghi = PurchaseMurghi::with(['item', 'account'])->whereDate('date', $current_month)->latest()->get();
            //Cashflow 
            //get Opening
            $c_cash_credit  = CashBook::sum('receipt_ammount');
            $c_cash_debit  = CashBook::sum('payment_ammount');
            $c_ex  = Expense::sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            //dd($c_net_c);
            //get Closing
            $cash_credit  = CashBook::sum('receipt_ammount');
            $cash_debit  = CashBook::sum('payment_ammount');
            
            $ex  = Expense::sum('ammount');
            
            
            $day_exp = Expense::whereDate('date', '=', $current_month)->sum('ammount');
            
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $current_month)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
            
            $data = array(
                'title' => 'DayBook report',
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                'cashbook'  => $c,
                'date'      => $current_month,
                'expire_medicine'   => $expire_medicine,
                'purchase_medicine'  => $tot_purchase_medicine,
                'sale_medicine'     =>  $tot_sale_medicine,   
                'return_medicine'     =>  $tot_return_medicine,   
                
                'purchase_murghi'   => $tot_purchase_murghi,
                'sale_murghi'       => $tot_sale_murghi,   
                'purchase_chick'   => $tot_purchase_chick,
                'sale_chick'       => $tot_sale_chick,   
                'purchase_feed'   => $tot_purchase_feed,
                'sale_feed'       => $tot_sale_feed, 
                'return_feed'       => $tot_return_feed, 
                
            );
        }
        
        $pdf = Pdf::loadView('admin.report.daybook_report_pdf', $data);
        return $pdf->download('daybook_report_pdf.pdf');
        
    }

    public function cashflowReport(Request $req){
        
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        
        
        
        if(isset($req->to_date)){
            
            //get Opening
            $c_cash_credit  = CashBook::whereDate('date', '<', $req->to_date)->sum('receipt_ammount');
            $c_cash_debit  = CashBook::whereDate('date', '<', $req->to_date)->sum('payment_ammount');
            $c_ex  = Expense::whereDate('date', '<', $req->to_date)->sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            //dd($c_net_c);
            //get Closing
            $cash_credit  = CashBook::whereDate('date', '<=', $req->to_date)->sum('receipt_ammount');
            $cash_debit  = CashBook::whereDate('date', '<=', $req->to_date)->sum('payment_ammount');
            $ex  = Expense::whereDate('date', '<=', $req->to_date)->sum('ammount');
            $day_exp = Expense::whereDate('date', '=', $req->to_date)->sum('ammount');
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $req->to_date)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
            //dd($net_c);
            // dd($net_c);
            // dd($cash_credit);
                

            
            $data = array(
                'title' => 'CashFlow Report',
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                
                'expire_medicine'   => $expire_medicine,
                'to_date' => $req->to_date ,
                'cashbook'  => $c
            );
            //dd($data['cashbook']);
    
        }else{
            
            $data = array(
                'title' => 'CashFlow Report',
                'expire_medicine'   => $expire_medicine,
            
            );
        }
        
        return view('admin.report.cashflow')->with($data);
    }
    
    public function cashflowReportPdf(Request $req){
            
         $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        
        
        
        if(isset($req->to_date)){
            
            //get Opening
            $c_cash_credit  = CashBook::whereDate('date', '<', $req->to_date)->sum('receipt_ammount');
            $c_cash_debit  = CashBook::whereDate('date', '<', $req->to_date)->sum('payment_ammount');
            $c_ex  = Expense::whereDate('date', '<', $req->to_date)->sum('ammount');
            
            $c_open = 2909858;
            $ccc_net = $c_open + $c_cash_credit;
            
            $ex_cc = $c_ex + $c_cash_debit ;
            
            $c_net_c = $ccc_net - $ex_cc;
            
            //dd($c_net_c);
            //get Closing
            $cash_credit  = CashBook::whereDate('date', '<=', $req->to_date)->sum('receipt_ammount');
            $cash_debit  = CashBook::whereDate('date', '<=', $req->to_date)->sum('payment_ammount');
            $ex  = Expense::whereDate('date', '<=', $req->to_date)->sum('ammount');
            $day_exp = Expense::whereDate('date', '=', $req->to_date)->sum('ammount');
            //get Cashbook
            $c  = CashBook::whereDate('date', '=', $req->to_date)->latest()->get();
            
            $next_open = $c_net_c;
            $net_credit = $cash_credit - $c_cash_credit;
            $net_debit = $cash_debit - $c_cash_debit;
            $net_ex = $ex - $c_ex;
            $net = $net_credit + $next_open;
            
            $net_c = $net - $net_debit;
            $ov = $net_c - $ex ;
            //dd($net_c);
            // dd($net_c);
            // dd($cash_credit);
                

            
            $data = array(
                'title' => 'CashFlow Report',
                'account_opening' => $c_net_c,
                'account_closing' => $ov,
                'expense' => $ex,
                'day_exp' => $day_exp,
                'credit' => $net_credit,
                'debit'  => $net_debit,
                
                'expire_medicine'   => $expire_medicine,
                'to_date' => $req->to_date ,
                'cashbook'  => $c
            );
            //dd($data['cashbook']);
    
        }else{
            
            $data = array(
                'title' => 'CashFlow Report',
                'expire_medicine'   => $expire_medicine,
            
            );
        }
        
        $pdf = Pdf::loadView('admin.report.cashflow_pdf', $data);
        return $pdf->download('CashFlow.pdf');   
        
    }

    public function accounts_head_report(Request $req){
     
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        


        if(isset($req->from_date) ){
            
            
            $accounts = Account::where('grand_parent_id','=',hashids_decode($req->parent_id))->latest()->get();
            //dd($accounts);

            for($i = 0; $i < count($accounts); $i++) {
                
                
                $arr = [];
                $balance = $accounts[$i]->opening_balance;
                $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->whereDate('date', '<=', $req->from_date)->sum('credit');
                $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->whereDate('date', '<=', $req->from_date)->sum('debit');
                
                
                if($accounts[$i]->account_nature == "credit"){
                    $t_cr += $balance;
                    
                }else{

                    $t_dr +=  $balance;
                }

                $dues = $t_cr - $t_dr;

                if($dues < 0){
                    $a_n = "debit";

                }else{

                    $a_n = "credit";
                }
              
                $accounts[$i]->opening_balance = $dues;
                $accounts[$i]->account_nature = $a_n;
                
            }
            //dd($accounts);
            $data = array(
                'title' => 'All Accounts Ledger',
                'expire_medicine'   => $expire_medicine,
                'Item' => Item::where('category_id',3)->latest()->get(),
                'acounts' => Account::latest()->get(),
                'ac' => $accounts ,
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                'accounts'  => Account::latest()->get(),
            
            );
        }else{
            

            $accounts = Account::latest()->get();
            

            for($i = 0; $i < count($accounts); $i++) {
                
                $arr = [];
                $balance = $accounts[$i]->opening_balance;
                $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('credit');
                $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('debit');
                
                
                if($accounts[$i]->account_nature == "credit"){
                    $t_cr += $balance;
                    
                }else{

                    $t_dr +=  $balance;
                }

                $dues = $t_cr - $t_dr;

                if($dues < 0){
                    $a_n = "debit";

                }else{

                    $a_n = "credit";
                }
              
                $accounts[$i]->opening_balance = $dues;
                $accounts[$i]->account_nature = $a_n;
                
            }
            //dd($accounts);
            $data = array(
                'title' => 'All Accounts Ledger',
                'expire_medicine'   => $expire_medicine,
                'Item' => Item::where('category_id',3)->latest()->get(),
                'acounts' => Account::latest()->get(),
                'ac' => $accounts ,
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                'accounts'  => Account::latest()->get(),
            
            );
        }
        
        return view('admin.report.accounts_head_report')->with($data);
    }
    
    public function all_accounts_report_request(Request $req){

        if(isset($req->from_date) ){
            
            
            $accounts = Account::where('grand_parent_id','=',hashids_decode($req->parent_id))->latest()->get();
            //dd($accounts);

            for($i = 0; $i < count($accounts); $i++) {
                
                
                $arr = [];
                $balance = $accounts[$i]->opening_balance;
                $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->whereDate('date', '<=', $req->from_date)->sum('credit');
                $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->whereDate('date', '<=', $req->from_date)->sum('debit');
                
                
                if($accounts[$i]->account_nature == "credit"){
                    $t_cr += $balance;
                    
                }else{

                    $t_dr +=  $balance;
                }

                $dues = $t_cr - $t_dr;

                if($dues < 0){
                    $a_n = "debit";

                }else{

                    $a_n = "credit";
                }
              
                $accounts[$i]->opening_balance = $dues;
                $accounts[$i]->account_nature = $a_n;
                
            }
            $data = array(
                'title' => 'All Accounts Report',
                'ac' => $accounts ,
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                
            );

        }else{
            

            $accounts = Account::latest()->get();
            

            for($i = 0; $i < count($accounts); $i++) {
                
                $arr = [];
                $balance = $accounts[$i]->opening_balance;
                $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('credit');
                $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('debit');
                
                
                if($accounts[$i]->account_nature == "credit"){
                    $t_cr += $balance;
                    
                }else{

                    $t_dr +=  $balance;
                }

                $dues = $t_cr - $t_dr;

                if($dues < 0){
                    $a_n = "debit";

                }else{

                    $a_n = "credit";
                }
              
                $accounts[$i]->opening_balance = $dues;
                $accounts[$i]->account_nature = $a_n;
                
            }

            $data = array(
                'title' => 'All Accounts Report',
                'ac' => $accounts ,
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                
            );
        }

        return view('admin.report.all_accounts_reports')->with($data);
    }
    
    public function all_report($id){
        //dd($id);
        if($id == "purchase_medicine"){
            
            $data = array(
                'title' => 'Purchase Medicine Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',4)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "purchasemedicine",
                'all_reports_values' => "",
                
            
            );
            
        }
        
        //Sale Medicine
        if($id == "sale_medicine"){
            
            $data = array(
                    'title' => 'Sale Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::where('category_id',4)->latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemedicine",
                    'all_reports_values' => "",
                    
                
                );
            
        }

        //Return Medicine
        if($id == "return_medicine"){
            
            $data = array(
                'title' => 'Return Medicine Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',4)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "returnmedicine",
                'all_reports_values' => "",
                
            
            );
            
        }
        
        //Purchase Feed 
        if($id == "purchase_feed"){
            
            $data = array(
                'title' => 'Purchase Feed Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',3)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "purchasefeed",
                'all_reports_values' => "",
                
            
            );
            
        }
        
        //Sale Medicine
        if($id == "sale_feed"){
            
            $data = array(
                    'title' => 'Sale Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::where('category_id',3)->latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salefeed",
                    'all_reports_values' => "",
                    
                
                );
            
        }

        //Return Medicine
        if($id == "return_feed"){
            
            $data = array(
                'title' => 'Return Feed Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',3)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "returnfeed",
                'all_reports_values' => "",
                
            
            );
            
        }

        //Sale Medicine
        if($id == "sale_chick"){
            
            $data = array(
                    'title' => 'Sale Chick Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::where('category_id',2)->latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salechick",
                    'all_reports_values' => "",
                    
                
                );
            
        }

        //Return Medicine
        if($id == "purchase_chick"){
            
            $data = array(
                'title' => 'Purchase Chick Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',2)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "purchasechick",
                'all_reports_values' => "",
                
            
            );
            
        }
        
        //Sale Murghi
        if($id == "sale_murghi"){
            
            $data = array(
                    'title' => 'Sale Murghi Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::where('category_id',8)->latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemurghi",
                    'all_reports_values' => "",
                    
                
                );
            
        }

        //Purchase Murghi
        if($id == "purchase_murghi"){
            
            $data = array(
                'title' => 'Purchase Murghi Report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('category_id',8)->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'item_name' => false,
                'account_name' => false,
                'id'  => "purchasemurghi",
                'all_reports_values' => "",
                
            
            );
            
        }
        
        return view('admin.report.all_reports')->with($data);
    }

    public function all_reports_request(Request $req){
        
        if($req->id == "purchasemedicine"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
                
                if(isset($req->account_id) && !isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                   
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasemedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }
        
        //Sale Medicine
        if($req->id == "salemedicine"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Return Medicine
        if($req->id == "returnmedicine"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Return Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "returnmedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Feed 
        if($req->id == "purchasefeed"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                   
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(isset($req->account_id) && !isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                
                
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasefeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }
        
        //Sale Medicine
        if($req->id == "salefeed"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
               
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    //dd($req->all());
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::with(['company','account','item'])->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->invoice_no), function($query) use ($req){
                                                        $query->where('invoice_no',$req->invoice_no);
                                                    })
                                                    ->when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id',hashids_decode($req->item_id));
                                                    })
                                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                
                //dd($data);
            }else{
                $data = array(
                    'title' => 'Sale Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salefeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Return Medicine
        if($req->id == "returnfeed"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Return Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "returnfeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Sale Chcik
        if($req->id == "salechick"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Chcik Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salechick",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Chick
        if($req->id == "purchasechick"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Chick Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasechick",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Sale Murghi
        if($req->id == "salemurghi"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Murghi Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemurghi",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Murghi
        if($req->id == "purchasemurghi"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Murghi Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasemurghi",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        return view('admin.report.all_reports')->with($data);
    }

    public function all_reports_pdf(Request $req){
        
        if($req->id == "purchasemedicine"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
                
                if(isset($req->account_id) && !isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                   
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    $data = array(  
                        'title' => 'Purchase Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasemedicine",
                        'all_reports_values'  => PurchaseMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasemedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }
        
        //Sale Medicine
        if($req->id == "salemedicine"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Return Medicine
        if($req->id == "returnmedicine"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Return Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "returnmedicine",
                        'all_reports_values'  => ReturnMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Return Medicine Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "returnmedicine",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Feed 
        if($req->id == "purchasefeed"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                   
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(isset($req->account_id) && !isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    $data = array(  
                        'title' => 'Purchase Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasefeed",
                        'all_reports_values'  => PurchaseFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                
                
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasefeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }
        
        //Sale Medicine
        if($req->id == "salefeed"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Medicine Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemedicine",
                        'all_reports_values'  => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
               
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    //dd($req->all());
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salefeed",
                        'all_reports_values'  => SaleFeed::with(['company','account','item'])->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->invoice_no), function($query) use ($req){
                                                        $query->where('invoice_no',$req->invoice_no);
                                                    })
                                                    ->when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id',hashids_decode($req->item_id));
                                                    })
                                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                
                //dd($data);
            }else{
                $data = array(
                    'title' => 'Sale Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salefeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Return Medicine
        if($req->id == "returnfeed"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Return Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "returnfeed",
                        'all_reports_values'  => ReturnFeed::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Return Feed Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "returnfeed",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Sale Chcik
        if($req->id == "salechick"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Chick Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Feed Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salechick",
                        'all_reports_values'  => SaleChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Chcik Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salechick",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Chick
        if($req->id == "purchasechick"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Purchase Chick Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasechick",
                        'all_reports_values'  => PurchaseChick::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Chick Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasechick",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Sale Murghi
        if($req->id == "salemurghi"){
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Sale Murghi Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "salemurghi",
                        'all_reports_values'  => SaleMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Sale Murghi Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "salemurghi",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        //Purchase Murghi
        if($req->id == "purchasemurghi"){
            
            if(isset($req->account_id) || isset($req->item_id) || isset($req->to_date)){
            
                if(isset($req->account_id) && !isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => "",
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->account_id) && isset($req->item_id)){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }

                if(isset($req->item_id) && !isset($req->account_id)){
                    
                   
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => "",
                        'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'is_update' => true,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                if(!isset($req->account_id) && !isset($req->item_id) ){
                    
                    $data = array(  
                        'title' => 'Purchase Murghi Report',
                        'account_name' => false,
                        'acounts' => Account::latest()->get(),
                        'items' =>   Item::latest()->get(),
                        'item_name' => false,
                        'is_update' => true,
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'id'  => "purchasemurghi",
                        'all_reports_values'  => PurchaseMurghi::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                                    })
                                                    ->latest()->get(), 
                    );
                    
                }
                
                //dd($data['purchases']);
            }else{
                $data = array(
                    'title' => 'Purchase Murghi Report',
                    'acounts' => Account::latest()->get(),
                    'items' => Item::latest()->get(),
                    'accounts'  => Account::latest()->get(),
                    'item_name' => false,
                    'account_name' => false,
                    'id'  => "purchasemurghi",
                    'all_reports_values' => "",
                    
                
                );
            }
        }

        $pdf = Pdf::loadView('admin.report.all_reports_pdf', $data);
        return $pdf->download('account_report.pdf');
    }

    public function DebtorReport(Request $req){
     
        
            
        $accounts = Account::with(['grand_parent'])->where('account_nature','debit')->latest()->get();
        
        for($i = 0; $i < count($accounts); $i++) {
            
            $arr = [];
            $balance = $accounts[$i]->opening_balance;
            $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('credit');
            $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('debit');
            
            
            if($accounts[$i]->account_nature == "credit"){
                $t_cr += $balance;
                
            }else{

                $t_dr +=  $balance;
            }

            $dues = $t_cr - $t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $accounts[$i]->opening_balance = $dues;
            $accounts[$i]->account_nature = $a_n;
            
        }

        $data = array(
            'title' => 'Debtor Account Report',
           
            'accounts'  => $accounts,
           
            
        );
        //dd($data['party_name']);
   
    
        return view('admin.report.debtor_report')->with($data);
    }

    public function TrialBalanceReport(Request $req){
     
        
            
        $accounts = Account::with(['grand_parent'])->where('grand_parent_id',3)->latest()->get();
        $tot_Assets = 0;

        for($i = 0; $i < count($accounts); $i++) {
            
            $arr = [];
            $balance = $accounts[$i]->opening_balance;
            $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('credit');
            $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('debit');
            
            
            if($accounts[$i]->account_nature == "credit"){
                $t_cr += $balance;
                
            }else{

                $t_dr +=  $balance;
            }

            $dues = $t_cr - $t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $accounts[$i]->opening_balance = $dues;
            $accounts[$i]->account_nature = $a_n;
            $tot_Assets += $accounts[$i]->opening_balance;
        }

        $ex_accounts = Account::with(['grand_parent'])->where('grand_parent_id',4)->latest()->get();
        $tot_Expense = 0;

        for($i = 0; $i < count($ex_accounts); $i++) {
            
            $arr = [];
            $ex_balance = $ex_accounts[$i]->opening_balance;
            $ex_t_cr = AccountLedger::where('account_id',$ex_accounts[$i]->id)->sum('credit');
            $ex_t_dr = AccountLedger::where('account_id',$ex_accounts[$i]->id)->sum('debit');
            
            
            if($ex_accounts[$i]->account_nature == "credit"){
                $ex_t_cr += $ex_balance;
                
            }else{

                $ex_t_dr +=  $ex_balance;
            }

            $dues = $ex_t_cr - $ex_t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $ex_accounts[$i]->opening_balance = $dues;
            $ex_accounts[$i]->account_nature = $a_n;
            $tot_Expense += $ex_accounts[$i]->opening_balance;
        }

        $lia_accounts = Account::with(['grand_parent'])->where('grand_parent_id',5)->latest()->get();
        $tot_liabilities = 0;

        for($i = 0; $i < count($lia_accounts); $i++) {
            
            $arr = [];
            $lia_balance = $lia_accounts[$i]->opening_balance;
            $lia_t_cr = AccountLedger::where('account_id',$lia_accounts[$i]->id)->sum('credit');
            $lia_t_dr = AccountLedger::where('account_id',$lia_accounts[$i]->id)->sum('debit');
            
            
            if($lia_accounts[$i]->account_nature == "credit"){
                $lia_t_cr += $lia_balance;
                
            }else{

                $lia_t_dr +=  $lia_balance;
            }

            $dues = $lia_t_cr - $lia_t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $lia_accounts[$i]->opening_balance = $dues;
            $lia_accounts[$i]->account_nature = $a_n;
            $tot_liabilities += $lia_accounts[$i]->opening_balance;
        }


        $rev_accounts = Account::with(['grand_parent'])->where('grand_parent_id',6)->latest()->get();
        $tot_revenue = 0;

        for($i = 0; $i < count($rev_accounts); $i++) {
            
            $arr = [];
            $rev_balance = $rev_accounts[$i]->opening_balance;
            $$rev_t_cr = AccountLedger::where('account_id',$rev_accounts[$i]->id)->sum('credit');
            $$rev_t_dr = AccountLedger::where('account_id',$rev_accounts[$i]->id)->sum('debit');
            
            
            if($rev_accounts[$i]->account_nature == "credit"){
                $$rev_t_cr += $rev_balance;
                
            }else{

                $$rev_t_dr +=  $rev_balance;
            }

            $dues = $$rev_t_cr - $$rev_t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $rev_accounts[$i]->opening_balance = $dues;
            $rev_accounts[$i]->account_nature = $a_n;
            $tot_revenue += $rev_accounts[$i]->opening_balance;
        }

        $prop_accounts = Account::with(['grand_parent'])->where('grand_parent_id',7)->latest()->get();
        $tot_propritorship = 0;

        for($i = 0; $i < count($prop_accounts); $i++) {
            
            $arr = [];
            $prop_balance = $prop_accounts[$i]->opening_balance;
            $prop_t_cr = AccountLedger::where('account_id',$prop_accounts[$i]->id)->sum('credit');
            $prop_t_dr = AccountLedger::where('account_id',$prop_accounts[$i]->id)->sum('debit');
            
            
            if($prop_accounts[$i]->account_nature == "credit"){
                $prop_t_cr += $prop_balance;
                
            }else{

                $prop_t_dr +=  $prop_balance;
            }

            $dues = $prop_t_cr - $prop_t_dr;

            if($dues < 0){
                $a_n = "debit";

            }else{

                $a_n = "credit";
            }
          
            $prop_accounts[$i]->opening_balance = $dues;
            $prop_accounts[$i]->account_nature = $a_n;
            $tot_propritorship += $prop_accounts[$i]->opening_balance;
        }

        $data = array(
            'title' => 'Trail Balance Report',
           
           
            'assets'  => $tot_Assets,
            'expense' => $tot_Expense,
            'liabilities' => $tot_liabilities,
            'revenue' => $tot_revenue,
            'propritorship' => $tot_propritorship,

            
        );
        //dd($data['assets']);
   
    
        return view('admin.report.trial_balance_report')->with($data);
    }

    public function CreditorReport(Request $req){
     
        
            
            $accounts = Account::with(['grand_parent'])->where('account_nature','credit')->latest()->get();
            
            for($i = 0; $i < count($accounts); $i++) {
                
                $arr = [];
                $balance = $accounts[$i]->opening_balance;
                $t_cr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('credit');
                $t_dr = AccountLedger::where('account_id',$accounts[$i]->id)->sum('debit');
                
                
                if($accounts[$i]->account_nature == "credit"){
                    $t_cr += $balance;
                    
                }else{

                    $t_dr +=  $balance;
                }

                $dues = $t_cr - $t_dr;

                if($dues < 0){
                    $a_n = "debit";

                }else{

                    $a_n = "credit";
                }
              
                $accounts[$i]->opening_balance = $dues;
                $accounts[$i]->account_nature = $a_n;
                
            }

            $data = array(
                'title' => 'Creditor Account Report',
               
                'accounts'  => $accounts,
               
                
            );
            //dd($data['party_name']);
       
        
        return view('admin.report.creditor_report')->with($data);
    }

    public function accountReport(Request $req){
        
        
        if(isset($req->parent_id)){
            
                if( hashids_decode($req->parent_id) == 535 ){

                    $account_detail = Account::with(['grand_parent'])->where('id',hashids_decode($req->parent_id))->latest()->get();
                
                    if($account_detail[0]->account_nature == "debit" ){
                        $detail = "Assets";
        
                    }else{
                        $detail = "Not Assets";
                        
                    }
                    
                    $data = array(
                        'title' => 'Account Report',
                        'account_types' => AccountType::whereNull('parent_id')->get(), 
                        'accounts'  => Account::latest()->get(),
                        'account_opening' => $account_detail ,
                        'account_parent' => $detail ,
        
                        'party_name' => Account::where('id',hashids_decode($req->parent_id))->latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
                        'cash_in_hand' => true,
                        'account_ledger'  => AccountLedger::when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                        $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                                    })->where('cash_id','!=',0)->orderby('date','asc')->latest()->get()
                    );
                    
                }else{

                    $account_detail = Account::with(['grand_parent'])->where('id',hashids_decode($req->parent_id))->latest()->get();

                    if($account_detail[0]->account_nature == "debit" ){
                        $detail = "Assets";
        
                    }else{
                        $detail = "Not Assets";
                        
                    }
                    
                    
                    if($account_detail[0]->account_nature == "debit"){
                        
                        $open_credit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '<', $req->from_date);
                        })->where('credit' ,'!=',0)->sum('credit');
                        $open_debit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '<', $req->from_date);
                        })->where('debit' ,'!=',0)->sum('debit');
                        
                        $grand_open = ($account_detail[0]->opening_balance + $open_debit) - $open_credit;
                       
                        
                        
                        

                    }else{
                        
                        $open_credit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '<', $req->from_date);
                        })->where('credit' ,'!=',0)->sum('credit');
                        $open_debit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->  when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '<', $req->from_date);
                        })->where('debit' ,'!=',0)->sum('debit');
                       
                        $grand_open = ($account_detail[0]->opening_balance + $open_debit) - $open_credit;
                        
                        //dd($open_credit);
                        //$grand_open = ($account_detail[0]->opening_balance + $open_credit) - $open_debit;
                    }

                    $account_detail[0]->opening_balance = abs($grand_open) ;
                    //dd($account_detail);

                    $data = array(
                        'title' => 'Account Report',
                        'account_types' => AccountType::whereNull('parent_id')->get(), 
                        'accounts'  => Account::latest()->get(),
                        'account_opening' => $account_detail ,
                        'account_parent' => $detail ,
                        'cash_in_hand' => false,
                        'party_name' => Account::where('id',hashids_decode($req->parent_id))->latest()->get(),
                        'from_date' => $req->from_date ,
                        'to_date' => $req->to_date ,
        
                        'account_ledger'  => AccountLedger::when(isset($req->parent_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->parent_id));
                                                    })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                        $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                                    })->orderBy('date','asc')->get()
                    );
                }
            //dd($data['party_name']);
        }else{
            
            $data = array(
                'title' => 'Account report',
                'acounts' => Account::latest()->get(),
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                'accounts'  => Account::latest()->get(),
            
            );
        }
        
        return view('admin.report.account_report')->with($data);
    }

    public function accountReportPdf(Request $req){
            //dd($req->from_date);
            $toDate = Carbon::parse($req->from_date);
            $fromDate = Carbon::parse($req->to_date);
      
            $days = $fromDate->diffInDays($toDate);
            $account_detail = Account::with(['grand_parent'])->where('id',hashids_decode($req->parent_id))->latest()->get();
             $names = $account_detail[0]->name . $days . 'Days' ;
            $data = array(
                'account_ledger'  => AccountLedger::when(isset($req->parent_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->parent_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                            })->orderBy('date', 'asc')->get(),
                'to_date' => $req->to_date,
                'from_date' => $req->from_date,
                'days' => $days,
                'names' =>  $names ,
                'account_opening' => $account_detail ,
                'account_name' =>  Account::findOrFail(hashids_decode($req->parent_id)),
                                           
            );
           
           
           
            
                

            $account_detail = Account::with(['grand_parent'])->where('id',hashids_decode($req->parent_id))->latest()->get();
        
            if($account_detail[0]->account_nature == "debit" ){
                $detail = "Assets";

            }else{
                $detail = "Not Assets";
                
            }
            
            
            if($account_detail[0]->account_nature == "debit"){
                
                $open_credit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                    $query->whereDate('date', '<', $req->from_date);
                })->where('credit' ,'!=',0)->sum('credit');
                $open_debit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                    $query->whereDate('date', '<', $req->from_date);
                })->where('debit' ,'!=',0)->sum('debit');
                
                $grand_open = ($account_detail[0]->opening_balance + $open_debit) - $open_credit;
               
                
                
                

            }else{
                
                $open_credit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                    $query->whereDate('date', '<', $req->from_date);
                })->where('credit' ,'!=',0)->sum('credit');
                $open_debit = AccountLedger::where('account_id',hashids_decode($req->parent_id))->  when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                    $query->whereDate('date', '<', $req->from_date);
                })->where('debit' ,'!=',0)->sum('debit');
               
                $grand_open = ($account_detail[0]->opening_balance + $open_debit) - $open_credit;
                
                //dd($open_credit);
                //$grand_open = ($account_detail[0]->opening_balance + $open_credit) - $open_debit;
            }

            $account_detail[0]->opening_balance = abs($grand_open) ;
            
            $data = array(
                'title' => 'Account Report',
                'account_types' => AccountType::whereNull('parent_id')->get(), 
                'accounts'  => Account::latest()->get(),
                'account_opening' => $account_detail ,
                'account_parent' => $detail ,
                'cash_in_hand' => false,
                'party_name' => Account::where('id',hashids_decode($req->parent_id))->latest()->get(),
                'to_date' => $req->to_date,
                'from_date' => $req->from_date,
                'days' => $days,
                'names' =>  $names ,
                'account_opening' => $account_detail ,
                'account_name' =>  Account::findOrFail(hashids_decode($req->parent_id)),
                'account_ledger'  => AccountLedger::when(isset($req->parent_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->parent_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                            })->orderBy('date','asc')->get()
            );
                
            
            $pdf = Pdf::loadView('admin.report.account_pdf', $data);
            return $pdf->download('.'.$names.'..pdf');
    }
    
    public function itemReport(Request $req){

        if(isset($req->item_id)){
            $data = array(
                'title' => 'Item report',
                'items' => Item::where('type','purchase')->latest()->get(),
                'item_name' => Item::where('id',hashids_decode($req->item_id))->latest()->get(),
                'from_date' => $req->from_date ,
                'to_date' => $req->to_date ,
                'purchases'  => AccountLedger::when(isset($req->item_id), function($query) use ($req){
                                                $query->where('item_id', hashids_decode($req->item_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                            })->where('sale_id',"")->latest()->get(),
            );
            //dd($data['purchases']);
        }else{
            $data = array(
                'title' => 'Item report',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('type','purchase')->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'purchases' => "",
                
            
            );
        }
        
        
        return view('admin.report.item_report')->with($data);
    
    }

    public function itemReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.item_pdf', $data);
        return $pdf->download('item_report.pdf');
    }

    public function itemReportPrint(Request $req){

        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
        $days = $fromDate->diffInDays($toDate);
        
        $data = array(
            'purchases'  => PurchaseBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::find(hashids_decode($req->item_id)),
                                       
        );
        return view('admin.report.item_print')->with($data);
    }

    public function inwardReport(Request $req){
        $data = array(
            'title' => 'Inward Ledger',
            'items' => Item::latest()->get(),
            'accounts'  => Account::latest()->get(),
            'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.inward_report')->with($data);
    }

    public function inwardReportPdf(Request $req){
        
          //dd($req->from_date);
          $toDate = Carbon::parse($req->from_date);
          $fromDate = Carbon::parse($req->to_date);
    
          $days = $fromDate->diffInDays($toDate);
  
        $data = array(
            'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                            $query->where('item_id', hashids_decode($req->item_id));
                        })->when(isset($req->account_id), function($query) use ($req){
                            $query->where('account_id', hashids_decode($req->account_id));
                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        $pdf = Pdf::loadView('admin.report.inward-pdf', $data);
        return $pdf->download('inward_report.pdf');
    }

    public function inwardReportPrint(Request $req){
        
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

      $data = array(
          'inward'  => Inward::when(isset($req->item_id), function($query) use ($req){
                          $query->where('item_id', hashids_decode($req->item_id));
                      })->when(isset($req->account_id), function($query) use ($req){
                          $query->where('account_id', hashids_decode($req->account_id));
                      })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                          $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                      })->latest()->get(),
          'to_date' => $req->to_date,
          'from_date' => $req->from_date,
          'days' => $days,
          'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
      );
      //window.print(view('admin.report.inward_print')->with($data));
      //$pdf = Pdf::loadView('admin.report.inward-print', $data);
      return view('admin.report.inward_print')->with($data);
  }

    public function outwardReport(Request $req){
        $data = array(
            'title' => 'Outward Ledger',
            'items' => Item::latest()->get(),
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get()
        );
        return view('admin.report.outward_report')->with($data);
    }

    public function outwardReportPdf(Request $req){
        $data = array(
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        $pdf = Pdf::loadView('admin.report.outward-pdf', $data);
        return $pdf->download('outward_report.pdf');
    }

    public function outwardReportPrint(Request $req){
        $data = array(
            'outward'  => Outward::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => @$days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
        );
        // $pdf = Pdf::loadView('admin.report.outward-pdf', $data);
        // return $pdf->download('outward_report.pdf');
        return view('admin.report.outward_print')->with($data);
    }

    public function PurchaseBookReport(Request $req){
        if(isset($req->account_id)){
            $data = array(  
                'title' => 'Purchase Book Ledger',
                'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                'acounts' => Account::latest()->get(),
                'from_date' => $req->from_date ,
                'to_date' => $req->to_date ,
                'purchases'  => AccountLedger::when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                            })->where('sale_id',"")->latest()->get(),
            );
            //dd($data['purchases']);
        }else{
            $data = array(
                'title' => 'Purchase Book Ledger',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('type','purchase')->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'purchases' => "",
                
            
            );
        }
        
        
        return view('admin.report.purchase_book')->with($data);
    }

    public function PurchaseReportPdf(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
                'title' => 'Purchase Book Ledger',
                'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                'acounts' => Account::latest()->get(),
                'from_date' => $req->from_date ,
                'to_date' => $req->to_date ,
                'purchases'  => AccountLedger::when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                            })->where('sale_id',"")->latest()->get(),

                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.purchase_pdf', $data);
        return $pdf->download('purchase_book_report.pdf');
    }

    public function PurchasePrint(Request $req){

        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
        $days = $fromDate->diffInDays($toDate);
        
        $data = array(
                'title' => 'Purchase Book Ledger',
                'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                'acounts' => Account::latest()->get(),
                'from_date' => $req->from_date ,
                'to_date' => $req->to_date ,
                'days' => $days,
                'purchases'  => AccountLedger::with(['account','item'])->when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                            })->where('sale_id',"")->latest()->get(),
                                       
        );
        //dd($data['account_name']);
        return view('admin.report.purchase_print')->with($data);
    }

    public function saleBookReport(Request $req){


        if(isset($req->account_id)){
            $data = array(  
                'title' => 'Sales Book Ledger',
                'account_name' => Account::where('id',hashids_decode($req->account_id))->latest()->get(),
                'acounts' => Account::latest()->get(),
                'from_date' => $req->from_date ,
                'to_date' => $req->to_date ,
                'sales'  => AccountLedger::when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('created_at', '>=', $req->from_date)->whereDate('created_at', '<=', $req->to_date);
                                            })->where('purchase_id',"")->latest()->get(),
            );
            //dd($data['purchases']);
        }else{
            $data = array(
                'title' => 'Sales Book Ledger',
                'acounts' => Account::latest()->get(),
                'items' => Item::where('type','purchase')->latest()->get(),
                'accounts'  => Account::latest()->get(),
                'sales' => "",
                
            
            );
        }
        
        

        
        return view('admin.report.sale_book_report')->with($data);
    }

    public function SaleReportPdf(Request $req){
        //dd($req->all());
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'sales'  => SaleBook::with(['item','account'])->when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),

                                       
        );
        //dd($data['sales']);
        $pdf = Pdf::loadView('admin.report.sale_pdf', $data);
        return $pdf->download('sale_book_report.pdf');
    }

    public function SaleReportPrint(Request $req){
        //dd($req->from_date);
        $toDate = Carbon::parse($req->from_date);
        $fromDate = Carbon::parse($req->to_date);
  
        $days = $fromDate->diffInDays($toDate);

        $data = array(
            'purchases'  => SaleBook::when(isset($req->item_id), function($query) use ($req){
                                            $query->where('item_id', hashids_decode($req->item_id));
                                        })->when(isset($req->account_id), function($query) use ($req){
                                            $query->where('account_id', hashids_decode($req->account_id));
                                        })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                            $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                        })->latest()->get(),
            'to_date' => $req->to_date,
            'from_date' => $req->from_date,
            'days' => $days,
            'item_name' =>  Item::findOrFail(hashids_decode($req->item_id)),
            'account_name' =>  Account::findOrFail(hashids_decode($req->account_id)),

                                       
        );
        //dd($data['to_date']);
        $pdf = Pdf::loadView('admin.report.sale_pdf', $data);
        return $pdf->download('sale_book_report.pdf');
    }
}
