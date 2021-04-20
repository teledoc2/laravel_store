<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTypePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_type_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('package_type_id')->constrained();
            $table->integer('max_booking_days')->default(7);
            $table->double('size_price', 8, 2)->default(0.00);
            $table->boolean('price_per_kg')->default(false);
            $table->double('distance_price', 8, 2)->default(0.00);
            $table->boolean('price_per_km')->default(true);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('package_type_pricings');
    }
}
