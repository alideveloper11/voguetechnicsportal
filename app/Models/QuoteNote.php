<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteNote extends Model
{
    protected $fillable = [
        'quote_id',
        'note',
        'created_by',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
