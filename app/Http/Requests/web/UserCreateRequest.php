<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check(); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email,{$id},id,deleted_at,NULL|email|regex:/^[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}/i',
            'name' => 'required',
            'user_name'         => 'string|required|unique:users,user_name,{$id},id,deleted_at,NULL',
            'password' => 'required|confirmed',
            'phone' => 'nullable|regex:/(0)[0-9]/|unique:users,phone',

        ];
    }
}
