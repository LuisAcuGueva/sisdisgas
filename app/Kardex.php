<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;

class Kardex extends Model
{
	use SoftDeletes;
    protected $table = 'kardex';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $producto_id, $sucursal_id , $fechainicio, $fechafin, $tipo)
    {
        return $query->join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'kardex.detalle_mov_almacen_id')
                     ->join('movimiento', 'movimiento.id', '=', 'detalle_mov_almacen.movimiento_id')
                     ->join('producto', 'producto.id', '=', 'detalle_mov_almacen.producto_id')
                     //->join('lote', 'lote.id', '=', 'detalle_mov_almacen.lote_id')
                    ->where(function($subquery) use($producto_id, $sucursal_id , $fechainicio, $fechafin , $tipo)
		            {
		            	if (!is_null($producto_id)) {
		            		$subquery->where('producto.id', $producto_id );
		            	}
                        if (!is_null($sucursal_id)) {
		            		$subquery->where('kardex.sucursal_id', $sucursal_id);
                        }
                        if (!is_null($tipo)) {
		            		$subquery->where('kardex.tipo', $tipo);
                        }
                        if (!is_null($fechainicio) && !is_null($fechafin)) {
							$subquery->whereBetween(DB::raw('CONVERT(kardex.fecha,date)'),[$fechainicio,$fechafin]);
		            	}
                    })
                    ->where('movimiento.estado',1)
        			->orderBy('movimiento.fecha', 'DESC');//->orderBy('kardex.id', 'DESC');
    }

    public function detallemovimiento()
    {
        return $this->belongsTo('App\Detallemovalmacen', 'detalle_mov_almacen_id');
    }

}
