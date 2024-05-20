<?php

namespace App\Http\Controllers;

use App\Models\FeedInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;

class FeedInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function createPurchase(){
        
        $title = "Purchase Feed";
        $invoice_no = generateUniqueID(new FeedInvoice,'Purchase','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '3')->latest()->get() ;
        
        return view('admin.feed.purchase_feed', compact(['title','invoice_no','accounts','products']));
    }

    public function createSale(){
        
        $title = "Sale Feed";
        $invoice_no = generateUniqueID(new FeedInvoice,'Sale','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '3')->latest()->get() ;
        
        return view('admin.feed.sale_feed', compact(['title','invoice_no','accounts','products']));
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
    public function show(FeedInvoice $feedInvoice)
    {
        return response()->json($feedInvoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeedInvoice $feedInvoice)
    {
        $feedInvoice->delete();
        return response()->json(null, 204);
    }
}
