<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product',
        'category_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Value_tax',
        'Rate_tax',
        'Total',
        'Status',
        'Value_Status',
        'note',
        'Payment_Date',
    ];

     protected $table = 'invoices';
 
    protected $dates = ['deleted_at'];

 public function category()
   {
        return $this->belongsTo('App\Models\Category');
   }
}
