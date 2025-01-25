<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripLog extends Model
{
    use HasFactory;

    protected $fillable = ['fleet_id', 'driver_id', 'cost_type', 'duration_or_distance', 'trip_cost', 'trip_date'];

    /**
     * Get the fleet associated with the trip log.
     */
    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    /**
     * Get the driver associated with the trip log.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Calculate the cost of the trip based on cost type.
     */
    public function calculateCost()
    {
        return $this->fleet->calculateCost($this->duration_or_distance);
    }
}
