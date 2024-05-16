<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\Outward;
use App\Models\OutwardDetail;
use App\Models\SaleMurghi;
use App\Models\AccountLedger;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PurchaseMedicine;

class SaleMurghiController extends Controller
{
    public function index(Request $req){


        $month = date('m');
        $tot_qty = SaleMurghi::whereMonth('date', $month)->latest()->get()->sum('quantity');
        $tot_amt = SaleMurghi::whereMonth('date', $month)->latest()->get()->sum('net_ammount');
       
        //Invoice
        $inv_no = SaleMurghi::latest()->first();
        
        if($inv_no == null){
           
            $inv_no['invoice_no'] = "GHMI-00";
        
        }else{

            $g = $inv_no['invoice_no'];
        }

        $ac = explode("-",$inv_no['invoice_no']);
        $p = "GHMI-0";
        $v = $ac[1]+ 1;
        $n = $p.$v;

        
        $data = array(
            'title'     => 'Sale Murghi',
            'accounts'  => Account::latest()->get(),
            'tot_qty' => $tot_qty,
            'tot_amt' => $tot_amt,
            'invoice_no'      =>$n,
            'pending_purchase'  => SaleMurghi::where('rate', 0                      )->latest()->get(),
            'items'     => Item::where('category_id',8)->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->get(),
            'sales'     => SaleMurghi::with(['account:id,name','item:id,name'])->when(isset($req->parent_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->parent_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                            })->orderBy('date', 'desc')->get(),
        );
        
        return view('admin.sale_murghi.add_sale')->with($data);
    }

    public function store(Request $req){

        
        //dd($req->all());
        if(check_empty($req->sale_id)){
            
            $sale = SaleMurghi::findOrFail(hashids_decode($req->sale_id));
            $msg  = 'Sale updated successfully';

            $sale->date            = $req->date;
            $sale->invoice_no         = $req->invoice_no;
            $sale->account_id      = hashids_decode($req->account_id);
            $sale->item_id         = hashids_decode($req->item_id);
            $sale->vehicle_no      = $req->vehicle_no;
            $sale->no_of_crate     = $req->no_of_crate;
            $sale->net_weight     = $req->net_weight;

            $sale->rate            = $req->rate;
            $sale->quantity        =  $req->quantity;
            $sale->gross_ammount   = $req->gross_ammount;
            $sale->average         = $req->average;
            $sale->net_ammount     = $req->net_ammount;
            $sale->other_charges  = $req->other_charges;
            $sale->remarks         = $req->remarks;
            $sale->save();
            
            
            //Account Ledger
            $ac_le = AccountLedger::where('sale_murghi_id',hashids_decode($req->sale_id))->get();
                
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::with(['account'])->findOrFail($ac_id);
            
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;

            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
            $account_id = $account->id;
            
            
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = hashids_decode($req->sale_id);
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;

            $accountledger->cash_id          = 0;
            $accountledger->debit            = $req->net_ammount ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = 'Veh #'.$req->vehicle_no .',Crate:'.$req->no_of_crate.',  Total Murghi:'.$req->quantity.',  Total Weight:'.$req->net_weight.',kg  @ Rate :'.$req->rate.',  Account #'.'['.$account_id.']'.$account_name;
            
            $accountledger->save();
        
            
            return response()->json([
                'success' => $msg,
                'redirect'  => route('admin.sale_murghis.index'),
            ]);

        }else{

            $sale = new SaleMurghi();
            $msg  = 'Sale added successfully';

            $sale->date            = $req->date;
            $sale->invoice_no         = $req->invoice_no;
            $sale->account_id      = hashids_decode($req->account_id);
            $sale->item_id         = hashids_decode($req->item_id);
            $sale->vehicle_no      = $req->vehicle_no;
            $sale->no_of_crate     = $req->no_of_crate;
            $sale->net_weight     = $req->net_weight;

            $sale->rate            = $req->rate;
            $sale->quantity        =  $req->quantity;
            $sale->gross_ammount   = $req->gross_ammount;
            $sale->average         = $req->average;
            $sale->net_ammount     = $req->net_ammount;
            $sale->other_charges  = $req->other_charges;
            $sale->remarks         = $req->remarks;
            $sale->save();
            
            //Account Ledger
            $accountledger = new AccountLedger();
            $id = SaleMurghi::with(['item','account'])->latest('created_at')->first();
            
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;

            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
            $account_id = $account->id;
            
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = $id->id;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;

            $accountledger->cash_id          = 0;
            $accountledger->debit            = $req->net_ammount ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = 'Veh #'.$req->vehicle_no .',Crate:'.$req->no_of_crate.',  Total Murghi:'.$req->quantity.',  Total Weight:'.$req->net_weight.',kg  @ Rate :'.$req->rate.',  Account #'.'['.$account_id.']'.$account_name;
            $accountledger->save();
        
            Item::find(hashids_decode($req->item_id))->decrement('stock_qty', $req->quantity);//increment item stock

            return response()->json([
                'success' => 'Sale added successfully',
                'redirect'  => route('admin.sale_murghis.index'),
            ]);


        }
        
        


    }

