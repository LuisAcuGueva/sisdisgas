<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKardex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha');
            $table->integer('cantidad');
            $table->string('tipo',1);
            $table->decimal('precio_compra',10,2);
            $table->decimal('precio_venta',10,2);
            $table->integer('stock_anterior');
            $table->integer('stocl_actual');
            $table->integer('almacen_id')->unsigned();
            $table->integer('lote_id')->unsigned();
            $table->integer('detalle_mov_almacen_id')->unsigned();
            $table->foreign('almacen_id')->references('id')->on('almacen')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('detalle_mov_almacen_id')->references('id')->on('detalle_mov_almacen')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('kardex');
    }
}
