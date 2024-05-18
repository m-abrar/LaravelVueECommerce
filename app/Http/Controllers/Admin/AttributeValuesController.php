<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValues;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttributeValuesController extends Controller
{
    public function index()
    {
        return AttributeValues::with('attribute')->orderBy('sort_order')->get();
    }

    public function getAllMedia($attribute_id)
    {
        $attribute = AttributeValues::findOrFail($attribute_id);

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
        $attribute = AttributeValues::findOrFail($attribute_id);

        $mediaIds = $attribute->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $attribute->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $attribute->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($attribute_id, $media_id)
    {
        $attribute = AttributeValues::findOrFail($attribute_id);

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
            'attribute_id' => 'required',
        ]);

        AttributeValues::create([
            'attribute_id' => $validated['attribute_id'],
            'name' => $validated['name'],
            'description' => $request->description,
            'image' => $request->image_created,
        ]);
        return response()->json(['message' => 'success']);
    }

    public function edit(AttributeValues $attributevalues)
    {
        return $attributevalues;
    }

    public function update(Request $request, AttributeValues $attributevalues)
    {
        $validated = request()->validate([
            'attribute_id' => 'required',
            'name' => 'required',
        ]);

        $attributevalues->update([
            'attribute_id' => $validated['attribute_id'],
            'name' => $validated['name'],
            'description' => $request->description,
        ]);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('image')) {
            $link = Storage::disk('public')->put('/images', $request->file('image'));

            AttributeValues::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(AttributeValues $attributevalues)
    {
        $attributevalues->delete();

        return response()->json(['success' => true], 200);
    }

    public function updateSortOrder(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $index => $id) {
            AttributeValues::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'Sort order updated successfully']);
    }
}
