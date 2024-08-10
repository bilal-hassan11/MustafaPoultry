<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashBook;
use App\Models\ChickInvoice;
use App\Models\FeedInvoice;
use App\Models\MedicineInvoice;
use App\Models\MurghiInvoice;
use App\Models\OtherInvoice;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ReportingController extends Controller
{
    protected $financialReportService;

    public function __construct(FinancialReportService $financialReportService)
    {
        $this->financialReportService = $financialReportService;
    }

    public function getIncomeReport(Request $request)
    {
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        $invoiceTypes = ['Medicine', 'Chick', 'Murghi', 'Feed', 'Others'];

        $incomeReports = [];
        if ($startDate && $endDate) {
            foreach ($invoiceTypes as $type) {
                switch ($type) {
                    case 'Medicine':
                        $incomeReports[$type] = $this->financialReportService->getIncomeReport(new MedicineInvoice, $startDate, $endDate);
                        break;
                    case 'Chick':
                        $incomeReports[$type] = $this->financialReportService->getIncomeReport(new ChickInvoice(), $startDate, $endDate);
                        break;
                    case 'Murghi':
                        $incomeReports[$type] = $this->financialReportService->getIncomeReport(new MurghiInvoice(), $startDate, $endDate);
                        break;
                    case 'Feed':
                        $incomeReports[$type] = $this->financialReportService->getIncomeReport(new FeedInvoice(), $startDate, $endDate);
                        break;
                    case 'Others':
                        $incomeReports[$type] = $this->financialReportService->getIncomeReport(new OtherInvoice(), $startDate, $endDate);
                        break;
                }
            }
        }

        if ($request->ajax()) {
            return response()->json($incomeReports);
        } else if ($request->has('generate_pdf')) {
            $labels = [
                'opening_stock' => 'Opening Stock',
                'total_purchases' => 'Total Purchases',
                'purchase_returns' => 'Purchase Returns',
                'total_sales' => 'Total Sales',
                'sales_returns' => 'Sales Returns',
                'net_sales' => 'Net Sales',
                'closing_stock' => 'Closing Stock',
                'cost_of_goods_sold' => 'Cost of Goods Sold',
                'gross_profit' => 'Gross Profit'
            ];
            $html = view('admin.report.income_report_pdf', compact('incomeReports', 'labels'))->render();
            $mpdf = new Mpdf([
                'format' => 'A4-P',
                'margin_top' => 10,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
            ]);
            $mpdf->SetAutoPageBreak(true, 25);
            $mpdf->SetHTMLFooter('<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>');
            return generatePDFResponse($html, $mpdf);
        } else {
            return view('admin.report.income_report', compact('incomeReports'));
        }
    }
    public function getPoultryFarmReport(Request $request)
    {
        if ($request->has('generate_pdf')) {
            $startDate = $request->input('from_date');
            $endDate = $request->input('to_date');
            $account = $request->input('account');

            $medicineInvoices = MedicineInvoice::select('invoice_no', 'date', DB::raw('SUM(amount) as total_amount'))
                ->whereBetween('date', [$startDate, $endDate])
                ->where('account_id', $account)
                ->groupBy('invoice_no', 'date')
                ->orderBy('date')
                ->get();

            $feedInvoices = FeedInvoice::whereBetween('date', [$startDate, $endDate])->where('account_id', $account)->orderBy('date')->get();
            $chickInvoices = ChickInvoice::whereBetween('date', [$startDate, $endDate])->where('account_id', $account)->orderBy('date')->get();
            $murghiInvoices = MurghiInvoice::whereBetween('date', [$startDate, $endDate])->where('account_id', $account)->where('type', 'Purchase')->orderBy('date')->get();
            $cashBook = CashBook::whereBetween('entry_date', [$startDate, $endDate])->where('account_id', $account)->orderBy('entry_date')->get();

            $data = [
                'medicineInvoices' => $medicineInvoices,
                'feedInvoices' => $feedInvoices,
                'chickInvoices' => $chickInvoices,
                'murghiInvoices' => $murghiInvoices,
                'cashBook' => $cashBook,
            ];

            return generateReportPDF('poultry_farm_report_pdf', $data);
        } else {
            $accounts = Account::get();
            return view('admin.report.poultry_farm_report', compact('accounts'));
        }
    }
}
