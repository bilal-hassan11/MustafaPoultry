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
