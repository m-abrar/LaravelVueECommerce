<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attributes;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttributesController extends Controller
{
    public function index()
    {
        // return Attributes::all();
        return Attributes::orderBy('sort_order')->get();
    }


    public function getAllMedia($attribute_id)
    {
        $attribute = Attributes::findOrFail($attribute_id);

        $featuredMediaFile = $attribute->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $attribute->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($attribute_id, $media_id)
    {
        $attribute = Attributes::findOrFail($attribute_id);

        $mediaIds = $attribute->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $attribute->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $attribute->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($attribute_id, $media_id)
    {
        $attribute = Attributes::findOrFail($attribute_id);

        $existingMedia = $attribute->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $response = $attribute->mediaFiles()->detach($media_id, ['model_type' => get_class($attribute)]);
            return 'removed';
        } else {
            $response = $attribute->mediaFiles()->attach($media_id, ['model_type' => get_class($attribute)]);
            return 'attached';
        }
    }


    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        Attributes::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit(Attributes $attributes)
    {
        return $attributes;
    }

    public function update(Request $request, Attributes $attributes)
    {
        $validated = request()->validate([
            'name' => 'required',
        ]);

        $attributes->update([
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
            Attributes::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Attributes $attributes)
    {
        $attributes->delete();

        return response()->json(['success' => true], 200);
    }


    public function updateSortOrder(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $index => $id) {
            Attributes::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'Sort order updated successfully']);
    }
}
