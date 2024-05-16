<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashBook;
use App\Models\AccountLedger;
use App\Http\Requests\CashBookRequest;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\CashInHand;
use App\Models\Expense;

use App\Models\PurchaseMedicine;
use Carbon\Carbon; 

class CashController extends Controller
{
    public function index(Request $req){
        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $tot_get_cr = CashBook::sum('payment_ammount');
        $tot_get_dr = CashBook::sum('receipt_ammount');
        $c_ex  = Expense::sum('ammount');
        
        $tot = 2909858;
        $Tot_net_dr = $tot_get_dr +  $tot;
        $cash_in_hand = $Tot_net_dr - ($tot_get_cr + $c_ex)   ;
        //dd($tot_get_cr);
        
        
        $month = date('Y-m-d');
        if(isset($req->cash_from_date) && isset($req->cash_to_date)){
        
            $tot_cr = CashBook::when(isset($from_date, $to_date), function($query) use ($req){
                                $query->whereBetween('date', [$req->cash_from_date, $req->cash_to_date]);
                            })->sum('receipt_ammount');
        $tot_dr = CashBook::when(isset($from_date, $to_date), function($query) use ($req){
                                $query->whereBetween('date', [$req->cash_from_date, $req->cash_to_date]);
                            })->sum('payment_ammount');
        $tot_ex = Expense::when(isset($from_date, $to_date), function($query) use ($req){
                                $query->whereBetween('date', [$req->cash_from_date, $req->cash_to_date]);
                            })->sum('ammount');
        }else{
            
            
        $tot_cr = CashBook::wheredate('date', $month)->sum('receipt_ammount');
        $tot_dr = CashBook::wheredate('date', $month)->sum('payment_ammount');
        $tot_ex = Expense::wheredate('date', $month)->sum('ammount');
            
        }
        

        $data = array(
            'title'     => 'Cash Book',
            'cash_in_hand' =>$cash_in_hand,
            'expire_medicine'   => $expire_medicine,
            'tot_cr' => $tot_cr,
            'tot_dr' => $tot_dr,
            'tot_ex' => $tot_ex,
            
            'accounts'  => Account::latest()->get()->sortBy('name'),
            'cash' => CashBook::with(['account'])
                            ->when(isset($req->acc_id), function($query) use ($req){
                                $query->where('account_id', hashids_decode($req->acc_id));
                            })
                            ->when(isset($req->status), function($query) use ($req){
                                $query->where('status', $req->status);
                            })
                            ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                $query->whereBetween('date', [$req->from_date, $req->to_date]);
                            })
                            ->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
            
        );
        return view('admin.cash_book.add_cash')->with($data);
    }

