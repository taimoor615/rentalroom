<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id', 'user_id', 'start_date', 'end_date',
        'status', 'total_price', 'guests', 'notes', 'stripe_payment_intent_id','booked_by', 'guest_name', 'guest_email', 'guest_phone'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getDaysAttribute()
    {
        return Carbon::parse($this->start_date)->diffInDays($this->end_date);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function isProxyBooking()
    {
        return !is_null($this->booked_by);
    }

    public function getGuestDisplayNameAttribute()
    {
        return $this->isProxyBooking() ? $this->guest_name : $this->user->name;
    }
}
