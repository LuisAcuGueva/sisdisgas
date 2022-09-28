<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaDetallePagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('monto',10,2);
            $table->string('tipo',1); //* R -> repartidor --- S -> sucursal
            $table->integer('credito');
            $table->integer('pedido_id')->unsigned();
            $table->foreign('pedido_id')->references('id')->on('movimiento')->onDelete('restrict')->onUpdate('restrict');
            $table->integer('metodo_pago_id')->unsigned();
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pagos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('detalle_pagos');
    }
}
