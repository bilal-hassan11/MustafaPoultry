<?php

namespace App\Http\Controllers;

use App\Models\FeedInvoice;
use App\Models\Account;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use App\Traits\GeneratePdfTrait;
use Illuminate\Http\Request;
use App\Traits\SendsWhatsAppMessages;
use Mpdf\Mpdf;

class FeedInvoiceController extends Controller
{

    use SendsWhatsAppMessages;
    use GeneratePdfTrait;
    protected $FeedInvoice;

    public function __construct(FeedInvoice $FeedInvoice)
    {
        $this->FeedInvoice = $FeedInvoice;
    }

    public function createPurchase(Request $req)
    {
        $title = "Purchase Feed";
        $invoice_no = generateUniqueID(new FeedInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 3)->get();

        $purchase_Feed = FeedInvoice::with('account', 'item')
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

        $pending_Feed = FeedInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.feed.purchase_feed', compact(['title', 'pending_Feed', 'invoice_no', 'accounts', 'products', 'purchase_Feed']));
    }

    public function editPurchase($invoice_no)
    {
        $title = "Edit Purchase Feed";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 3)->get();
        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase')
            ->with('account', 'item')
            ->get();



        return view('admin.feed.edit_purchase_feed', compact(['title', 'accounts', 'products', 'FeedInvoice']));
    }
    public function editSale($invoice_no)
    {
        $title = "Edit Sale Feed";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = $this->FeedInvoice->getStockInfo();
        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale')
            ->get();

        $pending_Feed = FeedInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.feed.edit_sale_feed', compact(['title', 'pending_Feed', 'accounts', 'products', 'FeedInvoice']));
    }

    public function createSale(Request $req)
    {

        $title = "Sale Feed";
        $invoice_no = generateUniqueID(new FeedInvoice, 'Sale', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $FeedInvoice = new FeedInvoice();

        $products = $FeedInvoice->getStockInfo();
        // dd($products);
        $sale_Feed = $FeedInvoice::with('account', 'item')
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

        $pending_Feed = $FeedInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.feed.sale_feed', compact(['title', 'pending_Feed', 'sale_Feed', 'invoice_no', 'accounts', 'products']));
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
            $FeedInvoices = FeedInvoice::where('invoice_no', $invoiceNumber)
                ->where('type', $request->type)
                ->get();
            $FeedInvoiceIds = $FeedInvoices->pluck('id');
            FeedInvoice::whereIn('id', $FeedInvoiceIds)->delete();
            AccountLedger::whereIn('feed_invoice_id', $FeedInvoiceIds)
                ->where('type', $request->type)
                ->delete();
        } else {
            $invoiceNumber = generateUniqueID(new FeedInvoice, $request->type, 'invoice_no');
        }

        try {

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {

                $price = in_array($request->type, ['Sale', 'Adjust Out']) ? $validatedData['sale_price'][$index] : $validatedData['purchase_price'][$index];
                $netAmount = ($price * $validatedData['quantity'][$index]) - ($validatedData['discount_in_rs'][$index] ?? 0);
                $costAmount = $validatedData['quantity'][$index] * $validatedData['purchase_price'][$index];

                $FeedInvoice = FeedInvoice::create([
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
                    'feed_invoice_id' => $FeedInvoice->id,
                    'type'  => $request->type,
                    'date' => $date,
                    'account_id' => $validatedData['account'],
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Item: ' . $item->name . ', Qty: ' . $validatedData['quantity'][$index] . ', Rate: ' . $price,
                    'debit' => in_array($request->type, ['Sale', 'Adjust Out']) ? $netAmount : 0,
                    'credit' => in_array($request->type, ['Purchase', 'Adjust In']) ? $netAmount : 0,
                ]);

                if ($request->type == 'Sale') {
                    $medicineInvoice = FeedInvoice::where('invoice_no', $FeedInvoice->invoice_no)
                        ->where('type', $request->type)
                        ->with('account', 'item')
                        ->get();
                    $previous_balance = $FeedInvoice[0]->account->getBalance($FeedInvoice[0]->date);
                    $htmlContent = view('admin.feed.invoice_pdf', compact('FeedInvoice', 'previous_balance'))->render();
                    $pdfPath = $this->generatePdf($htmlContent, 'FeedSale-' . $FeedInvoice[0]->invoice_no);
                    $result = $this->sendWhatsAppMessage($FeedInvoice[0]->account->phone_no, 'Sale Invoice', $pdfPath);
                }
            }

            DB::commit();

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateStockQuantities($validatedData)
    {
        $products = $this->FeedInvoice->getStockInfo();

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
            'Feed_invoice_id' => 'required|exists:Feed_invoices,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required',
        ]);
        $type = $validatedData['type'];

        $originalInvoice = $this->FeedInvoice->findOrFail($validatedData['Feed_invoice_id']);

        $stockInfo = $this->FeedInvoice->getStockInfo();

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
            $invoiceNumber = generateUniqueID(new FeedInvoice, $type, 'invoice_no');
            $amount =  $price * $validatedData['quantity'];
            $netAmount = $amount - $originalInvoice->discount_in_rs;


            $FeedInvoice = FeedInvoice::create([
                'date' => now(),
                'account_id' => $originalInvoice->account_id,
                'ref_no' => $validatedData['Feed_invoice_id'],
                'description' => $validatedData['description'],
                'invoice_no' => $invoiceNumber,
                'type' => $validatedData['type'],
                'stock_type' => ($type == 'Purchase Return') ? 'Out' : 'In',
                'item_id' => $originalInvoice->item_id,
                'purchase_price' => $originalInvoice->purchase_price,
                'sale_price' =>  $originalInvoice->sale_price,
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
                'feed_invoice_id' => $FeedInvoice->id,
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

        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', $type)
            ->with('account', 'item')
            ->get();

        if ($FeedInvoice->isEmpty()) {
            abort(404, 'Feed Invoice not found');
        }

        $FeedInvoiceIds = $FeedInvoice->pluck('id');
        $returnType = $type . ' Return';

        $previous_balance = $FeedInvoice[0]->account->getBalance($FeedInvoice[0]->date);

        $returnedQuantities = FeedInvoice::whereIn('ref_no', $FeedInvoiceIds)
            ->where('type', $returnType)
            ->groupBy('ref_no')
            ->select('ref_no', DB::raw('SUM(quantity) as total_returned'))
            ->pluck('total_returned', 'ref_no');

        $FeedInvoice = $FeedInvoice->map(function ($item) use ($returnedQuantities) {
            $item->total_returned = $returnedQuantities->get($item->id, 0);
            return $item;
        });

        if (request()->has('generate_pdf')) {
            $html = view('admin.feed.invoice_pdf', compact('FeedInvoice', 'type', 'previous_balance'))->render();
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
            return view('admin.feed.show_feed', compact('FeedInvoice', 'type'));
        }
    }
}
