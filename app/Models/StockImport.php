<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'cost_per_unit', 'imported_at'];

    // Define relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

