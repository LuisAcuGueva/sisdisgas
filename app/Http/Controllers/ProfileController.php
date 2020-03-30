<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\User;
use App\Person;
use App\Usertype;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $folderview      = 'app.profile';
    protected $tituloAdmin     = 'Perfil';
    protected $tituloRegistrar = 'Registrar usuario';
    protected $tituloModificar = 'Modificar usuario';
    protected $tituloEliminar  = 'Eliminar usuario';
    protected $rutas           = array('index'  => 'profile.index',
            'save'  => 'profile.save',
            'update'  => 'profile.update',
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad          = 'Profile';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $user             = Auth::user();
        $person           = Person::where('id','=',$user->person_id)->first();
        $listar           = Libreria::getParam($request->input('listar'), 'SI');
        $formData       = array('profile.update', $user->id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal' , 'id' => 'formMantenimientoPassword', 'autocomplete' => 'off');
        return view($this->folderview.'.admin')->with(compact('entidad', 'formData', 'user','listar', 'person', 'title', 'titulo_registrar', 'ruta'));
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

    public function save(Request $request)
    {
        $id             = Auth::user()->person_id;
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'dni'       => 'required|max:8',
            'registro'       => 'required|max:4',
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

    public function update(Request $request, $id){
        $error = null;
        $success = "";
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed|min:6|max:18',
        ];
        
        $messages = [
            'mypassword.required' => 'El campo contraseña actual es requerido.',
            'password.required' => 'El campo nueva contraseña es requerido.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'El mínimo permitido son 6 caracteres.',
            'password.max' => 'El máximo permitido son 18 caracteres.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()){
            return $validator->messages()->toJson();
        }
        else{
            if (Hash::check($request->mypassword, Auth::user()->password) && !Hash::check($request->password, Auth::user()->password) ){
                $error = DB::transaction(function() use($request, $id){
                    $usuario           = User::find($id);
                    $usuario->password = bcrypt($request->get('password'));
                    $usuario->save();
                });
                return is_null($error) ? "OK" : $error;
            }
            else if(Hash::check($request->password, Auth::user()->password))
            {
                $error =  'IGUAL';
                return $error;
            }
            else
            {
                $error =  'ERROR';
                return $error;
            }
        }
    }
}