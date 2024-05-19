<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use App\Models\PurchaseMedicine;
use Illuminate\Http\Request;

class MedicineInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function createPurchase(){
        
        $invoice_no = generateUniqueID(new MedicineInvoice,'Purchase','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '4')->latest()->get() ;
        
        return view('admin.medicine.purchase_medicine', compact(['invoice_no','accounts','products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_id.*' => 'required|exists:items,id',
            'purchase_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'amount.*' => 'required|numeric',
            'discount_in_rs.*' => 'nullable|numeric',
            'discount_in_percent.*' => 'nullable|numeric',
            'net_amount.*' => 'required|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);
    
        $validatedData['invoice_no'] = generateUniqueID(new MedicineInvoice, 'Purchase', 'invoice_no');
        $validatedData['type'] = 'Purchase';
        $validatedData['stock_type'] = 'In';
        
        $medicineInvoice = MedicineInvoice::create($validatedData);
    
        return response()->json(['success' => true, 'medicineInvoice' => $medicineInvoice], 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(MedicineInvoice $medicineInvoice)
    {
        return response()->json($medicineInvoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineInvoice $medicineInvoice)
    {
        $medicineInvoice->delete();
        return response()->json(null, 204);
    }
}