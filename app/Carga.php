<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Carga extends Model
{
    use SoftDeletes;
    protected $table = 'carga';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $numero)
    {   
        return $query->where(function($subquery) use($numero)
		            {
		            	if (!is_null($numero)) {
                            $subquery->where('numero', 'LIKE', '%'.$numero.'%');
		            	}
                    })
                    ->orderBy('numero', 'ASC');
    }

}
