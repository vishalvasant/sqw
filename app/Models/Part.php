<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'part_name', 'part_number', 'quantity'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
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
