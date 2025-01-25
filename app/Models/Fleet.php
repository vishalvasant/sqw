<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'cost_type', 'base_cost'];

    /**
     * Get the fuel logs associated with the fleet.
     */
    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }

    /**
     * Get the trip logs associated with the fleet.
     */
    public function tripLogs()
    {
        return $this->hasMany(TripLog::class);
    }

    /**
     * Calculate the trip cost based on the cost type.
     */
    public function calculateCost($durationOrDistance)
    {
        switch ($this->cost_type) {
            case 'per_hour':
                return $this->base_cost * $durationOrDistance; // e.g., per hour rate
            case 'per_km':
                return $this->base_cost * $durationOrDistance; // e.g., per km rate
            case 'fixed_trip':
                return $this->base_cost; // fixed cost per trip
            default:
                return 0;
        }
    }
}
