<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('delivery_fee',8,2)->default(0);
            $table->double('delivery_range',8,2)->default(0);
            $table->string('tax')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->double('commission',8,2)->default(0);
            $table->boolean('pickup')->default(true);
            $table->boolean('delivery')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('charge_per_km')->default(false);
            $table->boolean('is_open')->default(true);
            $table->boolean('is_package_vendor')->default(false);
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
        Schema::dropIfExists('vendors');
    }
}
