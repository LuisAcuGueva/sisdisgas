<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{

    protected $folderview      = 'app.producto';
    protected $tituloAdmin     = 'Productos';
    protected $tituloRegistrar = 'Registrar producto';
    protected $tituloModificar = 'Modificar producto';
    protected $tituloEliminar  = 'Eliminar producto';
    protected $rutas           = array('create' => 'producto.create', 
            'edit'     => 'producto.edit', 
            'delete'   => 'producto.eliminar',
            'search'   => 'producto.buscar',
            'index'    => 'producto.index',
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
        $resultado        = Producto::listar($descripcion);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DESCRIPCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO COMPRA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO VENTA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO COMPRA + ENVASE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO VENTA + ENVASE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ACTIVO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'PRECIO EDITABLE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RECARGABLE', 'numero' => '1');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
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
        $entidad          = 'Producto';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Producto';
        $producto  = null;
        $formData     = array('producto.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Guardar'; 
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        if($request->input('recargable') ==  0){
            $reglas     = array('descripcion' => 'required|max:100',
                            'precio_venta' => 'required|numeric',
                            'precio_compra' => 'required|numeric',
                            );
        }else if($request->input('recargable') ==  1){
            $reglas     = array('descripcion' => 'required|max:100',
                            'precio_venta' => 'required|numeric',
                            'precio_compra' => 'required|numeric',
                            'precio_venta_envase' => 'required|numeric',
                            'precio_compra_envase' => 'required|numeric',
                            );
        }
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $producto       = new Producto();
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->precio_venta = $request->input('precio_venta');
            $producto->precio_compra = $request->input('precio_compra');
            $producto->precio_venta_envase = $request->input('precio_venta_envase');
            $producto->precio_compra_envase = $request->input('precio_compra_envase');
            $producto->frecuente = $request->input('frecuente');
            $producto->editable = $request->input('editable');
            $producto->recargable = $request->input('recargable');
            $producto->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $producto = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('producto.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        if($request->input('recargable') ==  0){
            $reglas     = array('descripcion' => 'required|max:100',
                            'precio_venta' => 'required|numeric',
                            'precio_compra' => 'required|numeric',
                            );
        }else if($request->input('recargable') ==  1){
            $reglas     = array('descripcion' => 'required|max:100',
                            'precio_venta' => 'required|numeric',
                            'precio_compra' => 'required|numeric',
                            'precio_venta_envase' => 'required|numeric',
                            'precio_compra_envase' => 'required|numeric',
                            );
        }
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $producto       = Producto::find($id);
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->precio_venta = $request->input('precio_venta');
            $producto->precio_compra = $request->input('precio_compra');
            $producto->precio_venta_envase = $request->input('precio_venta_envase');
            $producto->precio_compra_envase = $request->input('precio_compra_envase');
            $producto->frecuente = $request->input('frecuente');
            $producto->editable = $request->input('editable');
            $producto->recargable = $request->input('recargable');
            $producto->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Producto::find($id);
            $producto->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('route' => array('producto.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function productoautocompleting($searching)
    {
        $entidad    = 'Producto';
        $resultado = Producto::where('descripcion', 'LIKE', '%'.strtoupper($searching).'%')
        ->whereNull('deleted_at')
        ->orderBy('id', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'id'    => $value->id,
                            'descripcion'    => $value->descripcion,
                        );
        }
        return json_encode($data);
    }
}
