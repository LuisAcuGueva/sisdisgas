<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;

class Detalleturnopedido extends Model
{
   use SoftDeletes;
   protected $table = 'detalle_turno_pedido';   
   protected $date = 'delete_at';
   
   /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $turno_id)
    {
        return $query->where(function($subquery) use($turno_id)
		            {
		            	if (!is_null($turno_id) ) {
                        $subquery->where('turno_id', '=', $turno_id);
		            	}
                    })
        			->orderBy('turno_id', 'ASC');
    }

   public function turno(){
      return $this->belongsTo('App\Turnorepartidor', 'turno_id');
   }

   public function pedido(){
      return $this->belongsTo('App\Movimiento', 'pedido_id');
   }
}
