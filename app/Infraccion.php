<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Infraccion extends Model
{
    use SoftDeletes;
    protected $table = 'infraccion';
    protected $dates = ['deleted_at'];

}
