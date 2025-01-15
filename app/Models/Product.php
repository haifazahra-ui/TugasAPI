<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["product_name", "price", "qty", "category_id", "photo", "photo_name"];
}
