<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TurnoRepartidorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turno_repartidor', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('inicio');
            $table->timestamp('fin')->nullable();
            $table->string('estado',1);
            $table->integer('apertura_id')->unsigned();
            $table->integer('vuelto_id')->unsigned();
            $table->integer('trabajador_id')->unsigned();
            $table->foreign('apertura_id')->references('id')->on('movimiento')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('vuelto_id')->references('id')->on('movimiento')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('trabajador_id')->references('id')->on('person')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('turno_repartidor');
    }
}
