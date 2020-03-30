<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Contribuyente extends Model
{
    use SoftDeletes;
    protected $table = 'contribuyente';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $contribuyente, $ruc)
    {   
        return $query->where(function($subquery) use($contribuyente)
		            {
		            	if (!is_null($contribuyente)) {
                            $subquery->where('contribuyente', 'LIKE', '%'.$contribuyente.'%');
		            	}
                    })
                    ->orWhere(function($subquery) use($ruc)
		            {
		            	if (!is_null($ruc)) {
                            $subquery->orwhere('ruc', 'LIKE',  '%'.$ruc.'%');
		            	}
                    })
                    ->orderBy('contribuyente', 'ASC')->orderBy('ruc', 'ASC');
    }

}
