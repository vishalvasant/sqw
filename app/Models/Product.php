<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'unit_id', 'price', 'stock','min_qty'
    ];

    // Define relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define relationship with Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'asset_part')->withPivot('quantity')->withTimestamps();
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'asset_part')->withPivot('quantity')->withTimestamps();
    }
}
