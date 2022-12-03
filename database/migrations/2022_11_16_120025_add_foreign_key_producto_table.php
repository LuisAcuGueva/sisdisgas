<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyProductoTable extends Migration
{
    protected $table = "producto";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->integer('unidadmedida_id')->unsigned()->nullable();
            $table->foreign('unidadmedida_id')
                 ->references('id')
                 ->on('unidad_medida')
                 ->onDelete('restrict')
                 ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('producto_unidadmedida_id_foreign');
    }
}
