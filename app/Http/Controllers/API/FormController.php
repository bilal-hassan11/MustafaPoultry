<?php

namespace App\Http\Controllers\API;

use App\Helpers\CommonHelpers;
use App\Models\Facility;
use App\Models\FormAnswer;
use App\Models\FormReponse;
use App\Models\SyncData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;


class FormController extends ApiController
{
    public function single_sync(Request $request){

        $validation = Validator::make($request->all(), [
            'facility_id' => ['required'],
            'district_id' => ['required'],
            'district' => ['required', 'string'],
            'was_open' => ['required', 'boolean'],
            'facility_incharge_name' => ['required', 'string'],
            'facility_contact_no' => ['required'],
            'time_of_visit' => ['required', 'date_format:Y-m-d H:i:s'],
            'shifts' => ['required', 'string'],
            'visit_type' => ['required', 'string'],
            'responses' => ['required', 'string'],
            'start_lng' => ['required', 'numeric'],
            'start_lat' => ['required', 'numeric'],
            'sync_lng' => ['required', 'numeric'],
            'sync_lat' => ['required', 'numeric'],
            ]);
        if($request->visit_type=='medical_camp_visit'){
            $validation = Validator::make($request->all(), [
            'district_id' => ['required'],
            'district' => ['required', 'string'],
            'uc_id' => ['required'],
            'uc' => ['required', 'string'],
            'medical_camp_incharge_name' => ['required', 'string'],
            'medical_camp_incharge_contact' => ['required'],
            'fixed_site_name' => ['required', 'string'],
            'comment' => ['required', 'string'],
            'visit_type' => ['required', 'string'],
            'responses' => ['required', 'string'],
            // 'start_lng' => ['required', 'numeric'],
            // 'start_lat' => ['required', 'numeric'],
            // 'sync_lng' => ['required', 'numeric'],
            // 'sync_lat' => ['required', 'numeric'],
            ]);
        }


        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        //for data integrity check
        $r = implode('|', $validation->validated());
        $checksum = md5($r);

        $user = $this->current_user();
        $date = now();
        $answers = json_decode($request->responses);

        if($request->was_open == 1 && ($answers == null || safeCount($answers) == 0)){
            return api_response(false, null, 'No answers provided');
        }

        DB::beginTransaction();

        $facility = Facility::find($request->facility_id, ['id', 'type']);
        // print_r();
        // echo 'hello';
        // exit;

        try{
            //save data to sync_data table
            $sync = new SyncData();
            $sync->user_id = $user->id;
            $sync->type = 'single';
            $sync->data = $answers;
            $sync->save();


            //save form data and responses to database
            $form_response = new FormReponse();
            $form_response->user_id = $user->id;
            $form_response->form_no = CommonHelpers::get_new_visit_id(date('Y-m-d'),$request->visit_type);
            $form_response->visit_type = $request->visit_type;
            $form_response->uc_id = ($request->uc_id!='null') ? $request->uc_id : 0;
            $form_response->uc = @$request->uc;
            $form_response->medical_camp_incharge_name = @$request->medical_camp_incharge_name;
            $form_response->medical_camp_contact_no = @$request->medical_camp_incharge_contact;
            $form_response->fixed_site_name = @$request->fixed_site_name;
            $form_response->comment = @$request->comment;
            $form_response->medical_camp_managed_by = @$request->medical_camp_managed_by;
            $form_response->medical_camp_type = @$request->medical_camp_type;
            $form_response->schedule_id = @$request->schedule_id;
            $form_response->facility_id = (@$facility) ? $request->facility_id : 0;
            $form_response->district_id = @$request->district_id;
            $form_response->facility_type = (@$facility) ? $facility->type : '';
            $form_response->incharge_name = @$request->facility_incharge_name;
            $form_response->contact_no = @$request->facility_contact_no;
            $form_response->district = @$request->district;
            $form_response->was_open =   (@$request->was_open!='null') ? $request->was_open : 0;
            $form_response->closed_reason = @$request->closed_reason;
            $form_response->time_of_visit =  $request->time_of_visit ;
            $form_response->shifts = ($request->shifts) ? json_decode($request->shifts) : '';
            $form_response->data = $request->responses;
            $form_response->checksum = $checksum;
            $form_response->start_lng = ($request->start_lng) ?? null;
            $form_response->start_lat = ($request->start_lat) ?? null;
            $form_response->sync_lng = ($request->sync_lng) ?? null;
            $form_response->sync_lat = ($request->sync_lat) ?? null;
            $form_response->save();

            //save form answers to database
            $form_answers = [];
            foreach ($answers as $_answer) {
                // $form_answer = new FormAnswer();
                $form_answer = [];
                $form_answer['form_id'] = $form_response->id;
                $form_answer['question_id'] = $_answer->question_id;
                $form_answer['anwser'] = $_answer->answer ?? null;
                $form_answer['created_at'] = $date;
                $form_answer['updated_at'] = $date;
                $form_answers[] = $form_answer;
            }
            DB::table('form_answers')->insert($form_answers);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return api_response(false, null, $e->getMessage());
        }

        $data = ['sync_details' => $sync->id , 'form_response_id' => $form_response->id];
        CommonHelpers::activity_logs('Single Data sycned from app', platform: $request->header('platform'), user_id: $user->id, data: $data);
        Cache::forget('dasboard_totals');
        return api_response(true, null, 'Data sycned successfully');
    }

