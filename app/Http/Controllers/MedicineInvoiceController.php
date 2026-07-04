<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Models\Account;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\AccountLedger;
use Illuminate\Http\Request;
use App\Traits\SendsWhatsAppMessages;
use Mpdf\Mpdf;
use App\Traits\GeneratePdfTrait;

class MedicineInvoiceController extends Controller
{

    use SendsWhatsAppMessages;
    use GeneratePdfTrait;
    protected $medicineInvoice;

    public function __construct(MedicineInvoice $medicineInvoice)
    {
        $this->medicineInvoice = $medicineInvoice;
        $this->middleware(function ($request, $next) {
            if (!auth('admin')->check()) {
                return redirect()->route('login');
            }
            if (!auth('admin')->user()->hasPermissionTo('Medicine Invoices Access')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function createPurchase(Request $req)
    {
        $title = "Purchase Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Purchase', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $products = Item::where('category_id', 4)->get();


        $warehouseId = $req->warehouse_id;
        $supplierId = $req->supplier_id;
        $referenceNo = $req->reference_no;
        $fromDate = $req->from_date;
        $toDate = $req->to_date;

        $purchase_medicine = MedicineInvoice::with('account', 'item')
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

        $pending_medicine = MedicineInvoice::with('account', 'item')
            ->where('type', 'Purchase')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.medicine.purchase_medicine', compact(['title', 'pending_medicine', 'invoice_no', 'accounts', 'products', 'purchase_medicine']));
    }
    public function createAdjustmentIn(Request $req)
    {
        $title = "Adjust In";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Adjust Stock', 'invoice_no');

        $products = Item::where('category_id', 4)->get();

        $purchase_medicine = MedicineInvoice::with('account', 'item')
            ->where('type', 'Adjust Stock')
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

        return view('admin.medicine.adjust_stock_in', compact(['title',  'invoice_no', 'products', 'purchase_medicine']));
    }

    public function createAdjustmentOut(Request $req)
    {
        $title = "Adjust In";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Adjust Stock', 'invoice_no');
        $medicineInvoice = new MedicineInvoice();

        $stock = $this->medicineInvoice->getStockInfo();

        $products = $stock->filter(function ($product) {
            return $product->category_id == 4;
        });

        $purchase_medicine = MedicineInvoice::with('account', 'item')
            ->where('type', 'Adjust Stock')
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

        return view('admin.medicine.adjust_stock_out', compact(['title',  'invoice_no', 'products', 'purchase_medicine']));
    }

    public function editPurchase($invoice_no)
    {
        $title = "Edit Purchase Medicine";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 4)->get();
        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase')
            ->with('account', 'item')
            ->get();

        return view('admin.medicine.edit_purhcase_medicine', compact(['title', 'accounts', 'products', 'medicineInvoice']));
    }
    public function editSale($invoice_no)
    {
        $title = "Edit Sale Medicine";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $stock = $this->medicineInvoice->getStockInfo();

        $products = $stock->filter(function ($product) {
            return $product->category_id == 4;
        });
        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale')
            ->get();

        return view('admin.medicine.edit_sale_medicine', compact(['title', 'accounts', 'products', 'medicineInvoice']));
    }

    public function createSale(Request $req)
    {

        $title = "Sale Medicine";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Sale', 'invoice_no');
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        $medicineInvoice = new MedicineInvoice();

        $stock = $this->medicineInvoice->getStockInfo();

        $products = $stock->filter(function ($product) {
            return $product->category_id == 4 && $product->quantity > 0;
        });

        $sale_medicine = $medicineInvoice::with('account', 'item')
            ->where('type', 'Sale')
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

        $pending_medicine = $medicineInvoice::with('account', 'item')
            ->where('type', 'Sale')
            ->where('net_amount', 0)
            ->latest()
            ->get();

        return view('admin.medicine.sale_medicine', compact(['title', 'pending_medicine', 'sale_medicine', 'invoice_no', 'accounts', 'products']));
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
            'transport_name' => 'nullable|string|max:255',
            'vehicle_no' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:255',
            'builty_no' => 'nullable|string|max:255',
        ]);

        $date = $request->input('date');

        if ($request->type == 'Sale' || $request->stockType == 'Out') {
            $stockErrors = $this->validateStockQuantities($validatedData, true);

            if (!empty($stockErrors)) {
                return response()->json(['errors' => $stockErrors], 422);
            }
        }

        DB::beginTransaction();
        if ($request->has('editMode')) {
            $invoiceNumber = $request->invoice_no;
            $medicineInvoices = MedicineInvoice::where('invoice_no', $invoiceNumber)
                ->where('type', $request->type)
                ->get();
            $medicineInvoiceIds = $medicineInvoices->pluck('id');
            MedicineInvoice::whereIn('id', $medicineInvoiceIds)->delete();
            AccountLedger::whereIn('medicine_invoice_id', $medicineInvoiceIds)
                ->where('type', $request->type)
                ->delete();
        } else {
            $invoiceNumber = generateUniqueID(new MedicineInvoice, $request->type, 'invoice_no');
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

                $medicineInvoice = MedicineInvoice::create([
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
                    'transport_name' => $validatedData['transport_name'] ?? null,
                    'vehicle_no' => $validatedData['vehicle_no'] ?? null,
                    'driver_name' => $validatedData['driver_name'] ?? null,
                    'contact_no' => $validatedData['contact_no'] ?? null,
                    'builty_no' => $validatedData['builty_no'] ?? null,
                ]);
                $item = Item::find($itemId);

                AccountLedger::create([
                    'medicine_invoice_id' => $medicineInvoice->id,
                    'type'  => $request->type,
                    'date' => $date,
                    'account_id' => $validatedData['account'],
                    'description' => 'Invoice #: ' . $invoiceNumber . ', ' . 'Item: ' . $item->name . ', Qty: ' . $validatedData['quantity'][$index] . ', Rate: ' . $price,
                    'debit' => in_array($request->type, ['Sale', 'Adjust Out']) ? $netAmount : 0,
                    'credit' => in_array($request->type, ['Purchase', 'Adjust In']) ? $netAmount : 0,
                ]);
            }
            if ($request->type == 'Sale') {
                $medicineInvoice = MedicineInvoice::where('invoice_no', $medicineInvoice->invoice_no)
                    ->where('type', $request->type)
                    ->with('account', 'item')
                    ->get();
                $previous_balance = $medicineInvoice[0]->account->getBalance($medicineInvoice[0]->date);
                $htmlContent = view('admin.medicine.invoice_pdf', compact('medicineInvoice', 'previous_balance'))->render();
                $pdfPath = $this->generatePdf($htmlContent, 'MedicineSale-' . $medicineInvoice[0]->invoice_no);
                $result = $this->sendWhatsAppMessage($medicineInvoice[0]->account->phone_no, 'Sale Invoice', $pdfPath);
            }
            DB::commit();
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeAdjsutment(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_no' =>  'required',
            'date' => 'required|date',
            'stock_type' => 'required',
            'ref_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'item_id.*' => 'required|exists:items,id',
            'id.*' => 'nullable',
            'purchase_price.*' => 'required|numeric',
            'sale_price.*' => 'required|numeric',
            'quantity.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
            'expiry_date.*' => 'nullable|date',
            'whatsapp_status' => 'nullable|boolean',
        ]);

        $date = $request->input('date');
        if ($request->stock_type == 'Out') {
            $stockErrors = $this->validateStockQuantities($validatedData);

            if (!empty($stockErrors)) {
                return response()->json(['errors' => $stockErrors], 422);
            }
        }

        DB::beginTransaction();

        $invoiceNumber = generateUniqueID(new MedicineInvoice, $request->type, 'invoice_no');
        try {

            $items = $validatedData['item_id'];
            foreach ($items as $index => $itemId) {

                $price = $validatedData['purchase_price'][$index];
                $netAmount = $price * $validatedData['quantity'][$index];

                $medicineInvoice = MedicineInvoice::create([
                    'date' => $date,
                    'ref_no' => $validatedData['ref_no'],
                    'description' => $validatedData['description'],
                    'invoice_no' => $invoiceNumber,
                    'type' => $request->type,
                    'stock_type' => $request->stock_type,
                    'item_id' => $itemId,
                    'purchase_price' => $validatedData['purchase_price'][$index],
                    'sale_price' => $validatedData['sale_price'][$index],
                    'quantity' => $request->stock_type == 'Out' ? -$validatedData['quantity'][$index] : $validatedData['quantity'][$index],
                    'amount' => $validatedData['amount'][$index],
                    'discount_in_rs' => 0,
                    'discount_in_percent' => 0,
                    'total_cost' =>  $request->stock_type == 'Out'  ? -$netAmount : $netAmount,
                    'net_amount' => $netAmount,
                    'expiry_date' => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status' => $validatedData['whatsapp_status'] ?? 'Not Sent',
                ]);
            }

            DB::commit();
            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateStockQuantities($validatedData, $editMode = false)
    {
        $products = $editMode == true ? $this->medicineInvoice->ignore($validatedData['invoice_no'])->getStockInfo() :
            $this->medicineInvoice->getStockInfo();

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
            'medicine_invoice_id' => 'required|exists:medicine_invoices,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required',
        ]);
        $type = $validatedData['type'];

        $originalInvoice = $this->medicineInvoice->findOrFail($validatedData['medicine_invoice_id']);

        $stockInfo = $this->medicineInvoice->getStockInfo();

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
                'medicine_invoice_id' => $medicineInvoice->id,
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
    // PURCHASE MEDICINE RETURN — CRUD
    // =========================================================================

    /**
     * List all Purchase Medicine Return invoices with filters.
     */
    public function purchaseReturnIndex(Request $req)
    {
        $title    = "Purchase Medicine Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 4)->get();

        $returns = MedicineInvoice::with('account', 'item')
            ->where('type', 'Purchase Return')
            ->when($req->filled('account_id'), fn($q) => $q->where('account_id', hashids_decode($req->account_id)))
            ->when($req->filled('invoice_no'),  fn($q) => $q->where('invoice_no', $req->invoice_no))
            ->when($req->filled('item_id'),     fn($q) => $q->where('item_id', hashids_decode($req->item_id)))
            ->when($req->filled('from_date') && $req->filled('to_date'),
                   fn($q) => $q->whereBetween('date', [$req->from_date, $req->to_date]))
            ->latest()
            ->get();

        return view('admin.medicine.purchase_medicine_return',
                    compact('title', 'accounts', 'products', 'returns'));
    }

    /**
     * Show the create form for Purchase Medicine Return.
     */
    public function purchaseReturnCreate()
    {
        $title      = "Create Purchase Medicine Return";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Purchase Return', 'invoice_no');
        $accounts   = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        // Only show items with positive stock — Purchase Return removes stock
        $stockInfo = $this->medicineInvoice->getStockInfo();
        $products  = $stockInfo->filter(fn($p) => $p->category_id == 4 && $p->quantity > 0)->values();

        return view('admin.medicine.create_purchase_medicine_return',
                    compact('title', 'invoice_no', 'accounts', 'products'));
    }

    /**
     * Edit an existing Purchase Medicine Return invoice.
     */
    public function purchaseReturnEdit($invoice_no)
    {
        $title    = "Edit Purchase Medicine Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();

        // Fresh instance so we don't mutate the shared $this->medicineInvoice
        $stockInfo = (new MedicineInvoice)->ignore($invoice_no)->getStockInfo();
        $products  = $stockInfo->filter(fn($p) => $p->category_id == 4)->values();

        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Purchase Medicine Return invoice not found');
        }

        return view('admin.medicine.edit_purchase_medicine_return',
                    compact('title', 'accounts', 'products', 'medicineInvoice'));
    }

