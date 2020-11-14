<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;
use App\Sucursal;
use App\Kardex;
use App\Almacen;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
{

    protected $folderview      = 'app.kardex';
    protected $tituloAdmin     = 'Kardex de Inventario';
    protected $rutas           = array(
            'search'   => 'kardex.buscar',
            'index'    => 'kardex.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Kardex';
        $producto_id      = Libreria::getParam($request->input('producto_id'));
        $sucursal_id       = Libreria::getParam($request->input('sucursal_id'));
        $tipo             = Libreria::getParam($request->input('tipo'));
        $fechainicio      = Libreria::getParam($request->input('fechai'));
        $fechafin         = Libreria::getParam($request->input('fechaf'));
        $resultado        = Kardex::listar($producto_id, $sucursal_id , $fechainicio, $fechafin ,$tipo);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'FECHA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TIPO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TIPO MOVIMIENTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO DOCUMENTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRODUCTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CANTIDAD', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO UNIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CANT ENVASES', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO ENVASE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'STOCK ANTERIOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'STOCK ACTUAL', 'numero' => '1');
        
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Kardex';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $cboTipo = array(
            '' => 'Todos',
            'I' => 'Ingreso',
            'E' => 'Egreso',
        );
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboTipo', 'cboSucursal','title', 'ruta'));
    }

}
