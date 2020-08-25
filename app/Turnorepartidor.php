<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\DB;

class Turnorepartidor extends Model
{
   use SoftDeletes;
   protected $table = 'turno_repartidor';   
   protected $date = 'delete_at';
   
   public function scopeturnoscompletados($query, $trabajador_id, $fechainicio, $fechafin)
   {
     return $query->where(function($subquery) use($trabajador_id, $fechainicio, $fechafin)
                 {
                    if (!is_null($trabajador_id)) {
                    $subquery->where('trabajador_id', $trabajador_id);
                    }
                    if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(inicio,date)'),[$fechainicio,$fechafin]);
		            	}
              })
               ->where('estado','C')
               ->orderBy('id','DESC')->orderBy('inicio', 'DESC');
  }

   public function person(){
      return $this->belongsTo('App\Person', 'trabajador_id');
   }

}
