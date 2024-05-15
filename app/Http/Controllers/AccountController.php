<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
        $data = array(
            'title'     => 'Accounts',
            'accounts'  => Account::with(['grand_parent', 'parent'])->latest()->get(),
        );
        return view('admin.account.index')->with($data);
    }

    public function add($grand_parent_id, $parent_id){
        $data = array(
            'title' => 'Add Accounts',
            'grand_parent_id'=> $grand_parent_id,
            'parent_id'     => $parent_id,
            'grand_parent'=> AccountType::where('id',hashids_decode($grand_parent_id))->get(),
            'parent'     => AccountType::where('id',hashids_decode($parent_id))->get(),
            'grand_parents' => AccountType::whereNull('parent_id')->get(),
        );
        return view('admin.account.add')->with($data);
    }

    public function store(AccountRequest $req){
        //dd($req->all());
        if(check_empty($req->account_id)){
            $account = Account::findOrFail(hashids_decode($req->account_id));
            $msg     = 'Account updated successfully';
        }else{
            $account = new Account;
            $msg     = 'Account added successfully';
        }

        $account->grand_parent_id = hashids_decode($req->grand_parent_id);
        $account->parent_id       = hashids_decode($req->parent_id);
        $account->name            = $req->name;
        $account->opening_balance = floatval($req->opening_balance);
        $account->opening_date    = $req->opening_date;
        $account->account_nature  = $req->account_nature;
        $account->status  = $req->status;

        $account->ageing          = intval($req->ageing);
        $account->commission      = intval($req->commission);
        $account->discount        = intval($req->discount);
        $account->address         = $req->address;
        $account->save();
        
        return response()->json([
            'success'   => $msg,
            'redirect'    => route('admin.accounts.index')
        ]);
    }

    public function edit($id){
        $data = array(
            'title' => 'Edit Account',
            'grand_parent' => AccountType::whereNull('parent_id')->get(),
            'edit_account'  => Account::findOrFail(hashids_decode($id)),
            'is_update'     => true
        );
        $data['parent']    = AccountType::where('parent_id', $data['edit_account']->grand_parent_id)->get();//get the parents of specific grand parent id
        return view('admin.account.add')->with($data);
    }

    public function delete($id){
        Account::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Account deleted successfully',
            'reload'    => true
        ]);
    }
}
