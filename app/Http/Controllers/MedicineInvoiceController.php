<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\ExpiryStock;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use Illuminate\Http\Request;

class MedicineInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function createPurchase()
    {

        $title = "Purchase Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Purchase', 'invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', 4)
            ->with(['latestMedicineInvoice' => function ($query) {
                $query->select('item_id', 'purchase_price');
            }])
            ->latest()
            ->get();

        return view('admin.medicine.purchase_medicine', compact(['title', 'invoice_no', 'accounts', 'products']));
    }

    public function createSale()
    {

        $title = "Sale Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Sale', 'invoice_no');
        $accounts  = Account::with(['grand_parent', 'parent'])->latest()->get()->sortBy('name');
        $products = Item::where('category_id', '4')->latest()->get();

        return view('admin.medicine.sale_medicine', compact(['title', 'invoice_no', 'accounts', 'products']));
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
            $totalNetAmount = 0;

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {
                $netAmount = $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0);
                $totalNetAmount += $netAmount;

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
                    'discount_in_rs' => $validatedData['discount_in_rs'][$index] ?? 0,
                    'discount_in_percent' => $validatedData['discount_in_percent'][$index] ?? 0,
                    'net_amount' => $netAmount,
                    'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status' => $validatedData['whatsapp_status'] ?? 'Not Sent',
                ]);

                $expiryStock = ExpiryStock::where('item_id', $itemId)
                    ->where('expiry_date', $validatedData['expiry_date'][$index] ?? null)
                    ->first();

                if ($expiryStock) {
                    $expiryStock->quantity += $validatedData['quantity'][$index];
                    $expiryStock->rate += $netAmount;
                    $expiryStock->save();
                } else {
                    $expiryStock = ExpiryStock::create([
                        'date' => $validatedData['date'],
                        'medicine_invoice_id' => $invoiceNumber,
                        'item_id' => $itemId,
                        'rate' => $netAmount,
                        'quantity' => $validatedData['quantity'][$index],
                        'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    ]);
                }

                AccountLedger::create([
                    'purchase_medicine_id' => $invoiceNumber,
                    'date' => $validatedData['date'],
                    'account_id' => $validatedData['account'],
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Item: ' . $expiryStock->item->name . ', Qty: ' . $validatedData['quantity'][$index] . ', Rate: ' . $validatedData['purchase_price'][$index],
                    'debit' => 0,
                    'credit' => $netAmount,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function singleReturn(Request $request)
    {
        $validatedData = $request->validate([
            'medicine_invoice_id' => 'required|exists:medicine_invoices,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required',
        ]);

        $originalInvoice = MedicineInvoice::findOrFail($validatedData['medicine_invoice_id']);

        $expiryStock = ExpiryStock::where('item_id', $originalInvoice->item_id)
            ->where('expiry_date', $originalInvoice->expiry_date)
            ->first();

        if ($expiryStock->quantity < $validatedData['quantity']) {
            return response()->json(['error' => 'Insufficient stock for the return.'], 422);
        }

        $invoiceNumber = generateUniqueID(new MedicineInvoice, 'Purchase Return', 'invoice_no');

        DB::beginTransaction();

        try {
            $netAmount = ($originalInvoice->purchase_price * $validatedData['quantity']) - $originalInvoice->discount_in_rs;

            MedicineInvoice::create([
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'ref_no' => $validatedData['medicine_invoice_id'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => $validatedData['type'],
                'stock_type' => 'Out',
                'item_id' => $originalInvoice->item_id,
                'purchase_price' => $originalInvoice->purchase_price,
                'sale_price' => 0,
                'quantity' => $validatedData['quantity'],
                'amount' => $originalInvoice->purchase_price * $validatedData['quantity'],
                'discount_in_rs' => $originalInvoice->discount_in_rs,
                'discount_in_percent' => $originalInvoice->discount_in_percent,
                'net_amount' => $netAmount,
                'expiry_date' => $originalInvoice->expiry_date,
                'whatsapp_status' => 'Not Sent',
            ]);

            $expiryStock->quantity -= $validatedData['quantity'];
            $expiryStock->rate -= $netAmount;
            $expiryStock->save();

            AccountLedger::create([
                'purchase_medicine_id' => $invoiceNumber,
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'description' => 'Return #: ' . $invoiceNumber . ', ' . 'Item: ' . $expiryStock->item->name . ', Qty: ' . $validatedData['quantity'] . ', Rate: ' . $originalInvoice->purchase_price,
                'debit' => $netAmount,
                'credit' => 0,
            ]);

            DB::commit();

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($invoice_no)
    {
        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase')
            ->with('account', 'item')
            ->get();

        $medicineInvoiceIds = $medicineInvoice->pluck('id');

        $returnedQuantities = MedicineInvoice::whereIn('ref_no', $medicineInvoiceIds)
            ->where('type', 'Purchase Return')
            ->groupBy('ref_no')
            ->select('ref_no', DB::raw('SUM(quantity) as total_returned'))
            ->pluck('total_returned', 'ref_no');

        $medicineInvoice = $medicineInvoice->map(function ($item) use ($returnedQuantities) {
            $item->total_returned = $returnedQuantities->get($item->id, 0);
            return $item;
        });

        return view('admin.medicine.show_medicine', compact('medicineInvoice'));
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
