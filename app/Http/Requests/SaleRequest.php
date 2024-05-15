<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'sale_date'         => ['required', 'date'],
            'gp_no'             => ['required', 'string', 'max:100'],
            'remarks'           => ['nullable', 'string'],
            'account_name'      => ['required'],
            'sub_dealer_name'   => ['required', 'string', 'max:250'],
            'item_id'           => ['array'],
            'item_id.*'         => ['required'],
            'bags'              => ['array'],
            'bags.*'            => ['required', 'integer'],
            'rate'              =>['array'],
            'rate.*'            => ['required', 'integer'],
            'bags_value'        => ['required', 'integer'],
            'commission'        => ['required', 'integer'],
            'discount'          => ['required', 'integer'],
            'fare_value'        => ['required', 'integer'],
            'net_value'         => ['required', 'integer'],
            'vehicle_no'        => ['required'],
            'fare_status'       => ['required', 'in:0,1'],
            'sale_id'           => ['required'],
        ];
    }
}
