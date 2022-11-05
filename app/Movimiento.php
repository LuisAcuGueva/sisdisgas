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
							if($aperturaycierre == 0){ //apertura y cierre iguales ---- mostrar nada de ultima apertura a ultimo cierre
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

	public function scopelistarpedidosactual($query, $sucursal_id, $aperturaycierre, $maxima_apertura, $maximo_cierre, $tipomovimiento_id)
    {
		return $query->where(function($subquery) use( $aperturaycierre, $maxima_apertura, $maximo_cierre)
		            {
						if (!is_null($maxima_apertura) && !is_null($maximo_cierre)) {
							if($aperturaycierre == 0){ //apertura y cierre iguales ---- no mostrar nada
								$subquery->Where('id','>=', $maxima_apertura)->Where('id','<=', $maximo_cierre);
							}else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura
								$subquery->Where('id','>=', $maxima_apertura);
							}
						}else if(!is_null($maxima_apertura) && is_null($maximo_cierre)) {
							$subquery->Where('id','>=', $maxima_apertura);
						}
					})
					->where('sucursal_id', "=", $sucursal_id)
					->where('tipomovimiento_id', "=", $tipomovimiento_id)
        			->orderBy('id','DESC')->orderBy('fecha', 'DESC');
	}

	public function scopelistarpedidos($query, $fechainicio, $fechafin, $sucursal_id ,$cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale)
    {
		return $query->select('movimiento.*')
					->join('person', 'person.id', '=', 'movimiento.persona_id')
					->where(function($subquery) use( $fechainicio, $fechafin, $cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale)
		            {
						if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
						}
						if (!is_null($cliente)) {
							$subquery->where(DB::raw('CONCAT(nombres," ",apellido_pat," ",apellido_mat)'), 'LIKE', '%'.$cliente.'%');
						}
						if (!is_null($tipo)) {
							if($tipo == "R"){
								$subquery->where('movimiento.pedido_sucursal', "=", 0);
							}
							if($tipo == "S"){
								$subquery->where('movimiento.pedido_sucursal', "=", 1);
							}
						}
						if (!is_null($tipodocumento)) {
							$subquery->where('movimiento.tipodocumento_id', "=", $tipodocumento);
						}
						if (!is_null($tipovale)) {
							if($tipovale == "1"){
								$subquery->where('movimiento.vale_balon_fise', "=", 1);
							}
							if($tipovale == "2"){
								$subquery->where('movimiento.vale_balon_subcafae', "=", 1);
							}
							if($tipovale == "3"){
								$subquery->where('movimiento.vale_balon_monto', "=", 1);
							}
						}
						if (!is_null($trabajador_id)) {
		            		$subquery->where('movimiento.trabajador_id', $trabajador_id );
		            	}
					})
					->where('movimiento.sucursal_id', "=", $sucursal_id)
					->where('tipomovimiento_id', "=", 2)
        			->orderBy('movimiento.id','DESC')->orderBy('fecha', 'DESC');
	}

	public function scopelistarprestamos($query, $fechainicio, $fechafin, $sucursal_id ,$cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale)
    {
		return $query->select('movimiento.*')
					->join('person', 'person.id', '=', 'movimiento.persona_id')
					->where(function($subquery) use( $fechainicio, $fechafin, $cliente, $trabajador_id, $tipo, $tipodocumento, $tipovale)
		            {
						if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
						}
						if (!is_null($cliente)) {
							$subquery->where(DB::raw('CONCAT(nombres," ",apellido_pat," ",apellido_mat)'), 'LIKE', '%'.$cliente.'%');
						}
						if (!is_null($tipo)) {
							if($tipo == "R"){
								$subquery->where('movimiento.pedido_sucursal', "=", 0);
							}
							if($tipo == "S"){
								$subquery->where('movimiento.pedido_sucursal', "=", 1);
							}
						}
						if (!is_null($tipodocumento)) {
							$subquery->where('movimiento.tipodocumento_id', "=", $tipodocumento);
						}
						if (!is_null($tipovale)) {
							if($tipovale == "1"){
								$subquery->where('movimiento.vale_balon_fise', "=", 1);
							}
							if($tipovale == "2"){
								$subquery->where('movimiento.vale_balon_subcafae', "=", 1);
							}
							if($tipovale == "3"){
								$subquery->where('movimiento.vale_balon_monto', "=", 1);
							}
						}
						if (!is_null($trabajador_id)) {
		            		$subquery->where('movimiento.trabajador_id', $trabajador_id );
		            	}
					})
					->where('movimiento.sucursal_id', "=", $sucursal_id)
					->where('movimiento.balon_prestado', "=", 1)
					->where('tipomovimiento_id', "=", 2)
        			->orderBy('movimiento.id','DESC')->orderBy('fecha', 'DESC');
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
        			->orderBy('fecha', 'DESC')->orderBy('id', 'DESC');
	}

	public function scopelistarmovalmacen($query, $fechainicio, $fechafin, $sucursal_id ,$proveedor_id)
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
					->where('tipomovimiento_id', "=", 4)
        			->orderBy('fecha', 'DESC')->orderBy('id', 'DESC');
	}

	public function scopebalonescredito($query, $fechainicio, $fechafin, $sucursal_id )
    {
		return $query->where(function($subquery) use($fechainicio, $fechafin)
		            {
		            	if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
		            	}
					})
					->where('sucursal_id', "=", $sucursal_id)
					->where('balon_a_cuenta', "=", 1)
					->where('tipomovimiento_id', "=", 2)
        			->orderBy('num_caja','DESC')->orderBy('fecha', 'ASC');
	}

	public function scopecomprascredito($query, $fechainicio, $fechafin, $sucursal_id , $proveedor_id)
    {
		return $query->where(function($subquery) use($fechainicio, $fechafin, $proveedor_id)
		            {
		            	if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(fecha,date)'),[$fechainicio,$fechafin]);
						}
						
						if (!is_null($proveedor_id)) {
		            		$subquery->where('persona_id', $proveedor_id );
		            	}
					})
					->where('sucursal_id', "=", $sucursal_id)
					->where('balon_a_cuenta', "=", 1)
					->where('tipomovimiento_id', "=", 3)
        			->orderBy('num_caja','DESC')->orderBy('fecha', 'ASC');
	}

	//listar detalles
	public function scopelistardetallespedidosactual($query, $sucursal_id, $aperturaycierre, $maxima_apertura, $maximo_cierre)
    {
		return $query->join('detalle_mov_almacen', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
					->join('producto', 'detalle_mov_almacen.producto_id', '=', 'producto.id')
					->where(function($subquery) use( $aperturaycierre, $maxima_apertura, $maximo_cierre)
		            {
						if (!is_null($maxima_apertura) && !is_null($maximo_cierre)) {
							if($aperturaycierre == 0){ //apertura y cierre iguales ---- no mostrar nada
								$subquery->Where('movimiento.id','>=', $maxima_apertura)->Where('movimiento.id','<=', $maximo_cierre);
							}else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura
								$subquery->Where('movimiento.id','>=', $maxima_apertura);
							}
						}else if(!is_null($maxima_apertura) && is_null($maximo_cierre)) {
							$subquery->Where('movimiento.id','>=', $maxima_apertura);
						}
					})
					->where('movimiento.sucursal_id', "=", $sucursal_id)
					->where('movimiento.tipomovimiento_id', "=" , 2)
					->orderBy('producto.descripcion','ASC')
					->groupBy('detalle_mov_almacen.producto_id');
	}

	public function scopeiniciocredito($query, $sucursal_id )
    {
		return $query->where('sucursal_id', "=", $sucursal_id)
					->where('balon_a_cuenta', "=", 1)
					->where('tipomovimiento_id', "=", 2)
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

	public function tipomovimiento(){
		return $this->belongsTo('App\Tipomovimiento', 'tipomovimiento_id');
	}

	public function tipodocumento(){
		return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
	}

	public function venta(){
		return $this->belongsTo('App\Movimiento', 'venta_id');
	}

	public function compra(){
		return $this->belongsTo('App\Movimiento', 'compra_id');
	}
	
}
