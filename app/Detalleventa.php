<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;

class Detalleventa extends Model
{
   use SoftDeletes;
   protected $table = 'detalle_venta';   
   protected $date = 'delete_at';

   public function producto(){
		return $this->belongsTo('App\Producto', 'producto_id');
	}
	
}
