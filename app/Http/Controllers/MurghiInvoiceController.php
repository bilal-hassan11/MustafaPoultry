<?php

namespace App\Http\Controllers;

use App\Models\MurghiInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;

class MurghiInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function createPurchase(){
        
        $title = "Purchase Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice,'Purchase','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '8')->latest()->get() ;
        
        return view('admin.murghi.purchase_murghi', compact(['title','invoice_no','accounts','products']));
    }

    public function createSale(){
        
        $title = "Sale Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice,'Sale','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '8')->latest()->get() ;
        
        return view('admin.murghi.sale_murghi', compact(['title','invoice_no','accounts','products']));
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
     
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show(MurghiInvoice $murghiInvoice)
    {
        return response()->json($murghiInvoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MurghiInvoice $murghiInvoice)
    {
        $murghiInvoice->delete();
        return response()->json(null, 204);
    }
}
