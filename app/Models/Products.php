<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\MediaFile;

class Products extends Model
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

    public function type()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attributes::class, 'category_attribute_pivot', 'category_id', 'attribute_id');
    }

    public function features()
    {
        return $this->belongsToMany(Features::class, 'category_feature_pivot', 'category_id', 'feature_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_category_pivot', 'category_id', 'category_id');
    }

    public function lineitems()
    {
        return $this->hasMany(CategoryLineItem::class, 'category_id');
    }

    public function services()
    {
        return $this->hasMany(CategoryService::class, 'category_id');
    }

    public function neighbours()
    {
        return $this->hasMany(CategoryNeighbour::class, 'category_id');
    }

    public function rooms()
    {
        return $this->hasMany(CategoryRoom::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(CategoryPrice::class, 'category_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'category_id');
    }

}
