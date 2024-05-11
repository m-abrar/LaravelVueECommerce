<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\MediaManager;
use App\Traits\MediaFile;

class Locations extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, MediaFile;

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


    protected $casts = [];


    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset(Storage::url($value) ?? 'noimage.png'),
        );
    }

    public function properties()
    {
        return $this->belongsToMany(Properties::class, 'property_feature_pivot', 'feature_id', 'property_id');
    }

}
