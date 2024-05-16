<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseMedicineRequest;
use App\Http\Requests\SaleMedicineRequest;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Models\Account;
use App\Models\Item;
use App\Models\Outward;
use App\Models\Company;
use App\Models\Shade;
use App\Models\ShadeItemAdded;
use App\Models\OutwardDetail;
use App\Models\SaleBook;
use App\Models\AccountLedger;
use App\Models\AccountType;
use App\Models\Category;
use App\Models\ItemAvailable;
use App\Models\PurchaseMedicine;
use App\Models\ExpireMedicine;
use App\Models\ReturnMedicine;
use App\Models\SaleMedicine;
use App\Models\MissingMedicine;
use App\Models\SaleMedicineDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;
use Carbon\Carbon;

class MedicineController extends Controller
{

    public function purchase_medicine(Request $req){
        
        $data = array(
            'title'     => 'Purchase Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'purchase_medicines'     => PurchaseMedicine::with(['company','account','item'])
                                            ->when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })
                                            ->when(isset($req->invoice_no), function($query) use ($req){
                                                $query->where('invoice_no', $req->invoice_no);
                                            })
                                            ->when(isset($req->item_id), function($query) use ($req){
                                                $query->where('item_id', hashids_decode($req->item_id));
                                            })
                                            ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                            })
                                            ->orderBy('date', 'desc')->get(),
            
        );
        return view('admin.medicine.purchase_medicine')->with($data);
    }

    public function sale_medicine_invoice(Request $req){
        $data = array(
            'title'     => 'Medicine Invoice',
            
        );
        return view('admin.medicine.invoice')->with($data);
    }

    public function storePurchaseMedicine(Request $req){
        
        
        if(isset($req->purchase_medicine_id) && !empty($req->purchase_medicine_id)){
            
            //update the recrod
            
            $purchase = PurchaseMedicine::findOrFail(hashids_decode($req->purchase_medicine_id));
            
             Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//increment item stock
                
            //previous Item Increment
            Item::find($purchase->item_id)->increment('stock_qty',$purchase->quantity);//increment item stock
            
            $msg      = 'Purcahse medicine updated successfully';
            
            $ac_le = AccountLedger::where('purchase_medicine_id',hashids_decode($req->purchase_medicine_id))->latest()->get();
            $ac_id = $ac_le[0]->id;
            
            $accountledger = AccountLedger::findOrFail($ac_id);
            
            $purchase->date             = $req->date;
            $purchase->company_id       = hashids_decode($req->company_id);
            $purchase->item_id          = hashids_decode($req->item_id);
            $purchase->account_id       = hashids_decode($req->account_id);
            $purchase->invoice_no        = $req->Invoice_no;
            $purchase->rate             = $req->rate;
            $purchase->quantity         = $req->quantity;
            $purchase->net_ammount      = $req->net_ammount;
            $purchase->purchase_ammount  = $req->purchase_ammount;
            $purchase->commission       = $req->commission;
            $purchase->discount         = $req->discount;
            $purchase->other_charges    = $req->other_charges;
            $purchase->status           = $req->status;
            $purchase->expiry_date           = $req->expiry_date;
    
            $purchase->remarks          = $req->remarks;
            $purchase->save();
    
            //Account Ledger
            $it = Item::findOrFail(hashids_decode($req->item_id));
             $ac = Account::findOrFail(hashids_decode($req->account_id));
            $id = PurchaseMedicine::with(['account'])->where('id',hashids_decode($req->purchase_medicine_id))->latest()->get();
            
            $led_get_amt               = $req->net_ammount;
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = $purchase->id;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = (int) ($req->net_ammount) ;
            $accountledger->description      =  'Invoice #:'.$req->Invoice_no . 'Item:'.$it->name .',  Quantity:'.$req->quantity. '@ Rate:'.$req->rate.' Account #'.'['.$ac->id.']'.$ac->name;
            $accountledger->save();
            
        }else{//add new record
            
            $purchase = new PurchaseMedicine;
            $msg      = 'Purchase medicine added successfully';
            $accountledger = new AccountLedger();
            
            $purchase->date             = $req->date;
            $purchase->company_id       = hashids_decode($req->company_id);
            $purchase->item_id          = hashids_decode($req->item_id);
            $purchase->account_id       = hashids_decode($req->account_id);
            $purchase->invoice_no        = $req->Invoice_no;
            $purchase->rate             = $req->rate;
            $purchase->quantity         = $req->quantity;
            $purchase->net_ammount      = $req->net_ammount;
            $purchase->purchase_ammount  = $req->purchase_ammount;
            $purchase->commission       = $req->commission;
            $purchase->discount         = $req->discount;
            $purchase->other_charges    = $req->other_charges;
            $purchase->status           = $req->status;
            $purchase->expiry_date           = $req->expiry_date;
    
            $purchase->remarks          = $req->remarks;
            $purchase->save();
    
            //Account Ledger
            
            $id = PurchaseMedicine::with(['item','account'])->latest('created_at')->first();
            $it = Item::findOrFail(hashids_decode($req->item_id));
            $ac = Account::findOrFail(hashids_decode($req->account_id));
            
            $led_get_amt               = $req->net_ammount;
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = 0;
            $accountledger->purchase_medicine_id          = $purchase->id;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = (int) ($req->net_ammount); ;
            $accountledger->description      =  'Invoice #:'.$req->Invoice_no .','. 'Item:'.$it->name .','.' Quantity:'.$req->quantity. '@ Rate:'.$req->rate.' Account #'.'['.$ac->id.']'.$ac->name;
            $accountledger->save();
    
        }
        

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.medicines.purchase_medicine')
        ]);
    }

    public function editPurchaseMedicine($id){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();
        
        $data = array(
            'title'             => 'Edit Purchase Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'expire_medicine'   => $expire_medicine,
            'purchase_medicines'=> PurchaseMedicine::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
            
            'edit_medicine'         => PurchaseMedicine::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.medicine.purchase_medicine')->with($data);
    }

    public function deletePurchaseMedicine($id){
        
        PurchaseMedicine::destroy(hashids_decode($id));
        $ac_id = AccountLedger::where('purchase_medicine_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        return response()->json([
            'success'   => 'Purchase medicine deleted successfully',
            'reload'    => true
        ]);
    }

    public function sale_medicine(Request $req){
        
        //dd("Fsdf");
       
        $gp_no = SaleMedicine::latest()->first();
        
        if($gp_no == null){
           
            $gp_no['invoice_no'] = "GH-00";
        
        }else{

            $g = $gp_no['invoice_no'];
        }

        $ac = explode("-",$gp_no['invoice_no']);
        $p = "GH-0";
        $v = $ac[1]+ 1;
        $n = $p.$v;
        
        $data = array(
            'title'     => 'Sale Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'shades'          => Shade::latest()->get(),
            'invoice_no'      =>$n,
            'sale_medicines'     => SaleMedicine::when(isset($req->item_id), function($query) use ($req){
                                                        $query->where('item_id', hashids_decode($req->item_id));
                                                    })->when(isset($req->account_id), function($query) use ($req){
                                                        $query->where('account_id', hashids_decode($req->account_id));
                                                    })->when(isset($req->from_date) && isset($req->to_date), function($query) use ($req){
                                                        $query->whereDate('date', '>=', $req->from_date)->whereDate('date', '<=', $req->to_date);
                                                    })->orderBy('date', 'Asc')->latest()->get(),
            'sale_items' => Item::where('type', 'sale')->latest()->get(),
            'purchase_items' => Item::where('type', 'purchase')->latest()->get(),
        );
        return view('admin.medicine.sale_medicine')->with($data);
    }

    public function storeSaleMedicine(Request $req){
        
        if(check_empty($req->sale_medicine_id)){
            
            $msg      = 'Sale Medicine udpated successfully';
               
            $sale = SaleMedicine::findOrFail(hashids_decode($req->sale_medicine_id));
            
            if($req->shade_id == null){
                $sale->shade_id          = 0;
            }else{
                $sale->shade_id          = hashids_decode($req->shade_id);
            }
            
            $sale->date              = $req->date;
            $sale->invoice_no        = $req->Invoice_no;
            $sale->account_id        = hashids_decode($req->account_id);
            $sale->item_id           = hashids_decode($req->item_id);
            $sale->rate              = $req->item_rate;
            $sale->quantity          = $req->item_quantity;
            $sale->sale_ammount      = $req->ammount;
            $sale->discount          = $req->discount/1;
            $sale->commission        = $req->commission;
            $sale->other_charges     = $req->other_charges/1;
            $sale->net_ammount       = $req->net_ammount;
            $get_value               = ($req->ammount * $req->commission)/100;
            $sale->profit            = $get_value - ($req->discount/1) ;
            $sale->remarks           = $req->remarks;
            $sale->save();

            
            $ac_le = AccountLedger::where('sale_medicine_id',hashids_decode($req->sale_medicine_id))->latest()->get();
            $ac_id = $ac_le[0]->id;
            $it = Item::findOrFail(hashids_decode($req->item_id));
            $ac = Account::findOrFail(hashids_decode($req->account_id));
            
            $accountledger = AccountLedger::findOrFail($ac_id);
            $id = SaleMedicine::with(['account:id,name','item:id,name'])->where('id',hashids_decode($req->sale_medicine_id))->latest()->get();
            $led_get_amt                       = $sale->sale_ammount +  $sale->profit ; 
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = $sale->id;
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
            $accountledger->description      = 'Invoice #'. $req->Invoice_no . ',  Item:'.$id[0]->item->name .',  Quantity:'.$req->item_quantity.'@Rate :'.$req->item_rate.',  Account #'.'['.$id[0]->account->id.']'.$id[0]->account->name;
            $accountledger->save();

        }else{
            
            $msg  = 'Sale Medicine added successfully';
            
            $c = count($req->item_id);
            ///dd($req->all());
            for ($x = 0; $x < count($req->item_id); $x++) {
                
                if(isset($req->item_id[$x])){
                    $sale = new SaleMedicine();
                    $sale->date              = $req->date;
                    $sale->invoice_no        = $req->Invoice_no;
                    $sale->account_id        = hashids_decode($req->account_id);
                    if($req->shade_id == null){
                        $sale->shade_id          = 0;
                    }else{
                        $sale->shade_id          = hashids_decode($req->shade_id);
                    }
                    $sale->item_id           = hashids_decode($req->item_id[$x]);
                    $sale->rate              = $req->item_rate[$x];
                    $sale->quantity          = $req->item_quantity[$x];
                    $sale->sale_ammount      = $req->ammount[$x];
                    $sale->discount          = $req->discount/$c;
                    $sale->commission        = $req->commission;
                    $sale->other_charges     = $req->other_charges/$c;
                    $sale->net_ammount       = $req->net_ammount;
                    $get_value               = ($req->ammount[$x] * $req->commission)/100;
                    $sale->profit            = $get_value - ($req->discount/$c) ;
                    $sale->remarks           = $req->remarks;
                    $sale->save();

                }

                //Account Ledger
                $accountledger = new AccountLedger();
                $id = SaleMedicine::with(['item','account'])->latest('created_at')->first();
                
                $led_get_amt = $sale->sale_ammount +  $sale->profit ; 
                $accountledger->account_id = hashids_decode($req->account_id);
                $accountledger->date               = $req->date;
        
                $accountledger->sale_chick_id          = 0;
                $accountledger->purchase_chick_id          = 0;
                $accountledger->sale_medicine_id          = $sale->id;
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
                $accountledger->description      = 'Invoice #'. $req->Invoice_no . ',  Item:'.$id->item->name .',  Quantity:'.$req->item_quantity[$x].'@Rate :'.$req->item_rate[$x].',  Account #'.'['.$id->account->id.']'.$id->account->name;
                $accountledger->save();

            }

        }
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.medicines.invoice',['invoice_no'=>$req->Invoice_no])
        ]);

    }

    public function editSaleMedicine($id){
        
        
        $data = array(
            'title'             => 'Edit Sale Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'shades'          => Shade::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Chick')->first(),
             'purchase_items' => Item::where('type', 'purchase')->latest()->get(),
            'sale_medicines'=> SaleMedicine::with(['account:id,name','item:id,name'])->latest()->get(),
            'edit_medicine'         => SaleMedicine::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.medicine.sale_medicine')->with($data);
    }

    public function deleteSaleMedicine($id){
        SaleMedicine::destroy(hashids_decode($id));
        $ac_id = AccountLedger::where('sale_medicine_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        return response()->json([
            'success'   => 'Sale medicine deleted successfully',
            'reload'    => true
        ]);
    }

    public function saleInvoice($invoice_no){
        
        $data = array(
            'dcs' => SaleMedicine::with(['account', 'item'])->where('invoice_no',$invoice_no)->get(),
        );

        return view('admin.medicine.invoice')->with($data);

    }

    //Expire Medicine
    public function expire_medicine(Request $req){

        $data = array(
            'title'     => 'Expire Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'companies'             => Company::where('category_id',4)->latest()->get(),
            'expire_medicines'     => ExpireMedicine::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
        );
        return view('admin.medicine.expire_medicine')->with($data);
    }

    public function storeExpireMedicine(Request $req){
        
     
        if(isset($req->expire_medicine_id) && !empty($req->expire_medicine_id)){//update the recrod
            $purchase = ExpireMedicine::findOrFail(hashids_decode($req->expire_medicine_id));
            
            $ac_le = AccountLedger::where('expire_medicine_id',hashids_decode($req->expire_medicine_id))->latest()->get();
            $ac_id = $ac_le[0]->id;
            $accountledger = AccountLedger::findOrFail($ac_id);
            $msg      = 'Expire medicine updated successfully';

            $p  = PurchaseMedicine::findOrFail(hashids_decode($req->expire_medicine_id));
           
            $p->expiry_status             = "expire";
            $p->save();
            
            $purchase = new ExpireMedicine;
            $msg      = 'Expire medicine added successfully';
            
            $purchase->date             = $req->date;
            $purchase->company_id       =  hashids_decode($req->company_id);
            $purchase->item_id          =  hashids_decode($req->item_id);
            $purchase->account_id       =  hashids_decode($req->account_id);
            $purchase->rate             =  $req->rate;
            $purchase->quantity         =  $req->quantity;
            $purchase->net_ammount      =  $req->net_ammount;
            $purchase->purchase_ammount  =  $req->purchase_ammount;
            $purchase->commission       =  $req->commission;
            $purchase->discount         = $req->discount;
            $purchase->other_charges    = $req->other_charges;
    
            $purchase->status           = $req->status;
            $purchase->expiry_date      = $req->expiry_date;
    
            $purchase->remarks          = $req->remarks;
            $purchase->save();
            
            $id = ExpireMedicine::with(['item','account'])->where('id',hashids_decode($req->expire_medicine_id))->first();
            
            $led_get_amt               = $req->net_ammount;
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = $purchase->id;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = (int) ($req->net_ammount) ;
            $accountledger->credit           = 0 ;
            $accountledger->description      =   'Item:'.$id->item->name .',  Quantity:'.$req->quantity.',  Account #'.'['.$id->account->id.']'.$id->account->name;
            $accountledger->save();

        }else{
            //add new record
            $purchase = new ExpireMedicine;
            $msg      = 'Expire medicine added successfully';
            $accountledger = new AccountLedger();
            
            
            $purchase->date             = $req->date;
            $purchase->company_id       =  hashids_decode($req->company_id);
            $purchase->item_id          =  hashids_decode($req->item_id);
            $purchase->account_id       =  hashids_decode($req->account_id);
            $purchase->rate             =  $req->rate;
            $purchase->quantity         =  $req->quantity;
            $purchase->net_ammount      =  $req->net_ammount;
            $purchase->purchase_ammount  =  $req->purchase_ammount;
            $purchase->commission       =  $req->commission;
            $purchase->discount         = $req->discount;
            $purchase->other_charges    = $req->other_charges;
            $purchase->status           = $req->status;
            $purchase->expiry_date      = $req->expiry_date;
            $purchase->remarks          = $req->remarks;
            $purchase->save();
            
             $id = ExpireMedicine::with(['item','account'])->latest('created_at')->first();
    
            $led_get_amt               = $req->net_ammount;
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->date               = $req->date;
            
            $accountledger->sale_chick_id          = 0;
            $accountledger->purchase_chick_id          = 0;
            $accountledger->sale_medicine_id          = 0;
            $accountledger->return_medicine_id          = 0;
            $accountledger->expire_medicine_id          = $purchase->id;
            $accountledger->purchase_medicine_id          = 0;
            $accountledger->sale_feed_id          = 0;
            $accountledger->purchase_feed_id          = 0;
            $accountledger->purchase_murghi_id          = 0;
            $accountledger->sale_murghi_id          = 0;
            $accountledger->general_purchase_id          = 0;
            $accountledger->general_sale_id      = 0;
            $accountledger->expense_id      = 0;
    
            $accountledger->cash_id          = 0;
            $accountledger->debit            = (int) ($req->net_ammount) ;
            $accountledger->credit           = 0 ;
            $accountledger->description      =   'Item:'.$id->item->name .',  Quantity:'.$req->quantity.',  Account #'.'['.$id->account->id.']'.$id->account->name;
            $accountledger->save();
            
            
            Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//increment item stock

            
        }
        
        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.medicines.expire_medicine')
        ]);
    }

    public function editExpireMedicine($id){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();
        
        $data = array(
            'title'             => 'Expire Medicine ',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'companies'             => Company::where('category_id',4)->latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'expire_medicines'=> ExpireMedicine::with(['account:id,name','item:id,name'])->latest()->get(),
            'edit_medicine'         => PurchaseMedicine::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.medicine.expire_medicine')->with($data);
    }

    public function deleteExpireMedicine($id){
        
        ExpireMedicine::destroy(hashids_decode($id));
        $ac_id = AccountLedger::where('expire_medicine_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        return response()->json([
            'success'   => 'Purchase medicine deleted successfully',
            'reload'    => true
        ]);
    }

    //Return Medicine
    public function return_medicine(Request $req){
        
        $data = array(
            'title'     => 'Return Medicine',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'return_medicines'=> ReturnMedicine::with(['account','item'])->when(isset($req->account_id), function($query) use ($req){
                                                $query->where('account_id', hashids_decode($req->account_id));
                                            })
                                            ->when(isset($req->invoice_no), function($query) use ($req){
                                                $query->where('invoice_no',$req->invoice_no);
                                            })
                                            ->when(isset($req->item_id), function($query) use ($req){
                                                $query->where('item_id', hashids_decode($req->item_id));
                                            })
                                            ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                            })
                                            ->orderBy('date', 'desc')->get(),
            'companies'             => Company::where('category_id',4)->latest()->get(),
            'expire_medicines'     => ReturnMedicine::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
        );

        return view('admin.medicine.return_medicine')->with($data);
    }

    public function storeReturnMedicine(Request $req){
       
        if(isset($req->return_medicine_id) && !empty($req->return_medicine_id)){//update the recrod
            
            $return = ReturnMedicine::findOrFail(hashids_decode($req->return_medicine_id));
            
            $msg      = 'Return medicine updated successfully';
            
            $return->date             = $req->date;
            $return->invoice_no        = $req->Invoice_no;
            $return->account_id       =  hashids_decode($req->account_id);
            $return->company_id       =  hashids_decode($req->company_id);
            $return->item_id          =  hashids_decode($req->item_id);
            
            $return->quantity         =  $req->quantity;
            $return->rate             =  $req->rate;
            $return->net_ammount      =  $req->net_ammount;
            $return->purchase_ammount  =  $req->purchase_ammount;
            $return->commission       =  $req->commission;
            $return->discount         = $req->discount;
            $return->other_charges    = $req->other_charges;
    
            $return->status           = $req->status;
            $return->expiry_date           = $req->expiry_date;
    
            $return->remarks          = $req->remarks;
            $return->save();
            
            //Ledger
            if($req->return_status == "return_in"){//Add to the item stock
            
            
                $ac_le = AccountLedger::where('return_medicine_id',hashids_decode($req->return_medicine_id))->latest()->get();
                $ac_id = $ac_le[0]->id;
                
                $accountledger = AccountLedger::findOrFail($ac_id);
                
                //Account Ledger  Work
                $id = ReturnMedicine::with(['item','account'])->latest('created_at')->first();
        
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;
                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;
        
                $accountledger->account_id = hashids_decode($req->account_id);
                $accountledger->date               = $req->date;
            
                $accountledger->sale_chick_id          = 0;
                $accountledger->purchase_chick_id          = 0;
                $accountledger->sale_medicine_id          = 0;
                $accountledger->return_medicine_id          = $return->id;
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
                $accountledger->credit           = $req->net_ammount ;
                $accountledger->description      = 'Account Name:'.$account_name." , ".'Item Name:'. $item_name . ' ,  '. '@Rate '.$req->rate. '  , '. 'Total Quantity   '.$req->quantity.'Kg';
                $accountledger->save();

            }else{ 
            
            
                $ac_le = AccountLedger::where('return_medicine_id',hashids_decode($req->return_medicine_id))->latest()->get();
                $ac_id = $ac_le[0]->id;
                
                $accountledger = AccountLedger::findOrFail($ac_id);
                
               //Account Ledger  Work
             
               $id = ReturnMedicine::with(['item','account'])->latest('created_at')->first();
               
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;
        
                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;
                
               $accountledger->account_id = hashids_decode($req->account_id);
               $accountledger->date               = $req->date;
            
               $accountledger->sale_chick_id          = 0;
               $accountledger->purchase_chick_id          = 0;
               $accountledger->sale_medicine_id          = 0;
               $accountledger->return_medicine_id          = $id->id;
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
               $accountledger->debit            = $req->net_ammount ;
               $accountledger->credit           = 0 ;
                $accountledger->description      = 'Account Name:'.$account_name." , ".'Item Name:'. $item_name .  ' '. 'Rate '.$req->rate. '  , '. 'Total Quantity   '.$req->quantity.'Kg';
               $accountledger->save();
            }

            
        }else{//add new record
                 
            $return = new ReturnMedicine;
            $msg      = 'Return medicine added successfully';
            
            $return->date             = $req->date;
            $return->invoice_no        = $req->Invoice_no;
            $return->account_id       =  hashids_decode($req->account_id);
            $return->company_id       =  hashids_decode($req->company_id);
            $return->item_id          =  hashids_decode($req->item_id);
            
            $return->quantity         =  $req->quantity;
            $return->rate             =  $req->rate;
            $return->net_ammount      =  $req->net_ammount;
            $return->purchase_ammount  =  $req->purchase_ammount;
            $return->commission       =  $req->commission;
            $return->discount         = $req->discount;
            $return->other_charges    = $req->other_charges;
    
            $return->status           = $req->status;
            $return->expiry_date           = $req->expiry_date;
    
            $return->remarks          = $req->remarks;
            $return->save();

            //Ledger
              if($req->return_status == "return_in"){//Add to the item stock
    
                Item::find(hashids_decode($req->item_id))->increment('stock_qty',$req->quantity);//increment item stock
    
                
                //Account Ledger  Work
                $accountledger = new AccountLedger();
                $id = ReturnMedicine::with(['item','account'])->latest('created_at')->first();
    
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;
        
                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;
    
                $accountledger->account_id = hashids_decode($req->account_id);
                $accountledger->date               = $req->date;
            
                $accountledger->sale_chick_id          = 0;
                $accountledger->purchase_chick_id          = 0;
                $accountledger->sale_medicine_id          = 0;
                $accountledger->return_medicine_id          = $return->id;
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
                $accountledger->credit           = $req->net_ammount ;
                $accountledger->description      = 'Account Name:'.$account_name." , ".'Item Name:'. $item_name . ' ,  '. 'Rate '.$req->rate. '  , '. 'Total Quantity   '.$req->quantity.'Kg';
                $accountledger->save();
    
            }else{ 
            
                Item::find(hashids_decode($req->item_id))->decrement('stock_qty',$req->quantity);//increment item stock
    
               //Account Ledger  Work
               $accountledger = new AccountLedger();
               $id = ReturnMedicine::with(['item','account'])->latest('created_at')->first();
                $item = Item::findOrFail(hashids_decode($req->item_id));
                $item_name = $item->name;
        
                $account = Account::findOrFail(hashids_decode($req->account_id));
                $account_name = $account->name;
                
               $accountledger->account_id = hashids_decode($req->account_id);
               $accountledger->date               = $req->date;
            
               $accountledger->sale_chick_id          = 0;
               $accountledger->purchase_chick_id          = 0;
               $accountledger->sale_medicine_id          = 0;
               $accountledger->return_medicine_id          = $id->id;
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
               $accountledger->debit            = $req->net_ammount ;
               $accountledger->credit           = 0 ;
                $accountledger->description      = 'Account Name:'.$account_name." , ".'Item Name:'. $item_name .  ' '. 'Rate '.$req->rate. '  , '. 'Total Quantity   '.$req->quantity.'Kg';
               $accountledger->save();
            }
            
        }
        
        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.medicines.return_medicine')
        ]);
    }

    public function editReturnMedicine($id){
        
        
        $data = array(
            'title'             => 'Return Medicine ',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name'),
            'category'          => Category::with(['companies', 'items'])->where('name', 'medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'companies'             => Company::where('category_id',4)->latest()->get(),
            'return_medicines'=> ReturnMedicine::with(['account:id,name','item:id,name'])->latest()->get(),
            'edit_medicine'         => ReturnMedicine::findOrFail(hashids_decode($id)),
            'is_update'        => true
        );
        return view('admin.medicine.return_medicine')->with($data);

        
    }

    public function deleteReturnMedicine($id){
        ReturnMedicine::destroy(hashids_decode($id));
        $ac_id = AccountLedger::where('return_medicine_id',hashids_decode($id))->latest()->get();
        
        AccountLedger::destroy($ac_id[0]->id);
        return response()->json([
            'success'   => 'Purchase medicine deleted successfully',
            'reload'    => true
        ]);
    }
    
    //Mising Medicine
    
    public function missing_medicine(Request $req){
        
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->where('expiry_status','enable')->orderBy('created_at', 'desc')->latest()->get();
        

        $data = array(
            'title'     => 'Missing Stock',
            'accounts'          => Account::where('grand_parent_id','5')->latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'Medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'missing_medicines'=> MissingMedicine::with(['item:id,name'])
                                            ->when(isset($req->invoice_no), function($query) use ($req){
                                                $query->where('invoice_no',$req->invoice_no);
                                            })
                                            ->when(isset($req->item_id), function($query) use ($req){
                                                $query->where('item_id', hashids_decode($req->item_id));
                                            })
                                            ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                                $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                            })
                                            ->orderBy('date', 'desc')->get(),
            'companies'             => Company::where('category_id',4)->latest()->get(),
            'expire_medicines'     => ReturnMedicine::with(['company:id,name','account:id,name','item:id,name'])->latest()->get(),
        );

       
        return view('admin.medicine.missing_medicine')->with($data);
    }

    public function storemissingMedicine(Request $req){
       
       
        if(isset($req->missing_medicine_id) && !empty($req->missing_medicine_id)){//update the recrod
            $missing = MissingMedicine::findOrFail(hashids_decode($req->missing_medicine_id));
            $msg      = 'Missing Medicine updated successfully';

            $p  = MissingMedicine::findOrFail(hashids_decode($req->missing_medicine_id));
           
            Item::find($p->item_id)->decrement('stock_qty',$p->quantity);//increment item stock
            

        }else{//add new record
            $missing = new MissingMedicine;
            $msg      = 'Missing Medicine added successfully';

        }

        $item_detail = Item::find(hashids_decode($req->item_id))->latest()->get();//increment item stock
        
        $missing->date             = $req->date;
        $missing->invoice_no        = $req->Invoice_no;
        
        $missing->item_id          =  hashids_decode($req->item_id);
        $missing->rate             =  $item_detail[0]->price;
        $missing->quantity         =  $req->quantity;
        $missing->net_ammount      =  $missing->rate * $missing->quantity;
        $missing->remarks          = $req->remarks;
        $missing->save();
    
        Item::find(hashids_decode($req->item_id))->increment('stock_qty',$req->quantity);//increment item stock


        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.medicines.missing_medicine')
        ]);
    }

    public function editmissingMedicine($id){
        
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();
        
        $data = array(
            'title'             => 'Missing Stock ',
            'accounts'          => Account::latest()->get(),
            'category'          => Category::with(['companies', 'items'])->where('name', 'medicine')->first(),
            'items'             => Item::where('category_id',4)->latest()->get(),
            'companies'         => Company::where('category_id',4)->latest()->get(),
            'expire_medicine'   => $expire_medicine,
            'missing_medicines'  => MissingMedicine::with(['item:id,name'])->latest()->get(),
            'edit_medicine'     => MissingMedicine::findOrFail(hashids_decode($id)),
            'is_update'         => true
        );
        return view('admin.medicine.missing_medicine')->with($data);

        
    }

    public function deletemissingMedicine($id){
        MissingMedicine::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Purchase medicine deleted successfully',
            'reload'    => true
        ]);
    }
    
    

}
