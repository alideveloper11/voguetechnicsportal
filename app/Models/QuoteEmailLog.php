<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteEmailLog extends Model
{
    protected $fillable = [
        'quote_id',
        'recipient_email',
        'subject',
        'body',
        'sent_by',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
