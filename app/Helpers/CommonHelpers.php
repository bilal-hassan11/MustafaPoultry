<?php

namespace App\Helpers;

use App\Models\AccountType;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\FormReponse;

class CommonHelpers
{
    public static function pdf_file($path, $dir, $view, $name, $data)
    {
        if(Storage::has($path)){
            return Storage::download($path);
        }

        $pdf = \PDF::loadView($view, array($name => $data));
        $content = $pdf->output();

        Storage::put($path, $content, 'private');
        return Storage::download($path);
    }

    public static function uploadSingleFile($file, $path = 'uploads/images/', $types = "png,gif,jpeg,jpg", $filesize = '1024', $absolute_path = false)
    {
        if ($absolute_path == false) {
            $path = $path . date('Y');
        }

        $rules = array('image' => 'required|mimes:' . $types . "|max:" . $filesize);
        $validator = Validator::make(array('image' => $file), $rules);
        if (!$validator->fails()) {
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $file_path = Storage::put($path, $file);
            return $file_path;
        } else {
            return ['error' => $validator->errors()->first('image')];
        }
    }

    public static function activity_logs($activity, $data = null, $user_type = 'admin', $platform = 'web', $user_id = null){
        $user_id = $user_id ?? auth($user_type)->user()->id ?? 0;
        return ActivityLog::insert([
                'user_id' => $user_id,
                'user_type' => $user_type,
                'ip' => request()->ip(),
                'user_agent' => request()->header('user-agent'),
                'platform' => $platform,
                'activity' => $activity,
                'data' => ($data == null) ? null : json_encode($data),
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
    }

    public static function get_new_visit_id($created_date,$type)
    {
         $lastOrder = FormReponse::whereDate('created_at',$created_date)->whereVisitType($type)->orderBy('id', 'desc')->first();
        if (empty($lastOrder)) {
            $number = date('Ymd',strtotime($created_date)).'0001';
        } else {
            $number = (int) $lastOrder->form_no + 1;
        }
        return (string) $number;
    }

    
}
