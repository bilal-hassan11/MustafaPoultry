<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use stdClass;

trait StockTrait
{
    /**
     * Calculate the available stock quantity, average price, total cost, last purchase price, and last sale price
     * grouped by item and expiry.
     *
     * @return \Illuminate\Support\Collection
     */


    public function getStockInfo()
    {
        $invoices = $this->select(
            'item_id',
            'expiry_date',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total_cost) as total_cost'),
            DB::raw('CASE WHEN SUM(quantity) != 0 THEN SUM(total_cost) / SUM(quantity) ELSE 0 END as average_price'),
            DB::raw('(SELECT purchase_price FROM medicine_invoices AS mi2 WHERE mi2.item_id = medicine_invoices.item_id AND mi2.date <= NOW() ORDER BY mi2.date DESC LIMIT 1) AS last_purchase_price'),
            DB::raw('(SELECT sale_price FROM medicine_invoices AS mi2 WHERE mi2.item_id = medicine_invoices.item_id AND mi2.date <= NOW() ORDER BY mi2.date DESC LIMIT 1) AS last_sale_price')
        )
            ->groupBy('item_id', 'expiry_date')
            ->with(['item:id,name,category_id', 'item.category:id,name'])
            ->havingRaw('total_quantity > 0')
            ->get();
        $srno = 1;
        return $invoices->map(function ($invoice) use (&$srno) {
            $item = new stdClass;
            $item->id = $srno++;
            $item->item_id = $invoice->item_id;
            $item->name = $invoice->item->name;
            $item->category_id = $invoice->item->category_id;
            $item->category_name = $invoice->item->category->name;
            $item->expiry_date = $invoice->expiry_date;
            $item->quantity = number_format($invoice->total_quantity, 2);
            $item->average_price = number_format($invoice->average_price, 2);
            $item->total_cost = number_format($invoice->total_cost, 2);
            $item->last_purchase_price = number_format($invoice->last_purchase_price, 2);
            $item->last_sale_price = number_format($invoice->last_sale_price, 2);

            return $item;
        });
    }
}
