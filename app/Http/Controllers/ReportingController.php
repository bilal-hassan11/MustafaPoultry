<?php

namespace App\Http\Controllers;

use App\Models\ChickInvoice;
use App\Models\FeedInvoice;
use App\Models\MedicineInvoice;
use App\Models\MurghiInvoice;
use App\Models\OtherInvoice;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;

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

        if ($request->ajax()) {
            return response()->json($incomeReports);
        } else {
            return view('admin.report.income_report', compact('incomeReports'));
        }
    }
}
