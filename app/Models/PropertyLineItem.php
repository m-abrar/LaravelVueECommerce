<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyLineItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'image',
    // ];

    protected $guarded = [];

    // protected $appends = ['formatted_start_time', 'formatted_end_time'];

    protected $casts = [
    //     'start_time' => 'datetime',
    //     'end_time' => 'datetime',
    //     'status' => AppointmentStatus::class,
    ];

    // public function client(): BelongsTo
    // {
    //     return $this->belongsTo(Client::class);
    // }

    // public function formattedStartTime(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->start_time->format('Y-m-d h:i A'),
    //     );
    // }

    // public function formattedEndTime(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->end_time->format('Y-m-d h:i A'),
    //     );
    // }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset(Storage::url($value) ?? 'noimage.png'),
        );
    }

    public function property()
    {
        return $this->belongsTo(Properties::class, 'property_id');
    }

}
