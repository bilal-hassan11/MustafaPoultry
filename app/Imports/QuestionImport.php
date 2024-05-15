<?php

namespace App\Imports;

use App\Models\FacilityType;
use App\Models\Question;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionImport implements ToCollection
{


    public function __construct(private $category_id)
    {
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $questions = [];
        $date = date('Y-m-d H:i:s');
        $order = 0;
        foreach ($rows as $k => $row)
        {
            if($k > 0){
                if($row[0]!=""){
                $order++;
                $question = trim($row[0]);
                $field = trim($row[1]);
                $field = $field == '-' ? null : $field;
                $option = trim($row[2]);
                $option = $option == '-' ? null : $option;
                $option = str_replace('/', ',', $option);
                // $option = safeCount($option) > 0 ? $option : null;
                $option = $option == '' ? null : explode(',', $option);
                if($option != null){
                    foreach ($option as $key => $value) {
                        $option[$key] = trim($value);
                    }
                }
                $type = trim($row[3]);
                $type = ($type=='Fixed medical site') ? '["fixed_medical_site"]' : null;
                $condition = isset($row[4]) ? trim($row[4]) : null;
                $questions[] = [
                    'category_id' => $this->category_id,
                    'question' => $question,
                    'type' => $field != null ? strtolower($field) : null,
                    'order' => $order,
                    'answer_option' => $option == null ? null : json_encode($option),
                    'facility_type' =>  $type,
                    'condition_text' => $condition,
                    'category_type'=>'medical_camp',
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                }

            }
        }
        // dd($questions);
        DB::table('questions')->insert($questions);
        DB::commit();
        exit;


        // $questions = Question::whereNotNull('facility_type')->get();
        // // $facility_types = FacilityType::whereNotIn('name', ['GD','RD','MH','UD', 'ED'])->get('name');
        // // $facility_types = FacilityType::whereNotIn('name', ['GD','RD','MH','UD'])->get('name');
        // // $facility_types = FacilityType::whereNotIn('name', ['GD','RD','UD','CD', 'ED', 'EDS', 'EDs', 'GRD'])->get('name');
        // // $facility_types = FacilityType::whereNotIn('name', ['ED', 'EDs', 'EDS'])->get('name');
        // $facility_types = FacilityType::whereNotIn('name', ['THQ'])->get('name');

        // $facilities = $facility_types->pluck('name')->toArray();
        // $i = 1;
        // foreach($questions as $q){
        //     // if($q->facility_type == 'THQ'){
        //     //     $q->facility_type = json_encode(['THQ']);
        //     //     $q->save();
        //     // }
        //     // if($q->facility_type == 'All (except CD/GRD/RD/UD)'){
        //     //     $q->facility_type = json_encode($facilities);
        //     //     $q->save();
        //     //     $i++;
        //     // }
        //     // if($q->facility_type == 'All (except THQ)'){
        //     //     $q->facility_type = json_encode($facilities);
        //     //     $q->save();
        //     //     $i++;
        //     // }
        // }
        // DB::commit();
        // exit;
        // dump($i);

        // $questions = Question::where('id', '>', 255)->where('id', '<', 280)->get();
        // foreach($questions as $k => $q){
        //     if($q->condition_text != null){
        //         $depend_id = $questions[$k-1]->id;
        //         $condition = ['depend_on' => $depend_id, 'show_on' => 'Available'];
        //         $q->conditions = $condition;
        //         $q->is_hidden = true;
        //         $q->save();
        //         // dump($q->toArray());
        //         // exit;
        //     }
        // }

        // DB::commit();
        // exit;
        // $questions = Question::whereIn('id', [5])->get();
        // foreach($questions as $k => $q){
        //     $condition = ['depend_on' => 4, 'show_on' => 'Available'];
        //     $q->conditions = $condition;
        //     $q->is_hidden = true;
        //     dump($q->toArray());
        //     $q->save();
        // }

        // DB::commit();
        // exit;

        // $old_questions = DB::table('questions_old')->get();
        // $questions = Question::all();

        // foreach($questions as $k => $q){
        //     if($q->question == $old_questions[$k]->question){
        //         dump($old_questions[$k]->conditions);
        //         $q->conditions = $old_questions[$k]->conditions;
        //         $q->save();
        //     }
        // }

        // $old_questions = DB::table('questions_old')->get();
        // $questions = Question::all();

        /* foreach($questions as $question){
            $check_question = $old_questions->where('question', $question->question)->first();
            if($check_question){
                $question->is_hidden = $check_question->is_hidden;
                $question->conditions = $check_question->conditions;
                $question->save();
            }
        } */

        // foreach($questions as $k => $question){

        //     // if($question->condition_text == 'if the option "Available" is selected in the previous question then this question should appear'){
        //     //     // dd($question->toArray());
        //     //     if($questions[$k-1]->condition_text == null && $questions[$k+1]->condition_text == null){
        //     //         $question->conditions = ['depend_on' => $questions[$k-1]->id, 'show_on' => 'Available'];
        //     //         $question->is_hidden = true;
        //     //         $question->save();
        //     //     }
        //     // }
        //     // if($question->condition_text == "If the answer for the previous question is '0', then this question should not appear"){
        //     //     // dd($question->toArray());
        //     //     if($questions[$k-1]->condition_text == null && $questions[$k+1]->condition_text == null){
        //     //         $question->conditions = ['depend_on' => $questions[$k-1]->id, 'hide_on' => '0'];
        //     //         $question->is_hidden = 0;
        //     //         $question->save();
        //     //     }
        //     // }

        //     if($question->condition_text == "if answer to the previous question is 'Not available' then this question should not appear" || $question->condition_text == "if answer to the previous question is 'Not available' then this question should not appear"){
        //         // dd($question->toArray());
        //         if($questions[$k-1]->condition_text == null && $questions[$k+1]->condition_text == null){
        //             $question->conditions = ['depend_on' => $questions[$k-1]->id, 'show_on' => 'Available'];
        //             $question->is_hidden = true;
        //             $question->save();
        //         }
        //     }
        // }
    }
}
