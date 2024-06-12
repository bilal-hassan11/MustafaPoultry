<?php

namespace App\Http\Controllers;

use App\Models\MedicineInvoice;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
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

        $incomeReport = $this->financialReportService->getIncomeReport(new MedicineInvoice, $startDate, $endDate);

        if ($request->ajax()) {
            return response()->json($incomeReport);
        } else {
            return view('admin.report.income_report', compact('incomeReport'));
        }
    }
}
