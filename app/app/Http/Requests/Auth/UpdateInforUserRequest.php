<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInforUserRequest extends FormRequest
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
            'id'                    =>      'required|exists:users,id',
            'full_name'             =>      'required|min:5|max:255',
            'email'                 =>      'required|email|unique:users,email,' . $this->id,
            'phone'                 =>      'required|digits:10',
            'id_district'           =>      'required',
            'id_ward'               =>      'required',
            'address'               =>      'required',
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
            'digits'        =>  ':attribute must have 10 numbers',
        ];
    }

    public function attributes()
    {
        return [
            'id'                =>  'ID',
            'full_name'         =>  'Full name',
            'email'             =>  'Email',
            'phone'                 =>      'Phone number',
            'id_district'           =>      'District',
            'id_ward'               =>      'Ward',
            'address'               =>      'Address',
        ];
    }
}
