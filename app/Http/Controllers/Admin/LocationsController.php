<?php

namespace App\Http\Controllers\Admin;

use App\Models\Locations;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MediaManager;

class LocationsController extends Controller
{
    
    
    
    public function getAllMedia($location_id)
    {
        $location = Locations::findOrFail($location_id);

        $featuredMediaFile = $location->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            // Check if $featuredMediaFile is not null
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        // Retrieve all media for the location
        $mediaFiles = $location->mediaFiles()->orderBy('display_order', 'ASC')->get();

        // Iterate through media files and set URLs
        $mediaFiles->each(function ($mediaItem) {
            $mediaItem->url = $mediaItem->getUrl();
        });

        $attachmentIDs = $mediaFiles->map->id;

        return [
            'featuredMediaFile' => $featuredMediaFile,
            'mediaFiles' => $mediaFiles,
            'attachmentIDs' => $attachmentIDs,
        ];
    }

    public function featuredUpdate($location_id, $media_id)
    {
        $location = Locations::findOrFail($location_id);

        $mediaIds = $location->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $location->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $location->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($location_id, $media_id)
    {
        $location = Locations::findOrFail($location_id);

        $existingMedia = $location->mediaFiles()->find($media_id);

        if ($existingMedia) {
            // The media_id is already attached, so detach it
            $response = $location->mediaFiles()->detach($media_id, ['model_type' => get_class($location)]);
            return 'removed';
        } else {
            // The media_id is not attached, so attach it
            $response = $location->mediaFiles()->attach($media_id, ['model_type' => get_class($location)]);
            return 'attached';
        }
    }

    
    
    
    
    
    
    
    
    public function media($id)
    {
        // TEST
        $ids = [8,9,10];
        
        // $mediaFiles = MediaFile::find($ids);

        $location = Locations::findOrFail($id);
        // $response = $location->mediaFiles()->detach($ids, ['model_type' => get_class($location)]);
        // $response = $location->mediaFiles()->attach($ids, ['model_type' => get_class($location), 'is_featured' => true]);

        $mediaFiles = $location->mediaFile; //Trait Function



        $mediaFiles = $location->mediaFiles()
                            ->wherePivot('is_featured', true)
                            ->get();


        echo $mediaFilesFeaturedURL = $location->featuredMediaFileURL();
        echo 'featured<br/>';


        foreach ($mediaFiles as $mediaItem) {
            $url = $location->mediaFileURL($mediaItem->id);
            echo $url . '<br>';
        }
        dd($mediaFiles);

    }

    public function index()
    {
        return Locations::all();
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Locations::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        return Locations::findOrFail($id);
    }

    public function update(Request $request, Locations $locations)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $locations->update([
            'name' => $validated['name'],
            'description' => $request->description,
        ]);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);

        if ($request->file('image')) {
            $link = Storage::disk('public')->put('/images', $request->file('image'));

            // // $file = $request->file('image');
            // // $filename = $file->getClientOriginalName(); // Use the original filename or generate a unique one
            // Store the image in the 'public' disk under the 'images' directory
            // // Storage::disk('public')->put('images/' . $filename, file_get_contents($file));
            // You can save the image file path in your database if needed
            Locations::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Locations $locations)
    {
        $locations->delete();

        return response()->json(['success' => true], 200);
    }
}
