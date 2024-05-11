<?php
namespace App\Traits;

use App\Models\MediaManager;

trait MediaFile
{
    public function mediaFiles()
    {
        
        // Get the class name of the model that uses this trait
        $modelClass = get_class($this);
        
        return $this->belongsToMany(MediaManager::class, 'media_pivot', 'model_id', 'media_id')
            ->wherePivot('model_type', $modelClass);
    }

    public function featuredMediaFileURL()
    {
        $modelClass = get_class($this);

        $mediaFile = $this->belongsToMany(MediaManager::class, 'media_pivot', 'model_id', 'media_id')
            ->wherePivot('is_featured', true)
            ->wherePivot('model_type', $modelClass)
            ->latest()
            ->first();
        if ($mediaFile) {
            return $mediaFile->getUrl();
        }

        return null;
    }


    public function mediaFileURL($id)
    {
        $mediaFile = MediaManager::find($id); // Replace with your actual method of retrieving the media file

        if ($mediaFile) {
            return $mediaFile->getUrl();
        }

        return null;
    }

}
