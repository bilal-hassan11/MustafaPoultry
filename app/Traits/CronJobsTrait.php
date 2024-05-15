<?php

namespace App\Traits;

use App\Models\FormAnswer;
use App\Models\FormReponse;

Trait CronJobsTrait
{
    public function remove_same_form_responses()
    {
        set_time_limit(0);
        //remove same checksum form responses
        $form_responses = FormReponse::where('checksum_checked', 0)->orderBy('id', 'asc')->get();
        $ids = [];
        foreach ($form_responses as $form_response) {
            $ids[] = $form_response->id;
            $form_response->checksum_checked = 1;
            $form_response->save();
            $checksum = $form_response->checksum;
            $deleted_forms = FormReponse::where('checksum', $checksum)->whereNotIn('id', $ids)->get()->each(function ($item) {
                $item->checksum_checked = 1;
                $item->save();
                $item->delete();
            });
            $deleted_forms = $deleted_forms->pluck('id')->toArray();
            FormAnswer::whereIn('form_id', $deleted_forms)->delete();
        }
    }
}