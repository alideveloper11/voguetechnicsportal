<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vrm',
        'make',
        'model',
        'year',
        'vin',
        'fuel_type',
        'engine_size',
        'engine_code',
        'engine_number',
        'engine_type',
        'maximum_bhp',
        'color',
        'body_style',
        'body_type',
        'number_of_doors',
        'seat_capacity',
        'wheel_plan',
        'aspiration',
        'transmission',
        'co2_emissions',
        'gearbox_type',
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
