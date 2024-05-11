<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Properties::factory(10)->create();
        // \App\Models\Amenities::factory(10)->create();
        // \App\Models\Features::factory(10)->create();
        // \App\Models\Service::factory(10)->create();
        // \App\Models\LineItem::factory(10)->create();
        \App\Models\Locations::factory(10)->create();
        // \App\Models\Appointment::factory(10)->create();
        // \App\Models\Client::factory(10)->create(); //applied

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        // $properties = \App\Models\Properties::all();
        // $amenities = \App\Models\Amenities::all();

        // // Loop through properties and associate them with random amenities
        // $properties->each(function ($property) use ($amenities) {
        //     // Randomly select a subset of amenities to associate with the property
        //     $associatedAmenities = $amenities->random(rand(1, 5)); // Associate with 1 to 5 random amenities

        //     // Sync the property's amenities, replacing existing associations
        //     $property->amenities()->sync($associatedAmenities);
        // });


    }
}
