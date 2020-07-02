<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Sucursal;
use App\Concepto;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{

    protected $folderview      = 'app.caja';
    protected $tituloAdmin     = 'Caja';
    protected $tituloRegistrar = 'Registrar Movimiento de Caja';
    protected $tituloEliminar  = 'Anular Movimiento de Caja';
    protected $tituloApertura  = 'Apertura de Caja';
    protected $tituloCierre    = 'Cierre de Caja';
    protected $tituloPersona   = 'Registrar Nueva Persona';
    protected $rutas           = array('create' => 'caja.create', 
            'persona'  => 'caja.persona',
            'guardarpersona'  => 'caja.guardarpersona',
            'delete'   => 'caja.eliminar',
            'search'   => 'caja.buscar',
            'index'    => 'caja.index',
            'apertura' => 'caja.apertura',
            'cierre'   => 'caja.cierre',
            'repetido' => 'caja.repetido',
            'aperturaycierre' => 'caja.aperturaycierre',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $sucursal = Sucursal::find($sucursal_id);
        $empresa_id = $sucursal->empresa_id;

        //cantidad de aperturas
        $aperturas = Movimiento::where('concepto_id', 1)
                                ->where('sucursal_id', "=", $sucursal_id)
                                ->where('estado', "=", 1)
                                ->count();
        //cantidad de cierres
        $cierres = Movimiento::where('concepto_id', 2)
                            ->where('sucursal_id', "=", $sucursal_id)
                            ->where('estado', "=", 1)
                            ->count();

        $aperturaycierre = null;

        if($aperturas == $cierres){ // habilitar apertura de caja
            $aperturaycierre = 0;
        }else if($aperturas != $cierres){ //habilitar cierre de caja
            $aperturaycierre = 1;
        }

        //max apertura
        $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        //max cierre
        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

                $montoapertura = 0.00;
                $ingresos_efectivo = 0.00;
                $ingresos_visa = 0.00;
                $ingresos_master = 0.00;
                $ingresos_total = 0.00;
                $egresos = 0.00;
                $saldo = 0.00;
        
                if (!is_null($maxapertura) && !is_null($maxcierre)) { // Ya existe una apertura y un cierre
                    $apertura = Movimiento::where('concepto_id', 1)
                        ->where('sucursal_id', "=", $sucursal_id)
                        ->where('estado', "=", 1)
                        ->where('num_caja',$maxapertura)->first();
                    $montoapertura = $apertura->total;
                    if($aperturaycierre == 0){ //apertura y cierre iguales ---- mostrar desde apertura a cierre
                        /*
        
                        SELECT SUM(montoefectivo)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        
                        //ingresos efectivo
                        $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('num_caja','<', $maxcierre)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montoefectivo');
        
                        round($ingresos_efectivo,2);
        
                        /*
        
                        SELECT SUM(montovisa)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta visa
        
                        $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('num_caja','<', $maxcierre)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montovisa');
        
                        round($ingresos_visa,2);
        
                        /*
        
                        SELECT SUM(montomaster)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta mastercard
        
                        $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('num_caja','<', $maxcierre)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montomaster');
        
                        round($ingresos_master,2);
        
                        //ingresos total
        
                        $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('num_caja','<', $maxcierre)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('total');
                        round($ingresos_total,2);
        
                        /*
        
                        SELECT SUM(total)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 1 // EGRESO
        
                        */
                        //egresos
                        $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('num_caja','<', $maxcierre)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 1) //egreso
                                                    ->sum('total');
                        round($egresos,2);
        
                        //saldo
                        $saldo = round($ingresos_total - $egresos, 2);
        
                    }else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                        /*
        
                        SELECT SUM(montoefectivo)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        
                        //ingresos efectivo
                        $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montoefectivo');
                        round($ingresos_efectivo,2);
                        /*
        
                        SELECT SUM(montovisa)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta visa
        
        
                        $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montovisa');
                        round($ingresos_visa,2);
        
                        /*
        
                        SELECT SUM(montomaster)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta mastercard
        
                        $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)  
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montomaster');
                        round($ingresos_master,2);
        
                        //ingresos total
        
                        $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('total');
                        round($ingresos_total,2);
        
                        /*
        
                        SELECT SUM(total)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 1 // EGRESO
        
                        */
                        //egresos
                        $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 1) //egreso
                                                    ->sum('total');
                        round($egresos,2);
                        //saldo
                        $saldo = round($ingresos_total - $egresos, 2);
                    }
                    $saldo += $montoapertura;
                }else if(!is_null($maxapertura) && is_null($maxcierre)) { //existe apertura pero no existe cierre
                    $apertura = Movimiento::where('concepto_id', 1)
                        ->where('sucursal_id', "=", $sucursal_id)
                        ->where('estado', "=", 1)
                        ->where('num_caja',$maxapertura)->first();
                    $montoapertura = $apertura->total;
                    if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                        /*
        
                        SELECT SUM(montoefectivo)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        
                        //ingresos efectivo
                        $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montoefectivo');
                        round($ingresos_efectivo,2);
                        /*
        
                        SELECT SUM(montovisa)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta visa
        
                        $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montovisa');
                        round($ingresos_visa,2);
        
                        /*
        
                        SELECT SUM(montomaster)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.concepto_id = con.id
                        WHERE mov.num_caja >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 0 // INGRESO
        
                        */
                        //ingresos tarjeta mastercard
        
                        $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('montomaster');
                        round($ingresos_master,2);
        
                        //ingresos total
        
                        $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 0) //ingreso
                                                    ->sum('total');
                        round($ingresos_total,2);
        
                        /*
        
                        SELECT SUM(total)
                        FROM movimiento as mov
                        INNER JOIN concepto as con 
                        ON mov.num_caja = con.id
                        WHERE mov.serie_numero >= 5 
                        and mov.sucursal_id = 1
                        and con.tipo = 1 // EGRESO
        
                        */
                        //egresos
                        $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                                    ->where('tipomovimiento_id',1)
                                                    ->where('estado', "=", 1)
                                                    ->where('sucursal_id', "=", $sucursal_id)
                                                    ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                    ->where('concepto.tipo', "=", 1) //egreso
                                                    ->sum('total');
                        round($egresos,2);
                        //saldo
                        $saldo = round($ingresos_total - $egresos, 2);
                    }
                    $saldo += $montoapertura;
                }

        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Caja';
        $num_caja         = Libreria::getParam($request->input('num_caja'));
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $resultado        = Movimiento::listar($fechainicio,$fechafin,$num_caja, $sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 1);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'ANUL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'NRO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TRABAJADOR', 'numero' => '1');
        $cabecera[]       = array('valor' => 'INGRESOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'EGRESOS', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'USUARIO', 'numero' => '1');
        
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
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
            return view($this->folderview.'.list')->with(compact('montoapertura', 'lista', 'ingresos_efectivo', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo',  'aperturas' , 'cierres' , 'ruta', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'aperturaycierre', 'titulo_eliminar', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('montoapertura', 'lista', 'ingresos_efectivo', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo', 'aperturas' , 'cierres' , 'ruta', 'aperturaycierre', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Caja';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
        $ruta             = $this->rutas;
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal' , 'title', 'titulo_registrar', 'titulo_apertura' , 'titulo_cierre' , 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $titulo_persona   = $this->tituloPersona;
        $ruta             = $this->rutas;
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        $anonimo = Person::find(1);
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('anonimo','titulo_persona','ruta','num_caja' , 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function apertura(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        $cierre_ultimo = Movimiento::where('concepto_id', 2)
                    ->where('sucursal_id', "=", $sucursal_id)
                    ->where('estado', "=", 1)
                    ->where('num_caja',"=", $maxcierre)
                    ->first();
                    
        $boton        = 'Registrar'; 
        return view($this->folderview.'.apertura')->with(compact('persona_id' , 'num_caja', 'movimiento', 'cierre_ultimo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function cierre(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');

        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->person_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        
        $boton        = 'Registrar';
        return view($this->folderview.'.cierre')->with(compact('persona_id' , 'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
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
        if($request->input('concepto_id') == 1){
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'monto'      => 'required|numeric',
                            );
        }else{
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'total'      => 'required|numeric',
                            );
        }
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $movimiento       = new Movimiento();
            $movimiento->tipomovimiento_id = 1;
            $movimiento->concepto_id    = $request->input('concepto_id');
            $movimiento->num_caja   = $request->input('num_caja');
            if($request->input('concepto_id') == 1){
                $movimiento->total          = $request->input('monto');
                $movimiento->subtotal          = $request->input('monto');
            }else{
                $movimiento->total             = $request->input('total');
                $movimiento->subtotal          = $request->input('total');
                $movimiento->montoefectivo    = $request->input('total');
            }
            $movimiento->estado         = 1;
            if($request->input('concepto_id') == 1 || $request->input('concepto_id') == 2){
                $movimiento->trabajador_id     = $request->input('persona_id');
            }else{
                $movimiento->persona_id     = $request->input('persona_id');
            }
            $user           = Auth::user();
            $movimiento->usuario_id     = $user->id;
            $movimiento->sucursal_id   = $request->input('sucursal');
            $movimiento->comentario     = strtoupper($request->input('comentario'));
            $movimiento->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('motivo' => 'required|max:300');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $movimiento = Movimiento::find($id);
            $movimiento->estado = 0;
            $movimiento->comentario_anulado  = strtoupper($request->input('motivo'));  
            $movimiento->save();

            if($movimiento->venta_id != null){
                $movimientoventa = Movimiento::find($movimiento->venta_id);
                $movimientoventa->estado = 0;
                $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                $movimientoventa->save();
            }
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
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movimiento::find($id);
        $entidad  = 'Caja';
        $formData = array('route' => array('caja.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        $mensaje  = '<blockquote><p class="text-danger">¿Está seguro de anular el registro?</p></blockquote>';
        return view('app.caja.confirmarAnular')->with(compact( 'mensaje' ,'modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function clienteautocompletar($searching)
    {
        $type = 'C';
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado = DB::table('personamaestro')
            ->where(function($subquery) use($searching)
            {
                $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE','%'.strtoupper($searching).'%')->orWhere('razonsocial','LIKE','%'.strtoupper($searching).'%');
            })
            ->where(function($subquery) use($type)
            {
                if (!is_null($type)) {
                   
                    $subquery->where('type', '=', $type)->orwhere('secondtype','=', $type)->orwhere('type','=', 'T');
                   
                }		            		
            })
            ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
            ->where('persona.empresa_id', '=', $empresa_id)
            ->whereNull('personamaestro.deleted_at')
            ->orderBy('apellidos', 'ASC')->orderBy('nombres', 'ASC')->orderBy('razonsocial', 'ASC')
            ->take(5);
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->razonsocial != null) {
                $name = $value->razonsocial;
            }else{
                $name = $value->apellidos." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                        );
        }
        return json_encode($data);
    }

    public function generarConcepto(Request $request)
    {
        //QUITAR APERTURA Y CIERRE DE CAJA
        $tipoconcepto_id  = $request->input('tipoconcepto_id');   
        $conceptos = Concepto::where('tipo', '=' , $tipoconcepto_id)
                                ->where('id','!=',1)
                                ->where('id','!=',2)
                                ->where('id','!=',3)
                                ->orderBy('id','ASC')->get();
        $html = "";
        foreach($conceptos as $key => $value){
            $html = $html . '<option value="'. $value->id .'">'. $value->concepto .'</option>';
        }
        return $html;
    }
}
