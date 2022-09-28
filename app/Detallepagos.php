<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;

class Detallepagos extends Model
{
   use SoftDeletes;
   protected $table = 'detalle_pagos';   
   protected $date = 'delete_at';

   public function pedido(){
      return $this->belongsTo('App\Movimiento', 'pedido_id');
   }

   public function metodo_pago(){
      return $this->belongsTo('App\Metodopago', 'metodo_pago_id');
   }
}
