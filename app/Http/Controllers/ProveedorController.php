<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\Distrito;
use App\Provincia;
use App\Departamento;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{
    protected $folderview      = 'app.proveedor';
    protected $tituloAdmin     = 'Proveedores';
    protected $tituloRegistrar = 'Registrar Proveedor';
    protected $tituloModificar = 'Modificar Proveedor';
    protected $tituloEliminar  = 'Eliminar Proveedor';
    protected $rutas           = array('create' => 'proveedor.create', 
            'edit'   => 'proveedor.edit', 
            'delete' => 'proveedor.eliminar',
            'search' => 'proveedor.buscar',
            'index'  => 'proveedor.index',
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Proveedor';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $dni              = Libreria::getParam($request->input('dni'));
        $resultado        = Person::listar($nombre,$dni,'P', null);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RUC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'RAZÓN SOCIAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CELULAR', 'numero' => '1');
        
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
        $entidad          = 'Proveedor';
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
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Proveedor'; 
        $proveedor     = null;
        $formData       = array('proveedor.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Guardar'; 
        $accion = 0;
        return view($this->folderview.'.mant')->with(compact('accion' ,'proveedor', 'formData', 'entidad', 'boton', 'listar'));
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
        $documento = $request->input('dni');
        $reglas = array(
            'ruc'       => 'required|max:11|unique:person,ruc,NULL,id,deleted_at,NULL',
            'razon_social'    => 'required|max:100',
            'direccion'    => 'required|max:400',
            'celular'       => 'required|numeric|digits:9',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $proveedor                = new Person();
            $proveedor->ruc           = $request->input('ruc');
            $proveedor->razon_social       = strtoupper($request->input('razon_social'));
            $proveedor->tipo_persona  = "P";
            $proveedor->direccion  = $request->input('direccion');
            $proveedor->celular  = $request->input('celular');
            $proveedor->save();
            
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
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $proveedor      = Person::find($id);
        $entidad        = 'Proveedor';
        $formData       = array('proveedor.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'proveedor', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'ruc'       => 'required|max:11',
            'razon_social'    => 'required|max:100',
            'direccion'    => 'required|max:400',
            'celular'       => 'required|numeric|digits:9',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $proveedor                = Person::find($id);
            $proveedor->ruc           = $request->input('ruc');
            $proveedor->razon_social       = strtoupper($request->input('razon_social'));
            $proveedor->tipo_persona  = "P";
            $proveedor->direccion  = $request->input('direccion');
            $proveedor->celular  = $request->input('celular');
            $proveedor->save();
            
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $proveedor = Person::find($id);
            $proveedor->delete();
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Person::find($id);
        $entidad  = 'Proveedor';
        $formData = array('route' => array('proveedor.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function proveedorautocompleting($searching)
    {
        $entidad    = 'Proveedor';
        $resultado = Person::where('razon_social', 'LIKE', '%'.strtoupper($searching).'%')
        ->where('tipo_persona','P')
        ->whereNull('person.deleted_at')
        ->orderBy('razon_social', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'razon_social' => $value->razon_social,
                            'id'    => $value->id,
                            'ruc'    => $value->ruc,
                            'direccion'    => $value->direccion,
                        );
        }
        return json_encode($data);
    }

    public function buscarEmpresa(Request $request) {
        $ruc = $request->input('ruc');

        $empresa = Person::where('ruc', $ruc)->first();

        if(count($empresa) == 0) {
            $data = '';
        } else {
            $data = $empresa->id;
            $data .= ';;' . $empresa->razon_social;
            $data .= ';;' . $empresa->direccion;
        }
        echo $data;
    }
    
    public function ultimoproveedor(){
        $ultimo = Person::where('tipo_persona' , 'P')->max('id');
        $ultimoproveedor = Person::find($ultimo);
        return $ultimoproveedor;
    }
}
