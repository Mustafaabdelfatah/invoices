<?php

namespace App\models;

use App\models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'description',
    ];

    protected $table = 'products';

    public function category()
    {
         return $this->belongsTo(Category::class);
    }

}
