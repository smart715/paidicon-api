<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomNotificationMultipleRequest extends FormRequest
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
            'title' => 'required|string',
            'content' => 'nullable|string'
        ];
    }
}