    public function sync_data(Request $request){
        $validation = Validator::make($request->all(), [
            'data' => ['required'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = $this->current_user();
        $date = now();
        $responses = json_decode($request->data, true);

        if($responses == null || safeCount($responses) == 0){
            return api_response(false, null, 'No responses provided');
        }
        DB::beginTransaction();

        try {
            //save data to sync_data table
            $sync = new SyncData();
            $sync->user_id = $user->id;
            $sync->type = 'multiple';
            $sync->data = $responses;
            $sync->save();
            $form_response_ids = [];

            foreach($responses as $response){
                $facility = Facility::find($response['facility_id'], ['id', 'type']);
                //setting up some variables for each response
                $answers = $response['responses'];
                $response['responses'] = json_encode($response['responses']);
                $response['shifts'] = json_encode($response['shifts']);
                $response['sync_type'] = 'multi';

                //for data integrity check
                $r = implode('|', $response);
                $checksum = md5($r);

                //save form data and responses to database
                $form_response = new FormReponse();
                $form_response->user_id = $user->id;
                $form_response->form_no = CommonHelpers::get_new_visit_id(date('Y-m-d'),$response['visit_type']);
                $form_response->visit_type = $response['visit_type'];
                $form_response->uc_id = ($response['uc_id']!='null') ? $response['uc_id'] : 0;
                $form_response->uc = @$response['uc'];
                $form_response->medical_camp_incharge_name = @$response['medical_camp_incharge_name'];
                $form_response->medical_camp_contact_no = @$response['medical_camp_incharge_contact'];
                $form_response->fixed_site_name = @$response['fixed_site_name'];
                $form_response->comment = @$response['comment'];
                $form_response->medical_camp_managed_by =@$response['medical_camp_managed_by'];
                $form_response->medical_camp_type = @$response['medical_camp_type'];
                $form_response->schedule_id = @$response['schedule_id'];
                $form_response->facility_id = (@$facility) ? @$response['facility_id'] : 0;
                $form_response->district_id = @$response['district_id'];
                $form_response->district = @$response['district'];
                $form_response->was_open = (@$response['was_open'] !='null') ? @$response['was_open'] : 0;
                $form_response->closed_reason = @$response['closed_reason'];
                $form_response->facility_type = @$facility->type;
                $form_response->incharge_name = @$response['facility_incharge_name'] ?? null;
                $form_response->contact_no = @$response['facility_contact_no'] ?? null;
                $form_response->time_of_visit = @$response['time_of_visit'];
                $form_response->shifts = (@$response['shifts']) ? json_decode($response['shifts']) : '';
                $form_response->data = @$response['responses'];
                $form_response->sync_type = @$response['sync_type'];
                $form_response->checksum = $checksum;
                $form_response->start_lng = ($response['start_lng']) ?? null;
                $form_response->start_lat = ($response['start_lat']) ?? null;
                $form_response->sync_lng = ($request->sync_lng) ?? null;
                $form_response->sync_lat = ($request->sync_lat) ?? null;
                $form_response->save();
                $form_response_ids[] = $form_response->id;

                //save form answers to database
                if(safeCount($answers) > 0){
                    $form_answers = [];
                    foreach ($answers as $_answer) {
                        // $form_answer = new FormAnswer();
                        $form_answer = [];
                        $form_answer['form_id'] = $form_response->id;
                        $form_answer['question_id'] = $_answer['question_id'];
                        $form_answer['anwser'] = $_answer['answer'] ?? null;
                        $form_answer['created_at'] = $date;
                        $form_answer['updated_at'] = $date;
                        $form_answers[] = $form_answer;
                    }
                    DB::table('form_answers')->insert($form_answers);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return api_response(false, null, $e->getMessage());
        }

        $data = ['sync_details' => $sync->id , 'form_response_id' => $form_response_ids];
        CommonHelpers::activity_logs('Multiple data sycned from app', platform: $request->header('platform'), user_id: $user->id, data: $data);
        Cache::forget('dasboard_totals');
        return api_response(true, null, 'Data sycned successfully');
    }

    public function get_completed_checklists(Request $request){
        $validation = Validator::make($request->all(), [
            'from' => ['required', 'date'],
            'to' => ['required', 'date'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = $this->current_user();
        $from = $request->from;
        $to = $request->to;

        $fields = ['visit_type','form_no', 'district', 'time_of_visit', 'shifts', 'facility_id','created_at','uc'];
        $checklists = FormReponse::with(['facility:id,code,name,type,managed_by'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->latest()->get($fields);

        $data = ['checklists' => $checklists];
        CommonHelpers::activity_logs('Checklist api called with params', platform: $request->header('platform'), user_id: $user->id, data: ['from' => $from, 'to' => $to]);
        return api_response(true, $data);
    }
}
