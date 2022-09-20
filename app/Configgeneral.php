<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Configgeneral extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='config_general';

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
                            $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }
}
