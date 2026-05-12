<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as Audit;

class Bank extends Model implements Auditable
{
    use Audit;

    protected $fillable = [
        'website_id',
        'name',
        'account_title',
        'account_number',
        'branch_name',
        'sort_code',
        'is_vat',
        'vat',
        'active',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
