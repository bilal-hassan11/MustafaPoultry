<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getParentAccounts($id){
        $accounts = AccountType::where('parent_id', hashids_decode($id))->get();
        $html     = view('admin.common.parent_accounts', compact('accounts'))->render();

        return response()->json([
            'html'  => $html,
        ]);
    }
}
