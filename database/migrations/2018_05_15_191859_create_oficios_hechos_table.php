<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOficiosHechosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oficios_hechos', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('html');
            $table->string('token');
            $table->string('fiscal');
            $table->integer('idOficio')->unsigned();
            $table->timestamps();
            $table->foreign('idOficio')->references('id')->on('oficios')->onDelete('restrict');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oficios_hechos');
    }
}
