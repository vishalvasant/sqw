<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelLog extends Model
{
    use HasFactory;

    protected $fillable = ['fleet_id', 'product_id', 'quantity', 'cost', 'refueled_at'];

    /**
     * Get the fleet associated with the fuel log.
     */
    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    /**
     * Get the product associated with the fuel log (fuel inventory).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
