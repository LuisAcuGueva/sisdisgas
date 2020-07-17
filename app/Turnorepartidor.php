<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;

class Turnorepartidor extends Model
{
   use SoftDeletes;
   protected $table = 'turno_repartidor';   
   protected $date = 'delete_at';
   
   public function person(){
      return $this->belongsTo('App\Person', 'trabajador_id');
  }

}
