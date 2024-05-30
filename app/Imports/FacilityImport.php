<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Facility;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Mavinoo\Batch\BatchFacade as Batch;

class FacilityImport implements ToCollection
{
    private $new = 0;
    private $updated = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {   
        
        $date = date('Y-m-d H:i:s');
        $new_facilities = $updated_facilities = $all_facilities = [];
        $districts = District::all(['id', 'name']);
        $_facilities = Facility::toBase()->get(['id', 'code', 'district', 'district_id', 'name', 'type', 'managed_by']);
        foreach($_facilities as $f){
            $all_facilities[$f->code] = $f;
        }
       
        DB::beginTransaction();
        foreach ($rows as $k => $row) {
            //get discrtict id
            if($k == 0){
                continue;
            }
            
            $code = trim($row[1]);
            $district_name = trim($row[2]);
            $name = trim($row[3]);
            $type = trim($row[4]);
            $managed_by = trim($row[5]);

            if($code == '' || $name == '' || $type == ''){
                continue;
            }

            $find_f = ($all_facilities[$code]) ?? null;
          
            $district = $districts->where('name', $district_name)->first();

            if($district == null){
                $district = new District();
                $district->name = $district_name;
                $district->slug = Str::slug($district_name);
                $district->save();
            }

            if($find_f == null){
                $new_facilities[] = [
                    'district_id' => $district->id,
                    'code' => trim($row[1]),
                    'district' => $district->name,
                    'name' => $name,
                    'type' => $type,
                    'managed_by' => $managed_by,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                $this->new++;
            }else{
                // if($find_f->district_id != $district->id || $find_f->district != $district->name || $find_f->name != $name || $find_f->type != $type || $find_f->managed_by != $managed_by){
                    
                // }
                // $find_facility->district_id = $district->id;
                // $find_facility->district = $district->name;
                // $find_facility->name = trim($row[3]);
                // $find_facility->type = trim($row[4]);
                // $find_facility->managed_by = trim($row[5]);
                // $find_facility->save();
                $updated_facilities[] = [
                    'id' => $find_f->id,
                    'district_id' => $district->id,
                    'district' => $district->name,
                    'name' => $name,
                    'type' => trim($type),
                    'managed_by' => $managed_by,
                ];
                $this->updated++;
            }
        }

        if(count($new_facilities) > 0){
            DB::table('facilities')->insert($new_facilities);
        }
        if(count($updated_facilities) > 0){
            Batch::update(new Facility, $updated_facilities, 'id');
        }
        DB::commit();
    }

    public function getCounts(){
        return ['new' => $this->new, 'updated' => $this->updated];
    }
}
