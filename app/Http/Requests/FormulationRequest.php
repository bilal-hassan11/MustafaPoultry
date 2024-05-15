<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormulationRequest extends FormRequest
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
            'sale_item_id'        => ['required'],
            'sale_weight'         => ['required', 'integer', 'min:500'],
            'purchase_item_id'    => ['array'],
            'purchase_item_id.*'  => ['required'],
            'purchase_weight'     => ['array'],
            'purchase_weight.*'   => ['required'],
            'formulation_id'      => ['nullable']   
        ];
    }
}
