<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;

class Detalleprestamo extends Model
{
   use SoftDeletes;
   protected $table = 'detalle_prestamo';   
   protected $date = 'delete_at';

   public function detallemovimiento()
   {
      return $this->belongsTo('App\Detallemovalmacen', 'detalle_mov_almacen_id');
   }
}
