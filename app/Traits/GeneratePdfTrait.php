<?php

namespace App\Traits;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\File;

trait GeneratePdfTrait
{
    public function generatePdf($htmlContent, $fileNamePrefix)
    {
        $mpdf = new Mpdf([
            'format' => 'A4-P',
            'margin_top' => 10,
            'margin_bottom' => 2,
            'margin_left' => 2,
            'margin_right' => 2,
        ]);

        $mpdf->SetAutoPageBreak(true, 15);
        $mpdf->SetHTMLFooter('<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>');

        $mpdf->WriteHTML($htmlContent);

        $fileName = $fileNamePrefix . '_' . time() . '.pdf';
        $filePath = public_path('pdf/' . $fileName);

        if (!File::exists(public_path('pdf'))) {
            File::makeDirectory(public_path('pdf'), 0755, true);
        }

        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
        return $filePath;
    }
}
