<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as Audit;

class Website extends Model implements Auditable
{
    use Audit;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'email',
        'phone',
        'landline',
        'address',
        'logo',
        'status',
    ];

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }
}
