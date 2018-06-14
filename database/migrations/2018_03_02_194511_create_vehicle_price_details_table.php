<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclePriceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_price_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->integer('saleprice')->nullable();
            $table->char('priceCurrency',3)->nullable();
            $table->string('poa')->nullable();
            $table->string('vat')->nullable();
            $table->integer('leasePrice')->nullable();
            $table->char('leaseCurrency',3)->nullable();
            $table->string('soldStatus')->default('InStock')->nullable();
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
        Schema::dropIfExists('vehicle_price_details');
    }
}
