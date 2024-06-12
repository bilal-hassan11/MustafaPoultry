<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Traits\StockTrait;

class FinancialReportService
{
    use StockTrait;
    /**
     * Get the sum of net amount for a given type between two dates.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $type
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getSumByTypeAndDateRange(Model $model, $type, $startDate, $endDate)
    {
        return $model->where('type', $type)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('net_amount');
    }

    /**
     * Get income report for a given model between two dates.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getIncomeReport(Model $model, $startDate, $endDate)
    {
        $openingStock = $this->getClosingStockInfo($model, $startDate);

        $totalSales = $this->getSumByTypeAndDateRange($model, 'Sale', $startDate, $endDate);
        $salesReturns = $this->getSumByTypeAndDateRange($model, 'Sale Return', $startDate, $endDate);
        $totalPurchases = $this->getSumByTypeAndDateRange($model, 'Purchase', $startDate, $endDate);
        $purchaseReturns = $this->getSumByTypeAndDateRange($model, 'Purchase Return', $startDate, $endDate);

        $closingStock = $this->getClosingStockInfo($model, $endDate);

        $netSales = $totalSales - $salesReturns;
        $netpurchases = $totalPurchases - $purchaseReturns;
        $cogs = ($openingStock + $netpurchases - $purchaseReturns) - $closingStock;
        $grossProfit = $netSales - $cogs;

        return [
            'opening_stock' => $openingStock,
            'total_purchases' => $totalPurchases,
            'purchase_returns' => $purchaseReturns,
            'total_sales' => $totalSales,
            'sales_returns' => $salesReturns,
            'net_sales'   => $netSales,
            'closing_stock' => $closingStock,
            'cost_of_goods_sold' => $cogs,
            'gross_profit' => $grossProfit
        ];
    }
}
