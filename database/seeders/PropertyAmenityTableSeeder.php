<?php

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Amenity;

class PropertyAmenityTableSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();
        $amenities = Amenity::all();

        // // Loop through properties and associate them with random amenities
        // $properties->each(function ($property) use ($amenities) {
        //     // Randomly select a subset of amenities to associate with the property
        //     $associatedAmenities = $amenities->random(rand(1, 5)); // Associate with 1 to 5 random amenities

        //     // Sync the property's amenities, replacing existing associations
        //     $property->amenities()->sync($associatedAmenities);
        // });
    }
}
