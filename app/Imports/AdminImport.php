<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $all_monitors = [];
        $csv_array =[];
        $date = date('Y-m-d H:i:s');

        $all_monitors =collect($all_monitors);
        $year =  date('Y');
        $num = 1;
        foreach ($rows as $k => $row)
        {
            if($k > 0){
                $hash = generateRandomString();
                $user_name =str_replace(' ', '', strtolower($row[2]).$year.'001');
                $check =$all_monitors->where('city',$row[2]);
                if($check->count()>0){
                    $last = substr($check->last()['username'], -1);
                    $last = $last+1;
                    $user_name =str_replace(' ', '',strtolower($row[2]).$year.'00'.$last);
                }
                    $all_monitors[] = [
                    'name' => $row[1],
                    'username' => $user_name,
                    'city'=>$row[2],
                    'email' => time().$k.'@yopmail.com',
                    'password' =>  Hash::make($hash),
                    'contact_no' => '',
                    'cnic' => '',
                    'user_type'=>'monitor',
                    'created_at' => $date,
                    'updated_at' => $date,
                    ];
                     $csv_array[] = [
                    'name' => $row[1],
                    'username' => $user_name,
                    'password' =>  $hash,
                    ];


            }
        }


        try {
              if (!\File::exists(public_path()."/files")) {
                    \File::makeDirectory(public_path() . "/files");
                }
                $filename =  public_path("files/monitor_list_with_pass.csv");
                $handle = fopen($filename, 'w');
                fputcsv($handle, [
                "Name",
                "Username",
                "Password",
                ]);
                foreach ($csv_array as $each_user) {
                    fputcsv($handle, [
                        $each_user['name'],
                        $each_user['username'],
                        $each_user['password'],

                    ]);

                }
        DB::table('admins')->insert($all_monitors->toArray());
        DB::commit();

        } catch (QueryException $e) {
            dd($e);
        }

    }
}
