<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detallemovalmacen extends Model
{
	use SoftDeletes;
    protected $table = 'detalle_mov_almacen';
    protected $dates = ['deleted_at'];

    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento', 'movimiento_id');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function lote()
    {
        return $this->belongsTo('App\Lote', 'lote_id');
    }

}
