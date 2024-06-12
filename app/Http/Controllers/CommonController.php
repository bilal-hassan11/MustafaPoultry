<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\ChickInvoice;
use App\Models\MedicineInvoice;
use App\Models\FeedInvoice;
use App\Models\MurghiInvoice;
use App\Models\OtherInvoice;

class CommonController extends Controller
{
    public function getParentAccounts($id)
    {
        $accounts = AccountType::where('parent_id', hashids_decode($id))->get();
        $html     = view('admin.common.parent_accounts', compact('accounts'))->render();

        return response()->json([
            'html'  => $html,
        ]);
    }

    public function getLatestSale($category)
    {
        $data = null;

        switch ($category) {
            case 'chick':
                $data = ChickInvoice::where('type', 'Sale')->orderBy('date', 'desc')->take(5)->get();
                break;
            case 'medicine':
                $data = MedicineInvoice::where('type', 'Sale')->orderBy('date', 'desc')->take(5)->get();
                break;
            case 'feed':
                $data = FeedInvoice::where('type', 'Sale')->orderBy('date', 'desc')->take(5)->get();
                break;
            case 'murghi':
                $data = MurghiInvoice::where('type', 'Sale')->orderBy('date', 'desc')->take(5)->get();
                break;
            case 'others':
                $data = OtherInvoice::where('type', 'Sale')->orderBy('date', 'desc')->take(5)->get();
                break;
        }


        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'No data available']);
        }
    }
}
