<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

trait StockTrait
{
    protected $ignoredInvoiceNo;

    /**
     * Set the invoice_no to be ignored.
     *
     * @param mixed $invoice_no
     * @return $this
     */
    public function ignore($invoice_no)
    {
        $this->ignoredInvoiceNo = $invoice_no;
        return $this;
    }

    /**
     * Calculate the available stock quantity, average price, total cost, last purchase price, and last sale price
     * grouped by item and expiry.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStockInfo()
    {
        $table = $this->getTable();
        $invoice_no = $this->ignoredInvoiceNo;

        $query = $this->select(
            'item_id',
            'expiry_date',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total_cost) as total_cost'),
            DB::raw('CASE WHEN SUM(quantity) != 0 THEN SUM(total_cost) / SUM(quantity) ELSE 0 END as average_price'),
            DB::raw("(SELECT purchase_price FROM {$table} AS mi2 WHERE mi2.item_id = {$table}.item_id AND type = 'Purchase' ORDER BY mi2.date DESC LIMIT 1) AS last_purchase_price"),
            DB::raw("(SELECT sale_price FROM {$table} AS mi2 WHERE mi2.item_id = {$table}.item_id AND type = 'Sale' ORDER BY mi2.date DESC LIMIT 1) AS last_sale_price")
        )
            ->when($invoice_no, function ($query) use ($invoice_no) {
                $query->whereNotIn('invoice_no', [$invoice_no])
                    ->orWhere('type', '!=', 'Sale');
            })
            ->groupBy('item_id', 'expiry_date')
            ->with(['item:id,name,category_id', 'item.category:id,name']);

        $invoices = $query->get();

        $srno = 1;
        return $invoices->map(function ($invoice) use (&$srno) {
            $item = new stdClass;
            $item->id = $srno++;
            $item->item_id = $invoice->item_id;

            if ($invoice->item) {
                $item->name = $invoice->item->name;
                $item->category_id = $invoice->item->category_id;
                $item->category_name = optional($invoice->item->category)->name ?? 'Unknown';
            } else {
                $item->name = 'Unknown';
                $item->category_id = 'Unknown';
                $item->category_name = 'Unknown';
            }

            $item->expiry_date = $invoice->expiry_date;
            $item->quantity = $invoice->total_quantity;
            $item->average_price = $invoice->average_price;
            $item->total_cost = $invoice->total_cost;
            $item->last_purchase_price = $invoice->last_purchase_price;
            $item->last_sale_price = $invoice->last_sale_price;

            return $item;
        });
    }

    public function filterNearExpiryStock($stockInfo, $months)
    {
        return $stockInfo->filter(function ($item) use ($months) {
            if ($item->expiry_date === null || $item->quantity <= 0) {
                return false;
            }

            $expiryDate = Carbon::parse($item->expiry_date);
            $currentDate = Carbon::now();
            $endDate = $currentDate->copy()->addMonths($months);

            return $expiryDate->between($currentDate, $endDate);
        });
    }

    /**
     * Filter products with low stock alert.
     *
     * @param \Illuminate\Support\Collection $stockInfo
     * @param int $lowStockLimit
     * @return \Illuminate\Support\Collection
     */
    public function filterLowStock($stockInfo, $lowStockLimit = 10)
    {
        return $stockInfo->filter(function ($item) use ($lowStockLimit) {
            return $item->quantity < $lowStockLimit;
        });
    }

    /**
     * Get the closing stock value for a given date.
     *
     * @param string $table
     * @param string $date
     * @param int|null $itemId
     * @return float
     */
    public function getClosingStockInfo($model, $date, $itemId = null): float
    {
        $query = $model->select(
            'item_id',
            'expiry_date',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total_cost) as total_cost'),
            DB::raw('CASE WHEN SUM(quantity) != 0 THEN SUM(total_cost) / SUM(quantity) ELSE 0 END as average_price')
        )
            ->where('date', '<', $date)
            ->groupBy('item_id', 'expiry_date');

        if ($itemId) {
            $query->where('item_id', $itemId);
        }

        $totalStockValue = $query->sum('total_cost');

        return $totalStockValue;
    }
}
