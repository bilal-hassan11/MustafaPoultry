<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\ExpiryStock;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\SendsWhatsAppMessages;
use Mpdf\Mpdf;

class MedicineInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SendsWhatsAppMessages;

    public function createPurchase(Request $req)
    {
        $title = "Purchase Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 4)
            ->with(['latestMedicineInvoice' => function ($query) {
                $query->select('item_id', 'purchase_price');
            }])
            ->latest()
            ->get();
        $purchase_medicine = MedicineInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->latest()
            ->get();

        return view('admin.medicine.purchase_medicine', compact(['title', 'invoice_no', 'accounts', 'products', 'purchase_medicine']));
    }


    public function createSale()
    {

        $title = "Sale Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Sale', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = ExpiryStock::with(['latestMedicineInvoice' => function ($query) {
            $query->select('item_id', 'sale_price');
        }])
            ->where('quantity', '>', 0)
            ->latest()
            ->get();

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

        $invoiceNumber = generateUniqueID(new MedicineInvoice, $request->type, 'invoice_no');

        DB::beginTransaction();

        try {
            $totalNetAmount = 0;

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {
                $netAmount = $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0);
                $totalNetAmount += $netAmount;

                $medicineInvoice = MedicineInvoice::create([
                    'date' => $validatedData['date'],
                    'account_id' => $validatedData['account'],
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => $request->type,
                    'stock_type' => in_array($request->type, ['Purchase', 'Sale Return', 'Adjust In']) ? 'In' : 'Out',
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

                $costAmount = $validatedData['quantity'][$index] * $validatedData['purchase_price'][$index];
                $expiryStock = ExpiryStock::where('item_id', $itemId)
                    ->where('expiry_date', $validatedData['expiry_date'][$index] ?? null)
                    ->first();

                if ($expiryStock) {
                    $expiryStock->quantity += $validatedData['quantity'][$index];
                    $expiryStock->rate += $costAmount;
                    $expiryStock->save();
                } else {
                    $expiryStock = ExpiryStock::create([
                        'date' => $validatedData['date'],
                        'medicine_invoice_id' => $invoiceNumber,
                        'item_id' => $itemId,
                        'rate' => $costAmount,
                        'quantity' => $validatedData['quantity'][$index],
                        'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    ]);
                }

                AccountLedger::create([
                    'medicine_invoice_id' => $medicineInvoice->id,
                    'type'  => $request->type,
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'account' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_id.*' => 'required|exists:items,id',
            'purchase_price.*' => 'required|numeric',
            'sale_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'amount.*' => 'required|numeric',
            'discount_in_rs.*' => 'nullable|numeric',
            'discount_in_percent.*' => 'nullable|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $items = $request->input('item_id', []);
        $quantities = $request->input('quantity', []);
        $expiryDates = $request->input('expiry_date', []);

        $itemExpiryQuantities = [];

        foreach ($items as $index => $itemId) {
            $expiryDate = $expiryDates[$index] ?? null;
            $quantity = $quantities[$index];

            $key = $itemId . '_' . ($expiryDate ?? 'no_expiry');

            if (!isset($itemExpiryQuantities[$key])) {
                $itemExpiryQuantities[$key] = 0;
            }

            $itemExpiryQuantities[$key] += $quantity;
        }

        $stockErrors = [];
        foreach ($itemExpiryQuantities as $key => $totalQuantity) {
            [$itemId, $expiryDate] = explode('_', $key);
            $expiryDate = $expiryDate === 'no_expiry' ? null : $expiryDate;

            $expiryStock = ExpiryStock::with('item')
                ->where('item_id', $itemId)
                ->where('expiry_date', $expiryDate)
                ->where('quantity', '>', 0)
                ->first();

            if (!$expiryStock) {
                $stockErrors["item_id.$itemId"] = ['No stock found for item with ID ' . $itemId . ' and expiry date ' . ($expiryDate ?? 'none')];
            } elseif ($expiryStock->quantity < $totalQuantity) {
                $stockErrors["item_id.$itemId"] = ['Insufficient stock for item ' . ($expiryStock->item->name ?? 'Unknown') . ' with expiry date ' . ($expiryDate ?? 'none')];
            }
        }

        if (!empty($stockErrors)) {
            return response()->json([
                'errors' => $stockErrors
            ], 422);
        }

        $validatedData = $validator->validated();


        $invoiceNumber = generateUniqueID(new MedicineInvoice, $request->type, 'invoice_no');

        DB::beginTransaction();

        try {
            $totalNetAmount = 0;

            foreach ($items as $index => $itemId) {
                $netAmount = $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0);
                $totalNetAmount += $netAmount;

                $medicineInvoice = MedicineInvoice::create([
                    'date' => $validatedData['date'],
                    'account_id' => $validatedData['account'],
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => $request->type,
                    'stock_type' => in_array($request->type, ['Purchase', 'Sale Return', 'Adjust In']) ? 'In' : 'Out',
                    'item_id' => $itemId,
                    'purchase_price' => $validatedData['purchase_price'][$index],
                    'sale_price' => $validatedData['sale_price'][$index],
                    'quantity' => $validatedData['quantity'][$index],
                    'amount' => $validatedData['quantity'][$index] * $validatedData['sale_price'][$index],
                    'discount_in_rs' => $validatedData['discount_in_rs'][$index] ?? 0,
                    'discount_in_percent' => $validatedData['discount_in_percent'][$index] ?? 0,
                    'net_amount' => $netAmount,
                    'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status' => $validatedData['whatsapp_status'] ?? 'Not Sent',
                ]);

                $expiryStock = ExpiryStock::where('item_id', $itemId)
                    ->where('expiry_date', $validatedData['expiry_date'][$index] ?? null)
                    ->first();

                $costAmount = $validatedData['quantity'][$index] * $validatedData['purchase_price'][$index];

                if ($expiryStock) {
                    $expiryStock->quantity -= $validatedData['quantity'][$index];
                    $expiryStock->rate -= $costAmount;
                    $expiryStock->save();
                } else {
                    ExpiryStock::create([
                        'date' => $validatedData['date'],
                        'medicine_invoice_id' => $medicineInvoice->id,
                        'item_id' => $itemId,
                        'rate' => $costAmount,
                        'quantity' => $validatedData['quantity'][$index],
                        'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    ]);
                }

                AccountLedger::create([
                    'medicine_invoice_id' => $medicineInvoice->id,
                    'type' => $request->type,
                    'date' => $validatedData['date'],
                    'account_id' => $validatedData['account'],
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Item: ' . $expiryStock->item->name . ', Qty: ' . $validatedData['quantity'][$index] . ', Rate: ' . $validatedData['sale_price'][$index],
                    'debit' => $netAmount,
                    'credit' => 0,
                ]);
            }
            $file_url = 'https://www.clickdimensions.com/links/TestPDFfile.pdf';
            $this->sendWhatsAppMessage('923003025291', 'Welcome to Laravel', $file_url);
            DB::commit();
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $type = $validatedData['type'];
        $originalInvoice = MedicineInvoice::findOrFail($validatedData['medicine_invoice_id']);

        $expiryStock = ExpiryStock::where('item_id', $originalInvoice->item_id)
            ->where('expiry_date', $originalInvoice->expiry_date)
            ->first();

        if ($type == 'Purchase Return' ||  $type == 'Ajust Out') {
            $price  = $originalInvoice->purchase_price;
            if ($expiryStock->quantity < $validatedData['quantity']) {
                return response()->json(['error' => 'Insufficient stock for the return. (' . $expiryStock->quantity . ')'], 422);
            }
        } else {
            $price  = $originalInvoice->sale_price;
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = generateUniqueID(new MedicineInvoice, $type, 'invoice_no');
            $amount =  $price * $validatedData['quantity'];
            $netAmount = $amount - $originalInvoice->discount_in_rs;


            $medicineInvoice = MedicineInvoice::create([
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'ref_no' => $validatedData['medicine_invoice_id'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => $validatedData['type'],
                'stock_type' => in_array($type, ['Purchase', 'Sale Return', 'Adjust In']) ? 'In' : 'Out',
                'item_id' => $originalInvoice->item_id,
                'purchase_price' => $originalInvoice->purchase_price,
                'sale_price' =>  $originalInvoice->sale_price,
                'quantity' => $validatedData['quantity'],
                'amount' => $amount,
                'discount_in_rs' => $originalInvoice->discount_in_rs,
                'discount_in_percent' => $originalInvoice->discount_in_percent,
                'net_amount' => $netAmount,
                'expiry_date' => $originalInvoice->expiry_date,
                'whatsapp_status' => 'Not Sent',
            ]);

            if ($type == 'Purchase Return') {
                $costPrice =  ($originalInvoice->purchase_price * $validatedData['quantity']) - $originalInvoice->discount_in_rs;
                $expiryStock->quantity -= $validatedData['quantity'];
                $expiryStock->rate -= $costPrice;
            } else {
                $costPrice =  ($originalInvoice->purchase_price * $validatedData['quantity']);
                $expiryStock->quantity += $validatedData['quantity'];
                $expiryStock->rate += $costPrice;
            }

            $expiryStock->save();

            AccountLedger::create([
                'medicine_invoice_id' => $medicineInvoice->id,
                'type'  => $type,
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'description' => 'Return #: ' . $invoiceNumber . ', ' . 'Item: ' . $expiryStock->item->name . ', Qty: ' . $validatedData['quantity'] . ', Rate: ' . $price,
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
        $url = request()->url();
        preg_match('/\/(\w+)(?=\/\d+)/', $url, $matches);
        $type = isset($matches[1]) ? ucfirst($matches[1]) : 'Purchase';

        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', $type)
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Medicine Invoice not found');
        }

        $medicineInvoiceIds = $medicineInvoice->pluck('id');
        $returnType = $type . ' Return';

        $returnedQuantities = MedicineInvoice::whereIn('ref_no', $medicineInvoiceIds)
            ->where('type', $returnType)
            ->groupBy('ref_no')
            ->select('ref_no', DB::raw('SUM(quantity) as total_returned'))
            ->pluck('total_returned', 'ref_no');

        $medicineInvoice = $medicineInvoice->map(function ($item) use ($returnedQuantities) {
            $item->total_returned = $returnedQuantities->get($item->id, 0);
            return $item;
        });

        if (request()->has('generate_pdf')) {
            $html = view('admin.medicine.invoice_pdf', compact('medicineInvoice', 'type'))->render();
            $mpdf = new Mpdf([
                'format' => 'A4-P', 'margin_top' => 10,
                'margin_bottom' => 2,
                'margin_left' => 2,
                'margin_right' => 2,
            ]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        } else {
            return view('admin.medicine.show_medicine', compact('medicineInvoice', 'type'));
        }
    }
}
