<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseFeedRequest;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\Outward;
use App\Models\OutwardDetail;
use App\Models\SaleBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use App\Models\Category;
use App\Models\PurchaseFeed;
use App\Models\SaleFeed;
use App\Models\Shade;
use App\Models\ShadeItemAdded;
use App\Models\ItemAvailable;
use Carbon\Carbon;
use App\Models\PurchaseMedicine;
use App\Models\ReturnFeed;

class FeedController extends Controller
{
    public function return_feed(Request $req){

        $data = array(
            'title'             => 'Return Feeds',
            'accounts'          => Account::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'purchase_feed'     => ReturnFeed::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
        );
        return view('admin.feed.return_feed')->with($data);
        
    }

    public function storeReturnFeed(Request $req){
        //dd($req->all());
        
        $item      = Item::findOrFail(hashids_decode($req['item_id']));
        
        if(isset($req['return_feed_id']) && !empty($req['return_feed_id'])){//update the recrod
            $purchase = ReturnFeed::findOrFail(hashids_decode($req['return_feed_id']));
            
            $msg      = 'Purcahse feed updated successfully';
            
            $ac_le = AccountLedger::where('return_feed_id',hashids_decode($req['return_feed_id']))->latest()->get();
            $ac_id = $ac_le[0]->id;
            
            $accountledger = AccountLedger::findOrFail($ac_id);
            
           //previous Item Increment
            Item::find($purchase->item_id)->increment('stock_qty',$purchase->quantity);//increment item stock
            
            Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//deccrement current item stock
            
        }else{//add new record
            $purchase = new ReturnFeed;
            $msg      = 'Purchase feed added successfully';
            $accountledger = new AccountLedger();
            
            Item::find(hashids_decode($req['item_id']))->increment('stock_qty',$req['quantity']);//increment item stock
        }

        $purchase->date = $req['date'];
        $purchase->company_id    =  hashids_decode($req['company_id']);
        $purchase->item_id       =  hashids_decode($req['item_id']);
        $purchase->account_id    =  hashids_decode($req['account_id']);
        $purchase->item_rate          =  $req->rate;
        $purchase->rate =  $req->purchase_rate;
        $dif = $purchase->rate - $purchase->purchase_rate ;

        $purchase->quantity     =  $req['quantity'];
        $purchase->discount     =  $dif * $req['quantity'] ;

        $purchase->net_ammount  =  $req['net_ammount'];
        $purchase->status       = $req['status'];
        $purchase->remarks      = $req['remarks'];
        $purchase->save();

        //Account Ledger
        
        $id = ReturnFeed::with(['item','account'])->latest('created_at')->first();

        $led_get_amt                       = $req['net_ammount'] ; 
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
        $accountledger->sale_murghi_id          = 0;
        $accountledger->general_purchase_id          = 0;
        $accountledger->general_sale_id      = 0;
        $accountledger->expense_id      = 0;
        $accountledger->return_feed_id      = $purchase->id;
        $accountledger->return_chick_id      = 0;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = 0 ;
        $accountledger->credit           = $led_get_amt ;
        
        $accountledger->description      = 'Return Item:'.$id->item->name .',  Quantity:'.$req['quantity'].',  Account #'.'['.$id->account->id.']'.$id->account->name;
        $accountledger->save();
        
        

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.feeds.return_feed')
        ]);
    }

    public function editReturnFeed($id){
    
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();


        $data = array(
            'title'             => 'Return Feeds',
            'expire_medicine'   => $expire_medicine,
            'accounts'          => Account::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'purchase_feed'     => ReturnFeed::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
            'edit_feed'         => ReturnFeed::findOrFail(hashids_decode($id)),
            'is_updated'        => true
        );
        
        //dd($data['']);
        return view('admin.feed.return_feed')->with($data);
        
        
    }

    public function deleteReturnFeed($id){
        ReturnFeed::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Return feed deleted successfully',
            'reload'    => true
        ]);
    }
    
    //Purchase
    public function purchase_feed(Request $req){
      
        $n = 0;

        $data = array(
            'title'             => 'Purchase Feeds',
            'accounts'          => Account::latest()->get(),
            'items'             => Item::where('category_id',3)->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'invoice_no'        =>  $n,
            'purchase_feed'     =>  PurchaseFeed::with(['company', 'account'])
                                        ->when(isset($req->parent_id), function ($query) use ($req) {
                                            $query->where('account_id', hashids_decode($req->parent_id));
                                        })
                                        ->when(isset($req->invoice_no), function ($query) use ($req) {
                                            $query->where('invoice_no', $req->invoice_no);
                                        })
                                        ->when(isset($req->item_id), function ($query) use ($req) {
                                            $query->where('item_id', $req->item_id);
                                        })
                                        ->when(isset($req->from_date) && isset($req->to_date), function ($query) use ($req) {
                                            $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                        })
                                        ->orderBy('date', 'desc')
                                        ->get(),
        );

       
        return view('admin.feed.purchase_feed')->with($data);
    }

    public function storePurchaseFeed(Request $req){
        //dd($req->all());
        
        $item      = Item::findOrFail(hashids_decode($req['item_id']));
        
        if(isset($req['purchase_feed_id']) && !empty($req['purchase_feed_id'])){//update the recrod
            $purchase = PurchaseFeed::findOrFail(hashids_decode($req['purchase_feed_id']));
            $msg      = 'Purcahse feed updated successfully';
             $ac_le = AccountLedger::where('purchase_feed_id',hashids_decode($req['purchase_feed_id']))->latest()->get();
            $ac_id = $ac_le[0]->id;
            
            $accountledger = AccountLedger::findOrFail($ac_id);
            
        }else{//add new record
        
            $purchase = new PurchaseFeed;
            $msg      = 'Purchase feed added successfully';
            $accountledger = new AccountLedger();
        }
        
        $purchase->date = $req['date'];
        $purchase->invoice_no    = $req->Invoice_no;
        $purchase->company_id    =  hashids_decode($req['company_id']);
        $purchase->item_id       =  hashids_decode($req['item_id']);
        $purchase->account_id    =  hashids_decode($req['account_id']);
        $purchase->item_rate          =  $req->rate;
        $purchase->rate =  $req->purchase_rate;
        $dif = $purchase->rate - $purchase->rate ;

        $purchase->quantity     =  $req['quantity'];
        $purchase->discount     =  $dif * $req['quantity'] ;

        $purchase->net_ammount  =  $req['net_ammount'];
        $purchase->status       = $req['status'];
        $purchase->remarks      = $req['remarks'];
        $purchase->save();

        //Account Ledger
        
        $id = PurchaseFeed::with(['item','account'])->latest('created_at')->first();
        $ac      = Account::findOrFail(hashids_decode($req['account_id']));
        
        $led_get_amt                       = $req['net_ammount'] ; 
        $accountledger->account_id = hashids_decode($req->account_id);
        $accountledger->date               = $req->date;
        $accountledger->sale_chick_id          = 0;
        $accountledger->purchase_chick_id          = 0;
        $accountledger->sale_medicine_id          = 0;
        $accountledger->return_medicine_id          = 0;
        $accountledger->expire_medicine_id          = 0;
        $accountledger->purchase_medicine_id          = 0;
        $accountledger->sale_feed_id          = 0;
        $accountledger->purchase_feed_id          = $purchase->id;
        $accountledger->purchase_murghi_id          = 0;
        $accountledger->sale_murghi_id          = 0;
        $accountledger->general_purchase_id          = 0;
        $accountledger->general_sale_id      = 0;
        $accountledger->expense_id      = 0;

        $accountledger->cash_id          = 0;
        $accountledger->debit            = 0 ;
        $accountledger->credit           = $led_get_amt ;
        
        $accountledger->description      = ' Item:'.$id->item->name .',  Quantity:'.$req['quantity'].'@Rate'.$req->purchase_rate. ', Account #'.'['.$ac->id.']'.$ac->name;
        $accountledger->save();
        
        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.feeds.purchase_feed')
        ]);
    }

    public function editPurchaseFeed($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Edit Purchase Feed',
            'expire_medicine'   => $expire_medicine,
            'accounts'          => Account::latest()->get(),
            'items'             => Item::where('category_id',3)->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'purchase_feed'     => PurchaseFeed::with(['company','account','item'])->latest()->get(),
            'edit_feed'         => PurchaseFeed::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.feed.purchase_feed')->with($data);
    }

    public function deletePurchaseFeed($id){
        PurchaseFeed::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purchase feed deleted successfully',
            'reload'    => true
        ]);
    }

    public function sale_feed(Request $req){
        
        
        $n = 0;

        $data = array(
            'title'     => 'Sale Feeds',
            'accounts'          => Account::latest()->get(),
            'shades'          => Shade::latest()->get(),
            'invoice_no'      =>$n,
            'items'             => Item::where('category_id',3)->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'sale_feed'       => SaleFeed::with(['company','account','item'])->when(isset($req->parent_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->parent_id));
                                    })->when(isset($req->invoice_no), function($query) use ($req){
                                        $query->where('invoice_no',$req->invoice_no);
                                    })
                                    ->when(isset($req->item_id), function($query) use ($req){
                                        $query->where('item_id',$req->item_id);
                                    })
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })
                                    ->latest()->get(),            
        );
        
        //dd($data['items']);
        return view('admin.feed.sale_feed')->with($data);
        
    }
    

    public function storeSaleFeed(Request $req){
        
        
        if(isset($req['sale_feed_id']) && !empty($req['sale_feed_id'])){
            //update the recrod
            
            $ac      = Account::findOrFail(hashids_decode($req['account_id']));
            //$p_amt  = $ac->sale_feed_discount *  $req['quantity'];
            
            
            //$net_profit = $p_amt; 
            
            $sale = SaleFeed::findOrFail(hashids_decode($req['sale_feed_id']));
            $msg      = 'Sale feed updated successfully';
            
            $sale->date = $req['date'];
            $sale->invoice_no        = $req->Invoice_no;
            $sale->company_id    =  hashids_decode($req['company_id']);
            $sale->item_id       =  hashids_decode($req['item_id']);
            $sale->account_id    =  hashids_decode($req['account_id']);
            $sale->rate          =  $req->rate;
            $sale->fare          =  $req->fare;
            
            $sale->sale_rate =  $req->sale_rate;
            $dif = $req->rate - $sale->sale_rate ;
    
            $sale->quantity     =  $req['quantity'];
            $sale->discount     =  0 ;
    
            $sale->purchase_ammount  =  $req->rate * $req['quantity'] ;
            $sale->sale_ammount  =  $req->sale_rate * $req['quantity'];
            
            $sale->profit  =  0;
    
            $sale->net_ammount  =  $req['net_ammount'];
            $sale->status       = $req['status'];
            $sale->remarks      = $req['remarks'];
            $sale->save();
    
            Item::find(hashids_decode($req['item_id']))->decrement('stock_qty',$req['quantity']);//increment item stock
    
            //Account Ledger
            
            $ac_le = AccountLedger::where('sale_feed_id',hashids_decode($req->sale_feed_id))->latest()->get();
           
            $ac_id = $ac_le[0]->id;
            
            $accountledger = AccountLedger::findOrFail($ac_id);
            
            $led_get_amt                       = $sale->net_ammount  ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = $sale->id;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
            $accountledger->return_feed_id      = 0;
            $accountledger->return_chick_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = $led_get_amt  ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = ' Item Quantity:'.$req['quantity'].',Rate:'.$req->sale_rate.',  Account #'.'['.$ac->id.']'.$ac->name;
            $accountledger->save();
            
        }else{//add new record
            
            $ac      = Account::findOrFail(hashids_decode($req['account_id']));
            //$p_amt  = $ac->sale_feed_discount *  $req['quantity'];
            
            //$net_profit = $p_amt; 
            
            $sale = new SaleFeed;
            $msg      = 'Sale feed added successfully';
            $accountledger = new AccountLedger();
            
            $sale->date = $req['date'];
            $sale->invoice_no        = $req->Invoice_no;
            $sale->company_id    =  hashids_decode($req['company_id']);
            $sale->item_id       =  hashids_decode($req['item_id']);
            $sale->account_id    =  hashids_decode($req['account_id']);
            $sale->rate          =  $req->rate;
            $sale->fare          =  $req->fare;
            
            $sale->sale_rate =  $req->sale_rate;
            $dif = $sale->rate - $sale->sale_rate ;
    
            $sale->quantity     =  $req['quantity'];
            $sale->discount     =  0 ;
    
            $sale->purchase_ammount  =  $req->rate * $req['quantity'] ;
            $sale->sale_ammount  =  $req->sale_rate * $req['quantity'];
            $sale->profit  =  0;
    
    
            $sale->net_ammount  =  $req['net_ammount'];
            $sale->status       = $req['status'];
            $sale->remarks      = $req['remarks'];
            $sale->save();
    
            Item::find(hashids_decode($req['item_id']))->decrement('stock_qty',$req['quantity']);//increment item stock
    
            //Account Ledger
            
            $id = SaleFeed::with(['item','account'])->latest('created_at')->first();
    
            $led_get_amt  = $req['net_ammount'] + 0 ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = $sale->id;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = $led_get_amt ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = ' Item:'.$id->item->name .',  Quantity:'.$req['quantity'].',@Rate:'.$req->sale_rate.',  Account #'.'['.$id->account->id.']'.$id->account->name;
            $accountledger->save();
            
            
        }

       

        // if($req->shade_id == null){
        //     $purchase->shade_id          = 0;
        // }else{
        //     $purchase->shade_id          = hashids_decode($req->shade_id);
        // }
        
        // if($req->shade_id != null){
                    
        //     //Add Item To Shade 
        //     $shade_item = new ShadeItemAdded();
                
        //     $shade_item->reference_type  = "Feed";
        //     $shade_item->reference_id  = $purchase->id;
        //     $shade_item->date              = $req->date;
        //     $shade_item->shade_id          = hashids_decode($req->shade_id);
        //     $shade_item->category_id       = 3;
        //     $shade_item->item_id           = hashids_decode($req->item_id);
        //     $shade_item->added_quantity    = $req['quantity'];
        //     $get_amt                       = $purchase->sale_ammount ; 
        //     $shade_item->ammount           = $get_amt;
        //     $shade_item->status           = "available";
        //     $shade_item->save();


        
        
        //     if (ItemAvailable::where('item_id', '=',hashids_decode($req->item_id) )->exists()) {
               
        //         ItemAvailable::where('item_id', '=',hashids_decode($req->item_id))->increment('stock_qty',$req['quantity']);//increment item stock

        //     }else{

        //         $available_item = new ItemAvailable();
                
        //         $available_item->reference_type  = "Feed";
        //         $available_item->reference_id  = $purchase->id;
        //         $available_item->date              = $req->date;
        //         $available_item->shade_id          = hashids_decode($req->shade_id);
        //         $available_item->category_id       = 3;
        //         $available_item->item_id           = hashids_decode($req->item_id);
        //         $available_item->stock_qty         = $req['quantity'];
        //         $available_item->status            = "available";
        //         $available_item->save();

        //     }
        // }

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.feeds.sale_feed')
        ]);
    }

    public function editSaleFeed($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'             => 'Sale Feed',
            'expire_medicine'   => $expire_medicine,
            'accounts'          => Account::latest()->get(),
            'shades'          => Shade::latest()->get(),
            'items'             => Item::where('category_id',3)->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Feed')->first(),
            'sale_feed'         => SaleFeed::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),            
            'edit_feed'         => SaleFeed::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.feed.sale_feed')->with($data);
    }
    
    public function deleteSaleFeed($id){
        PurchaseFeed::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Sale feed deleted successfully',
            'reload'    => true
        ]);
    }

    public function accountDetails($id){
        $account = Account::findOrFail(hashids_decode($id));
        return response()->json([
            'account'   => $account
        ]);
    }
    
     public function accountBalance($id){
         
        $account = Account::findOrFail(hashids_decode($id));
         $balance = $account->opening_balance;
        $t_cr = AccountLedger::where('account_id',$account->id)->sum('credit');
        $t_dr = AccountLedger::where('account_id',$account->id)->sum('debit');
             
                
                if($account->account_nature == "credit"){
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
              
                $account->opening_balance = $dues;
                $account->account_nature = $a_n;
        
        return response()->json([
            'account'   => $account
        ]);
    }

    public function editSale($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $data = array(
            'title'     => 'Edit sale',
            'expire_medicine'   => $expire_medicine,
            'sales'     => SaleBook::with(['outwardDetail.item'])->latest()->get(),
            'edit_sale' => SaleBook::findOrfail(hashids_decode($id)),
            'accounts'  => Account::latest()->get(),
            'is_update' => true
        );
        return view('admin.sales_book.all_sales')->with($data);
    }   

    public function deleteSale($id){
        SaleBook::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Sale deleted successfully',
            'reload'    => true
        ]);
    }
}
