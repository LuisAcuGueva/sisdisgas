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

    public function scopelistar($query, $nombre, $dni, $type, $secondtype)
    {   
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
                            $subquery->where(DB::raw('CONCAT(nombres," ",apellido_pat," ",apellido_mat)'), 'LIKE', '%'.$nombre.'%');
		            	}
                    })
                    ->orWhere(function($subquery) use($dni)
		            {
		            	if (!is_null($dni)) {
                            $subquery->orwhere('dni', '=', $dni);
		            	}
                    })
                    ->Where(function($subquery) use($type , $secondtype)
		            {
		            	if (!is_null($type)) {
                            $subquery->orwhere('tipo_persona', '=', $type);
                        }
                        if (!is_null($secondtype)) {
                            $subquery->orwhere('tipo_persona', '=', $secondtype);
		            	}
                    })
                    ->orderBy('apellido_pat', 'ASC')->orderBy('apellido_mat', 'ASC')->orderBy('nombres', 'ASC');
    }

    public function sucursal(){
		return $this->belongsTo('App\Sucursal', 'sucursal_id');
	}

}
