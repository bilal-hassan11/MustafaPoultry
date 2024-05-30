<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'grand_parent_id'   => ['required', 'max:500'],
            'parent_id'         => ['required', 'max:500'],
            'name'      => ['required', 'string', 'max:250'],
            'opening_balance'   => ['required', 'integer', 'min:0'],
            'opening_date'      => ['required', 'date'],
            'account_nature'    => ['required', 'in:credit,debit'],
            'status'            => ['required', 'integer'],
            'ageing'           => ['required', 'integer', 'min:0'],
            'commission'        => ['required', 'integer', 'min:0', 'max:100'],
            'discount'          => ['required', 'integer', 'min:0', 'max:100'],
            'address'           => ['nullable', 'string', 'max:65000']
        ];
    }
}
