<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Sucursal extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='sucursal';

    protected $primaryKey='id';


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
                            $subquery->where('nombre', 'LIKE', '%'.$descripcion.'%');
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }

    public function empresa(){
        return $this->belongsTo('App\Empresa','empresa_id');
    } 
}
