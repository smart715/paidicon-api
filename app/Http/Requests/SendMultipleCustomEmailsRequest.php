<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMultipleCustomEmailsRequest extends FormRequest
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
            'filters' => 'present|array',
            'filters.*.property' => 'required|string',
            'filters.*.value' => 'required',
            'filters.*.symbol' => 'nullable|string',
            'subject' => 'required|string',
            'header' => 'nullable|string',
            'body' => 'required|string',
            'signature'=> 'nullable|string',
            'footer' => 'nullable|string'
        ];
    }
}
