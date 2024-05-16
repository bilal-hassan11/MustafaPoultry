<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseChickRequest;
use App\Http\Requests\SaleChickRequest;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Category;
use App\Models\Companies;
use App\Models\Item;
use App\Models\PurchaseChick;
use App\Models\SaleChick;
use App\Models\AccountLedger;
use App\Models\Shade;
use App\Models\ShadeItemAdded;
use App\Models\ItemAvailable;
use Carbon\Carbon;
use App\Models\PurchaseMedicine;

class ChickController extends Controller
{

    public function purchase_chick(Request $req){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Purchase Chicks',
            'accounts'          => Account::latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'shades'            => Shade::latest()->get(),
            'pending_purchase'  => PurchaseChick::where('rate', 0)->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Chick')->first(),
            'purchase_chicks'   => PurchaseChick::with(['company','account','item'])->when(isset($req->parent_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->parent_id));
                                    })->when(isset($req->invoice_no), function($query) use ($req){
                                        $query->where('invoice_no',$req->invoice_no);
                                    })
                                    
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })->orderBy('date','desc')->latest()->get(),
        );
        //dd($data['purchase_chicks']);
        return view('admin.chick.purchase_chick')->with($data);
    }

    public function storePurchaseChick(Request $req){
        
        
        $item      = Item::findOrFail(hashids_decode($req->item_id));
        
        if(isset($req->purchase_chick_id) && !empty($req->purchase_chick_id)){//update the recrod
            $purchase = PurchaseChick::findOrFail(hashids_decode($req->purchase_chick_id));
            $msg      = 'Purcahse chick updated successfully';
            
             $purchase->date = $req->date;
             $purchase->invoice_no        = $req->Invoice_no;
             
            $purchase->company_id   = (int) hashids_decode($req->company_id);
            $purchase->item_id      = (int) hashids_decode($req->item_id);
            $purchase->account_id   = (int) hashids_decode($req->account_id);
            $purchase->rate         = (int) $req->rate;
            $purchase->quantity     = (int) $req->quantity;
            $purchase->net_ammount  = (int) ($req->rate * $req->quantity);
            $purchase->status       = $req->status;
            $purchase->remarks      = $req->remarks;
            $purchase->save();
    
            Item::find(hashids_decode($req->item_id))->increment('stock_qty',$req->quantity);//increment item stock

            
             $ac_le = AccountLedger::where('purchase_chick_id',hashids_decode($req->purchase_chick_id))->get();
                
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::with(['account'])->findOrFail($ac_id);
            
            $id = SaleChick::with(['item','account'])->latest('created_at')->first();
            
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;
    
            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
    
    
            $led_get_amt                       = $purchase->net_ammount ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            
            $accountledger->date               = $req->date;
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = $purchase->id;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $led_get_amt ;
            $accountledger->description      = ' Item:'.$item_name .',  Quantity:'.$req->quantity.', Rate:'.$req->rate.',  Account #'.'['.$account->id.']'.$account_name;
            $accountledger->save();
            
            
            
        }else{
            
            //add new record
            $purchase = new PurchaseChick;
            $msg      = 'Purchase chick added successfully';
            
            $purchase->date = $req->date;
            $purchase->invoice_no        = $req->Invoice_no;
            $purchase->company_id   = (int) hashids_decode($req->company_id);
            $purchase->item_id      = (int) hashids_decode($req->item_id);
            $purchase->account_id   = (int) hashids_decode($req->account_id);
            $purchase->rate         = (int) $req->rate;
            $purchase->quantity     = (int) $req->quantity;
            $purchase->net_ammount  = (int) ($req->rate * $req->quantity);
            $purchase->status       = $req->status;
            $purchase->remarks      = $req->remarks;
            $purchase->save();
    
            Item::find(hashids_decode($req->item_id))->increment('stock_qty',$req->quantity);//increment item stock
    
            //Account Ledger
            $accountledger = new AccountLedger();
            $id = SaleChick::with(['item','account'])->latest('created_at')->first();
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;
    
            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
    
            $led_get_amt                       = $purchase->net_ammount ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = $purchase->id;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $led_get_amt ;
            $accountledger->description      = ' Item:'.$item_name .',  Quantity:'.$req->quantity.', Rate:'.$req->rate.',  Account #'.'['.$account->id.']'.$account_name;
            $accountledger->save();
            
            
        }
        

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.chicks.purchase_chick')
        ]);

    }

    public function editPurchaseChick($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Purchase Chicks',
            'accounts'          => Account::latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'shades'          => Shade::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Chick')->first(),
            'pending_purchase'  => PurchaseChick::where('rate', 0)->latest()->get(),
            'purchase_chicks'   => PurchaseChick::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
            'edit_purchase'     => PurchaseChick::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.chick.purchase_chick')->with($data);
    }

    public function deletePurchaseChick($id){
        PurchaseChick::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purchase chick deleted successfully',
            'reload'    => true
        ]);
    }

    public function sale_chick(Request $req){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Sale Chicks',
            'expire_medicine'   => $expire_medicine,
            'accounts'          => Account::latest()->get(),
            'shades'          => Shade::latest()->get(),
            'pending_sale'  => SaleChick::where('rate', 0                      )->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Chick')->first(),
            'sale_chicks'       => SaleChick::with(['company','account','item'])->when(isset($req->parent_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->parent_id));
                                    })->when(isset($req->invoice_no), function($query) use ($req){
                                        $query->where('invoice_no',$req->invoice_no);
                                    })
                                    
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })->orderBy('date','desc')->get(),
        );
        return view('admin.chick.sale_chick')->with($data);
    }
    
    public function storeSaleChick(Request $req){
        
        
        $item      = Item::findOrFail(hashids_decode($req->item_id));
   
        if(isset($req->sale_chick_id) && !empty($req->sale_chick_id)){
            //update the recrod
            $sale = SaleChick::findOrFail(hashids_decode($req->sale_chick_id));
            $msg      = 'Sale chick updated successfully';
            
            $sale->date = $req->date;
            if($req->shade_id == null){
                $sale->shade_id          = 0;
            }else{
                $sale->shade_id          = hashids_decode($req->shade_id);
            }
            $sale->invoice_no        = $req->Invoice_no;
            $sale->company_id   =  hashids_decode($req->company_id);
            $sale->item_id      =  hashids_decode($req->item_id);
            $sale->account_id   =  hashids_decode($req->account_id);
            $sale->rate         =  $req->rate;
            $sale->quantity     =  $req->quantity;
            $sale->net_ammount  =  ($req->rate * $req->quantity);
            $sale->status       = $req->status;
            $sale->remarks      = $req->remarks;
            $sale->save();
    
            Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//increment item stock
    
            //Account Ledger
            $ac_le = AccountLedger::where('sale_chick_id',hashids_decode($req->sale_chick_id))->get();
                
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::with(['account'])->findOrFail($ac_id);
            
            $id = SaleChick::with(['item','account'])->latest('created_at')->first();
    
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;
    
            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
    
                
            $led_get_amt                       = $sale->net_ammount ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
                
            $accountledger->sale_chick_id          = $sale->id;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = $led_get_amt ;
            $accountledger->credit           = 0 ;
             $accountledger->description      = ' Item:'.$item_name .',  Quantity:'.$req->quantity.', Rate:'.$req->rate.',  Account #'.'['.$account->id.']'.$account_name;
            $accountledger->save();
            
            
        }else{//add new record
            $sale = new SaleChick;
            $msg      = 'Sale chick added successfully';
            
            $sale->date = $req->date;
            if($req->shade_id == null){
                $sale->shade_id          = 0;
            }else{
                $sale->shade_id          = hashids_decode($req->shade_id);
            }
            $sale->invoice_no        = $req->Invoice_no;
            $sale->company_id   =  hashids_decode($req->company_id);
            $sale->item_id      =  hashids_decode($req->item_id);
            $sale->account_id   =  hashids_decode($req->account_id);
            $sale->rate         =  $req->rate;
            $sale->quantity     =  $req->quantity;
            $sale->net_ammount  =  ($req->rate * $req->quantity);
            $sale->status       = $req->status;
            $sale->remarks      = $req->remarks;
            $sale->save();
    
            Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//increment item stock
    
                //Account Ledger
            $accountledger = new AccountLedger();
            $id = SaleChick::with(['item','account'])->latest('created_at')->first();
    
                $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;
    
            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;
    
                
            $led_get_amt                       = $sale->net_ammount ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
                
            $accountledger->sale_chick_id          = $sale->id;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = $led_get_amt ;
            $accountledger->credit           = 0 ;
              $accountledger->description      = ' Item:'.$item_name .',  Quantity:'.$req->quantity.', Rate:'.$req->rate.',  Account #'.'['.$account->id.']'.$account_name;
            $accountledger->save();

        }
        

        
        // if($req->shade_id != null){
                    
        //     //Add Item To Shade 
        //     $shade_item = new ShadeItemAdded();
                
        //     $shade_item->reference_type  = "chick";
        //     $shade_item->reference_id  = $sale->id;
        //     $shade_item->date              = $req->date;
        //     $shade_item->shade_id          = hashids_decode($req->shade_id);
        //     $shade_item->category_id       = 2;
        //     $shade_item->item_id           = hashids_decode($req->item_id);
        //     $shade_item->added_quantity    = $req->quantity;
        //     $get_amt                       = $sale->net_ammount ; 
        //     $shade_item->ammount           = $get_amt;
        //     $shade_item->status           = "available";
        //     $shade_item->save();


        
        
        //     if (ItemAvailable::where('item_id', '=',hashids_decode($req->item_id) )->exists()) {
               
        //         ItemAvailable::where('item_id', '=',hashids_decode($req->item_id))->increment('stock_qty',$req->quantity);//increment item stock

        //     }else{

        //         $available_item = new ItemAvailable();
                
        //         $available_item->reference_type  = "chick";
        //         $available_item->reference_id  = $sale->id;
        //         $available_item->date              = $req->date;
        //         $available_item->shade_id          = hashids_decode($req->shade_id);
        //         $available_item->category_id       = 2;
        //         $available_item->item_id           = hashids_decode($req->item_id);
        //         $available_item->stock_qty         = $req->quantity;
        //         $available_item->status            = "available";
        //         $available_item->save();

        //     }
        // }

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.chicks.sale_chick')
        ]);

    }

    public function editSaleChick($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Edit sale Chicks',
            'expire_medicine'   => $expire_medicine,
            'shades'          => Shade::latest()->get(),
            'accounts'          => Account::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Chick')->first(),
            'sale_chicks'       => SaleChick::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
            
            'pending_sale'  => SaleChick::where('rate', 0                      )->latest()->get(),
            'edit_sale'         => SaleChick::findOrFail(hashids_decode($id)),
            'is_update'         => true
        );
        return view('admin.chick.sale_chick')->with($data);
    }

    public function deleteSaleChick($id){
        SaleChick::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Sale chick deleted successfully',
            'reload'    => true
        ]);
    }
    
}
