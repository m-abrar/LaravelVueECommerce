<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductLineItem;
use App\Models\Productservice;
use App\Models\ProductNeighbour;
use App\Models\ProductRoom;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
    use App\Models\Locations;
    use App\Models\MediaManager;

class ProductsController extends Controller
{
    public function index()
    {
        return Products::query()
            ->with('category')
            ->when(request('type'), function ($query) {
                return $query->where('category_id', request('type'));
            })
            ->orderBy('sort_order') // Add this line to order by sort_order
            ->latest()
            ->paginate();
    }


    public function getAllMedia($product_id)
    {
        $product = Products::findOrFail($product_id);

        $featuredMediaFile = $product->mediaFiles()
            ->wherePivot('is_featured', true)
            ->latest()
            ->first();

        if ($featuredMediaFile) {
            $featuredMediaFile->url = $featuredMediaFile->getUrl();
        }

        $mediaFiles = $product->mediaFiles()->orderBy('display_order', 'ASC')->get();

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

    public function featuredUpdate($product_id, $media_id)
    {
        $product = Products::findOrFail($product_id);

        $mediaIds = $product->mediaFiles()->pluck('media_id')->toArray();

        foreach ($mediaIds as $mediaId) {
            $product->mediaFiles()->updateExistingPivot($mediaId, ['is_featured' => false]);
        }

        $response = $product->mediaFiles()->updateExistingPivot($media_id, ['is_featured' => true]);

        return $response;
    }

    public function addOrRemoveMedia($product_id, $media_id)
    {
        $product = Products::findOrFail($product_id);

        $existingMedia = $product->mediaFiles()->find($media_id);

        if ($existingMedia) {
            $response = $product->mediaFiles()->detach($media_id, ['model_type' => get_class($product)]);
            return 'removed';
        } else {
            $response = $product->mediaFiles()->attach($media_id, ['model_type' => get_class($product)]);
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
            // 'category_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            'category_id.required' => 'The category field is required.',
        ]);
        // Update the model with all form fields
        $products->create($request->except(['attributes','features','lineitems']));

        // Use the sync method to update the selected attributes
        $products->attributevalues()->sync($request->input('attributes', []));
        // Use the sync method to update the selected features
        $products->features()->sync($request->input('features', []));


        // Handle lineitems if provided
        if (isset($request['lineitems'])) {
            // Create and associate line items
            $lineItems = [];
            foreach ($request['lineitems'] as $lineitem) {
                $lineItems[] = new ProductLineItem([
                    'name' => $lineitem['name'],
                    'value' => $lineitem['value'],
                    'value_type' => $lineitem['value_type'],
                    'apply_on' => $lineitem['apply_on'],
                    'is_required' => $lineitem['is_required'],
                    'image' => $lineitem['image'],
                    'display_order' => $lineitem['display_order'],
                ]);
            }

            $product->lineitems()->saveMany($lineItems);
        }

        return response()->json(['message' => 'success']);
    }

    public function edit(Products $products)
    {
        // $products->load('services')->load('lineitems')->load('neighbours')->load('rooms')->load('prices')->load('bookings');
        $products['associated_attributevalues'] = $products->attributevalues->pluck('id');
        // $products['associated_features'] = $products->features->pluck('id');
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
            // 'category_id' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
        ];

        // Validate the specific fields
        $request->validate($validationRules, [
            // 'category_id.required' => 'The category field is required.',
        ]);
        // Update the model with all form fields
        $products->update($request->except(['attributevalues','features','categories']));

        // Use the sync method to update the selected attributes
        $products->attributevalues()->sync($request->input('attributevalues', []));
        // Use the sync method to update the selected features
        // $products->features()->sync($request->input('features', []));
        // Use the sync method to update the selected features
        $products->categories()->sync($request->input('categories', []));


        return response()->json(['success' => true]);
    }


    public function destroy(Products $products)
    {
        $products->delete();

        return response()->json(['success' => true], 200);
    }

    public function updateSortOrder(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $index => $id) {
            Products::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'Sort order updated successfully']);
    }
}
