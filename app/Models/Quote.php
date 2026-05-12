<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'quote_number',
        'vrm',
        'customer_id',
        'vehicle_id',
        'website_id',
        'email_template_id',
        'quote_amount',
        'mileage',
        'guarantee',
        'delivery_time',
        'offer_type',
        'status',
        'quote_type',
        'notes',
        'email_count',
        'no_answer',
        'created_by',
        'updated_by',
        'accepted_by',
        'accepted_at',
        'archived_by',
        'archived_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function quoteNotes()
    {
        return $this->hasMany(QuoteNote::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(QuoteEmailLog::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function acceptedByUser()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function archivedByUser()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
