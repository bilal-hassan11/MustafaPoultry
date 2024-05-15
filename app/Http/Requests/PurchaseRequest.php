<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'date'     => ['nullable', 'date'],
            'vehicle_no'        => ['nullable', 'string', 'max:100'],
            'bilty_no'          => ['nullable', 'integer', 'min:0'],
            'prod_inv_no'       => [ 'string', 'max:100'],
            'account_id'        => ['nullable', 'string'],
            'item_id'           => ['nullable', 'string'],
            'company_weight'    => [ 'integer', 'min:0'],
            'party_weight'      => ['nullable'],
            'weight_difference' => ['nullable'],
            'posted_weight'     => ['nullable'],
            'rate'              => ['nullable'],
            'gross_ammount'     => ['nullable'],
            'fare'              => ['nullable'],
            'others_charges'    => ['nullable'],
            'net_ammount'       => ['nullable'],
            'remarks'           => ['nullable', 'string'],
        ];
    }
}
