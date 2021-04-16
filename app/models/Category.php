<?php

namespace App\models;

use App\models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_name',
        'description',
        'Created_by',
    ];

    protected $table = 'categories';

    public function products()
    {
         return $this->hasMany(Product::class);
    }

}
