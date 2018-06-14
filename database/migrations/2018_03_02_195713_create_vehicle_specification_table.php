<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->string('colour')->nullable();
            $table->integer('externalHeight')->nullable();
            $table->integer('externalLength')->nullable();
            $table->string('externalUnit')->nullable();
            $table->integer('externalWidth')->nullable();
            $table->integer('grossWeight')->nullable();
            $table->integer('internalHeight')->nullable();
            $table->integer('internalLength')->nullable();
            $table->string('internalUnit')->nullable();
            $table->integer('internalWidth')->nullable();
            $table->integer('liftingCapacity')->nullable();
            $table->string('liftingCapacityUnit')->nullable();
            $table->integer('maxPayload')->nullable();
            $table->integer('maximumHeight')->nullable();
            $table->string('maximumHeightUnit')->nullable();
            $table->integer('maximumReach')->nullable();
            $table->string('maximumReachUnit')->nullable();
            $table->integer('numberOfDoors')->nullable();
            $table->integer('numberOfSeats')->nullable();
            $table->string('operatingType')->nullable();
            $table->integer('operatingWidth')->nullable();
            $table->string('operatingWidthUnit')->nullable();
            $table->string('payloadUnit')->nullable();
            $table->integer('speed')->nullable();
            $table->string('speedUnit')->nullable();
            $table->string('trailerAxis')->nullable();
            $table->integer('trailerWeight')->nullable();
            $table->string('trailerWeightUnit')->nullable();
            $table->integer('volume')->nullable();
            $table->string('volumeUnit')->nullable();
            $table->string('weightUnit')->nullable();
            $table->integer('wheelbase')->nullable();
            $table->string('wheelbaseType')->nullable();
            $table->string('wheelbaseUnit')->nullable();
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
        Schema::dropIfExists('vehicle_specifications');
    }
}
