<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
     
    public function authorize()
    {
        return true;
    }
 
    public function rules()
    {
        return [
            'invoice_number' => 'required',
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'category_id' => 'required|exists:categories,id',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
            'Discount' => 'required',
            'Value_tax' => 'required',
            'Rate_tax' => 'required',
            'Total' => 'required',
            'Status' => 'required',
            'Value_Status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'invoice_number.required' =>'يرجي ادخال  رقم الفاتوره  ',
            'invoice_Date.required' =>' يرجي ادخال تاريخ الفاتوره ',
        ];
    }
}
