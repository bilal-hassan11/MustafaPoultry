<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerOrderRequest extends FormRequest
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
            'name'          => ['required', 'string'],
            'email'         => ['required|email'],
            'phone'         =>       ['required|regex:/^[0-9]{10}$/u'],
            'rate'          => ['required|integer'],
            'quantity'      => ['required|integer'],
            'skus' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (count($value) !== count(array_unique($value))) {
                        $fail($attribute.' contains duplicate SKUs.');
                    }
                },
            ],
            'skus.*' => 'required|string',  
        ];
    }
}
