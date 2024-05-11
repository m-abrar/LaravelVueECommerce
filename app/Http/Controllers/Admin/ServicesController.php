<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    public function index()
    {
        return Service::all();
    }


    public function getAllMedia($service_id)
    {
        $service = Service::findOrFail($service_id);

        $featuredMediaFile = $service->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            // Check if $featuredMediaFile is not null
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        // Retrieve all media for the service
        $mediaFiles = $service->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($service_id, $media_id)
    {
        $service = Service::findOrFail($service_id);

        $mediaIds = $service->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $service->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $service->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($service_id, $media_id)
    {
        $service = Service::findOrFail($service_id);

        $existingMedia = $service->mediaFiles()->find($media_id);

        if ($existingMedia) {
            // The media_id is already attached, so detach it
            $response = $service->mediaFiles()->detach($media_id, ['model_type' => get_class($service)]);
            return 'removed';
        } else {
            // The media_id is not attached, so attach it
            $response = $service->mediaFiles()->attach($media_id, ['model_type' => get_class($service)]);
            return 'attached';
        }
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Service::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        return Service::findOrFail($id);
    }

    public function update(Request $request, Service $services)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $services->update([
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
            Service::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Services $services)
    {
        $services->delete();

        return response()->json(['success' => true], 200);
    }
}