    public function edit($id){
        
       
        $data = array(
            'title'     => 'Edit Sale Murghi',
            'accounts'  => Account::latest()->get(),
            'pending_purchase'  => SaleMurghi::where('rate',0)->latest()->get(),
            'items'     => Item::where('category_id',8)->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->get(),
            'sales'     => SaleMurghi::with(['account','item'])->latest()->get(),
            'edit_sale' => SaleMurghi::with(['account', 'item'])->where('id',hashids_decode($id))->first(),
            'is_update' => true,
        );
        
        return view('admin.sale_murghi.add_sale')->with($data);
    }

    public function delete($id){
        SaleMurghi::destroy(hashids_decode($id));

        $ac_id = AccountLedger::where('sale_murghi_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);

        return response()->json([
            'success'   => 'Sale delted successfully',
            'reload'    => true,
        ]);
    }

    public function accountDetails($id){
        $account = Account::findOrFail(hashids_decode($id));
        return response()->json([
            'account'   => $account
        ]);
    }

    public function allSales(){
        
        $data = array(
            'title' => 'All sales',
            'sales'     => SaleBook::with(['outwardDetail.item'])->latest()->get(),
        );
        // dd($data['sales'][0]);
        return view('admin.sales_book.all_sales')->with($data);
    }

    public function editSale($id){
        $data = array(
            'title'     => 'Edit sale',
            'sales'     => SaleBook::with(['outwardDetail.item'])->latest()->get(),
            'edit_sale' => SaleBook::findOrfail(hashids_decode($id)),
            'accounts'  => Account::latest()->get(),
            'is_update' => true
        );
        return view('admin.sales_book.all_sales')->with($data);
    }

    public function updateSale(Request $req){
        
        $sale = SaleBook::findOrFail(hashids_decode($req->sale_book_id));
        
        $total_amount           = $sale->inward->item->price * $req->no_of_bags;
        $commission             = ($total_amount * $sale->account->commission)/100;
        $discount               = $req->no_of_bags * $sale->account->discount;
        $sale->date             = $req->date;
        $sale->gp_no            = $req->gp_no;
        $sale->vehicle_no       = $req->vehicle_no;
        $sale->account_id       = hashids_decode($req->account_id);
        $sale->sub_dealer_name  = $req->sub_dealer_name;
        $sale->no_of_bags       = $req->no_of_bags;
        $sale->bag_rate         = $req->bag_rate;
        $sale->fare             = $req->fare;
        $sale->net_ammount       = $total_amount - ($commission+$discount);
        $sale->save();

        return response()->json([
            'success'   => 'Sale book updated successfully',
            'redirect'  => route('admin.sales.all_sales')
        ]);
    }

    public function deleteSale($id){
        SaleBook::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Sale deleted successfully',
            'reload'    => true
        ]);
    }
}