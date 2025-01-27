<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['asset_name', 'description', 'value', 'purchase_date', 'status', 'asset_id', 'product_id', 'quantity'];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'asset_part')->withPivot('quantity')->withTimestamps();
    }
}
