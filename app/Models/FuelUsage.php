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
        'cost_per_liter',
        'date',
        'total_cost'
    ];

    // Define relationship with Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
