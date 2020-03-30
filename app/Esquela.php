<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Esquela extends Model
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
                    ->where('accion_inductiva.tipo','=',2) //ESQUELA
                    ->join('contribuyente', 'accion_inductiva.contribuyente_id', '=', 'contribuyente.id')
                    ->join('carga', 'accion_inductiva.carga_id', '=', 'carga.id')
                    ->join('infraccion', 'accion_inductiva.infraccion_id', '=', 'infraccion.id')
                    ->orWhere(function($subquery) use($ruc)
		            {
		            	if (!is_null($ruc)) {
                            $subquery->orwhere('ruc', 'LIKE', '%'.$ruc.'%');
		            	}
                    })
                    ->orderBy('accion_inductiva.numero', 'ASC')->orderBy('ruc', 'ASC')
                    ->select('accion_inductiva.*', 'carga.descripcion as descripcion', 'contribuyente.ruc as ruc', 'contribuyente.contribuyente as contribuyente','infraccion.articulo as articulo', 'infraccion.numeral as numeral' );
                    //falta expediente
    }

}
