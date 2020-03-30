<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Carta extends Model
{
    use SoftDeletes;
    protected $table = 'accion_inductiva';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $numero, $ruc)
    {   
        return $query->where(function($subquery) use($numero)
		            {
		            	if (!is_null($numero)) {
                            $subquery->where('accion_inductiva.numero', 'LIKE', '%'.$numero.'%');
		            	}
                    })
                    ->where('accion_inductiva.tipo','=',1) //CARTA
                    ->join('contribuyente', 'accion_inductiva.contribuyente_id', '=', 'contribuyente.id')
                    ->join('carga', 'accion_inductiva.carga_id', '=', 'carga.id')
                    ->orWhere(function($subquery) use($ruc)
		            {
		            	if (!is_null($ruc)) {
                            $subquery->orwhere('ruc', 'LIKE', '%'.$ruc.'%');
		            	}
                    })
                    ->orderBy('accion_inductiva.numero', 'ASC')->orderBy('ruc', 'ASC')
                    ->select('accion_inductiva.*', 'carga.descripcion as descripcion', 'contribuyente.ruc as ruc', 'contribuyente.contribuyente as contribuyente' );
                    //falta expediente
    }

}
