<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetalleMovAlmacen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_mov_almacen', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('precio',10,2);
            $table->integer('cantidad');
            $table->integer('movimiento_id')->unsigned();
            $table->integer('producto_id')->unsigned();
            $table->integer('lote_id')->unsigned();
            $table->foreign('movimiento_id')->references('id')->on('movimiento')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('producto_id')->references('id')->on('producto')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_mov_almacen');
    }
}
