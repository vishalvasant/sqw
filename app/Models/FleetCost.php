<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetCost extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'cost_per_km'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
