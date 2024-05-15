<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\MediaFile;

class Booking extends Model
{
    use HasFactory, MediaFile;

    protected $guarded = [];

    // protected $appends = ['formatted_start_time', 'formatted_end_time'];

    protected $casts = [
        // 'start_time' => 'datetime',
        // 'end_time' => 'datetime',
        // 'status' => AppointmentStatus::class,
    ];

    // public function client(): BelongsTo
    // {
    //     return $this->belongsTo(Client::class);
    // }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('Y-m-d h:i A'),
        );
    }

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_at->format('Y-m-d h:i A'),
        );
    }

    public function lineitems()
    {
        return $this->hasMany(BookingLineItem::class);
    }

    public function services()
    {
        return $this->hasMany(BookingService::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

}
