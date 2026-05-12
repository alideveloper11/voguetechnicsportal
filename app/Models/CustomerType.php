<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as Audit;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerType extends Model implements Auditable
{
    use Audit, SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
