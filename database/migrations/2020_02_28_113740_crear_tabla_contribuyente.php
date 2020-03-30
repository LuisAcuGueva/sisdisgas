<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaContribuyente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribuyente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ruc',11)->nullable();
            $table->string('contribuyente')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->integer('ditrito_id')->unsigned();
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
        Schema::dropIfExists('contribuyente');
    }
}
