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
        // \App\Models\Attributes::factory(10)->create();
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
        // $attributes = \App\Models\Attributes::all();

        // // Loop through properties and associate them with random attributes
        // $properties->each(function ($property) use ($attributes) {
        //     // Randomly select a subset of attributes to associate with the property
        //     $associatedAttributes = $attributes->random(rand(1, 5)); // Associate with 1 to 5 random attributes

        //     // Sync the property's attributes, replacing existing associations
        //     $property->attributes()->sync($associatedAttributes);
        // });


    }
}
