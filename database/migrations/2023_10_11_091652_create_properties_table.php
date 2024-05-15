<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('uniqid', 255)->nullable();
            $table->string('item_code', 25)->nullable();
            $table->string('name');
            $table->string('slug', 255)->unique();
            $table->integer('category_id');
            $table->text('excerpt')->nullable();
            $table->blob('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('price_sale', 10, 2)->nullable();
            $table->decimal('price_nightly', 10, 2)->nullable();
            $table->decimal('price_weekly', 10, 2)->nullable();
            $table->decimal('price_two_weekly', 10, 2)->nullable();
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->string('catchwords', 255)->nullable();
            $table->integer('min_stay')->default(1);
            $table->integer('bedrooms')->nullable();
            $table->float('bathrooms')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('parkings')->nullable();
            $table->tinyInteger('is_child_friendly')->default(0);
            $table->tinyInteger('is_pets_allowed')->default(0);
            $table->string('address', 255)->nullable();
            $table->integer('location_id')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->float('first_payment_percentage')->default(100);
            $table->float('second_payment_percentage')->nullable();
            $table->float('third_payment_percentage')->nullable();
            $table->integer('first_payment_days')->default(1);
            $table->integer('second_payment_days')->default(1);
            $table->integer('third_payment_days')->default(1);
            $table->string('image', 255)->nullable();
            $table->integer('image_id')->default(0);
            $table->integer('display_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_new')->default(0);
            $table->tinyInteger('is_vacation')->default(0);
            $table->tinyInteger('is_monthly')->default(0);
            $table->tinyInteger('is_sale')->default(0);
            $table->tinyInteger('is_sale_policies')->nullable();
            $table->longText('sale_policies')->nullable();
            $table->tinyInteger('is_rental_policies')->nullable();
            $table->longText('rental_policies')->nullable();
            $table->blob('admin_notes')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
