<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
     
    public function authorize()
    {
        return true;
    }
 
    public function rules()
    {
        return [
            'category_name' => 'required|unique:categories|max:255',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' =>'يرجي ادخال اسم القسم',
            'category_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>' يرجي ادخال وصف القسم  ',
        ];
    }
}
