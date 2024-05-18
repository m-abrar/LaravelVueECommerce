<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\MediaFile;

class Categories extends Model
{
    use HasFactory, MediaFile;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    // Define the parent relationship
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    // Define the children relationship
    public function children(): HasMany
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }

    // Example of other relationships or attributes
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

    // public function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => asset(Storage::url($value) ?? 'noimage.png'),
    //     );
    // }
}
