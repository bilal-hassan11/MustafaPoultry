<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ChickInvoice;
use App\Models\FeedInvoice;
use App\Models\Item;
use App\Models\MedicineInvoice;
use App\Models\MurghiInvoice;
use App\Models\OtherInvoice;
use Mpdf\Mpdf;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    protected $stockInfo;

    public function __construct(
        MedicineInvoice $medicineInvoice,
        ChickInvoice $chickInvoice,
        FeedInvoice $feedInvoice,
        OtherInvoice $otherInvoice,
        MurghiInvoice $murghiInvoice
    ) {
        $medicineStockInfo = $medicineInvoice->getStockInfo();
        $chickStockInfo = $chickInvoice->getStockInfo();
        $feedStockInfo = $feedInvoice->getStockInfo();
        $otherStockInfo = $otherInvoice->getStockInfo();
        $murghiStockInfo = $murghiInvoice->getStockInfo();

        $this->stockInfo = $medicineStockInfo
            ->merge($chickStockInfo)
            ->merge($feedStockInfo)
            ->merge($otherStockInfo)
            ->merge($murghiStockInfo);
    }
    public function index(Request $request)
    {
        $categories = Category::all();
        $item_id = $request->item_id;
        $category_id = $request->category;

        if ($request->filled('item_id')) {
            $stocks = $this->stockInfo->filter(function ($item) use ($item_id) {
                return $item->item_id == $item_id;
            });
        } elseif ($request->filled('category')) {
            $stocks = $this->stockInfo->filter(function ($item) use ($category_id) {
                return $item->category_id == $category_id;
            });
        } else {
            $stocks = $this->stockInfo;
        }

        $grandTotal = $stocks->sum('total_cost');

        if ($request->ajax()) {
            return DataTables::of($stocks)
                ->editColumn('avg_amount', fn ($stock) => number_format($stock->average_price, 2))
                ->editColumn('expiry_date', fn ($stock) => $stock->expiry_date ?? 'N/A')
                ->with('grandTotal', number_format($grandTotal, 2))
                ->make(true);
        } elseif ($request->has('generate_pdf')) {
            $html = view('admin.stock.available_Stock_pdf', compact('stocks', 'grandTotal'))->render();
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
            return view('admin.stock.index', compact('categories'));
        }
    }

    public function getItemsByCategory(Request $request)
    {
        $items = Item::where('category_id', $request->category_id)->get();
        return response()->json(['items' => $items]);
    }

    public function expiryStockReport(Request $request)
    {
        $medicineInvoice = new MedicineInvoice();
        $query = $medicineInvoice->getStockInfo();

        if ($request->ajax()) {
            if ($request->filled('category')) {
                $query = $query->filter(function ($item) use ($request) {
                    return $item->category_id == $request->category;
                });
            }

            if ($request->filled('item')) {
                $query = $query->filter(function ($item) use ($request) {
                    return $item->item_id == $request->item;
                });
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query = $query->filter(function ($item) use ($request) {
                    return $item->expiry_date >= $request->from_date && $item->expiry_date <= $request->to_date;
                });
            }

            return DataTables::of($query)
                ->addColumn('item.name', function ($item) {
                    return $item->name;
                })
                ->make(true);
        }

        $categories = Category::all();
        return view('admin.stock.expiry_stock_report', compact('categories'));
    }

    public function lowStockReport(Request $request)
    {
        $medicineInvoice = new MedicineInvoice();
        $query = $medicineInvoice->getStockInfo()->filter(function ($item) {
            return $item->quantity < 10;
        });

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('item.name', function ($item) {
                    return $item->name;
                })
                ->make(true);
        }

        return view('admin.stock.low_stock_report');
    }

    public function maxSellingReport(Request $request)
    {
        $query = MedicineInvoice::with('item')
            ->where('type', 'Sale')
            ->groupBy('item_id')
            ->selectRaw('item_id, sum(quantity) as total_quantity')
            ->orderByDesc('total_quantity');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('item.name', function ($invoice) {
                    return $invoice->item->name;
                })
                ->addColumn('total_quantity', function ($invoice) {
                    return $invoice->total_quantity;
                })
                ->make(true);
        }

        return view('admin.stock.max_selling_report');
    }


    public function lowSellingReport(Request $request)
    {
        $query = MedicineInvoice::with('item')
            ->where('type', 'Sale')
            ->groupBy('item_id')
            ->selectRaw('item_id, sum(quantity) as total_quantity')
            ->orderBy('total_quantity');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('item.name', function ($invoice) {
                    return $invoice->item->name;
                })
                ->addColumn('total_quantity', function ($invoice) {
                    return $invoice->total_quantity;
                })
                ->make(true);
        }

        return view('admin.stock.low_selling_report');
    }

    public function nearExpiryProducts(Request $request)
    {
        $medicineInvoice = new MedicineInvoice();
        $stockInfo = $medicineInvoice->getStockInfo();
        return $medicineInvoice->filterNearExpiryStock($stockInfo, $request->months);
    }
}
