<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashBook;
use App\Models\AccountLedger;
use App\Http\Requests\CashBookRequest;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\CashInHand;
use Carbon\Carbon; 

class CashController extends Controller
{
    public function index(Request $req){
    
        $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();
        
        if(empty($cash[0])){
        
            $cash = CashInHand::latest('created_at')->get();
            $cash_in_hand = $cash[0]->cash_in_hand;
        
        }else{
        
            $cash_in_hand = $cash[0]->cash_in_hand;
        
        }

        $data = array(
            'title'     => 'Cash Book',
            'cash_in_hand' =>$cash_in_hand,
            'accounts'  => Account::latest()->get(),
            'cash' => CashBook::with(['account'])
                            ->when(isset($req->parent_id), function($query) use ($req){
                                $query->where('account_id', hashids_decode($req->parent_id));
                            })
                            ->when(isset($req->status), function($query) use ($req){
                                $query->where('status', $req->status);
                            })
                            ->when(isset($from_date, $to_date), function($query) use ($req){
                                $query->whereBetween('date', [$req->from_date, $req->to_date]);
                            })
                            ->latest()->get(),
            'account_types' => AccountType::whereNull('parent_id')->latest()->get(),
            
        );
        return view('admin.cash_book.add_cash')->with($data);
    }

    public function add(){
        
    }

    public function store(CashBookRequest $req){
        //check if today cash in hand
        $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();

        if(empty($cash[0])){
        
            $cash = CashInHand::latest('created_at')->get();
            $cash_in_hand = $cash[0]->cash_in_hand;//get last cash in hand
            $cashin = new CashInHand();//create new row i database
            
            // //check weather payment or receipt 
            if($req->receipt_ammount == null){

                $tot_debit = $cash[0]->cash_in_hand + $req->payment_ammount;
                dd($tot_debit);
                $cashin->date               = Carbon::today();
                //$cashin->cash_id         = hashids_decode($req->account_id);
                $cashin->cash_in_hand          = $tot_debit;
                $cashin->total_debit             = $req->payment_ammount;
                $cashin->total_credit            = 0;
                $cashin->save();
                
            }else{
                
                $tot_credit = $cash[0]->cash_in_hand - $req->receipt_ammount;
                //dd($tot_credit);
                $cashin->date               = Carbon::today();
                //$cashin->cash_id         = hashids_decode($req->account_id);
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

                $cash_in = $cash_in_hand + $req->payment_ammount;
                $debit_in = $pre_debit + $req->payment_ammount;

                $cashin->cash_in_hand          = $cash_in;
                $cashin->total_debit             = $debit_in;
                $cashin->total_credit             = $cash[0]->total_credit;

                $cashin->save();
                
            }else{
                $cash = CashInHand::whereDate('created_at', Carbon::today())->latest('created_at')->get();

                $cash_in_hand = $cash[0]->cash_in_hand;//get Today Cash IN Hand
                $pre_credit = $cash[0]->total_credit;//get previos credit of  

                $cash_in = $cash_in_hand - $req->receipt_ammount;
                $credit_in = $pre_credit + $req->receipt_ammount;
                
                //dd($cash_in_hand);

                $cashin->cash_in_hand          = $cash_in;
                $cashin->total_credit             = $credit_in;
                $cashin->total_debit             = $cash[0]->total_debit;

                $cashin->save();
            }    
        
        }

        if(check_empty($cash[0]->id)){
            $cashbook = CashBook::findOrFail($cash[0]->id);
            $msg      = 'Cash Book added successfully';

            
        }else{
            $cashbook = new CashBook();
            $msg      = 'Cash Book added successfully';
        }

        
        // //check weather payment or receipt 
        if(check_empty($req->cash_id)){
            $cashbook = CashBook::findOrFail(hashids_decode($req->cash_id));
            $msg      = 'Cash Book udpated successfully';
        }else{
            $cashbook = new CashBook();
            $msg      = 'Cash Book added successfully';
        }
        
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
        
        
        $account_detail = AccountLedger::where('account_id','=', hashids_decode($req->account_id))->latest()->get();


            
        if($req->receipt_ammount == null){
            //Payment Received
            $accountledger = new AccountLedger();

            $pay_ammount = $req->payment_ammount;
            $id = CashBook::latest('created_at')->first();
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->sale_id          = 0;
            $accountledger->purchase_id             = 0;
            $accountledger->cash_id             = $id->id;
            $accountledger->debit            = 0;
            $accountledger->credit          = $pay_ammount;
            $accountledger->description            = $req->narration;
            $accountledger->save();
        

        }else{
            $accountledger = new AccountLedger();

            $pay_ammount = $req->receipt_ammount;
            $id = CashBook::latest('created_at')->first();
            $accountledger->account_id = hashids_decode($req->account_id);
            $accountledger->sale_id          = 0;
            $accountledger->purchase_id      = 0;
            $accountledger->cash_id          = $id->id;
            $accountledger->debit            = $pay_ammount ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = $req->narration;
            $accountledger->save();
        
        }
    
    
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.cash.index')
        ]);
    }

    public function edit($id){

        
        $i = CashBook::findOrFail(hashids_decode($id));
        
        if($i->status == "receipt"){
            
            $data = array(
                'title'     => 'Cash Book',
                'accounts'  => Account::latest()->get(),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'edit_receipt' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_receipt'     => true
            );
        }else{
            //dd("Payment");
            $data = array(
                'title'     => 'Cash Book',
                'accounts'  => Account::latest()->get(),
                'cash' => CashBook::with(['account'])->latest()->get(),
                'edit_payment' => CashBook::findOrFail(hashids_decode($id)),
                'is_update_payment'     => true
            );
        }
        //dd($data);
        return view('admin.cash_book.add_cash')->with($data);
    }

    public function delete($id){
        
        CashBook::destroy(hashids_decode($id));
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
