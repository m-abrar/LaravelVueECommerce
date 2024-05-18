<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\MediaFile;

class AttributeValues extends Model
{
    use HasFactory, MediaFile;

    protected $guarded = [];

    protected $casts = [];

    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_value_pivot', 'attribute_value_id', 'product_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attributes::class, 'attribute_id');
    }
}
