<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
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
        $rid = request()->resource_id;
//        dd($rid);
        return [
            'email'=>'email|regex:/^[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,64}/i|unique:users,email,'.$rid.',id,deleted_at,NULL',
            'user_name'         => 'string|required|unique:users,user_name,'.$rid.',id,deleted_at,NULL',
            'phone' => 'nullable|unique:users,phone,'.$rid.'|regex:/(0)[0-9]/',
        ];
    }
}
