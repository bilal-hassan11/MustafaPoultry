<?php

namespace App\Imports;

use App\Models\UcList;
use App\Models\District;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class UclistImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $all_uclist = [];
        $date = date('Y-m-d H:i:s');
        // $not_present =[];
        foreach ($rows as $k => $row)
        {
            $uc_name = trim($row[2]);
            if($k > 0){
                $dis_id=District::whereName($row[0])->first()->id ?? 0;
                $all_uclist[$k]['name'] = $row[2];
                $all_uclist[$k]['district'] = $dis_id;
                $all_uclist[$k]['slug'] = \Str::slug($uc_name);
                $all_uclist[$k]['created_at'] = $date;
                $all_uclist[$k]['updated_at'] = $date;
            }
        }


        DB::table('uclists')->insert($all_uclist);
        DB::commit();
    }
}
