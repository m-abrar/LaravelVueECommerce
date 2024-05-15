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
        // \App\Models\Products::factory(10)->create();
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


        // $products = \App\Models\Products::all();
        // $attributes = \App\Models\Attributes::all();

        // // Loop through products and associate them with random attributes
        // $products->each(function ($product) use ($attributes) {
        //     // Randomly select a subset of attributes to associate with the product
        //     $associatedAttributes = $attributes->random(rand(1, 5)); // Associate with 1 to 5 random attributes

        //     // Sync the product's attributes, replacing existing associations
        //     $product->attributes()->sync($associatedAttributes);
        // });


    }
}
