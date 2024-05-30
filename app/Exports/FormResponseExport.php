<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormResponseExport implements FromView, WithColumnWidths, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // dd($this->data);
        // echo(view('admin.exports.medical_camp_visit_data')->with($this->data)->render());exit;
        $file_name = ($this->data['visit_type']=='medical_camp_visit') ?  'medical_camp_visit_data' : 'visit_data';

        return view('admin.exports.'.$file_name)->with($this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25
        ];
    }

    public function title(): string {
        return 'Visits';
    }
}
