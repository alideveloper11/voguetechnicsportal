<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_type_id',
        'name',
        'email',
        'phone',
        'city',
        'address',
    ];

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
