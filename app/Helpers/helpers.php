<?php

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('generateUniqueID')) {
    function generateUniqueID(Model $model, $type, $idFieldName)
    {
        $currentDate = now();
        $yearMonth = $currentDate->format('ym');

        $latestRecord = $model->where('type', $type)->max($idFieldName);

        $lastID = ($latestRecord !== null && substr($latestRecord, 0, 4) == $yearMonth)
            ? $latestRecord + 1
            : $yearMonth . '0001';

        return $lastID;
    }
}

if (!function_exists('generatePDFResponse')) {
    function generatePDFResponse($htmlContent, $mpdf)
    {
        $mpdf->WriteHTML($htmlContent);
        $pdfContent = $mpdf->Output('', 'S');

        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}

if (!function_exists('generateReportPDF')) {
    function generateReportPDF($reportName, array $data, $format = 'A4-P', $margins = ['top' => 10, 'bottom' => 5, 'left' => 5, 'right' => 5])
    {
        extract($data);
        $html = view("admin.report.{$reportName}", $data)->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => $format,
            'margin_top' => $margins['top'],
            'margin_bottom' => $margins['bottom'],
            'margin_left' => $margins['left'],
            'margin_right' => $margins['right'],
        ]);

        $mpdf->SetAutoPageBreak(true, 25);
        $mpdf->SetHTMLFooter('<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>');

        return generatePDFResponse($html, $mpdf);
    }
}
