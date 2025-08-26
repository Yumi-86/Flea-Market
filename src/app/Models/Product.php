<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_image',
        'name',
        'brand',
        'price',
        'description',
        'condition',
        'selling_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')->withTimestamps();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function scopeSearchKeyword($query, $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        });
    }
    public function getIsSoldAttribute()
    {
        return $this->selling_status; 
    }
}
