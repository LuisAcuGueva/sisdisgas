<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidadmedida extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='unidad_medida';

    protected $primaryKey='id';

     /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $nombre)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
                            $subquery->where('medida', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->orderBy('medida', 'ASC');
    }

    /* public function productos()
	{
		return $this->hasMany('App\Producto');
	} */
}
