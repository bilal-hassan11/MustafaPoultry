<?php

namespace App\Http\Controllers;

use App\Models\ChickInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;


class ChickInvoiceController extends Controller
{
    public function index()
    {

    }

    public function createPurchase(){
        
        $title = "Purchase Chick";
        $invoice_no = generateUniqueID(new ChickInvoice,'Purchase','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '2')->latest()->get() ;
        
        return view('admin.chick.purchase_chick', compact(['title','invoice_no','accounts','products']));
    }

    public function createSale(){
        
        $title = "Sale Chick";
        $invoice_no = generateUniqueID(new ChickInvoice,'Sale','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '2')->latest()->get() ;
        
        return view('admin.chick.sale_chick', compact(['title','invoice_no','accounts','products']));
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
    public function show(ChickInvoice $chickInvoice)
    {
        return response()->json($chickInvoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChickInvoice $chickInvoice)
    {
        $chickInvoice->delete();
        return response()->json(null, 204);
    }
}
