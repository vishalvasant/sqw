<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['asset_name', 'description', 'value', 'purchase_date', 'status'];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}
