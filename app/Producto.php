<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Producto extends Model
{
    use SoftDeletes;
    protected $table = 'producto';
    protected $dates = ['deleted_at'];

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $descripcion)
    {
        return $query->where(function($subquery) use($descripcion)
		            {
		            	if (!is_null($descripcion)) {
		            		$subquery->where('descripcion', 'LIKE', '%'.$descripcion.'%');
		            	}
                    })
        			->orderBy('descripcion', 'ASC');
    }

    public function scopeinventario($query, $descripcion, $almacen_id)
    {
        return $query->leftjoin('stock', 'producto.id', '=', 'stock.producto_id')
                    ->where(function($subquery) use($descripcion, $almacen_id)
		            {
		            	if (!is_null($descripcion)) {
		            		$subquery->where('producto.descripcion', 'LIKE', '%'.$descripcion.'%');
                        }
                        if (!is_null($almacen_id)) {
		            		$subquery->where('stock.almacen_id', $almacen_id);
		            	}
                    })
        			->orderBy('producto.descripcion', 'ASC');
    }

}
