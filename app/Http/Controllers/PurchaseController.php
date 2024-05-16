<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Inward;
use App\Models\Item;
use App\Models\PurchaseBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $req){
        $data = array(
            'title'     => 'Purchase Book',
            'accounts'  => Account::where('grand_parent_id',5)->latest()->get(),
            'items'     => Item::latest()->get(),
            // 'purchases' => PurchaseBook::with(['account', 'item'])->latest()->get(),
            'inwards'   => Inward::with(['account', 'item'])
                                    ->when(isset($req->parent_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->parent_id));
                                    })
                                    ->when(isset($req->vehicle_no), function($query) use ($req){
                                        $query->where('vehicle_no', $req->vehicle_no);
                                    })
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })
                                    ->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->get(), 

        );
        return view('admin.purchase_book.add_purchase')->with($data);
    }

    

    public function edit_inward(Request $req){
        dd($req->purchase_id);
        $purchase = new PurchaseBook();
        $msg      = 'Purchase added successfully';
        
        $purchase->date              = $req->purchase_date;
        $purchase->pro_inv_no        = $req->prod_inv_no;
        $purchase->item_id           = hashids_decode($req->item_id);
        $purchase->account_id        = hashids_decode($req->account_id);
        $purchase->vehicle_no        = $req->vehicle_no;
        $purchase->bag_rate          = $req->rate;
        $purchase->no_of_bags        = $req->no_of_begs;
        $purchase->commission        = $req->commission;
        $purchase->discount          = $req->discount;
        $purchase->fare              = $req->fare;
        $purchase->bilty_no          = $req->bilty_no;
        $purchase->loading_charges   = $req->loading_charges;
        $purchase->other_charges     = $req->others_charges;
        $purchase->company_weight    = $req->company_weight;
        $purchase->party_weight      = $req->party_weight;
        $purchase->weight_difference = $req->weight_difference;
        $purchase->posted_weight     = $req->posted_weight;
        $purchase->net_weight        = $req->company_weight - $req->posted_weight;
        $purchase->net_ammount       = $req->net_ammount;
        $purchase->remarks           = $req->remarks;
        $purchase->save();
        
        if(isset($req->purchase_id)){
            $update_inward = Inward::findOrFail(hashids_decode($req->purchase_id));
            $update_inward->purchase_status = "completed";
            $update_inward->save();
        }

        //Account Ledger
        $accountledger = new AccountLedger();
        
        $item = Item::findOrFail(hashids_decode($req->item_id));
        $item_name = $item->name;

        $account = Account::findOrFail(hashids_decode($req->account_id));
        $account_name = $account->name;

        $id = PurchaseBook::latest('created_at')->first();
        $accountledger->account_id = hashids_decode($req->account_id);
        
        $accountledger->purchase_id      = $id->id;
        $accountledger->item_id      = hashids_decode($req->item_id);
        $accountledger->vehicle_no      = $req->vehicle_no;
        $accountledger->no_of_bags      = $req->no_of_begs;
        $accountledger->fare            = $req->fare;
        $accountledger->rate            = $req->rate;
        $accountledger->sale_id          = 0;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = 0;
        $accountledger->credit           = $req->net_ammount ;
        $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', P.Id # '.$id->id.','.$req->no_of_begs .'Bags'. ',  Weight:'.$req->company_weight.'kg'.', '.$item_name .'@'.$req->rate.',  Account #'.'['.$account->id.']'.$account->name;
        $accountledger->save();
    
        // Item::find(hashids_decode($req->item_id))->increment('stock_qty', $req->company_weight);//increment item stock
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.purchases.index')
        ]);
        
    }

    public function store(Request $req){
      

        //dd($req->purchase_id);
        if(check_empty($req->purchase_id)){
            $get_purchase = PurchaseBook::where('inward','=',$req->purchase_id)->get();
            
            if($get_purchase->isEmpty() ){
                
                $purchase = new PurchaseBook();
                $msg      = 'Purchase added successfully';

                $purchase->date              = $req->purchase_date;
                $purchase->inward            = $req->purchase_id;

                $purchase->pro_inv_no        = $req->prod_inv_no;
                $purchase->item_id           = hashids_decode($req->item_id);
                $purchase->account_id        = hashids_decode($req->account_id);
                $purchase->vehicle_no        = $req->vehicle_no;
                $purchase->bag_rate          = $req->rate;
                $purchase->no_of_bags        = $req->no_of_begs;
                $purchase->commission        = $req->commission;
                $purchase->discount          = $req->discount;
                $purchase->fare              = $req->fare;
                $purchase->bilty_no          = $req->bilty_no;
                $purchase->loading_charges   = $req->loading_charges;
                $purchase->other_charges     = $req->others_charges;
                $purchase->company_weight    = $req->company_weight;
                $purchase->party_weight      = $req->party_weight;
                $purchase->weight_difference = $req->weight_difference;
                $purchase->posted_weight     = $req->posted_weight;
                $purchase->net_weight        = $req->company_weight - $req->posted_weight;
                $purchase->net_ammount       = $req->net_ammount;
                $purchase->remarks           = $req->remarks;
                $purchase->save();
                
                if(isset($req->purchase_id)){
                    $update_inward = Inward::findOrFail($req->purchase_id);
                    $update_inward->item_id = hashids_decode($req->item_id);
                    $update_inward->account_id = hashids_decode($req->account_id);
                    
                    $update_inward->vehicle_no = $req->vehicle_no;
                    $update_inward->fare = $req->fare;
                    $update_inward->bilty_no = $req->bilty_no;
                    $update_inward->company_weight = $req->company_weight;
                    $update_inward->purchase_status = "completed";
                    $update_inward->no_of_bags = $req->no_of_begs;;
                    $update_inward->save();
                }

                //Account Ledger
                $accountledger = new AccountLedger();
                
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;

                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;

                $id = PurchaseBook::latest('created_at')->first();
                $accountledger->account_id = hashids_decode($req->account_id);
                
                $accountledger->purchase_id      = $id->id;
                $accountledger->item_id      = hashids_decode($req->item_id);
                $accountledger->vehicle_no      = $req->vehicle_no;
                $accountledger->no_of_bags      = $req->no_of_begs;
                $accountledger->fare            = $req->fare;
                $accountledger->rate            = $req->rate;
                $accountledger->sale_id          = 0;
                $accountledger->cash_id          = 0;
                $accountledger->debit            = 0;
                $accountledger->credit           = $req->net_ammount ;
                $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', P.Id # '.$id->id.','.$req->no_of_begs .'Bags'. ',  Weight:'.$req->company_weight.'kg'.', '.$item_name .'@'.$req->rate.',  Account #'.'['.$account->id.']'.$account->name;
                $accountledger->save();

                return response()->json([
                    'success'   => $msg,
                    'redirect'    => route('admin.purchases.index')
                ]);


            }else{
               
                $g_purchase = PurchaseBook::where('inward','=',$req->purchase_id)->get();
                $p_id = $g_purchase[0]->id;
                
                $purchase = PurchaseBook::findOrFail($p_id);
                $msg      = 'Purchase updated successfully';

                $purchase->date              = $req->purchase_date;
                $purchase->inward            = $req->purchase_id;

                $purchase->pro_inv_no        = $req->prod_inv_no;
                $purchase->item_id           = hashids_decode($req->item_id);
                $purchase->account_id        = hashids_decode($req->account_id);
                $purchase->vehicle_no        = $req->vehicle_no;
                $purchase->bag_rate          = $req->rate;
                $purchase->no_of_bags        = $req->no_of_begs;
                $purchase->commission        = $req->commission;
                $purchase->discount          = $req->discount;
                $purchase->fare              = $req->fare;
                $purchase->bilty_no          = $req->bilty_no;
                $purchase->loading_charges   = $req->loading_charges;
                $purchase->other_charges     = $req->others_charges;
                $purchase->company_weight    = $req->company_weight;
                $purchase->party_weight      = $req->party_weight;
                $purchase->weight_difference = $req->weight_difference;
                $purchase->posted_weight     = $req->posted_weight;
                $purchase->net_weight        = $req->company_weight - $req->posted_weight;
                $purchase->net_ammount       = $req->net_ammount;
                $purchase->remarks           = $req->remarks;
                $purchase->save();
                
                if(isset($req->purchase_id)){
                    $update_inward = Inward::findOrFail($req->purchase_id);
                    $update_inward->item_id = hashids_decode($req->item_id);
                    $update_inward->account_id = hashids_decode($req->account_id);
                    
                    $update_inward->vehicle_no = $req->vehicle_no;
                    $update_inward->fare = $req->fare;
                    $update_inward->bilty_no = $req->bilty_no;
                    $update_inward->purchase_status = "completed";
                    $update_inward->no_of_bags = $req->no_of_begs;;
                    $update_inward->save();
                }

                //Account Ledger
                $ac_le = AccountLedger::where('purchase_id',$p_id)->get();
               
                $ac_id = $ac_le[0]->id;
                $accountledger = AccountLedger::findOrFail($ac_id);

                //dd($accountledger);
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;

                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;

                
                $accountledger->account_id = hashids_decode($req->account_id);
                
                $accountledger->purchase_id      = $p_id;
                $accountledger->item_id      = hashids_decode($req->item_id);
                $accountledger->vehicle_no      = $req->vehicle_no;
                $accountledger->no_of_bags      = $req->no_of_begs;
                $accountledger->fare            = $req->fare;
                $accountledger->rate            = $req->rate;
                $accountledger->sale_id          = 0;
                $accountledger->cash_id          = 0;
                $accountledger->debit            = 0;
                $accountledger->credit           = $req->net_ammount ;
                $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', P.Id # '.$p_id.','.$req->no_of_begs .'Bags'. ',  Weight:'.$req->company_weight.'kg'.', '.$item_name .'@'.$req->rate.',  Account #'.'['.$account->id.']'.$account->name;
                $accountledger->save();

                return response()->json([
                    'success'   => $msg,
                    'redirect'    => route('admin.purchases.index')
                ]);

            }
            

        }else{
            
            $purchase = new PurchaseBook();
            $msg      = 'Purchase added successfully';

            $purchase->date              = $req->purchase_date;
            $purchase->inward            = $req->purchase_id;

            $purchase->pro_inv_no        = $req->prod_inv_no;
            $purchase->item_id           = hashids_decode($req->item_id);
            $purchase->account_id        = hashids_decode($req->account_id);
            $purchase->vehicle_no        = $req->vehicle_no;
            $purchase->bag_rate          = $req->rate;
            $purchase->no_of_bags        = $req->no_of_begs;
            $purchase->commission        = $req->commission;
            $purchase->discount          = $req->discount;
            $purchase->fare              = $req->fare;
            $purchase->bilty_no          = $req->bilty_no;
            $purchase->loading_charges   = $req->loading_charges;
            $purchase->other_charges     = $req->others_charges;
            $purchase->company_weight    = $req->company_weight;
            $purchase->party_weight      = $req->party_weight;
            $purchase->weight_difference = $req->weight_difference;
            $purchase->posted_weight     = $req->posted_weight;
            $purchase->net_weight        = $req->company_weight - $req->posted_weight;
            $purchase->net_ammount       = $req->net_ammount;
            $purchase->remarks           = $req->remarks;
            $purchase->save();
            
            if(isset($req->purchase_id)){
                $update_inward = Inward::findOrFail($req->purchase_id);
                $update_inward->item_id = hashids_decode($req->item_id);
                $update_inward->account_id = hashids_decode($req->account_id);
                
                $update_inward->vehicle_no = $req->vehicle_no;
                $update_inward->fare = $req->fare;
                $update_inward->bilty_no = $req->bilty_no;
                $update_inward->company_weight = $req->company_weight;
                $update_inward->purchase_status = "completed";
                $update_inward->no_of_bags = $req->no_of_begs;;
                $update_inward->save();
            }

            //Account Ledger
            $accountledger = new AccountLedger();
            
            $item = Item::findOrFail(hashids_decode($req->item_id));
            $item_name = $item->name;

            $account = Account::findOrFail(hashids_decode($req->account_id));
            $account_name = $account->name;

            $id = PurchaseBook::latest('created_at')->first();
            $accountledger->account_id = hashids_decode($req->account_id);
            
            $accountledger->purchase_id      = $id->id;
            $accountledger->item_id      = hashids_decode($req->item_id);
            $accountledger->vehicle_no      = $req->vehicle_no;
            $accountledger->no_of_bags      = $req->no_of_begs;
            $accountledger->fare            = $req->fare;
            $accountledger->rate            = $req->rate;
            $accountledger->sale_id          = 0;
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0;
            $accountledger->credit           = $req->net_ammount ;
            $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', P.Id # '.$id->id.','.$req->no_of_begs .'Bags'. ',  Weight:'.$req->company_weight.'kg'.', '.$item_name .'@'.$req->rate.',  Account #'.'['.$account->id.']'.$account->name;
            $accountledger->save();

            return response()->json([
                'success'   => $msg,
                'redirect'    => route('admin.purchases.index')
            ]);

        }


    
        
        
    }

    public function edit($id){
        $data = array(
            'title'     => 'Purchase Book',
            'accounts'  => Account::where('grand_parent_id',5)->latest()->get(),
            'items'     => Item::latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(), 
            'inward_id' => hashids_decode($id),
            'purchases' => PurchaseBook::with(['account', 'item'])->latest()->get(),
            'edit_purchase' => Inward::with(['item'])->findOrFail(hashids_decode($id)),
            'inwards'   => Inward::with(['account', 'item'])->latest()->get(),
            'is_update'     => true
        );
        return view('admin.purchase_book.add_purchase')->with($data);
    }

    public function delete($id){
        
        PurchaseBook::destroy(hashids_decode($id));
        
        $ac_id = AccountLedger::where('purchase_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        
        return response()->json([
            'success'   => 'Purcahase deleted successfully',
            'reload'    => true
        ]);
    }

    
    public function migrateToPurchase($id){


        $inward   = Inward::findOrFail(hashids_decode($id));
        
        $item = Item::findOrFail($inward->item_id);
        $item_name = $item->name;
        $item_rate = $item->price;

        $purchase        = new PurchaseBook;
        $purchase_amount = $inward->item->price * $inward->	company_weight;
        $get_commission = ($purchase_amount * $inward->account->commission ) /100 ;
        $get_net_discount = $get_commission  ;
        
        $purchase_amount += $inward->fare;
        $purchase_amount += $get_net_discount;
        
        $purchase->date              = $inward->date;
        $purchase->vehicle_no        = $inward->vehicle_no;
        $purchase->bilty_no          = $inward->bilty_no;
        $purchase->pro_inv_no        = 0;
        $purchase->commission        = $get_commission;
        $purchase->account_id        = $inward->account_id;
        $purchase->item_id           = $inward->item_id;
        $purchase->company_weight    = $inward->company_weight;
        $purchase->party_weight      = $inward->party_weight;
        $purchase->weight_difference = $inward->weight_difference;
        $purchase->posted_weight     = $inward->posted_weight;
        $purchase->no_of_bags        = $inward->no_of_bags;
        $purchase->bag_rate          = $inward->rate;
        $purchase->fare              = $inward->fare;
        $purchase->net_ammount       = $purchase_amount;
        $purchase->remarks           = $inward->remarks;
        $purchase->save();

        //Account Ledger
        $accountledger = new AccountLedger();
       
        $account = Account::findOrFail($inward->account_id);
        $account_name = $account->name;

        $id = PurchaseBook::latest('created_at')->first();
        $accountledger->account_id = $inward->account_id;
        $accountledger->sale_id          = 0;
        $accountledger->purchase_id      = $id->id;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = 0;
        $accountledger->credit           = $purchase->net_ammount;
        $accountledger->description      = 'Vehicle #'. $inward->vehicle_no . ', Bilty # '.$inward->bilty_no .',  Item:'.$item_name .',  Weight:'.$inward->company_weight.'kg'.',  Posted Weight:'.$inward->posted_weight.'kg'.',  Account #'.'['.$account->id.']'.$account->name;
        $accountledger->save();
    
        Item::find($inward->item_id)->increment('stock_qty', $inward->company_weight);//increment item stock

        return response()->json([
            'success'   => 'Inward data migrated to purchase book successfully',
            'reload'    => true
        ]);
    }

    public function allPurchase(){
        $data = array(
            'title' => 'All purchase',
            'purchases'  => PurchaseBook::with(['inward.item'])->latest()->get(),
        );
        // dd($data['purchases'][0]);
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function editPurchase($id){
        $data = array(
            'title'         => 'Edit purchase',
            'edit_purchase' => PurchaseBook::with(['account', 'item'])->findOrFail(hashids_decode($id)),
            'accounts'  => Account::where('grand_parent_id',5)->latest()->get(),
            'items'     => Item::latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(), 
            'purchases' => PurchaseBook::with(['account', 'item'])->latest()->get(),
            'is_update'     => true  
        );
        return view('admin.purchase_book.all_purchase')->with($data);
    }

    public function updatePurchase(Request $req){
        
        $purchase = PurchaseBook::findOrFail(hashids_decode($req->purchase_book_id));
        $msg      = 'Purchase Updated successfully';
        
        $purchase->date              = $req->purchase_date;
        $purchase->pro_inv_no        = $req->prod_inv_no;
        $purchase->item_id           = hashids_decode($req->item_id);
        $purchase->account_id        = hashids_decode($req->account_id);
        $purchase->vehicle_no        = $req->vehicle_no;
        $purchase->bag_rate          = $req->rate;
        $purchase->no_of_bags        = $req->no_of_begs;
        $purchase->commission        = $req->commission;
        $purchase->discount          = $req->discount;
        $purchase->fare              = $req->fare;
        $purchase->bilty_no          = $req->bilty_no;
        $purchase->loading_charges   = $req->loading_charges;
        $purchase->other_charges     = $req->others_charges;
        $purchase->company_weight    = $req->company_weight;
        $purchase->party_weight      = $req->party_weight;
        $purchase->weight_difference = $req->weight_difference;
        $purchase->posted_weight     = $req->posted_weight;
        $purchase->net_weight        = $req->company_weight - $req->posted_weight;
        $purchase->net_ammount       = $req->net_ammount;
        $purchase->remarks           = $req->remarks;
        $purchase->save();
        
        

        //Account Ledger
        $accountledger = AccountLedger::findOrFail(hashids_decode($req->purchase_id));
        
        $item = Item::findOrFail(hashids_decode($req->item_id));
        $item_name = $item->name;

        $account = Account::findOrFail(hashids_decode($req->account_id));
        $account_name = $account->name;

        
        $accountledger->account_id = hashids_decode($req->account_id);
        
        $accountledger->purchase_id      = hashids_decode($req->purchase_id);
        $accountledger->item_id      = hashids_decode($req->item_id);
        $accountledger->vehicle_no      = $req->vehicle_no;
        $accountledger->no_of_bags      = $req->no_of_begs;
        $accountledger->fare            = $req->fare;
        $accountledger->rate            = $req->rate;
        $accountledger->sale_id          = 0;
        $accountledger->cash_id          = 0;
        $accountledger->debit            = 0;
        $accountledger->credit           = $req->net_ammount ;
        $accountledger->description      = 'Vehicle #'. $req->vehicle_no .', P.Id # '.$req->purchase_id.','.$req->no_of_begs .'Bags'. ',  Weight:'.$req->company_weight.'kg'.', '.$item_name .'@'.$req->rate.',  Account #'.'['.$account->id.']'.$account->name;
        $accountledger->save();
    
        // Item::find(hashids_decode($req->item_id))->increment('stock_qty', $req->company_weight);//increment item stock
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.purchases.index')
        ]);

        
    }
}