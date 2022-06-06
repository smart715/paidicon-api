<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderTransactionRequest  extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string',
/*            'card_number' => 'required|numeric',
            'exp_month' => 'required|numeric',
            'exp_year' => 'required|numeric',
            'cvc' => 'required',*/
            'amount' => 'required|numeric',
            'order_id' => 'required|numeric|exists:orders,id',
            'referral_code' => 'nullable|exists:users,referral_code'
        ];
    }
}
