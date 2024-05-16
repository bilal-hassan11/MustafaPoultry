<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\PurchaseMurghi;
use App\Models\AccountLedger;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PurchaseMedicine;

class PurchaseMurghiController extends Controller
{
    public function index(Request $req){
        
        $month = date('m');
        $tot_qty = PurchaseMurghi::whereMonth('date', $month)->latest()->get()->sum('final_weight');
        $tot_amt = PurchaseMurghi::whereMonth('date', $month)->latest()->get()->sum('net_ammount');
        
        //Invoice
        $inv_no = PurchaseMurghi::latest()->first();
        
        if($inv_no == null){
           
            $inv_no['invoice_no'] = "GHPM-00";
        
        }else{

            $g = $inv_no['invoice_no'];
        }

        $ac = explode("-",$inv_no['invoice_no']);
        $p = "GHPM-0";
        $v = $ac[1]+ 1;
        $n = $p.$v;
        
        $data = array(
            'title'     => 'Purchase Book',
            'tot_qty' => $tot_qty,
            'tot_amt' => $tot_amt,
            'invoice_no'      =>$n,
            'pending_purchase'  => PurchaseMurghi::where('rate', 0                      )->latest()->get(),
            'purchases' => PurchaseMurghi::with(['account', 'item'])->when(isset($req->parent_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->parent_id));
                                            })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                            })->orderBy('created_at','desc')->get(),
            
            
            'accounts'  => Account::latest()->get(),
            'items'     => Item::where('type','purchase')->where('category_id',8)->latest()->get(),
            
            'account_types' => AccountType::whereNull('parent_id')->get(), 

        );
        return view('admin.murghi.add_purchase')->with($data);
    }

    public function store(Request $req){
        
        if(check_empty($req->purchase_id)){
                
            $purchase = PurchaseMurghi::findOrFail(hashids_decode($req->purchase_id));
            $msg      = 'Murghi Purchase updated successfully';

            $purchase->date              = $req->date;
            $purchase->item_id           = hashids_decode($req->item_id);
            $purchase->account_id        = hashids_decode($req->account_id);
            $purchase->vehicle_no        = $req->vehicle_no;
            $purchase->no_of_crate       = $req->no_of_crate;
            $purchase->rate              = $req->rate;
            $purchase->rate_detection    = $req->rate_detection;
            $purchase->final_rate        = $req->final_rate;
            $purchase->quantity          = $req->quantity;
            $purchase->gross_ammount     = $req->gross_ammount;
            $purchase->average           = $req->average;
            $purchase->fare              = $req->fare;
            $purchase->invoice_no         = $req->invoice_no;
            $purchase->other_charges     = $req->other_charges;
            $purchase->weight_difference = $req->weight_difference;
            $purchase->crate_weight      = $req->crate_weight;
            $purchase->feed_weight       = $req->feed_weight;
            $purchase->mortality_weight  = $req->mortality_weight;
            $purchase->final_weight      = $req->final_weight;
            $purchase->net_weight        = $req->net_weight;
            $purchase->net_ammount       = $req->net_ammount;
            $purchase->remarks           = $req->remarks;
            $purchase->save();
            
            
            //Account Ledger
            $ac_le = AccountLedger::where('purchase_murghi_id',hashids_decode($req->purchase_id))->get();
            
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::findOrFail($ac_id);

            
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;

            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
            $account_id = $account->id;
            
            $accountledger->account_id = hashids_decode($req->account_id);
            
           $led_get_amt                       = $req->net_ammount ; 
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
            $accountledger->purchase_murghi_id          = $purchase->id;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $led_get_amt ;
            $accountledger->description      = 'Veh #'.$req->vehicle_no .',Crate:'.$req->no_of_crate.',  Total Murghi:'.$req['quantity'].',  Total Weight:'.$req->net_weight.',kg  @ Rate :'.$req->rate.',  Account #'.'['.$account_id.']'.$account_name;
            $accountledger->save();
            
            return response()->json([
                'success'   => $msg,
                'redirect'    => route('admin.purchase_murghis.index')
            ]);

        }else{
            //dd($req->all());
            
            $purchase = new PurchaseMurghi();
            $msg      = 'Murghi Purchase added successfully';

            $purchase->date              = $req->date;
            $purchase->item_id           = hashids_decode($req->item_id);
            $purchase->account_id        = hashids_decode($req->account_id);
            $purchase->vehicle_no        = $req->vehicle_no;
            $purchase->no_of_crate       = $req->no_of_crate;
            $purchase->rate              = $req->rate;
            $purchase->rate_detection    = $req->rate_detection;
            $purchase->final_rate        = $req->final_rate;
            $purchase->quantity          = $req->quantity;
            $purchase->gross_ammount     = $req->gross_ammount;
            $purchase->average           = $req->average;
            $purchase->fare              = $req->fare;
            $purchase->invoice_no         = $req->invoice_no;
            $purchase->other_charges     = $req->other_charges;
            $purchase->weight_difference = $req->weight_difference;
            $purchase->crate_weight      = $req->crate_weight;
            $purchase->feed_weight       = $req->feed_weight;   
            $purchase->mortality_weight  = $req->mortality_weight;
            $purchase->final_weight      = $req->final_weight;
            $purchase->net_weight        = $req->net_weight;
            $purchase->net_ammount       = $req->net_ammount;
            $purchase->remarks           = $req->remarks;
            $purchase->save();
            
            
            //Account Ledger
            $accountledger = new AccountLedger();
            $id = PurchaseMurghi::with(['item','account'])->latest('created_at')->first();
    
            $led_get_amt                       = $req->net_ammount ; 
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
            $accountledger->purchase_murghi_id          = $purchase->id;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $led_get_amt ;
            $accountledger->description      = 'Veh #'.$req->vehicle_no .',Crate:'.$req->no_of_crate.',  Total Murghi:'.$req['quantity'].',  Total Weight:'.$req->net_weight.',kg  @ Rate :'.$req->rate.'';
            $accountledger->save();
            
            return response()->json([
                'success'   => $msg,
                'redirect'    => route('admin.purchase_murghis.index')
            ]);

        }
        
    }

    public function edit($id){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'     => 'Purchase Book',
            'accounts'  => Account::latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'items'     => Item::latest()->get(),
            'purchases' => PurchaseMurghi::with(['account', 'item'])->orderBy('id','desc')->get(),
            'pending_purchase'  => PurchaseMurghi::where('rate', 0                      )->latest()->get(),
            'edit_purchase' => PurchaseMurghi::with(['account', 'item'])->findOrFail(hashids_decode($id)),
            'inwards'   => PurchaseMurghi::with(['account', 'item'])->orderBy('id','desc')->get(),
            'is_update'     => true
        );
        return view('admin.murghi.add_purchase')->with($data);
    }

    public function delete($id){
        PurchaseMurghi::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purcahase deleted successfully',
            'reload'    => true
        ]);
    }
  

    public function allPurchase(){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title' => 'All purchase',
            'expire_medicine'   => $expire_medicine,
            'purchases'  => PurchaseMurghi::with(['inward.item'])->latest()->get(),
        );
        // dd($data['purchases'][0]);
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function editPurchase($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'         => 'Edit purchase',
            'expire_medicine'   => $expire_medicine,
            'purchases'     => PurchaseMurghi::with(['inward.item'])->latest()->get(),
            'edit_purchase' => PurchaseMurghi::findOrFail(hashids_decode($id)),
            'accounts'      => Account::latest()->get(),
            'is_update'     => true  
        );
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function updatePurchase(Request $req){
        
        $sale = PurchaseMurghi::findOrFail(hashids_decode($req->purchase_book_id));
        $sale->date             = $req->date;
        $sale->pro_inv_no       = $req->pro_inv_no;
        $sale->vehicle_no       = $req->vehicle_no;
        $sale->account_id       = hashids_decode($req->account_id);
        // $sale->sub_dealer_name  = $req->sub_dealer_name;
        $sale->no_of_bags       = $req->no_of_bags;
        $sale->bag_rate         = $req->bag_rate;
        $sale->fare             = $req->fare;
        $sale->save();

        return response()->json([
            'success'   => 'purchase book updated successfully',
            'redirect'  => route('admin.sales.all_sales')
        ]);
    }
}