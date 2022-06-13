<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest  extends FormRequest
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
            'email' => 'email|unique:users,email',
            'full_name' => 'string|min:2',
            'password' => 'string|min:6',
            'address' => 'string|min:5',
            'country' => 'string|min:3',
            'state' => 'string|min:2',
            'phone' => 'string|min:5',
            'town' => 'string|min:3',
            'role' => 'required',

        ];
    }
}
