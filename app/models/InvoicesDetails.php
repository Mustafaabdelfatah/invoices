<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicesDetails extends Model
{
    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'category_id',
        'Status',
        'Value_Status',
        'note',
        'user',
        'Payment_Date',
    ];
}
