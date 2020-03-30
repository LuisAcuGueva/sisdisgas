<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('precio',10,2);
            $table->integer('cantidad');
            $table->integer('venta_id')->unsigned();
            $table->integer('servicio_id')->unsigned();
            $table->foreign('venta_id')->references('id')->on('venta')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('servicio_id')->references('id')->on('servicio')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('detalle_venta');
    }
}
