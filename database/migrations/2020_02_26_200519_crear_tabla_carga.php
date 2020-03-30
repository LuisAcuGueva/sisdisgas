<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCarga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carga', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero')->nullable();
            $table->date('fecha_generacion')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('programa')->nullable();
            $table->string('memorandum')->nullable();
            $table->string('ite')->nullable();
            $table->string('programador')->nullable();
            $table->string('tributo')->nullable();
            $table->string('periodo')->nullable();
            $table->string('num_casos')->nullable();
            $table->string('estado')->nullable();
            $table->string('plazo')->nullable();
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
        Schema::dropIfExists('carga');
    }
}
