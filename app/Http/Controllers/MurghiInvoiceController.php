<?php

namespace App\Http\Controllers;

use App\Models\MurghiInvoice;
use App\Models\Account;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use App\Traits\SendsWhatsAppMessages;
use Mpdf\Mpdf;

class MurghiInvoiceController extends Controller
{

    use SendsWhatsAppMessages;
    protected $MurghiInvoice;

    public function __construct(MurghiInvoice $MurghiInvoice)
    {
        $this->MurghiInvoice = $MurghiInvoice;
    }

    public function createPurchase(Request $req)
    {
        $title = "Purchase Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 8)->get();

        $purchase_Murghi = MurghiInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->when(isset($req->account_id), function ($query) use ($req) {
                $query->where('account_id', hashids_decode($req->account_id));
            })
            ->when(isset($req->invoice_no), function ($query) use ($req) {
                $query->where('invoice_no', $req->invoice_no);
            })
            ->when(isset($req->item_id), function ($query) use ($req) {
                $query->where('item_id', hashids_decode($req->item_id));
            })
            ->when(isset($req->from_date, $req->to_date), function ($query) use ($req) {
                $query->whereBetween('date', [$req->from_date, $req->to_date]);
            })
            ->latest()
            ->get();

        $pending_Murghi = MurghiInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.murghi.purchase_murghi', compact(['title', 'pending_Murghi', 'invoice_no', 'accounts', 'products', 'purchase_Murghi']));
    }

    public function editPurchase($invoice_no)
    {
        $title = "Edit Purchase Murghi";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 8)->get();
        $MurghiInvoice = MurghiInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase')
            ->with('account', 'item')
            ->get();

