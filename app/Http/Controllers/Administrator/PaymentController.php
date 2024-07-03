<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Account;
use App\Models\AccountLedger;



use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        $data = array(
            'title'         => 'Add Payment Book',
            'payment'    => Payment::with(['account','d_account'])->latest()->get(),
            'accounts'    => Account::latest()->get(),
            
        );
        return view('admin.paymentbook.add_payment')->with($data);
    }

    public function store(Request $req){
        
        

        if(isset($req->payment_id) && !empty($req->payment_id)){
            $paymentbook     = Payment::findOrFail(hashids_decode($req->payment_id));
            $msg          = 'Payment updated successfully';
            
            $paymentbook->date            = $req->date;
            $paymentbook->debtor_account_id     = hashids_decode($req->debtor_id);
            $paymentbook->debtor_ammount         = $req->debtor_amount;
            $paymentbook->creditor_account_id   = hashids_decode($req->creditor_id);
            $paymentbook->credit_ammount        = $req->creditor_amount;
            $paymentbook->remarks               = $req->remarks;
            $paymentbook->save();


            //Supplier Account Ledger
            $get_ledger_debit_id = AccountLedger::where('payment_id',hashids_decode($req->payment_id))->where('credit',0)->latest()->get();
            
            $accountledger   = AccountLedger::findOrFail($get_ledger_debit_id[0]->id);

            $account = Account::findOrFail(hashids_decode($req->debtor_id));
            $account_name = $account->name;
            $accountledger->date               = $req->date;
            $accountledger->account_id = hashids_decode($req->debtor_id);
            $accountledger->payment_id          = $paymentbook->id;
            $accountledger->debit            = $req->debtor_amount ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = ' Account #'.'['.$account->id.']'.$account->name.',  Paid Ammount '.$req->debtor_amount;
            $accountledger->save();

            //Farm/Feed Account Ledger
            $get_ledger_credit_id = AccountLedger::where('payment_id',hashids_decode($req->payment_id))->where('debit',0)->latest()->get();
            $accountledger = AccountLedger::findOrFail($get_ledger_credit_id[0]->id);
           
            $account = Account::findOrFail(hashids_decode($req->creditor_id));
            $account_name = $account->name;
            $accountledger->date               = $req->date;
            $accountledger->account_id = hashids_decode($req->creditor_id);
            $accountledger->payment_id          = $paymentbook->id;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $req->creditor_amount ;
            $accountledger->description      = ' Account #'.'['.$account->id.']'.$account->name.',  Paid Ammount '.$req->creditor_amount;
            $accountledger->save();


        }else{
           
            $msg          = 'Payment added successfully';

            $paymentbook = new Payment();
            $paymentbook->date            = $req->date;
            $paymentbook->debtor_account_id     = hashids_decode($req->debtor_id);
            $paymentbook->debtor_ammount         = $req->debtor_amount;
            $paymentbook->creditor_account_id   = hashids_decode($req->creditor_id);
            $paymentbook->credit_ammount        = $req->creditor_amount;
            $paymentbook->remarks               = $req->remarks;
            $paymentbook->save();


            //Supplier Account Ledger
            $accountledger = new AccountLedger();
           
            $account = Account::findOrFail(hashids_decode($req->debtor_id));
            $account_name = $account->name;
            $accountledger->date               = $req->date;
            $accountledger->account_id = hashids_decode($req->debtor_id);
            $accountledger->payment_id          = $paymentbook->id;
            $accountledger->debit            = $req->debtor_amount ;
            $accountledger->credit           = 0 ;
            $accountledger->description      = ' Account #'.'['.$account->id.']'.$account->name.',  Paid Ammount '.$req->debtor_amount;
            $accountledger->save();

            //Farm/Feed Account Ledger
            $accountledger = new AccountLedger();
           
            $account = Account::findOrFail(hashids_decode($req->creditor_id));
            $account_name = $account->name;
            $accountledger->date               = $req->date;
            $accountledger->account_id = hashids_decode($req->creditor_id);
            $accountledger->payment_id          = $paymentbook->id;
            $accountledger->debit            = 0 ;
            $accountledger->credit           = $req->creditor_amount ;
            $accountledger->description      = ' Account #'.'['.$account->id.']'.$account->name.',  Paid Ammount '.$req->creditor_amount;
            $accountledger->save();


        }
        
        

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.paymentbooks.index')
        ]);
    }

    public function edit($id){
        $data = array(
            'title'         => 'Edit Payment Book',
            'payment'    => Payment::with(['account','d_account'])->latest()->get(),
            'accounts'    => Account::latest()->get(),
            'is_update'     => true,
            'edit_Payment' => Payment::findOrFail(hashids_decode($id)),
        );
        return view('admin.paymentbook.add_payment')->with($data);
    }

    public function delete($id){
        Category::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Category deleted successfully',
            'reload'    => true
        ]);
    }
}
