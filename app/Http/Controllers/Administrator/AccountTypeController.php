<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormTypeRequest;
use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index(){
        $data = array(
            'title'         => 'Account Type',
            'grand_parents' => AccountType::whereNull('parent_id')->get(),
            'accounts'      => AccountType::with('grand_parent')->get(),
        );
        return view('admin.account_type.index')->with($data);
    }

    public function store(FormTypeRequest $req){
        
        $validated = $req->validated();

        if(isset($validated['account_type_id']) && !empty($validated['account_type_id'])){
            $account_type = AccountType::findOrFail(hashids_decode($validated['account_type_id']));
            $msg          = 'Account type updated successfully';
        }else{
            $account_type = new AccountType();
            $msg          = 'Account type added successfully';
        }

        $account_type->parent_id = (intval(hashids_decode($validated['parent_id'])) == 0) ? null : hashids_decode($validated['parent_id']) ;
        $account_type->name      = $validated['name'];
        $account_type->save();

        return response()->json([
            'success'   => $msg,
            'redirect'  => route('admin.account_types.index')
        ]);
    }

    public function edit($id){
        $data = array(
            'title'         => 'Account Type',
            'grand_parents' => AccountType::whereNull('parent_id')->get(),
            'accounts'      => AccountType::with('grand_parent')->get(),
            'is_update'     => true,
            'edit_account'  => AccountType::findOrFail(hashids_decode($id)),
        );
        return view('admin.account_type.index')->with($data);
    }

    public function delete($id){
        AccountType::destroy(hashids_decode($id));
        return response()->json([
            'success'   => 'Account type deleted successfully',
            'reload'    => true
        ]);
    }
}
