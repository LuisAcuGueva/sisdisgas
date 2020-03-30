<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serie_numero',15);
            $table->timestamp('fecha');
            $table->decimal('total',10,2);
            $table->decimal('subtotal',10,2);
            $table->decimal('igv',10,2);
            $table->integer('tipo_pago');
            $table->integer('estado');
            $table->integer('cliente_id')->unsigned();
            $table->integer('trabajador_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('personamaestro')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('trabajador_id')->references('id')->on('personamaestro')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('venta');
    }
}
