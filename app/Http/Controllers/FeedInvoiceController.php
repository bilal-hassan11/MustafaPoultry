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
        $this->middleware(function ($request, $next) {
            if (!auth('admin')->check()) {
                return redirect()->route('login');
            }
            if (!auth('admin')->user()->hasPermissionTo('Feed Invoices Access')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Redirect the bare /feed-invoices/ URL to the purchase listing.
     */
    public function index()
    {
        return redirect()->route('admin.feed-invoices.purchase');
    }

    /**
     * Redirect the bare /feed-invoices/ URL to the purchase listing.
     */
    public function index()
    {
        return redirect()->route('admin.feed-invoices.purchase');
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
            'commission_percent.*' => 'nullable|numeric',
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
                $amountAfterDiscount = ($price * $validatedData['quantity'][$index]) - ($validatedData['discount_in_rs'][$index] ?? 0);
                $commissionPercent = $validatedData['commission_percent'][$index] ?? 0;
                $commissionAmount = round($amountAfterDiscount * $commissionPercent / 100, 2);
                $netAmount = $amountAfterDiscount + $commissionAmount;
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
                    'commission_percent' => $commissionPercent,
                    'commission_amount' => $commissionAmount,
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


    // =========================================================================
    // PURCHASE FEED RETURN — CRUD
    // =========================================================================

    /**
     * List all Purchase Feed Return invoices with filters.
     */
    public function purchaseReturnIndex(Request $req)
    {
        $title = "Purchase Feed Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 3)->get();

        $returns = FeedInvoice::with('account', 'item')
            ->where('type', 'Purchase Return')
            ->when($req->filled('account_id'), function ($q) use ($req) {
                $q->where('account_id', hashids_decode($req->account_id));
            })
            ->when($req->filled('invoice_no'), function ($q) use ($req) {
                $q->where('invoice_no', $req->invoice_no);
            })
            ->when($req->filled('item_id'), function ($q) use ($req) {
                $q->where('item_id', hashids_decode($req->item_id));
            })
            ->when($req->filled('from_date') && $req->filled('to_date'), function ($q) use ($req) {
                $q->whereBetween('date', [$req->from_date, $req->to_date]);
            })
            ->latest()
            ->get();

        return view('admin.feed.purchase_feed_return', compact('title', 'accounts', 'products', 'returns'));
    }

    /**
     * Show the create form for Purchase Feed Return.
     */
    public function purchaseReturnCreate(Request $req)
    {
        $title = "Create Purchase Feed Return";
        $invoice_no = generateUniqueID(new FeedInvoice, 'Purchase Return', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        // Only items that actually have stock can be returned
        $stockInfo = $this->FeedInvoice->getStockInfo();
        $products  = $stockInfo->filter(fn($p) => $p->category_id == 3 && $p->quantity > 0)->values();

        return view('admin.feed.create_purchase_feed_return', compact('title', 'invoice_no', 'accounts', 'products'));
    }

    /**
     * Edit an existing Purchase Feed Return invoice.
     */
    public function purchaseReturnEdit($invoice_no)
    {
        $title = "Edit Purchase Feed Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        // Use a fresh instance so we don't mutate the shared $this->FeedInvoice
        $stockInfo = (new FeedInvoice)->ignore($invoice_no)->getStockInfo();
        $products  = $stockInfo->filter(fn($p) => $p->category_id == 3)->values();

        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')
            ->with('account', 'item')
            ->get();

        if ($FeedInvoice->isEmpty()) {
            abort(404, 'Purchase Feed Return invoice not found');
        }

        return view('admin.feed.edit_purchase_feed_return', compact('title', 'accounts', 'products', 'FeedInvoice'));
    }

    /**
     * Display a single Purchase Feed Return invoice.
     */
    public function purchaseReturnShow($invoice_no)
    {
        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')
            ->with('account', 'item')
            ->get();

        if ($FeedInvoice->isEmpty()) {
            abort(404, 'Purchase Feed Return invoice not found');
        }

        $type             = 'Purchase Return';
        $previous_balance = $FeedInvoice[0]->account->getBalance($FeedInvoice[0]->date);

        if (request()->has('generate_pdf')) {
            $html = view('admin.feed.invoice_pdf', compact('FeedInvoice', 'type', 'previous_balance'))->render();
            $mpdf = new Mpdf(['format' => 'A4-P', 'margin_top' => 10, 'margin_bottom' => 2,
                              'margin_left' => 2, 'margin_right' => 2]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align:right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        }

        return view('admin.feed.show_feed_return', compact('FeedInvoice', 'type', 'previous_balance'));
    }

    /**
     * Store (create or update) a Purchase Feed Return invoice.
     */
    public function purchaseReturnStore(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_no'            => 'required',
            'date'                  => 'required|date',
            'account'               => 'required|exists:accounts,id',
            'ref_no'                => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'item_id.*'             => 'required|exists:items,id',
            'purchase_price.*'      => 'required|numeric|min:0',
            'sale_price.*'          => 'required|numeric|min:0',
            'quantity.*'            => 'required|numeric|min:0.01',
            'amount.*'              => 'required|numeric|min:0',
            'discount_in_rs.*'      => 'nullable|numeric|min:0',
            'discount_in_percent.*' => 'nullable|numeric|min:0|max:100',
            'expiry_date.*'         => 'nullable|date',
        ]);

        // For Purchase Return, quantity reduces stock — validate availability
        $ignoredInvoiceNo = $request->has('editMode') ? $request->invoice_no : null;
        $stockErrors = $this->validatePurchaseReturnStock($validatedData, $ignoredInvoiceNo);
        if (!empty($stockErrors)) {
            return response()->json(['errors' => $stockErrors], 422);
        }

        DB::beginTransaction();
        try {
            if ($request->has('editMode')) {
                $invoiceNumber   = $request->invoice_no;
                $existingInvoices = FeedInvoice::where('invoice_no', $invoiceNumber)
                    ->where('type', 'Purchase Return')
                    ->get();
                $existingIds = $existingInvoices->pluck('id');
                FeedInvoice::whereIn('id', $existingIds)->delete();
                AccountLedger::whereIn('feed_invoice_id', $existingIds)
                    ->where('type', 'Purchase Return')
                    ->delete();
            } else {
                $invoiceNumber = generateUniqueID(new FeedInvoice, 'Purchase Return', 'invoice_no');
            }

            foreach ($validatedData['item_id'] as $index => $itemId) {
                $qty      = $validatedData['quantity'][$index];
                $price    = $validatedData['purchase_price'][$index];
                $discount = $validatedData['discount_in_rs'][$index] ?? 0;
                $amount   = $price * $qty;
                $netAmount = $amount - $discount;

                $feedInvoice = FeedInvoice::create([
                    'date'               => $validatedData['date'],
                    'account_id'         => $validatedData['account'],
                    'ref_no'             => $validatedData['ref_no'] ?? null,
                    'description'        => $validatedData['description'] ?? null,
                    'invoice_no'         => $invoiceNumber,
                    'type'               => 'Purchase Return',
                    'stock_type'         => 'Out',          // Purchase Return removes stock
                    'item_id'            => $itemId,
                    'purchase_price'     => $price,
                    'sale_price'         => $validatedData['sale_price'][$index],
                    'quantity'           => -$qty,           // negative = stock out
                    'amount'             => $amount,
                    'discount_in_rs'     => $discount,
                    'discount_in_percent'=> $validatedData['discount_in_percent'][$index] ?? 0,
                    'total_cost'         => -$netAmount,     // negative reduces inventory value
                    'net_amount'         => $netAmount,
                    'expiry_date'        => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status'    => 'Not Sent',
                ]);

                $item = Item::find($itemId);
                AccountLedger::create([
                    'feed_invoice_id' => $feedInvoice->id,
                    'type'            => 'Purchase Return',
                    'date'            => $validatedData['date'],
                    'account_id'      => $validatedData['account'],
                    'description'     => 'Purchase Return #: ' . $invoiceNumber . ', Item: ' . $item->name . ', Qty: ' . $qty . ', Rate: ' . $price,
                    'debit'           => $netAmount, // reduces supplier payable
                    'credit'          => 0,
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

    /**
     * Soft-delete all rows belonging to a Purchase Feed Return invoice.
     */
    public function purchaseReturnDelete($invoice_no)
    {
        $invoices = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')
            ->get();

        if ($invoices->isEmpty()) {
            abort(404, 'Purchase Feed Return invoice not found');
        }

        DB::beginTransaction();
        try {
            $ids = $invoices->pluck('id');
            FeedInvoice::whereIn('id', $ids)->delete();
            AccountLedger::whereIn('feed_invoice_id', $ids)
                ->where('type', 'Purchase Return')
                ->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.feed-invoices.purchase_return.index')
            ->with('success', 'Purchase Feed Return deleted successfully.');
    }

    /**
     * Validate that sufficient stock exists for a Purchase Feed Return.
     * Purchase Return reduces stock (stock_type Out), so we check available qty.
     */
    private function validatePurchaseReturnStock(array $validatedData, ?string $ignoredInvoiceNo = null): array
    {
        $feedModel = $ignoredInvoiceNo
            ? $this->FeedInvoice->ignore($ignoredInvoiceNo)
            : $this->FeedInvoice;

        $stockInfo = $feedModel->getStockInfo();
        $errors    = [];

        // Accumulate per item_id across rows
        $requested = [];
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $requested[$itemId] = ($requested[$itemId] ?? 0) + $validatedData['quantity'][$index];
        }

        foreach ($requested as $itemId => $requestedQty) {
            $stock = $stockInfo->filter(fn($p) => $p->item_id == $itemId)->sum('quantity');
            if ($stock < $requestedQty) {
                $item = Item::find($itemId);
                $name = $item ? $item->name : "Item #{$itemId}";
                $errors["item_id.{$itemId}"] = [
                    "Insufficient stock for \"{$name}\". Available: {$stock}, Requested: {$requestedQty}."
                ];
            }
        }

        return $errors;
    }

    // =========================================================================
    // SALE FEED RETURN — CRUD
    // =========================================================================

    /**
     * List all Sale Feed Return invoices with filters.
     */
    public function saleReturnIndex(Request $req)
    {
        $title    = "Sale Feed Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 3)->get();

        $returns = FeedInvoice::with('account', 'item')
            ->where('type', 'Sale Return')
            ->when($req->filled('account_id'), function ($q) use ($req) {
                $q->where('account_id', hashids_decode($req->account_id));
            })
            ->when($req->filled('invoice_no'), function ($q) use ($req) {
                $q->where('invoice_no', $req->invoice_no);
            })
            ->when($req->filled('item_id'), function ($q) use ($req) {
                $q->where('item_id', hashids_decode($req->item_id));
            })
            ->when($req->filled('from_date') && $req->filled('to_date'), function ($q) use ($req) {
                $q->whereBetween('date', [$req->from_date, $req->to_date]);
            })
            ->latest()
            ->get();

        return view('admin.feed.sale_feed_return', compact('title', 'accounts', 'products', 'returns'));
    }

    /**
     * Show the create form for Sale Feed Return.
     */
    public function saleReturnCreate()
    {
        $title      = "Create Sale Feed Return";
        $invoice_no = generateUniqueID(new FeedInvoice, 'Sale Return', 'invoice_no');
        $accounts   = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        // Sale Return adds stock back — show all Feed items (no stock check needed)
        $products = Item::where('category_id', 3)->get();

        return view('admin.feed.create_sale_feed_return', compact('title', 'invoice_no', 'accounts', 'products'));
    }

    /**
     * Edit an existing Sale Feed Return invoice.
     */
    public function saleReturnEdit($invoice_no)
    {
        $title    = "Edit Sale Feed Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 3)->get();

        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')
            ->with('account', 'item')
            ->get();

        if ($FeedInvoice->isEmpty()) {
            abort(404, 'Sale Feed Return invoice not found');
        }

        return view('admin.feed.edit_sale_feed_return', compact('title', 'accounts', 'products', 'FeedInvoice'));
    }

    /**
     * Display a single Sale Feed Return invoice.
     */
    public function saleReturnShow($invoice_no)
    {
        $FeedInvoice = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')
            ->with('account', 'item')
            ->get();

        if ($FeedInvoice->isEmpty()) {
            abort(404, 'Sale Feed Return invoice not found');
        }

        $type             = 'Sale Return';
        $previous_balance = $FeedInvoice[0]->account->getBalance($FeedInvoice[0]->date);

        if (request()->has('generate_pdf')) {
            $html = view('admin.feed.invoice_pdf', compact('FeedInvoice', 'type', 'previous_balance'))->render();
            $mpdf = new Mpdf(['format' => 'A4-P', 'margin_top' => 10, 'margin_bottom' => 2,
                              'margin_left' => 2, 'margin_right' => 2]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align:right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        }

        return view('admin.feed.show_feed_return', compact('FeedInvoice', 'type', 'previous_balance'));
    }

    /**
     * Store (create or update) a Sale Feed Return invoice.
     */
    public function saleReturnStore(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_no'            => 'required',
            'date'                  => 'required|date',
            'account'               => 'required|exists:accounts,id',
            'ref_no'                => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'item_id.*'             => 'required|exists:items,id',
            'purchase_price.*'      => 'required|numeric|min:0',
            'sale_price.*'          => 'required|numeric|min:0',
            'quantity.*'            => 'required|numeric|min:0.01',
            'amount.*'              => 'required|numeric|min:0',
            'discount_in_rs.*'      => 'nullable|numeric|min:0',
            'discount_in_percent.*' => 'nullable|numeric|min:0|max:100',
            'expiry_date.*'         => 'nullable|date',
        ]);

        // Sale Return: no stock validation — returned goods add back to stock

        DB::beginTransaction();
        try {
            if ($request->has('editMode')) {
                $invoiceNumber    = $request->invoice_no;
                $existingInvoices = FeedInvoice::where('invoice_no', $invoiceNumber)
                    ->where('type', 'Sale Return')
                    ->get();
                $existingIds = $existingInvoices->pluck('id');
                FeedInvoice::whereIn('id', $existingIds)->delete();
                AccountLedger::whereIn('feed_invoice_id', $existingIds)
                    ->where('type', 'Sale Return')
                    ->delete();
            } else {
                $invoiceNumber = generateUniqueID(new FeedInvoice, 'Sale Return', 'invoice_no');
            }

            foreach ($validatedData['item_id'] as $index => $itemId) {
                $qty       = $validatedData['quantity'][$index];
                $price     = $validatedData['sale_price'][$index];
                $discount  = $validatedData['discount_in_rs'][$index] ?? 0;
                $amount    = $price * $qty;
                $netAmount = $amount - $discount;
                $costPrice = $validatedData['purchase_price'][$index];

                $feedInvoice = FeedInvoice::create([
                    'date'               => $validatedData['date'],
                    'account_id'         => $validatedData['account'],
                    'ref_no'             => $validatedData['ref_no'] ?? null,
                    'description'        => $validatedData['description'] ?? null,
                    'invoice_no'         => $invoiceNumber,
                    'type'               => 'Sale Return',
                    'stock_type'         => 'In',           // Sale Return adds back to stock
                    'item_id'            => $itemId,
                    'purchase_price'     => $costPrice,
                    'sale_price'         => $price,
                    'quantity'           => $qty,            // positive = stock in
                    'amount'             => $amount,
                    'discount_in_rs'     => $discount,
                    'discount_in_percent'=> $validatedData['discount_in_percent'][$index] ?? 0,
                    'total_cost'         => $qty * $costPrice, // positive adds back inventory value
                    'net_amount'         => $netAmount,
                    'expiry_date'        => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status'    => 'Not Sent',
                ]);

                $item = Item::find($itemId);
                AccountLedger::create([
                    'feed_invoice_id' => $feedInvoice->id,
                    'type'            => 'Sale Return',
                    'date'            => $validatedData['date'],
                    'account_id'      => $validatedData['account'],
                    'description'     => 'Sale Return #: ' . $invoiceNumber . ', Item: ' . $item->name . ', Qty: ' . $qty . ', Rate: ' . $price,
                    'debit'           => 0,
                    'credit'          => $netAmount, // reduces customer receivable
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

    /**
     * Soft-delete all rows belonging to a Sale Feed Return invoice.
     */
    public function saleReturnDelete($invoice_no)
    {
        $invoices = FeedInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')
            ->get();

        if ($invoices->isEmpty()) {
            abort(404, 'Sale Feed Return invoice not found');
        }

        DB::beginTransaction();
        try {
            $ids = $invoices->pluck('id');
            FeedInvoice::whereIn('id', $ids)->delete();
            AccountLedger::whereIn('feed_invoice_id', $ids)
                ->where('type', 'Sale Return')
                ->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.feed-invoices.sale_return.index')
            ->with('success', 'Sale Feed Return deleted successfully.');
    }

    // =========================================================================
    // EXISTING SHOW METHOD
    // =========================================================================

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
