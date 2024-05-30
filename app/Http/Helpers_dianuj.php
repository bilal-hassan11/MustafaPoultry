<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_full_time')) {
    function get_full_time($date, $format = 'd, M Y h:i A')
    {
        $new_date = new \DateTime($date);
        return $new_date->format($format);
    }
}

if (!function_exists('get_date')) {
    function get_date($date, $format = 'd M Y')
    {
        return get_full_time($date, $format);
    }
}

if (!function_exists('get_price')) {
    function get_price($price, $symbol = 'PKR ')
    {
        return  $symbol . number_format(round($price, 2), 2);
    }
}

if (!function_exists("safeCount")) {
    function safeCount($array)
    {
        if (is_array($array) || is_object($array)) {
            return count($array);
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_title')) {
    function get_title($str)
    {
        return ucwords(str_replace('_', ' ', $str));
    }
}

if (!function_exists('dummy_image')) {
    function dummy_image($type = null)
    {
        switch ($type) {
            case 'categories':
                return get_asset('admin_assets/images/category_dummy.jpg');
            case 'user':
                return get_asset('frontend_assets/images/dummy_user.png');
            case 'blog':
                return get_asset('frontend_assets/images/dummy_blog.jpg');
            case 'shipment':
                return get_asset('frontend_assets/images/shipment_not_image_not_found.png');
            default:
                return get_asset('frontend_assets/images/dummy_user.png');
        }
    }
}

if (!function_exists('get_asset')) {
    function get_asset($file)
    {
        return asset($file);
    }
}

if (!function_exists('check_file')) {
    function check_file($file = null, $type = null, $diff_type_pic = null)
    {
        if ($file && Storage::has($file)) {
            return asset($file);
        } else {
            if($diff_type_pic != null){
                return check_file($diff_type_pic, $type);
            }
            return dummy_image($type);
        }
    }
}

if (!function_exists('dateTimeInterval')) {
    function dateTimeInterval($start, $end, $asArray = false, $format = 'Y-m-d', $interval = '1 day',  $arr_format = null, $separator = '|')
    {
        $begin = new DateTime($start);
        $end = new DateTime($end);
        if ($arr_format == null) {
            $arr_format = $format;
        }
        $data = array();
        for ($dt = $begin; $dt <= $end; $dt->modify($interval)) {
            $data[$dt->format($arr_format)] = (string) $dt->format($format);
        }
        if ($asArray) {
            return $data;
        } else {
            return implode($separator, $data);
        }
    }
}

if (!function_exists('hashids_encode')) {
    function hashids_encode($str)
    {
        return \Hashids::encode($str);
    }
}

if (!function_exists('hashids_decode')) {
    function hashids_decode($str)
    {
        try {
            return \Hashids::decode($str)[0];
        } catch (Exception $e) {
            return abort(404);
        }
    }
}

function format_cnic($str){
    if(strlen($str) < 13){
        return '-';
    }
    $str = substr_replace($str, '-', 5, 0);
    $str = substr_replace($str, '-', -1, 0);
    return $str;
}

if(!function_exists('api_response')){
    function api_response($status = false, $_data = null, $msg = null){
        $data = [
            'status' => $status,
            'data' => $_data ?? null,
            'msg' => $msg,
        ];

        if($_data != null){
            $data['base_url'] = url('/');
        }
        return response()->json($data, 200);
    }
}

if(!function_exists('facility_mananged_by')){
    function facility_mananged_by($in_string = false){
        $arr = ['DOH','HANDS','MERF','PPHI'];
        if($in_string){
            return implode(',', $arr);
        }
        return $arr;
    }
}

if(!function_exists('generateRandomString')){
    function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(!function_exists('visit_type')){
    function visit_type($status, $all = false)
    {
        $arr = array(
            "medical_camp_visit" => "Medical Camp",
            "facility_visit" => "Facility",
        );
        if ($all) {
            return array_keys($arr);
        }
        return $arr[$status] ?? '-';
    }
}

if(!function_exists('check_empty')){
    function check_empty($value){
        if(isset($value) && !empty($value) && $value != ''){
            return true;
        }
        return false;
    }
}
}

