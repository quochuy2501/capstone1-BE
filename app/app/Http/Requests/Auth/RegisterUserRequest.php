<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'full_name'             =>      'required|min:5|max:255',
            'email'                 =>      'required|email|unique:users,email',
            'password'              =>      'required|min:5|max:30',
            're_password'           =>      'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'required'      =>  ':attribute cannot be left blank',
            'max'           =>  ':attribute too long',
            'min'           =>  ':attribute too short',
            'unique'        =>  ':attribute already exist',
            'same'          =>  ':attribute and the password is not the same',
        ];
    }

    public function attributes()
    {
        return [
            'full_name'         =>  'Full name',
            'email'             =>  'Email',
            'password'          =>  'Password',
            're_password'       =>  'Repeat password',
        ];
    }
}
