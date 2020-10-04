<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;
use App\Sucursal;
use App\Almacen;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{

    protected $folderview      = 'app.inventario';
    protected $tituloAdmin     = 'Inventario de productos';
    protected $rutas           = array(
            'search'   => 'inventario.buscar',
            'index'    => 'inventario.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Producto';
        $descripcion      = Libreria::getParam($request->input('name'));
        $almacen_id       = Libreria::getParam($request->input('almacen_id'));
        $resultado        = Producto::inventario($descripcion, $almacen_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'NRO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DESCRIPCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO VENTA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO COMPRA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'STOCK', 'numero' => '1');
        
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
        $entidad          = 'Inventario';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        $almacenes = Almacen::where('sucursal_id', 1)->get();
        $cboAlmacenes = array();
        foreach ($almacenes as $key => $value) {
            $cboAlmacenes = $cboAlmacenes + array( $value->id => $value->nombre);
        }
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal', 'cboAlmacenes','title', 'ruta'));
    }

}