    /**
     * Show detail page for a Purchase Medicine Return invoice.
     */
    public function purchaseReturnShow($invoice_no)
    {
        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Purchase Medicine Return invoice not found');
        }

        $type             = 'Purchase Return';
        $previous_balance = $medicineInvoice[0]->account->getBalance($medicineInvoice[0]->date);

        if (request()->has('generate_pdf')) {
            $html = view('admin.medicine.invoice_pdf',
                         compact('medicineInvoice', 'type', 'previous_balance'))->render();
            $mpdf = new Mpdf(['format' => 'A4-P', 'margin_top' => 10,
                              'margin_bottom' => 2, 'margin_left' => 2, 'margin_right' => 2]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align:right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        }

        return view('admin.medicine.show_medicine_return',
                    compact('medicineInvoice', 'type', 'previous_balance'));
    }

    /**
     * Store (create or update) a Purchase Medicine Return invoice.
     * Purchase Return → stock_type Out, quantity negative, total_cost negative.
     * Ledger: debit = netAmount (reduces supplier payable).
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
            'transport_name'        => 'nullable|string|max:255',
            'vehicle_no'            => 'nullable|string|max:255',
            'driver_name'           => 'nullable|string|max:255',
            'contact_no'            => 'nullable|string|max:255',
            'builty_no'             => 'nullable|string|max:255',
        ]);

        // Stock validation — Purchase Return reduces stock
        $ignoredInvoiceNo = $request->has('editMode') ? $request->invoice_no : null;
        $stockErrors = $this->validatePurchaseReturnStock($validatedData, $ignoredInvoiceNo);
        if (!empty($stockErrors)) {
            return response()->json(['errors' => $stockErrors], 422);
        }

        DB::beginTransaction();
        try {
            if ($request->has('editMode')) {
                $invoiceNumber    = $request->invoice_no;
                $existing         = MedicineInvoice::where('invoice_no', $invoiceNumber)
                    ->where('type', 'Purchase Return')->get();
                $existingIds      = $existing->pluck('id');
                MedicineInvoice::whereIn('id', $existingIds)->delete();
                AccountLedger::whereIn('medicine_invoice_id', $existingIds)
                    ->where('type', 'Purchase Return')->delete();
            } else {
                $invoiceNumber = generateUniqueID(new MedicineInvoice, 'Purchase Return', 'invoice_no');
            }

            foreach ($validatedData['item_id'] as $index => $itemId) {
                $qty       = $validatedData['quantity'][$index];
                $price     = $validatedData['purchase_price'][$index];
                $discount  = $validatedData['discount_in_rs'][$index] ?? 0;
                $amount    = $price * $qty;
                $netAmount = $amount - $discount;

                $inv = MedicineInvoice::create([
                    'date'               => $validatedData['date'],
                    'account_id'         => $validatedData['account'],
                    'ref_no'             => $validatedData['ref_no'] ?? null,
                    'description'        => $validatedData['description'] ?? null,
                    'invoice_no'         => $invoiceNumber,
                    'type'               => 'Purchase Return',
                    'stock_type'         => 'Out',
                    'item_id'            => $itemId,
                    'purchase_price'     => $price,
                    'sale_price'         => $validatedData['sale_price'][$index],
                    'quantity'           => -$qty,
                    'amount'             => $amount,
                    'discount_in_rs'     => $discount,
                    'discount_in_percent'=> $validatedData['discount_in_percent'][$index] ?? 0,
                    'commission_percent' => 0,
                    'commission_amount'  => 0,
                    'total_cost'         => -$netAmount,
                    'net_amount'         => $netAmount,
                    'expiry_date'        => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status'    => 'Not Sent',
                    'transport_name'     => $validatedData['transport_name'] ?? null,
                    'vehicle_no'         => $validatedData['vehicle_no'] ?? null,
                    'driver_name'        => $validatedData['driver_name'] ?? null,
                    'contact_no'         => $validatedData['contact_no'] ?? null,
                    'builty_no'          => $validatedData['builty_no'] ?? null,
                ]);

                $item = Item::find($itemId);
                AccountLedger::create([
                    'medicine_invoice_id' => $inv->id,
                    'type'                => 'Purchase Return',
                    'date'                => $validatedData['date'],
                    'account_id'          => $validatedData['account'],
                    'description'         => 'Purchase Return #: ' . $invoiceNumber
                                            . ', Item: ' . $item->name
                                            . ', Qty: ' . $qty . ', Rate: ' . $price,
                    'debit'               => $netAmount,
                    'credit'              => 0,
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
     * Soft-delete a Purchase Medicine Return invoice and its ledger entries.
     */
    public function purchaseReturnDelete($invoice_no)
    {
        $invoices = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Purchase Return')->get();

        if ($invoices->isEmpty()) {
            abort(404, 'Purchase Medicine Return invoice not found');
        }

        DB::beginTransaction();
        try {
            $ids = $invoices->pluck('id');
            MedicineInvoice::whereIn('id', $ids)->delete();
            AccountLedger::whereIn('medicine_invoice_id', $ids)
                ->where('type', 'Purchase Return')->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.medicine-invoices.purchase_return.index')
            ->with('success', 'Purchase Medicine Return deleted successfully.');
    }

    /**
     * Validate that available stock covers the requested return quantity.
     */
    private function validatePurchaseReturnStock(array $validatedData, ?string $ignoredInvoiceNo = null): array
    {
        $model     = $ignoredInvoiceNo
            ? (new MedicineInvoice)->ignore($ignoredInvoiceNo)
            : $this->medicineInvoice;
        $stockInfo = $model->getStockInfo();
        $errors    = [];
        $requested = [];

        foreach ($validatedData['item_id'] as $index => $itemId) {
            $requested[$itemId] = ($requested[$itemId] ?? 0) + $validatedData['quantity'][$index];
        }

        foreach ($requested as $itemId => $requestedQty) {
            $available = $stockInfo->filter(fn($p) => $p->item_id == $itemId)->sum('quantity');
            if ($available < $requestedQty) {
                $item = Item::find($itemId);
                $name = $item ? $item->name : "Item #{$itemId}";
                $errors["item_id.{$itemId}"] = [
                    "Insufficient stock for \"{$name}\". Available: {$available}, Requested: {$requestedQty}."
                ];
            }
        }

        return $errors;
    }

    // =========================================================================
    // SALE MEDICINE RETURN — CRUD
    // =========================================================================

    /**
     * List all Sale Medicine Return invoices with filters.
     */
    public function saleReturnIndex(Request $req)
    {
        $title    = "Sale Medicine Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 4)->get();

        $returns = MedicineInvoice::with('account', 'item')
            ->where('type', 'Sale Return')
            ->when($req->filled('account_id'), fn($q) => $q->where('account_id', hashids_decode($req->account_id)))
            ->when($req->filled('invoice_no'),  fn($q) => $q->where('invoice_no', $req->invoice_no))
            ->when($req->filled('item_id'),     fn($q) => $q->where('item_id', hashids_decode($req->item_id)))
            ->when($req->filled('from_date') && $req->filled('to_date'),
                   fn($q) => $q->whereBetween('date', [$req->from_date, $req->to_date]))
            ->latest()
            ->get();

        return view('admin.medicine.sale_medicine_return',
                    compact('title', 'accounts', 'products', 'returns'));
    }

    /**
     * Show the create form for Sale Medicine Return.
     */
    public function saleReturnCreate()
    {
        $title      = "Create Sale Medicine Return";
        $invoice_no = generateUniqueID(new MedicineInvoice, 'Sale Return', 'invoice_no');
        $accounts   = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        // Sale Return adds stock — no stock check needed, use all items
        $products   = Item::where('category_id', 4)->get();

        return view('admin.medicine.create_sale_medicine_return',
                    compact('title', 'invoice_no', 'accounts', 'products'));
    }

    /**
     * Edit an existing Sale Medicine Return invoice.
     */
    public function saleReturnEdit($invoice_no)
    {
        $title    = "Edit Sale Medicine Return";
        $accounts = Account::with(['grand_parent', 'parent'])->latest()->orderBy('name')->get();
        $products = Item::where('category_id', 4)->get();

        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Sale Medicine Return invoice not found');
        }

        return view('admin.medicine.edit_sale_medicine_return',
                    compact('title', 'accounts', 'products', 'medicineInvoice'));
    }

