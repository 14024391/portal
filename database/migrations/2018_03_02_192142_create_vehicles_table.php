<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('category')->nullable();
            $table->integer('subcategory')->nullable();
            $table->integer('make');
            $table->integer('model')->nullable();
            $table->string('derivative')->nullable();
            $table->string('soldStatus')->default('In Stock');
            $table->tinyInteger('autoTraderWebYes')->default('1');
            $table->tinyInteger('myWebYes')->default('1');
            $table->tinyInteger('featured')->default('0');
            $table->tinyInteger('latest')->default('0');
            $table->foreign('type_id')->references('id')->on('types');
            $table->timestamps();
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
        Schema::dropIfExists('vehicles');
    }
}
