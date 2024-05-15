<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\UcList;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    protected function current_user(){
        if(auth('api')->check()){
            return auth('api')->user();
        }
        return null;
    }

    public function home_data(Request $request){
        // $time = 60 * 60 * 2;
        $time = 1;

        $data = Cache::remember('api_home_data', $time, function(){
            $question_categories = QuestionCategory::latest()->get(['id', 'order','name','type']);
            $facilities = Facility::latest()->get(['id', 'district_id', 'name', 'type', 'managed_by', 'code']);
            $districts = District::latest()->get(['id', 'name']);
            $uc_list   = UcList::latest()->get(['id', 'name','district']);

            $types = FacilityType::latest()->get(['id', 'name']);
            $questions = Question::whereIsActive(1)->orderBy('id', 'asc')->get(['id', 'category_id', 'question', 'type','order','is_required', 'answer_option', 'facility_type', 'conditions','category_type', 'is_hidden']);
            $answers = [];
            $question_facilities = [];
            $final_questions = [];
            foreach($questions as $k => $q){
                $ans = $q->answer_option;
                $opt = null;
                if(is_array($ans)){
                    foreach($ans as $k => $v){
                        $opt[] = [
                            'option_id' => $k,
                            'option_text' => $v
                        ];
                    }
                }
                $answers[] = ['question_id' =>  $q->id, 'answer_option' => $opt];
                $question_facilities[] = ['question_id' =>  $q->id, 'facility_type' => $q->facility_type];
                unset($q->answer_option);
                unset($q->facility_type);
                $final_questions[] = $q;
            }

            return [
                'facilities' => $facilities,
                'facility_types' => $types,
                'districts' => $districts,
                'questions' => $questions,
                'answers' => $answers,
                'uc_list'=>$uc_list,
                'question_facilities' => $question_facilities,
                'question_categories' => $question_categories,
            ];
        });


        $data = [
            // 'schedules' => $user->schedules,
            'facilities' => $data['facilities'],
            'facility_types' => $data['facility_types'],
            'districts' => $data['districts'],
            'questions' => $data['questions'],
            'answers' => $data['answers'],
            'uc_list'=>$data['uc_list'],
            'question_categories' => $data['question_categories'],
            'question_facilities' => $data['question_facilities']
        ];
        return api_response(true, $data, 'Success');
    }
}
