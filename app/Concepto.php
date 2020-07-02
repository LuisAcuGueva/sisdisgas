<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Concepto extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='concepto';

    protected $primaryKey='id';

    
    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $concepto, $tipo)
    {
        return $query->where(function($subquery) use($concepto , $tipo)
		            {
		            	if (!is_null($concepto) && !is_null($tipo) ) {
                            $subquery->where('concepto', 'LIKE', '%'.$concepto.'%');
                            $subquery->where('tipo', '=', $tipo);
		            	}else if (!is_null($concepto)) {
                            $subquery->where('concepto', 'LIKE', '%'.$concepto.'%');
                        }else if (!is_null($tipo)) {
                            $subquery->where('tipo', '=', $tipo);
                        }
                    })
        			->orderBy('concepto', 'ASC');
    }

}
