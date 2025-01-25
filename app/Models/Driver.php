<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'license_number', 'contact_number','vehicle_id'];

    /**
     * Get the trip logs associated with the driver.
     */
    public function tripLogs()
    {
        return $this->hasMany(TripLog::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}


