<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Properties;
use App\Models\PropertyLineItem;
use App\Models\PropertyService;
use App\Models\PropertyNeighbour;
use App\Models\PropertyRoom;
use App\Models\PropertyPrice;
use Illuminate\Http\Request;
    use App\Models\Locations;
    use App\Models\MediaManager;

class PropertiesController extends Controller
{
    public function index()
    {
        return Properties::query()
        ->with('type')
        ->when(request('type'), function ($query) {
            return $query->where('property_type_id', request('type'));
        })
            ->latest()
            ->paginate();
    }

    public function getAllMedia($property_id)
    {
        $property = Properties::findOrFail($property_id);

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
        $property = Properties::findOrFail($property_id);

        $mediaIds = $property->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $property->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $property->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($property_id, $media_id)
    {
        $property = Properties::findOrFail($property_id);

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

    public function store(Request $request, Properties $properties)
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
        $properties->create($request->except(['amenities','features','lineitems']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));


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

    public function edit(Properties $properties)
    {
        $properties->load('services')->load('lineitems')->load('neighbours')->load('rooms')->load('prices')->load('bookings');
        $properties['associated_amenities'] = $properties->amenities->pluck('id');
        $properties['associated_features'] = $properties->features->pluck('id');
        $properties['associated_categories'] = $properties->categories->pluck('id');

        return $properties;
    }

    public function update(Request $request, Properties $properties)
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
        $properties->update($request->except(['amenities','features','categories']));

        // Use the sync method to update the selected amenities
        $properties->amenities()->sync($request->input('amenities', []));
        // Use the sync method to update the selected features
        $properties->features()->sync($request->input('features', []));
        // Use the sync method to update the selected features
        $properties->categories()->sync($request->input('categories', []));


        return response()->json(['success' => true]);
    }


    public function destroy(Properties $properties)
    {
        $properties->delete();

        return response()->json(['success' => true], 200);
    }
}
