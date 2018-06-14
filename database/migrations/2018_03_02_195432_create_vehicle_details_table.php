<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->string('axleDrive')->nullable();
            $table->string('bodyType')->nullable();
            $table->string('cabType')->nullable();
            $table->boolean('isDriverLeftHandSide')->nullable();
            $table->integer('manufacturerYear')->nullable();
            $table->integer('mileage')->nullable();
            $table->string('mileageUnit',10)->nullable();
            $table->string('hoursUsed')->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_details');
    }
}
