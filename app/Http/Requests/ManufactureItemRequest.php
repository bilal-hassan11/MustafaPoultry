<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufactureItemRequest extends FormRequest
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
            'item_id'           => ['required', 'string', 'max:100'],
            'quantity'          => ['required', 'integer', 'min:1', 'gt:dispatch'],
            'dispatch'          => ['required', 'integer', 'min:1'],
            'manufacture_id'    => ['nullable']
        ];
    }
}