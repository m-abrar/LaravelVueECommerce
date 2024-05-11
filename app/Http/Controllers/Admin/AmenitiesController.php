<?php

namespace App\Http\Controllers\Admin;

use App\Models\Amenities;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenitiesController extends Controller
{
    public function index()
    {
        return Amenities::all();
    }


    public function getAllMedia($amenity_id)
    {
        $amenity = Amenities::findOrFail($amenity_id);

        $featuredMediaFile = $amenity->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $amenity->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($amenity_id, $media_id)
    {
        $amenity = Amenities::findOrFail($amenity_id);

        $mediaIds = $amenity->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $amenity->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $amenity->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($amenity_id, $media_id)
    {
        $amenity = Amenities::findOrFail($amenity_id);

        $existingMedia = $amenity->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $response = $amenity->mediaFiles()->detach($media_id, ['model_type' => get_class($amenity)]);
            return 'removed';
        } else {
            $response = $amenity->mediaFiles()->attach($media_id, ['model_type' => get_class($amenity)]);
            return 'attached';
        }
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Amenities::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit(Amenities $amenities)
    {
        return $amenities;
    }

    public function update(Request $request, Amenities $amenities)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $amenities->update([
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
            Amenities::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Amenities $amenities)
    {
        $amenities->delete();

        return response()->json(['success' => true], 200);
    }
}
