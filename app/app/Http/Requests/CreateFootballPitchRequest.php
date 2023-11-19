<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFootballPitchRequest extends FormRequest
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
            'name'             =>      'required',
            'describe'                 =>      'required',
            'price'               =>      'required',
            'detailed_schedule'               =>      'required',
            'id_category'               =>      'required',

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
            'integer'       =>  ':attribute must be a number',
        ];
    }

    public function attributes()
    {
        return [
            'name'             =>      'Name',
            'describe'                 =>      'Describe',
            'price'               =>      'Price',
            'detailed_schedule'               =>      'Detailed Schedule',
            'id_category'               =>      'Category',
        ];
    }
}
