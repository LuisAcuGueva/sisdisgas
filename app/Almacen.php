<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
	 use SoftDeletes;
    protected $table = 'almacen';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $descripcion, $sucursal_id)
    {
        return $query->where(function($subquery) use($descripcion, $sucursal_id)
		            {
		            	if (!is_null($descripcion)) {
                            $subquery->where('nombre', 'LIKE', '%'.$descripcion.'%');
                        }
                        if (!is_null($sucursal_id)) {
                            $subquery->where('sucursal_id', $sucursal_id);
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }

    public function sucursal(){
        return $this->belongsTo('App\Sucursal','sucursal_id');
    } 
}
