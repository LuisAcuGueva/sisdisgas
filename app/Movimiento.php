<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Movimiento extends Model
{
   use SoftDeletes;
   protected $table = 'movimiento';   
   protected $date = 'delete_at';

	/**
	 * Método para listar las opciones de menu
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopelistar($query, $fechainicio, $fechafin, $num_caja, $sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, $tipomovimiento_id)
    {
		return $query->where(function($subquery) use($fechainicio, $fechafin, $num_caja, $aperturaycierre, $maxapertura, $maxcierre)
		            {
						if (!is_null($maxapertura) && !is_null($maxcierre)) {
							if($aperturaycierre == 0){ //apertura y cierre iguales ---- no mostrar nada
								$subquery->Where('num_caja','>=', $maxapertura)->Where('num_caja','<=', $maxcierre);
							}else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura
								$subquery->Where('num_caja','>=', $maxapertura);
							}
						}
						
		            	if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin])->Where('num_caja','LIKE','%'.$num_caja.'%');
		            	}else{
							$subquery->Where('num_caja','LIKE','%'.$num_caja.'%');
						}
					})
					->where('sucursal_id', "=", $sucursal_id)
					->where('tipomovimiento_id', "=", $tipomovimiento_id)
        			->orderBy('num_caja','DESC')->orderBy('fecha', 'DESC');
	}

	public function scopelistarcompras($query, $fechainicio, $fechafin, $sucursal_id ,$proveedor_id)
    {
		return $query->where(function($subquery) use($fechainicio, $fechafin ,$proveedor_id)
		            {
		            	if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
						}
						if (!is_null($proveedor_id)) {
		            		$subquery->where('persona_id', $proveedor_id );
		            	}
					})
					->where('sucursal_id', "=", $sucursal_id)
					->where('tipomovimiento_id', "=", 3)
        			->orderBy('fecha', 'DESC');
	}

	public function scopebalonescredito($query, $fechainicio, $fechafin)
    {
		return $query->where(function($subquery) use($fechainicio, $fechafin)
		            {
		            	if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
		            	}
					})
					->where('balon_a_cuenta', "=", 1)
					->where('credito_cancelado', "!=", 1)
        			->orderBy('num_caja','DESC')->orderBy('fecha', 'ASC');
	}

	public function trabajador(){
		return $this->belongsTo('App\Person', 'trabajador_id');
	}

	public function concepto(){
		return $this->belongsTo('App\Concepto', 'concepto_id');
	}

	public function persona(){
		return $this->belongsTo('App\Person', 'persona_id');
	}

	public function sucursal(){
		return $this->belongsTo('App\Sucursal', 'sucursal_id');
	}

	public function almacen(){
		return $this->belongsTo('App\Almacen', 'almacen_id');
	}

	public function tipodocumento(){
		return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
	}

	public function venta(){
		return $this->belongsTo('App\Movimiento', 'venta_id');
	}
	
}
