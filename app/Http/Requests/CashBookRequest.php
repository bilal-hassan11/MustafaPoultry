<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashBookRequest extends FormRequest
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
            'date'     => ['required', 'date'],
            'bil_no'          => [ 'integer', 'min:0'],
            'narration'           => ['nullable', 'string'],
            'account_id'        => ['required', 'string'],
            'payment_ammount'    => [ 'integer'],
            'receipt_ammount'    => [ 'integer'],
            'status'       => ['required', 'string', 'min:0'],
            'remarks'           => ['nullable', 'string'],
        ];
    }
}
