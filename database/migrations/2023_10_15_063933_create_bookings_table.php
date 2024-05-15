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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('uniqid', 25)->nullable();
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->string('pets')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('customer_profile_id', 50)->nullable();
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->integer('product_id');
            $table->float('lodging_amount');
            $table->text('sub_total_detail')->nullable();
            $table->float('total_amount')->nullable();
            $table->string('status', 25)->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('created_by', 255)->nullable();
            $table->integer('housekeeper_id')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('bookings');
    }
};
