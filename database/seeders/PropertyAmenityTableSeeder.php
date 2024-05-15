<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Attribute;

class ProductAttributeTableSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $attributes = Attribute::all();

        // // Loop through products and associate them with random attributes
        // $products->each(function ($product) use ($attributes) {
        //     // Randomly select a subset of attributes to associate with the product
        //     $associatedAttributes = $attributes->random(rand(1, 5)); // Associate with 1 to 5 random attributes

        //     // Sync the product's attributes, replacing existing associations
        //     $product->attributes()->sync($associatedAttributes);
        // });
    }
}
