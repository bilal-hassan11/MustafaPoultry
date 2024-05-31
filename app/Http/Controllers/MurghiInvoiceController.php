<?php

namespace App\Http\Controllers;

use App\Models\MurghiInvoice;
use App\Models\Account;
use App\Models\Item;
use App\Models\ExpiryStock;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\SendsWhatsAppMessages;

class MurghiInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SendsWhatsAppMessages;
    public function index()
    {
    }

    public function createPurchase(Request $req)
    {
        $title = "Purchase Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 8)
            ->with(['latestMurghiInvoice' => function ($query) {
                $query->select('item_id', 'purchase_price');
            }])
            ->latest()
            ->get();
        $purchase_murghi = MurghiInvoice::with('account', 'item')
                                    ->where('type', 'Purchase')
                                    ->when(isset($req->account_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->account_id));
                                    })
                                    ->when(isset($req->invoice_no), function($query) use ($req){
                                        $query->where('invoice_no', $req->invoice_no);
                                    })
                                    ->when(isset($req->item_id), function($query) use ($req){
                                        $query->where('item_id', hashids_decode($req->item_id));
                                    })
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })
                                    ->latest()
                                    ->get();

        return view('admin.murghi.purchase_murghi', compact(['title', 'invoice_no', 'accounts', 'products', 'purchase_murghi']));
    }


    public function createSale(Request $req)
    {

        $title = "Sale Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice, 'Sale', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = ExpiryStock::with('item')
                                ->whereHas('item', function ($query) {
                                    $query->where('category_id', 8);
                                })
                                ->where('quantity', '>', 0)
                                ->get();

        $sale_murghi = MurghiInvoice::with('account', 'item')
                                    ->where('type', 'Sale')
                                    ->when(isset($req->account_id), function($query) use ($req){
                                        $query->where('account_id', hashids_decode($req->account_id));
                                    })
                                    ->when(isset($req->invoice_no), function($query) use ($req){
                                        $query->where('invoice_no', $req->invoice_no);
                                    })
                                    ->when(isset($req->item_id), function($query) use ($req){
                                        $query->where('item_id', hashids_decode($req->item_id));
                                    })
                                    ->when(isset($req->from_date, $req->to_date), function($query) use ($req){
                                        $query->whereBetween('date', [$req->from_date, $req->to_date]);
                                    })
                                    ->latest()
                                    ->get();


        return view('admin.murghi.sale_murghi', compact(['title','sale_murghi', 'invoice_no', 'accounts', 'products']));
    }

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
            'weight_detection.*' => 'nullable|numeric',
            'final_weight.*' => 'nullable|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);
        
        $invoiceNumber = generateUniqueID(new MurghiInvoice, $request->type, 'invoice_no');

        DB::beginTransaction();

        try {
            $totalNetAmount = 0;

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {
                $netAmount = $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0);
                $totalNetAmount += $netAmount;

                $murghiInvoice = MurghiInvoice::create([
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
                    'weight_detection' => $validatedData['discount_in_rs'][$index] ?? 0,
                    'final_weight' => $validatedData['discount_in_percent'][$index] ?? 0,
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
                    'murghi_invoice_id' => $murghiInvoice->id,
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
        // Validate request data
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'account' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'id.*' => 'required|exists:expiry_stocks,id',
            'id.*' => 'required|exists:expiry_stocks,id',
            'purchase_price.*' => 'required|numeric',
            'sale_price.*' => 'required|numeric',
            'quantity.*' => 'required|integer',
            'amount.*' => 'required|numeric',
            'weight_detection.*' => 'nullable|numeric',
            'final_weight.*' => 'nullable|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $items = collect($request->input('item_id'));
        $ids = collect($request->input('id'));
        $quantities = collect($request->input('quantity'));
        $groupedItems = $ids->zip($quantities)->groupBy(0)->map(function ($group) {
            return $group->sum(1);
        });

        $stockErrors = [];

        $groupedItems->each(function ($quantity, $id) use (&$stockErrors) {
            $expiryStock = ExpiryStock::find($id);
            if ($expiryStock->quantity < $quantity) {
                $itemName = $expiryStock->item->name ?? 'Unknown';
                $stockErrors["id.$id"] = ['Insufficient stock for item ' . $expiryStock->item->name];
            }
        });

        // Return stock errors if any
        if (!empty($stockErrors)) {
            return response()->json([
                'errors' => $stockErrors
            ], 422);
        }
        $invoiceNumber = generateUniqueID(new MurghiInvoice, $request->type, 'invoice_no');

        DB::beginTransaction();

        try {
            $totalNetAmount = 0;

            foreach ($items as $index => $itemId) {
                $netAmount = $validatedData['amount'][$index] - ($validatedData['discount_in_rs'][$index] ?? 0);
                $totalNetAmount += $netAmount;

                $murghiInvoice = MurghiInvoice::create([
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
                    'weight_detection' => $validatedData['discount_in_rs'][$index] ?? 0,
                    'final_weight' => $validatedData['discount_in_percent'][$index] ?? 0,
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
                        'medicine_invoice_id' => $murghiInvoice->id,
                        'item_id' => $itemId,
                        'rate' => $costAmount,
                        'quantity' => $validatedData['quantity'][$index],
                        'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    ]);
                }

                AccountLedger::create([
                    'murghi_invoice_id' => $murghiInvoice->id,
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
            'murghi_invoice_id' => 'required|exists:murghi_invoices,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required',
        ]);
        $type = $validatedData['type'];
        $originalInvoice = MurghiInvoice::findOrFail($validatedData['murghi_invoice_id']);

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
            $invoiceNumber = generateUniqueID(new MurghiInvoice, $type, 'invoice_no');
            $amount =  $price * $validatedData['quantity'];
            $netAmount = $amount - $originalInvoice->discount_in_rs;


            $murghiInvoice = MurghiInvoice::create([
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'ref_no' => $validatedData['murghi_invoice_id'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => $validatedData['type'],
                'stock_type' => in_array($type, ['Purchase', 'Sale Return', 'Adjust In']) ? 'In' : 'Out',
                'item_id' => $originalInvoice->item_id,
                'purchase_price' => $originalInvoice->purchase_price,
                'sale_price' =>  $originalInvoice->sale_price,
                'quantity' => $validatedData['quantity'],
                'amount' => $amount,
                'weight_detection' => 2,
                'final_weight' => 2,
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

            $debit = 0;
            $credit = 0;


            if ($type === 'Sale Return') {
                $credit = $netAmount;
            } else {
                $debit = $netAmount;
            }

            AccountLedger::create([
                'murghi_invoice_id' => $murghiInvoice->id,
                'type'  => $type,
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'description' => 'Return #: ' . $invoiceNumber . ', ' . 'Item: ' . $expiryStock->item->name . ', Qty: ' . $validatedData['quantity'] . ', Rate: ' . $price,
                'debit' => $debit,
                'credit' => $credit,
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

        $murghiInvoice = MurghiInvoice::where('invoice_no', $invoice_no)
            ->where('type', $type)
            ->with('account', 'item')
            ->get();

        if ($murghiInvoice->isEmpty()) {
            abort(404, 'murghi Invoice not found');
        }

        $murghiInvoiceIds = $murghiInvoice->pluck('id');
        $returnType = $type . ' Return';

        $returnedQuantities = MurghiInvoice::whereIn('ref_no', $murghiInvoiceIds)
            ->where('type', $returnType)
            ->groupBy('ref_no')
            ->select('ref_no', DB::raw('SUM(quantity) as total_returned'))
            ->pluck('total_returned', 'ref_no');

        $murghiInvoice = $murghiInvoice->map(function ($item) use ($returnedQuantities) {
            $item->total_returned = $returnedQuantities->get($item->id, 0);
            return $item;
        });

        if (request()->has('generate_pdf')) {
            $html = view('admin.murghi.invoice_pdf', compact('murghiInvoice', 'type'))->render();
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
            return view('admin.murghi.show_murghi', compact('murghiInvoice', 'type'));
        }
    }
}
