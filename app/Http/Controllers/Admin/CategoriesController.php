<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;
use App\Rules\ExistsOrZero;

class CategoriesController extends Controller
{
    public function index()
    {
        return Categories::with('parent')->with('children')->orderBy('sort_order')->get();
    }

    public function indexParents()
    {
        return Categories::where('parent_id', 0)->orderBy('sort_order')->get();
    }

    public function indexParentsChildren()
    {
        return Categories::where('parent_id', 0)->with('children')->orderBy('sort_order')->get();
    }

    public function getTypesWithCount()
    {
        $types = Categories::all();

        return collect($types)->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'count' => Products::where('category_id', $type->id)->count(),
                'color' => 'success',
            ];
        });
    }

    public function getAllMedia($service_id)
    {
        $service = Categories::findOrFail($service_id);

        $featuredMediaFile = $service->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();
        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $service->mediaFiles()->orderBy('display_order', 'ASC')->get();
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
        $service = Categories::findOrFail($service_id);

        $mediaIds = $service->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $service->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $service->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($service_id, $media_id)
    {
        $service = Categories::findOrFail($service_id);

        $existingMedia = $service->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $service->mediaFiles()->detach($media_id, ['model_type' => get_class($service)]);
            return 'removed';
        } else {
            $service->mediaFiles()->attach($media_id, ['model_type' => get_class($service)]);
            return 'attached';
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories',
            'slug' => 'required|alpha_dash|unique:categories',
            'parent_id' => ['nullable', new ExistsOrZero],
            'description' => 'nullable',
            'alt_text' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['parent_id'] = $validated['parent_id'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = Storage::disk('public')->put('/images', $request->file('image'));
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = Storage::disk('public')->put('/banners', $request->file('banner'));
        }

        $category = Categories::create($validated);

        return response()->json(['message' => 'success']);
    }

    public function edit(Categories $category)
    {
        return $category;
    }

    public function update(Request $request, Categories $category)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'slug' => 'required|alpha_dash|unique:categories,slug,' . $category->id,
            'parent_id' => ['nullable', new ExistsOrZero],
            'description' => 'nullable',
            'alt_text' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['parent_id'] = $validated['parent_id'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = Storage::disk('public')->put('/images', $request->file('image'));
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = Storage::disk('public')->put('/banners', $request->file('banner'));
        }

        $category->update($validated);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('image')) {
            $link = Storage::disk('public')->put('/images', $request->file('image'));
            Categories::where('id', $request->id)->update(['image' => $link]);
            return response()->json(['success' => true, 'image_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function uploadBanner(Request $request)
    {
        $request->validate([
            'banner' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('banner')) {
            $link = Storage::disk('public')->put('/banners', $request->file('banner'));
            Categories::where('id', $request->id)->update(['banner' => $link]);
            return response()->json(['success' => true, 'banner_created' => $link]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }

    public function destroy(Categories $category)
    {
        $category->delete();

        return response()->json(['success' => true], 200);
    }

    public function updateSortOrder(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $index => $id) {
            Categories::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'Sort order updated successfully']);
    }
}
