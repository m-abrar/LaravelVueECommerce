<?php

namespace App\Http\Controllers\Admin;

use App\Models\LineItem;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LineItemsController extends Controller
{
    public function index()
    {
        return LineItem::all();
    }

    
    public function getAllMedia($line_item_id)
    {
        $lineItem = LineItem::findOrFail($line_item_id);

        $featuredMediaFile = $lineItem->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            // Check if $featuredMediaFile is not null
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        // Retrieve all media for the line item
        $mediaFiles = $lineItem->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($line_item_id, $media_id)
    {
        $lineItem = LineItem::findOrFail($line_item_id);

        $mediaIds = $lineItem->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $lineItem->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $lineItem->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($line_item_id, $media_id)
    {
        $lineItem = LineItem::findOrFail($line_item_id);

        $existingMedia = $lineItem->mediaFiles()->find($media_id);

        if ($existingMedia) {
            // The media_id is already attached, so detach it
            $response = $lineItem->mediaFiles()->detach($media_id, ['model_type' => get_class($lineItem)]);
            return 'removed';
        } else {
            // The media_id is not attached, so attach it
            $response = $lineItem->mediaFiles()->attach($media_id, ['model_type' => get_class($lineItem)]);
            return 'attached';
        }
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        LineItem::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        return LineItem::findOrFail($id);
    }

    public function update(Request $request, LineItem $lineitems)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $lineitems->update([
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
            LineItem::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(LineItems $lineitems)
    {
        $lineitems->delete();

        return response()->json(['success' => true], 200);
    }
}
