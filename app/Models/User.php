<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, Billable, InteractsWithMedia;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'bio'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isHost()
    {
        return strtolower($this->role) === 'host';
    }

    public function isGuest()
    {
        return $this->role === 'guest';
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'host_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    public function adminBookings()
    {
        return $this->hasMany(Booking::class, 'booked_by');
    }
}