    /**
     * Show detail page for a Sale Medicine Return invoice.
     */
    public function saleReturnShow($invoice_no)
    {
        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Sale Medicine Return invoice not found');
        }

        $type             = 'Sale Return';
        $previous_balance = $medicineInvoice[0]->account->getBalance($medicineInvoice[0]->date);

        if (request()->has('generate_pdf')) {
            $html = view('admin.medicine.invoice_pdf',
                         compact('medicineInvoice', 'type', 'previous_balance'))->render();
            $mpdf = new Mpdf(['format' => 'A4-P', 'margin_top' => 10,
                              'margin_bottom' => 2, 'margin_left' => 2, 'margin_right' => 2]);
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->SetHTMLFooter('<div style="text-align:right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        }

        return view('admin.medicine.show_medicine_return',
                    compact('medicineInvoice', 'type', 'previous_balance'));
    }

    /**
     * Store (create or update) a Sale Medicine Return invoice.
     * Sale Return → stock_type In, quantity positive, total_cost positive.
     * Ledger: credit = netAmount (reduces customer receivable).
     * No stock availability check — returning goods always allowed.
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
            'commission_percent.*'  => 'nullable|numeric|min:0|max:100',
            'expiry_date.*'         => 'nullable|date',
            'transport_name'        => 'nullable|string|max:255',
            'vehicle_no'            => 'nullable|string|max:255',
            'driver_name'           => 'nullable|string|max:255',
            'contact_no'            => 'nullable|string|max:255',
            'builty_no'             => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('editMode')) {
                $invoiceNumber = $request->invoice_no;
                $existing      = MedicineInvoice::where('invoice_no', $invoiceNumber)
                    ->where('type', 'Sale Return')->get();
                $existingIds   = $existing->pluck('id');
                MedicineInvoice::whereIn('id', $existingIds)->delete();
                AccountLedger::whereIn('medicine_invoice_id', $existingIds)
                    ->where('type', 'Sale Return')->delete();
            } else {
                $invoiceNumber = generateUniqueID(new MedicineInvoice, 'Sale Return', 'invoice_no');
            }

            foreach ($validatedData['item_id'] as $index => $itemId) {
                $qty           = $validatedData['quantity'][$index];
                $salePrice     = $validatedData['sale_price'][$index];
                $costPrice     = $validatedData['purchase_price'][$index];
                $discount      = $validatedData['discount_in_rs'][$index] ?? 0;
                $commPct       = $validatedData['commission_percent'][$index] ?? 0;
                $amount        = $salePrice * $qty;
                $afterDiscount = $amount - $discount;
                $commAmount    = round($afterDiscount * $commPct / 100, 2);
                $netAmount     = $afterDiscount + $commAmount;

                $inv = MedicineInvoice::create([
                    'date'               => $validatedData['date'],
                    'account_id'         => $validatedData['account'],
                    'ref_no'             => $validatedData['ref_no'] ?? null,
                    'description'        => $validatedData['description'] ?? null,
                    'invoice_no'         => $invoiceNumber,
                    'type'               => 'Sale Return',
                    'stock_type'         => 'In',
                    'item_id'            => $itemId,
                    'purchase_price'     => $costPrice,
                    'sale_price'         => $salePrice,
                    'quantity'           => $qty,
                    'amount'             => $amount,
                    'discount_in_rs'     => $discount,
                    'discount_in_percent'=> $validatedData['discount_in_percent'][$index] ?? 0,
                    'commission_percent' => $commPct,
                    'commission_amount'  => $commAmount,
                    'total_cost'         => $qty * $costPrice,
                    'net_amount'         => $netAmount,
                    'expiry_date'        => $validatedData['expiry_date'][$index] ?? null,
                    'whatsapp_status'    => 'Not Sent',
                    'transport_name'     => $validatedData['transport_name'] ?? null,
                    'vehicle_no'         => $validatedData['vehicle_no'] ?? null,
                    'driver_name'        => $validatedData['driver_name'] ?? null,
                    'contact_no'         => $validatedData['contact_no'] ?? null,
                    'builty_no'          => $validatedData['builty_no'] ?? null,
                ]);

                $item = Item::find($itemId);
                AccountLedger::create([
                    'medicine_invoice_id' => $inv->id,
                    'type'                => 'Sale Return',
                    'date'                => $validatedData['date'],
                    'account_id'          => $validatedData['account'],
                    'description'         => 'Sale Return #: ' . $invoiceNumber
                                            . ', Item: ' . $item->name
                                            . ', Qty: ' . $qty . ', Rate: ' . $salePrice,
                    'debit'               => 0,
                    'credit'              => $netAmount,
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
     * Soft-delete a Sale Medicine Return invoice and its ledger entries.
     */
    public function saleReturnDelete($invoice_no)
    {
        $invoices = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', 'Sale Return')->get();

        if ($invoices->isEmpty()) {
            abort(404, 'Sale Medicine Return invoice not found');
        }

        DB::beginTransaction();
        try {
            $ids = $invoices->pluck('id');
            MedicineInvoice::whereIn('id', $ids)->delete();
            AccountLedger::whereIn('medicine_invoice_id', $ids)
                ->where('type', 'Sale Return')->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.medicine-invoices.sale_return.index')
            ->with('success', 'Sale Medicine Return deleted successfully.');
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

        $medicineInvoice = MedicineInvoice::where('invoice_no', $invoice_no)
            ->where('type', $type)
            ->with('account', 'item')
            ->get();

        if ($medicineInvoice->isEmpty()) {
            abort(404, 'Medicine Invoice not found');
        }

        $medicineInvoiceIds = $medicineInvoice->pluck('id');
        $returnType = $type . ' Return';

        $previous_balance = $medicineInvoice[0]->account->getBalance($medicineInvoice[0]->date);

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
            $html = view('admin.medicine.invoice_pdf', compact('medicineInvoice', 'type', 'previous_balance'))->render();
            $mpdf = new Mpdf([
                'format' => 'A4-P',
                'margin_top' => 10,
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
