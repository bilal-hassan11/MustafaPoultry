<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ExpiryStock;
use App\Models\Item;
use App\Models\MedicineInvoice;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.stock.index', compact('categories'));
    }

    public function filter(Request $request)
    {
        $query = ExpiryStock::with('item.category');

        if ($request->filled('category')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('item')) {
            $query->where('item_id', $request->item);
        }

        return DataTables::of($query)
            ->editColumn('avg_amount', function ($stock) {
                return number_format($stock->rate / $stock->quantity, 2);
            })
            ->editColumn('expiry_date', function ($stock) {
                return $stock->expiry_date ?? 'N/A';
            })
            ->make(true);
    }

    public function getItemsByCategory(Request $request)
    {
        $items = Item::where('category_id', $request->category_id)->get();
        return response()->json(['items' => $items]);
    }

    public function expiryStockReport(Request $request)
    {
        $query = ExpiryStock::with('item')->whereNot('expiry_date', null);

        if ($request->ajax()) {
            if ($request->filled('category')) {
                $query->whereHas('item', function ($q) use ($request) {
                    $q->where('category_id', $request->category);
                });
            }

            if ($request->filled('item')) {
                $query->where('item_id', $request->item);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('expiry_date', [$request->from_date, $request->to_date]);
            }

            return DataTables::of($query)
                ->addColumn('item.name', function (ExpiryStock $stock) {
                    return $stock->item->name;
                })
                ->make(true);
        }

        $categories = Category::all();
        return view('admin.stock.expiry_stock_report', compact('categories'));
    }

    public function lowStockReport(Request $request)
    {
        $query = ExpiryStock::with('item.category')->where('quantity', '<', 10);

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('item.name', function (ExpiryStock $stock) {
                    return $stock->item->name;
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
}
