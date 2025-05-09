<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'fuel_amount',
        'product_id',
        'cost_per_liter',
        'purpose',
        'distance_covered',
        'hours_used',
        'date',
        'total_cost',
        'description'
    ];

    // Define relationship with Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
