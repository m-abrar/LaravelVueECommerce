<?php

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Attribute;

class PropertyAttributeTableSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();
        $attributes = Attribute::all();

        // // Loop through properties and associate them with random attributes
        // $properties->each(function ($property) use ($attributes) {
        //     // Randomly select a subset of attributes to associate with the property
        //     $associatedAttributes = $attributes->random(rand(1, 5)); // Associate with 1 to 5 random attributes

        //     // Sync the property's attributes, replacing existing associations
        //     $property->attributes()->sync($associatedAttributes);
        // });
    }
}
