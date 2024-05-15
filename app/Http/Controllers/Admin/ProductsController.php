<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\PropertyLineItem;
use App\Models\Productservice;
use App\Models\PropertyNeighbour;
use App\Models\PropertyRoom;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;
    use App\Models\Locations;
    use App\Models\MediaManager;

class ProductsController extends Controller
{
    public function index()
    {
        return Products::query()
        ->with('type')
        ->when(request('type'), function ($query) {
            return $query->where('property_type_id', request('type'));
        })
            ->latest()
            ->paginate();
    }

    public function getAllMedia($property_id)
    {
        $property = Products::findOrFail($property_id);

        $featuredMediaFile = $property->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();

        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $property->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($property_id, $media_id)
    {
        $property = Products::findOrFail($property_id);

        $mediaIds = $property->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $property->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $property->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($property_id, $media_id)
    {
        $property = Products::findOrFail($property_id);

        $existingMedia = $property->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $response = $property->mediaFiles()->detach($media_id, ['model_type' => get_class($property)]);
            return 'removed';
        } else {
            $response = $property->mediaFiles()->attach($media_id, ['model_type' => get_class($property)]);
            return 'attached';
        }
    }



    public function test($id)
    {
        $ids = [8,9,10];
        
        // $mediaFiles = MediaFile::find($ids);

        $location = Locations::findOrFail($id);
        $response = $location->mediaFiles()->detach($ids, ['model_type' => get_class($location)]);
        $response = $location->mediaFiles()->attach($ids, ['model_type' => get_class($location), 'is_featured' => true]);

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

    public function store(Request $request, Products $products)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'property_type_id.required' => 'The category field is required.',
        ]);
        // Update the model with all form fields
        $products->create($request->except(['attributes','features','lineitems']));

        // Use the sync method to update the selected attributes
        $products->attributes()->sync($request->input('attributes', []));
        // Use the sync method to update the selected features
        $products->features()->sync($request->input('features', []));


        // Handle lineitems if provided
        if (isset($request['lineitems'])) {
            // Create and associate line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new PropertyLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }

            $property->lineitems()->saveMany($lineItems);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Products $products)
    {
        $products->load('services')->load('lineitems')->load('neighbours')->load('rooms')->load('prices')->load('bookings');
        $products['associated_attributes'] = $products->attributes->pluck('id');
        $products['associated_features'] = $products->features->pluck('id');
        $products['associated_categories'] = $products->categories->pluck('id');

        return $products;
    }

    public function update(Request $request, Products $products)
    {
        // Define validation rules for specific fields
        $validationRules = [
            'name' => 'required',
            'item_code' => 'required',
            'slug' => 'required',
            'property_type_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'property_type_id.required' => 'The category field is required.',
        ]);
        // Update the model with all form fields
        $products->update($request->except(['attributes','features','categories']));

        // Use the sync method to update the selected attributes
        $products->attributes()->sync($request->input('attributes', []));
        // Use the sync method to update the selected features
        $products->features()->sync($request->input('features', []));
        // Use the sync method to update the selected features
        $products->categories()->sync($request->input('categories', []));


        return response()->json(['success' => true]);
    }


    public function destroy(Products $products)
    {
        $products->delete();

        return response()->json(['success' => true], 200);
    }
}
