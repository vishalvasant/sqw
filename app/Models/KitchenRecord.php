<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenRecord extends Model
{
    use HasFactory;

    protected $table = 'kitchen_records';

    protected $fillable = [
        'date',
        'breakfast_count',
        'lunch_count',
        'dinner_count',
        'description',
    ];
}
