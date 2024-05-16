<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use App\Models\Consumption;
use App\Models\Item;

class AdminImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
       $all_uclist = [];
        $date = date('Y-m-d H:i:s');
        // $not_present =[];
        $i = 0 ; 
        $j =0;
        foreach ($rows as $k => $row)
        {   
            
            $i +=1;
            if($i > 6){
                $quantity = trim($row[5]);
                $dispatch = trim($row[10]);
               
                if($k > 0){
                    $j += 1 ; 
                    $dis_id=Item::where('code',$row[1])->first()->id ?? 0;
                    if($dis_id == 0){
                            
                    }else{
                        Item::find($dis_id)->decrement('stock_qty', $quantity);//decrement item stock
                    }
                    
                    $all_uclist[$k]['item_id'] = $dis_id;
                    $all_uclist[$k]['quantity'] = $quantity;
                    $all_uclist[$k]['dispatch'] = $dispatch;
                    $all_uclist[$k]['difference'] = $dispatch - $quantity;
                    $all_uclist[$k]['date'] = $date;
                    $all_uclist[$k]['created_at'] = $date;
                    $all_uclist[$k]['updated_at'] = $date;
                }
                
            
            }
            
        }

      
         DB::table('consumptions')->insert($all_uclist);
        DB::commit();  
        
       

    }
}
