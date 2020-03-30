<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Person extends Model
{
    use SoftDeletes;
    protected $table = 'person';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $nombre, $dni, $registro)
    {   
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
                            $subquery->where(DB::raw('CONCAT(apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.$nombre.'%');
		            	}
                    })
                    ->orWhere(function($subquery) use($dni)
		            {
		            	if (!is_null($dni)) {
                            $subquery->orwhere('dni', '=', $dni);
		            	}
                    })
                    ->orWhere(function($subquery) use($registro)
		            {
		            	if (!is_null($registro)) {
                            $subquery->orwhere('registro', '=', $registro);
		            	}
                    })
                    ->orderBy('registro', 'ASC')->orderBy('apellido_pat', 'ASC')->orderBy('apellido_mat', 'ASC')->orderBy('nombres', 'ASC');
    }

}