        return view('admin.murghi.edit_purchase_murghi', compact(['title', 'accounts', 'products', 'MurghiInvoice']));
    }
    public function editSale($invoice_no)
    {
        $title = "Edit Sale Murghi";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $stock = $this->MurghiInvoice->getStockInfo();

        $products = $stock->filter(function ($product) {
            return $product->category_id == 8;
        });

        $MurghiInvoice = MurghiInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale')
            ->get();

        $pending_Murghi = MurghiInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.murghi.edit_sale_murghi', compact(['title', 'pending_Murghi', 'accounts', 'products', 'MurghiInvoice']));
    }

    public function createSale(Request $req)
    {

        $title = "Sale Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice, 'Sale', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $MurghiInvoice = new MurghiInvoice();

        $stock = $this->MurghiInvoice->getStockInfo();

        $products = $stock->filter(function ($product) {
            return $product->category_id == 8;
        });

        $sale_Murghi = $MurghiInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->when(isset($req->account_id), function ($query) use ($req) {
                $query->where('account_id', $req->account_id);
            })
            ->when(isset($req->invoice_no), function ($query) use ($req) {
                $query->where('invoice_no', $req->invoice_no);
            })
            ->when(isset($req->item_id), function ($query) use ($req) {
                $query->where('item_id', $req->item_id);
            })
            ->when(isset($req->from_date, $req->to_date), function ($query) use ($req) {
                $query->whereBetween('date', [$req->from_date, $req->to_date]);
            })
            ->latest()
            ->get();

        $pending_Murghi = $MurghiInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.murghi.sale_murghi', compact(['title', 'pending_Murghi', 'sale_Murghi', 'invoice_no', 'accounts', 'products']));
    }

    public function createPurchaseSale(Request $req)
    {
        $title = "Purchase Murghi";
        $invoice_no = generateUniqueID(new MurghiInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 8)->get();

        $purchase_Murghi = MurghiInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->when(isset($req->account_id), function ($query) use ($req) {
                $query->where('account_id', hashids_decode($req->account_id));
            })
            ->when(isset($req->invoice_no), function ($query) use ($req) {
                $query->where('invoice_no', $req->invoice_no);
            })
            ->when(isset($req->item_id), function ($query) use ($req) {
                $query->where('item_id', hashids_decode($req->item_id));
            })
            ->when(isset($req->from_date, $req->to_date), function ($query) use ($req) {
                $query->whereBetween('date', [$req->from_date, $req->to_date]);
            })
            ->latest()
            ->get();

        $pending_Murghi = MurghiInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.murghi.purchase_sale', compact(['title', 'invoice_no', 'accounts', 'products', 'purchase_Murghi']));
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_no' =>  'required',
            'date' => 'required|date',
            'account' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_id.*' => 'required|exists:items,id',
            'id.*' => 'nullable',
            'purchase_price.*' => 'required|numeric',
            'sale_price.*' => 'required|numeric',
            'weight.*' => 'required|numeric',
            'weight_detection.*' => 'required|numeric',
            'quantity.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
            'discount_in_rs.*' => 'nullable|numeric',
            'discount_in_percent.*' => 'nullable|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);

        $date = $request->input('date');

        if ($request->type == 'Sale' || $request->type == 'Adjust Out') {
            $stockErrors = $this->validateStockQuantities($validatedData);

            if (!empty($stockErrors)) {
                return response()->json(['errors' => $stockErrors], 422);
            }
        }

        DB::beginTransaction();
        if ($request->has('editMode')) {
            $invoiceNumber = $request->invoice_no;
            $MurghiInvoices = MurghiInvoice::where('invoice_no', $invoiceNumber)
                ->where('type', $request->type)
                ->get();
            $MurghiInvoiceIds = $MurghiInvoices->pluck('id');
            MurghiInvoice::whereIn('id', $MurghiInvoiceIds)->delete();
            AccountLedger::whereIn('murghi_invoice_id', $MurghiInvoiceIds)
                ->where('type', $request->type)
                ->delete();
        } else {
            $invoiceNumber = generateUniqueID(new MurghiInvoice, $request->type, 'invoice_no');
        }

        try {

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {

                $price = in_array($request->type, ['Sale', 'Adjust Out']) ? $validatedData['sale_price'][$index] : $validatedData['purchase_price'][$index];
                $netAmount = ($price * $validatedData['quantity'][$index]) - ($validatedData['discount_in_rs'][$index] ?? 0);
                $costAmount = $validatedData['quantity'][$index] * $validatedData['purchase_price'][$index];

                $MurghiInvoice = MurghiInvoice::create([
                    'date' => $date,
                    'account_id' => $validatedData['account'],
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => $request->type,
                    'stock_type' => in_array($request->type, ['Purchase', 'Adjust In']) ? 'In' : 'Out',
                    'item_id' => $itemId,
                    'purchase_price' => $validatedData['purchase_price'][$index],
                    'sale_price' => $validatedData['sale_price'][$index],
                    'weight' => $validatedData['weight'][$index],
                    'weight_detection' => $validatedData['weight_detection'][$index],
                    'quantity' => in_array($request->type, ['Sale', 'Adjust Out']) ? -$validatedData['quantity'][$index] : $validatedData['quantity'][$index],
                    'amount' => $validatedData['amount'][$index],
                    'discount_in_rs' => $validatedData['discount_in_rs'][$index] ?? 0,
                    'discount_in_percent' => $validatedData['discount_in_percent'][$index] ?? 0,
                    'total_cost' => in_array($request->type, ['Sale', 'Adjust Out']) ? -$costAmount : $netAmount,
                    'net_amount' => $netAmount,
                    'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status' => $validatedData['whatsapp_status'] ?? 'Not Sent',
                ]);
                $item = Item::find($itemId);

                AccountLedger::create([
                    'murghi_invoice_id' => $MurghiInvoice->id,
                    'type'  => $request->type,
                    'date' => $date,
                    'account_id' => $validatedData['account'],
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Item: ' . $item->name . ', Qty: ' . $validatedData['quantity'][$index] . ', Rate: ' . $price,
                    'debit' => in_array($request->type, ['Sale', 'Adjust Out']) ? $netAmount : 0,
                    'credit' => in_array($request->type, ['Purchase', 'Adjust In']) ? $netAmount : 0,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storePurchaseSale(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_no' => 'required',
            'date' => 'required|date',
            'account' => 'required|exists:accounts,id',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric',
            'weight_detection' => 'required|numeric',
            'final_weight' => 'required|numeric',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'sale_account.*' => 'required|exists:accounts,id',
            'sale_weight.*' => 'required|numeric',
            'sale_weight_detection.*' => 'required|numeric',
            'sale_final_weight.*' => 'required|numeric',
            'sale_rate.*' => 'required|numeric',
            'sale_amount.*' => 'required|numeric',
        ]);

        $date = $request->input('date');

        DB::beginTransaction();
        try {
            // Handle Purchase
            $invoiceNumber = $request->invoice_no;
            $purchaseNetAmount = $validatedData['amount'];

            $MurghiInvoice = MurghiInvoice::create([
                'date' => $date,
                'account_id' => $validatedData['account'],
                'ref_no' => $validatedData['ref_no'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => 'Purchase',
                'stock_type' => 'In',
                'item_id' => 101,
                'weight' => $validatedData['weight'],
                'weight_detection' => $validatedData['weight_detection'],
                'quantity' => $validatedData['final_weight'],
                'purchase_price' => $validatedData['rate'],
                'amount' => $validatedData['amount'],
                'net_amount' => $purchaseNetAmount,
            ]);

            AccountLedger::create([
                'murghi_invoice_id' => $MurghiInvoice->id,
                'type' => 'Purchase',
                'date' => $date,
                'account_id' => $validatedData['account'],
                'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Weight: ' . $validatedData['final_weight'] . ', Rate: ' . $validatedData['rate'],
                'debit' => 0,
                'credit' => $purchaseNetAmount,
            ]);

            // Handle Sale
            $saleAccounts = $validatedData['sale_account'];
            foreach ($saleAccounts as $index => $saleAccountId) {
                $saleWeight = $validatedData['sale_weight'][$index];
                $saleWeightDetection = $validatedData['sale_weight_detection'][$index];
                $saleFinalWeight = $validatedData['sale_final_weight'][$index];
                $saleRate = $validatedData['sale_rate'][$index];
                $saleAmount = $validatedData['sale_amount'][$index];
                $saleNetAmount = $saleAmount; // Adjust this if there are any discounts or additional calculations

                $MurghiInvoiceSale = MurghiInvoice::create([
                    'date' => $date,
                    'account_id' => $saleAccountId,
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => 'Sale',
                    'stock_type' => 'Out',
                    'item_id' => 101,
                    'weight' => $saleWeight,
                    'weight_detection' => $saleWeightDetection,
                    'quantity' => -$saleFinalWeight,
                    'sale_price' => $saleRate,
                    'amount' => $saleAmount,
                    'net_amount' => $saleNetAmount,
                ]);

                AccountLedger::create([
                    'murghi_invoice_id' => $MurghiInvoiceSale->id,
                    'type' => 'Sale',
                    'date' => $date,
                    'account_id' => $saleAccountId,
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Weight: ' . $saleFinalWeight . ', Rate: ' . $saleRate,
                    'debit' => $saleNetAmount,
                    'credit' => 0,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateStockQuantities($validatedData)
    {
        $products = $this->MurghiInvoice->getStockInfo();

        $stockErrors = [];
        $stockQuantities = [];

        foreach ($validatedData['id'] as $index => $item_id) {
            $quantity = $validatedData['quantity'][$index];
            $stockQuantities[$item_id] = isset($stockQuantities[$item_id]) ? $stockQuantities[$item_id] + $quantity : $quantity;
        }

        foreach ($stockQuantities as $item_id => $summedQuantity) {
            $filteredProducts = $products->filter(function ($product) use ($item_id) {
                return $product->id == $item_id;
            });

            if ($filteredProducts->isEmpty()) {
                $stockErrors["item_id.$item_id"] = ['Product not found'];
            } else {
                $totalStockQuantity = $filteredProducts->sum('quantity');
                if ($totalStockQuantity < $summedQuantity) {
                    $itemName = $filteredProducts->first()->name;
                    $stockErrors["item_id.$item_id"] = ['Insufficient stock for item ' . $itemName];
                }
            }
        }

        return $stockErrors;
    }



    public function singleReturn(Request $request)
    {
        $validatedData = $request->validate([
            'Murghi_invoice_id' => 'required|exists:Murghi_invoices,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required',
        ]);
        $type = $validatedData['type'];

        $originalInvoice = $this->MurghiInvoice->findOrFail($validatedData['Murghi_invoice_id']);

        $stockInfo = $this->MurghiInvoice->getStockInfo();

        $stock = $stockInfo->first(function ($item) use ($originalInvoice) {
            return $item->item_id == $originalInvoice->item_id
                && $item->expiry_date == $originalInvoice->expiry_date;
        });

        if (!$stock) {
            return response()->json(['error' => 'Stock not found for the given item and expiry date'], 422);
        }

        if ($type == 'Purchase Return') {
            $price = $originalInvoice->purchase_price;
            if ($stock->quantity < $validatedData['quantity']) {
                return response()->json(['error' => 'Insufficient stock for the return. (' . $stock->quantity . ')'], 422);
            }
        } else {
            $price = $originalInvoice->sale_price;
        }


        DB::beginTransaction();
        try {
            $invoiceNumber = generateUniqueID(new MurghiInvoice, $type, 'invoice_no');
            $amount =  $price * $validatedData['quantity'];
            $netAmount = $amount - $originalInvoice->discount_in_rs;


            $MurghiInvoice = MurghiInvoice::create([
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'ref_no' => $validatedData['Murghi_invoice_id'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => $validatedData['type'],
                'stock_type' => ($type == 'Purchase Return') ? 'Out' : 'In',
                'item_id' => $originalInvoice->item_id,
                'purchase_price' => $originalInvoice->purchase_price,
                'sale_price' =>  $originalInvoice->sale_price,
                'weight' =>  $originalInvoice->weight,
                'weight_detection' =>  $originalInvoice->weight_detection,
                'quantity' => ($type == 'Purchase Return') ?  -$validatedData['quantity'] : $validatedData['quantity'],
                'amount' => $amount,
                'discount_in_rs' => $originalInvoice->discount_in_rs,
                'discount_in_percent' => $originalInvoice->discount_in_percent,
                'total_cost' => (($type == 'Purchase Return') ? -$netAmount : $amount),
                'net_amount' => $netAmount,
                'expiry_date' => $originalInvoice->expiry_date,
                'whatsapp_status' => 'Not Sent',
            ]);

            $debit = 0;
            $credit = 0;


            if ($type === 'Sale Return') {
                $credit = $netAmount;
            } else {
                $debit = $netAmount;
            }
            $items = Item::find($originalInvoice->item_id);
            AccountLedger::create([
                'murghi_invoice_id' => $MurghiInvoice->id,
                'type'  => $type,
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'description' => 'Return #: ' . $invoiceNumber . ', ' . 'Item: ' . $items->name . ', Qty: ' . $validatedData['quantity'] . ', Rate: ' . $price,
                'debit' => $debit,
                'credit' => $credit,
            ]);

            DB::commit();

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            info($e);
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

        $MurghiInvoice = MurghiInvoice::where('invoice_no', $invoice_no)
            ->where('type', $type)
            ->with('account', 'item')
            ->get();

        if ($MurghiInvoice->isEmpty()) {
            abort(404, 'Murghi Invoice not found');
        }

        $MurghiInvoiceIds = $MurghiInvoice->pluck('id');
        $returnType = $type . ' Return';

        $returnedQuantities = MurghiInvoice::whereIn('ref_no', $MurghiInvoiceIds)
            ->where('type', $returnType)
            ->groupBy('ref_no')
            ->select('ref_no', DB::raw('SUM(quantity) as total_returned'))
            ->pluck('total_returned', 'ref_no');

        $MurghiInvoice = $MurghiInvoice->map(function ($item) use ($returnedQuantities) {
            $item->total_returned = $returnedQuantities->get($item->id, 0);
            return $item;
        });

        if (request()->has('generate_pdf')) {
            $html = view('admin.murghi.invoice_pdf', compact('MurghiInvoice', 'type'))->render();
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
            return view('admin.murghi.show_murghi', compact('MurghiInvoice', 'type'));
        }
    }
}
