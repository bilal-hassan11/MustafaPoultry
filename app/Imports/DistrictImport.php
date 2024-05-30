<?php

namespace App\Imports;

use App\Models\District;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class DistrictImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $districts = [];
        foreach ($rows as $k => $row)
        {
            $district = trim($row[2]);
            if($k > 0){
                $districts[$district] = \Str::slug($district);
            }
        }
        $all_districts = [];
        $date = date('Y-m-d H:i:s');
        foreach ($districts as $k => $district) {
            $all_districts[] = [
                'name' => $k,
                'slug' => $district,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }
        DB::table('districts')->insert($all_districts);
        DB::commit();
    }
}
