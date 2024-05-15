<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MediaManager;
use App\Models\Locations;

class MediaController extends Controller
{
    public function index()
    {
        // Retrieve all media records
        $media = MediaManager::latest()->get();

        // Append the URL for each media file
        $media->each(function ($mediaItem) {
            $mediaItem->url = $mediaItem->getUrl();
        });

        // Return the collection as JSON
        return response()->json($media);
    }

    public function edit($id)
    {

        return $mediaFile = MediaManager::find($id);
        

    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $mediaFile = MediaManager::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'file_name' => $request->file('file')->hashName(),
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => $request->file('file')->getSize(),
            // Add custom_products as needed
            'model_id' => 0, // Adjust the model_id as needed
            'model_type' => 'App\Models\MediaManager', // Adjust the model_type as needed
        ]);

        // Associate the uploaded file with the 'images' collection
        $mediaFile->addMedia($request->file('file'))->toMediaCollection('images');

        return response()->json(['success' => true, 'message' => 'Media uploaded successfully']);
    }

    public function destroy(MediaManager $media)
    {
        $media->delete();
        return response()->json(['message' => 'Media deleted successfully']);
    }
}
