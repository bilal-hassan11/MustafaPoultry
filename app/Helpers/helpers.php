<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('generateUniqueID')) {
    function generateUniqueID(Model $model,$type, $idFieldName)
    {
        $currentDate = now();
        $yearMonth = $currentDate->format('ym');

        $latestRecord = $model->where('type',$type)->max($idFieldName);

        $lastID = ($latestRecord !== null && substr($latestRecord, 0, 4) == $yearMonth)
            ? $latestRecord + 1
            : $yearMonth . '0001';

        return $lastID;
    }

}