public function store(CashBookRequest $req){
        //check if today cash in hand
        $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();

        //Edit or save  Cash In Hand
        if(check_empty($req->cash_id)){
            $cashbook = CashBook::findOrFail(hashids_decode($req->cash_id));
            $msg      = 'Cash Book udpated successfully';

            //dd($req->date);
            $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();
            //dd($cash);
            $update_cash_in_hand = CashInHand::whereDate('created_at', $req->date)->latest()->get();
            
            
            //if cash in hand is empty for today so you will get last cash in hand and then make changes
            if($cash->isEmpty()){
                $cash = CashInHand::latest('created_at')->get();
                
                $cash_in_hand = $cash[0]->cash_in_hand;//get last cash in hand
                $cash_in_hand_detail = new CashInHand();

                //check weather payment or receipt 
                if($req->receipt_ammount == null){
                    //Receipt Coming
                    $tot_debit = $cash_in_hand - $req->payment_ammount;
                    $cash_in_hand_detail->date = Carbon::today();
                    $cash_in_hand_detail->cash_in_hand          = $tot_debit;
                    $cash_in_hand_detail->total_debit             = $req->payment_ammount;
                    $cash_in_hand_detail->total_credit            = 0;
                    $cash_in_hand_detail->save();                                                                                                                        
                    
                }else{
                    //Receipt Coming
                    $tot_credit                                 = $cash_in_hand + $req->receipt_ammount;
                    $cash_in_hand_detail->date = Carbon::today();
                    $cash_in_hand_detail->cash_in_hand          = $tot_credit;
                    $cash_in_hand_detail->total_debit          = 0;
                    $cash_in_hand_detail->total_credit          = $req->receipt_ammount;
                    $cash_in_hand_detail->save();
                   
                }

            }else{//if cash in hand is not empty for today so you will get today cash in hand and then make changes
                   
                $cashupdate = CashInHand::findOrFail($cash[0]->id);
                
                $cash_in_hand = $cash[0]->cash_in_hand;//get last cash in hand
                $pymnt_amt = $cashbook->payment_ammount;
                $recpt_amt = $cashbook->receipt_ammount;
                if($req->receipt_ammount == null){
                    //Payment Coming
                    $difference_value =  $req->payment_ammount -  $pymnt_amt ;
                    //dd($difference_value);
                    $tot_debit = $cash_in_hand - $difference_value;
                    //dd($tot_debit);
                    $cashupdate->cash_in_hand          = $tot_debit;
                    $cashupdate->total_debit             = $req->payment_ammount;
                    $cashupdate->total_credit            = 0;
                    $cashupdate->save();
                    
                }else{

                    //Receipt Coming
                    $difference_value =  $req->receipt_ammount  - $recpt_amt ;
                    //dd($difference_value);
                    
                    if($difference_value >= 0){
                        $tot_credit = $cash_in_hand + $difference_value;

                        //dd($tot_credit);
                        $cashupdate->cash_in_hand          = $tot_credit;
                        $cashupdate->total_debit             = 0;
    
                        $cashupdate->total_credit             = $req->receipt_ammount;
                        $cashupdate->save();
                    }else{
                        $tot_credit = $cash_in_hand + $difference_value;
                        //dd($tot_credit);    
                        //dd($tot_credit);
                        $cashupdate->cash_in_hand          = $tot_credit;
                        $cashupdate->total_debit             = 0;

                        $cashupdate->total_credit             = $req->receipt_ammount;
                        $cashupdate->save();
                    }
            
                }

            }
            
            

        }else{
            $cashbook = new CashBook();
            $msg      = 'Cash Book added successfully';

            if($cash->isEmpty()){
                $cash = CashInHand::latest('created_at')->get();
                $cash_in_hand = $cash[0]->cash_in_hand;//get last cash in hand
                $cashin = new CashInHand();//create new row i database
                
                // //check weather payment or receipt 
                if($req->receipt_ammount == null){
    
                    $tot_debit = $cash[0]->cash_in_hand - $req->payment_ammount;
                    $cashin->date               = Carbon::today();
                    $cashin->cash_in_hand          = $tot_debit;
                    $cashin->total_debit             = $req->payment_ammount;
                    $cashin->total_credit            = 0;
                    $cashin->save();
                    
                }else{
                    
                    $tot_credit = $cash[0]->cash_in_hand + $req->receipt_ammount;
                    $cashin->date               = Carbon::today();
                    $cashin->cash_in_hand          = $tot_credit;
                    $cashin->total_credit             = $req->receipt_ammount;
                    $cashin->total_debit            = 0;
                    $cashin->save();
                }
            
            }else{
                $cashin = CashInHand::findOrFail($cash[0]->id);
                
                if($req->receipt_ammount == null){
                    $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();
    
                    $cash_in_hand = $cash[0]->cash_in_hand;//get Today Cash IN Hand
                    $pre_debit = $cash[0]->total_debit;//get previos debit of  
    
                    $cash_in = $cash_in_hand - $req->payment_ammount;
                    $debit_in = $pre_debit + $req->payment_ammount;
    
                    $cashin->cash_in_hand          = $cash_in;
                    $cashin->total_debit             = $debit_in;
                    $cashin->total_credit             = $cash[0]->total_credit;
    
                    $cashin->save();
                    
                }else{
                    $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();
    
                    $cash_in_hand = $cash[0]->cash_in_hand;//get Today Cash IN Hand
                    $pre_credit = $cash[0]->total_credit;//get previos credit of  
    
                    $cash_in = $cash_in_hand + $req->receipt_ammount;
                    $credit_in = $pre_credit + $req->receipt_ammount;
                    
                    //dd($cash_in_hand);
    
                    $cashin->cash_in_hand          = $cash_in;
                    $cashin->total_credit             = $credit_in;
                    $cashin->total_debit             = $cash[0]->total_debit;
    
                    $cashin->save();
                }    
            
            }

        }
        
        // No

        // //check weather payment or receipt 
        if($req->receipt_ammount == null){
            $cashbook->receipt_ammount    = 0;
            $cashbook->payment_ammount    = $req->payment_ammount;
        }else{
            $cashbook->receipt_ammount    = $req->receipt_ammount;
            $cashbook->payment_ammount    = 0;
        }

        $cashbook->date               = $req->date;
        $cashbook->bil_no             = $req->bil_no;
        $cashbook->account_id         = hashids_decode($req->account_id);
        $cashbook->narration          = $req->narration;
        $cashbook->status             = $req->status;
        $cashbook->remarks            = $req->remarks;
        $cashbook->save();
        
        //Account Ledger Work 
        $account_detail = AccountLedger::where('account_id','=', hashids_decode($req->account_id))->latest()->get();
        if(check_empty($req->cash_id)){
            if($req->receipt_ammount == null){
                
                //Payment Received
                
                $ac_id = AccountLedger::where('cash_id',hashids_decode($req->cash_id))->latest()->get();
                $accountledger = AccountLedger::findOrFail($ac_id[0]->id);

                $pay_ammount = $req->payment_ammount;
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
                $accountledger->cash_id             = hashids_decode($req->cash_id);
                $accountledger->debit            = $pay_ammount;
                $accountledger->credit          = 0;
                $accountledger->description            = $req->narration;
                $accountledger->save();
            
    
            }else{
                $ac_id = AccountLedger::where('cash_id',hashids_decode($req->cash_id))->latest()->get();
                $accountledger = AccountLedger::findOrFail($ac_id[0]->id);

                
                $pay_ammount = $req->receipt_ammount;
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
                $accountledger->cash_id          = hashids_decode($req->cash_id);
                $accountledger->debit            = 0 ;
                $accountledger->credit           = $pay_ammount ;
                $accountledger->description      = $req->narration;
                $accountledger->save();
            
            }

        }else{
            
            //check Payment and Receipt  
            if($req->receipt_ammount == null){
                //Payment Received
                $accountledger = new AccountLedger();
    
                $pay_ammount = $req->payment_ammount;
                $id = CashBook::latest('created_at')->first();
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

                $accountledger->cash_id          = $id->id;
                $accountledger->debit            = $pay_ammount ;
                $accountledger->credit           = 0 ;
                $accountledger->description            = $req->narration;
                $accountledger->save();
            
    
            }else{
                $accountledger = new AccountLedger();
    
                $pay_ammount = $req->receipt_ammount;
                $id = CashBook::latest('created_at')->first();
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

                $accountledger->cash_id          = $id->id;
                
                $accountledger->debit           = 0 ;
                $accountledger->credit           = $pay_ammount ;
                $accountledger->description      = $req->narration;
                $accountledger->save();
            
            }

        }
            
    
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.cash.index')
        ]);
    }

   
    public function edit($id){

        $newDateTime = Carbon::now()->addMonth(2);
        $d = $newDateTime->toDateString();
        
        $expire_medicine = PurchaseMedicine::with(['item', 'account'])->where('expiry_date','<=', $d)->orderBy('created_at', 'desc')->latest()->get();

        $i = CashBook::findOrFail(hashids_decode($id));
          $month = date('Y-m-d');
        $tot_cr = CashBook::wheredate('created_at', $month)->sum('receipt_ammount');
        $tot_dr = CashBook::wheredate('created_at', $month)->sum('payment_ammount');
        $tot_ex = Expense::wheredate('created_at', $month)->sum('ammount');
        
        if($i->status == "receipt"){
            
            $data = array(
                'title'     => 'Cash Book',
                'expire_medicine'   => $expire_medicine,
                'tot_cr' => $tot_cr,
                'tot_dr' => $tot_dr,
                'tot_ex' => $tot_ex,
                
                'accounts'  => Account::latest()->get()->sortBy('name'),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
                'edit_receipt' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_receipt'     => true
            );
        }else{
            
            $data = array(
                'title'     => 'Cash Book',
                'expire_medicine'   => $expire_medicine,
                 'tot_cr' => $tot_cr,
                'tot_dr' => $tot_dr,
                'accounts'  => Account::latest()->get()->sortBy('name'),
                'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'edit_payment' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_payment'     => true
            );
        }
        
        return view('admin.cash_book.add_cash')->with($data);
    }

    public function delete($id){
        
        $c_b = CashBook::findOrFail(hashids_decode($id))->latest()->get();
        
        $cash = CashInHand::latest('created_at')->get();
        $cash_in_hand = $cash[0]->cash_in_hand;
        $cashin = CashInHand::findOrFail($cash[0]->id);
        
        if($c_b[0]->receipt_ammount == 0){
            $d = $cash_in_hand + $c_b[0]->payment_ammount;
            $cashin->cash_in_hand          = $d;
            $cashin->total_credit             = $cash[0]->total_credit - $c_b[0]->payment_ammount ;
            $cashin->total_debit             = $cash[0]->total_debit;

            $cashin->save();

        }else{
            $d = $cash_in_hand - $c_b[0]->receipt_ammount;
            $cashin->cash_in_hand          = $d;
            $cashin->total_credit             = $cash[0]->total_credit ;
            $cashin->total_debit             = $cash[0]->total_debit - - $c_b[0]->payment_ammount ;

            $cashin->save();
        }

        CashBook::destroy(hashids_decode($id));
        $ac_id = AccountLedger::where('cash_id',hashids_decode($id))->latest()->get();
        //dd($ac_id);
        AccountLedger::destroy($ac_id[0]->id);

        return response()->json([
            'success'   => 'Cash deleted successfully',
            'reload'    => true
        ]);
    }

    public function getParentAccounts($id){
        $parents = Account::where('parent_id', hashids_decode($id))->get();
        $html   = "<option value=''>Select account</option>";
        
        foreach($parents AS $parent){
            $html .= "<option value='{$parent->hashid}'>$parent->name</option>";
        }
        
        return response()->json([
            'html'  => $html
        ]);
    }
}
