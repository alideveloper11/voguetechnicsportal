<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as Audit;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model implements Auditable
{

    use Audit, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'is_active',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
