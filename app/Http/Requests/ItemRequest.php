<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'category_id'   => ['required', 'max:500'],
            'name'          => ['required', 'max:255'],
            'price'         => ['required', 'integer', 'min:0'],
            'type'          => ['required', 'in:purchase,sale'],
            'stock_status'  => ['required', 'in:0,1'],
            'item_status'   => ['required', 'in:0,1'],
            'remarks'       => ['nullable', 'max:65000'],
            'item_id'       => ['nullable', 'max:500']
        ];
    }
}
