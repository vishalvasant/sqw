<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_number', 'vehicle_type', 'fixed_cost_per_hour','odometer'];

    public function fuelUsages()
    {
        return $this->hasMany(FuelUsage::class);
    }

    public function fleetCost()
    {
        return $this->hasOne(FleetCost::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
