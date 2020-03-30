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

class PersonalController extends Controller
{
    protected $folderview      = 'app.personal';
    protected $tituloAdmin     = 'Trabajadores';
    protected $tituloRegistrar = 'Registrar Trabajador';
    protected $tituloModificar = 'Modificar Trabajador';
    protected $tituloEliminar  = 'Eliminar Trabajador';
    protected $rutas           = array('create' => 'personal.create', 
            'edit'   => 'personal.edit', 
            'delete' => 'personal.eliminar',
            'search' => 'personal.buscar',
            'index'  => 'personal.index',
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
        $entidad          = 'Personal';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $dni              = Libreria::getParam($request->input('dni'));
        $registro         = Libreria::getParam($request->input('registro'));
        $resultado        = Person::listar($nombre,$dni,$registro);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'EDIT', 'numero' => '1');
        $cabecera[]       = array('valor' => 'ELIM', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DNI', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Registro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre Completo', 'numero' => '1');
        
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
        $entidad          = 'Personal';
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
        $entidad        = 'Personal'; 
        $personal        = null;
        $formData       = array('personal.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $accion = 0;
        return view($this->folderview.'.mant')->with(compact('accion' ,'personal', 'formData', 'entidad', 'boton', 'listar'));
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
            'dni'       => 'required|max:8|unique:person,dni,NULL,id,deleted_at,NULL',
            'nombres'    => 'required|max:100',
            'apellido_pat'    => 'required|max:100',
            'apellido_mat'    => 'required|max:100',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $personal                = new Person();
            $personal->dni           = $request->input('dni');
            $personal->registro      = $request->input('registro');
            $personal->nombres       = strtoupper($request->input('nombres'));
            $personal->apellido_pat  = strtoupper($request->input('apellido_pat'));
            $personal->apellido_mat  = strtoupper($request->input('apellido_mat'));
            $personal->save();
            
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
        $personal        = person::find($id);
        $entidad        = 'Personal';
        $formData       = array('personal.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'personal', 'formData', 'entidad', 'boton', 'listar'));
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
            'dni'       => 'required|max:8',
            'nombres'    => 'required|max:100',
            'apellido_pat'    => 'required|max:100',
            'apellido_mat'    => 'required|max:100',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $personal                = Person::find($id);
            $personal->dni           = $request->input('dni');
            $personal->registro      = $request->input('registro');
            $personal->nombres       = strtoupper($request->input('nombres'));
            $personal->apellido_pat  = strtoupper($request->input('apellido_pat'));
            $personal->apellido_mat  = strtoupper($request->input('apellido_mat'));
            $personal->save();
            
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
            $personal = Person::find($id);
            $personal->delete();
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
        $entidad  = 'Personal';
        $formData = array('route' => array('personal.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function personalautocompleting($searching)
    {
        $entidad    = 'Personal';
        $mdlPerson = new Person();
        $resultado = Person::where(DB::raw('CONCAT(apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')
        ->whereNull('person.deleted_at')
        ->orderBy('apellido_pat', 'ASC')
        ->orderBy('apellido_mat', 'ASC')
        ->orderBy('nombres', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                            'id'    => $value->id,
                            'value' => $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat,
                            'registro'    => $value->registro,
                        );
        }
        return json_encode($data);
    }

    
    public function verificadorautocompleting($searching)
    {
        $entidad    = 'Personal';
        $mdlPerson = new Person();
        $resultado = Person::where(DB::raw('CONCAT(registro," ",apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')
        ->whereNull('person.deleted_at')
        ->orderBy('registro', 'ASC')
        ->orderBy('apellido_pat', 'ASC')
        ->orderBy('apellido_mat', 'ASC')
        ->orderBy('nombres', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->registro.' - '.$value->apellido_pat.' '.$value->apellido_mat.' '.$value->nombres,
                            'id'    => $value->id,
                            'value' => $value->registro.' - '.$value->apellido_pat.' '.$value->apellido_mat.' '.$value->nombres,
                        );
        }
        return json_encode($data);
    }

    public function supervisorautocompleting($searching)
    {
        $entidad    = 'Personal';
        $mdlPerson = new Person();
        $resultado = Person::where(DB::raw('CONCAT(registro," ",apellido_pat," ",apellido_mat," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')
        ->whereNull('person.deleted_at')
        ->orderBy('registro', 'ASC')
        ->orderBy('apellido_pat', 'ASC')
        ->orderBy('apellido_mat', 'ASC')
        ->orderBy('nombres', 'ASC');
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->registro.' - '.$value->apellido_pat.' '.$value->apellido_mat.' '.$value->nombres,
                            'id'    => $value->id,
                            'value' => $value->registro.' - '.$value->apellido_pat.' '.$value->apellido_mat.' '.$value->nombres,
                        );
        }
        return json_encode($data);
    }

}
