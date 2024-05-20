<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\Category;
use App\Models\PurchaseMedicine;
use Illuminate\Support\Facades\DB; 
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
        
        $title = "Purchase Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice,'Purchase','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '4')->latest()->get() ;
        
        return view('admin.medicine.purchase_medicine', compact(['title','invoice_no','accounts','products']));
    }

    public function createSale(){
        
        $title = "Sale Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice,'Sale','invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '4')->latest()->get() ;
        
        return view('admin.medicine.sale_medicine', compact(['title','invoice_no','accounts','products']));
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'account' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_id.*' => 'required|exists:items,id',
            'purchase_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'amount.*' => 'required|numeric',
            'discount_in_rs.*' => 'nullable|numeric',
            'discount_in_percent.*' => 'nullable|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);
    
        $invoiceNumber = generateUniqueID(new MedicineInvoice, 'Purchase', 'invoice_no');
    
        DB::beginTransaction();
    
        try {
            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {
                MedicineInvoice::create([
                    'date' => $validatedData['date'],
                    'account_id' => $validatedData['account'],
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => 'Purchase',
                    'stock_type' => 'In',
                    'item_id' => $itemId,
                    'purchase_price' => $validatedData['purchase_price'][$index],
                    'sale_price' => 0,
                    'quantity' => $validatedData['quantity'][$index],
                    'amount' => $validatedData['quantity'][$index] * $validatedData['purchase_price'][$index],
                    'discount_in_rs' => $validatedData['discount_in_rs'][$index] ?? null,
                    'discount_in_percent' => $validatedData['discount_in_percent'][$index] ?? null,
                    'net_amount' => $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0),
                    'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status' => $validatedData['whatsapp_status'] ?? 'Not Sent',
                ]);
            }
    
            DB::commit();
    
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while saving the invoice.'], 500);
        }
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