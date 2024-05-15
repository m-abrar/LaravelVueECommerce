<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaManager extends Model implements HasMedia
{
    protected $table = 'media_manager';
    
    use InteractsWithMedia;

    public function getUrl()
    {
        $mediaItem = $this->getFirstMedia('images'); // Replace with your media collection name
        if ($mediaItem) {
            return $mediaItem->getFullUrl();
        }

        return null; // Handle case where no media item is found
    }
    
    protected $fillable = ['name','file_name','mime_type','size','custom_products','model_id','model_type']; 

}