<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InwardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date'           => ['nullable', 'date'],
            'item_id'        => ['nullable', 'integer'],
            'account_id'     => ['nullable', 'integer'],
            'vehicle_no'     => ['nullable', 'string'],
            'vehicle_status' => ['nullable', 'string'],
            'no_of_bags'     => ['nullable', 'integer'],
            'fare'           => ['nullable', 'integer'],
            'bilty_no'       => ['nullable', 'string'],
            'gp_no'          => ['nullable', 'string'],
            'company_weight' => ['nullable', 'integer'],
            'party_weight' => ['nullable', 'integer'],
            'first_weight'   => ['nullable', 'integer'],
            'second_weight'  => ['nullable', 'integer'],
            'weight_difference'  => ['nullable', 'integer'],
            'party_weight_difference'  => ['nullable', 'integer'],
            'remarks'        => ['nullable', 'string'],
        ];
    }
}
