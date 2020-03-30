<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAccionInductiva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accion_inductiva', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo')->nullable();
            $table->string('des_tipo')->nullable();
            $table->string('numero',12)->nullable();
            $table->string('metodo')->nullable();
            $table->string('analisis')->nullable();
            $table->string('carga')->nullable();
            $table->string('estado')->nullable();
            $table->string('des_estado')->nullable();
            $table->date('fecha_generacion')->nullable();
            $table->date('fecha_notificacion')->nullable();
            $table->date('fecha_cita')->nullable()->nullable();
            $table->string('hora_cita')->nullable();
            $table->string('verificador')->nullable();  //tabla person
            $table->string('supervisor')->nullable();   //tabla person
            $table->date('plazo_lineamiento')->nullable();
            $table->date('plazo_tiempo_limite_tolerancia')->nullable();
            $table->string('detalles_resultado')->nullable();
            $table->string('observaciones')->nullable();
            $table->integer('dif_dias_cita_notificacion')->nullable();
            $table->string('asistencia_invitacion',1)->nullable();
            $table->string('regularizacion',1)->nullable();
            $table->string('levanta_incosistencia',1)->nullable();
            $table->string('realiza_pago',1)->nullable();
            $table->string('dias_reiterativo',1)->nullable();
            $table->date('fecha_reiterativo',1)->nullable();
            $table->date('plazo_lineamiento2')->nullable();
            $table->integer('carga_id')->unsigned();
            $table->foreign('carga_id')->references('id')->on('carga')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('accion_inductiva');
    }
}
