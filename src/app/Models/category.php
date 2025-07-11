<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product')->withTimestamps();
    }
}
