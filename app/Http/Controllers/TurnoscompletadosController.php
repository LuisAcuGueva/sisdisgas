<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Concepto;
use App\Turnorepartidor;
use App\Person;
use App\Sucursal;
use App\Movimiento;
use App\Detalleturnopedido;
use App\Detalleventa;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TurnoscompletadosController extends Controller
{

    protected $folderview      = 'app.turnoscompletados';
    protected $tituloAdmin     = 'Turnos de repartidores completados';
    protected $tituloDetalle  = 'Detalle de Turno de Repartidor';
    protected $rutas           = array('detalle' => 'turno.detalle', 
            'detalleturno' => 'turnoscompletados.detalleturno', 
            'search'   => 'turnoscompletados.buscar',
            'index'    => 'turnoscompletados.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $desde            = Libreria::getParam($request->input('desde'));
        $hasta            = Libreria::getParam($request->input('hasta'));
        $entidad          = 'Turnorepartidor';
        $trabajador_id         = Libreria::getParam($request->input('trabajador_id'));
        $lista            = array();
        $ingresos_repartidor = 0.00;
        $ingresos_credito = 0.00;
        $vueltos_repartidor = 0.00;
        $total_ingresos = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        if($trabajador_id != null){
            $resultado        = Turnorepartidor::turnoscompletados($trabajador_id, $desde, $hasta);
            $lista            = $resultado->get();

 /*           $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total');
                                                    
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            round($ingresos_repartidor,2);
            round($ingresos_credito,2);
            round($vueltos_repartidor,2);
            round($egresos_repartidor,2);

            $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

            $saldo_repartidor = $ingresos_repartidor + $ingresos_credito + $vueltos_repartidor - $egresos_repartidor;

            round($saldo_repartidor,2);*/

        }
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA INICIO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA FIN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CIERRE SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SALDO', 'numero' => '1');

        $tituloDetalle = $this->tituloDetalle;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_credito', 'ingresos_repartidor', 'total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloDetalle', 'ruta'));
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
        $entidad          = 'Turnorepartidor';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $empleados = Person::where('tipo_persona','T')->get();
        $cboSucursal      = Sucursal::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'empleados', 'cboSucursal','title', 'ruta'));
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
    public function detalleturno(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'turno_repartidor');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $turno    = Turnorepartidor::find($id);
        $entidad  = 'Turnodetalle';
        $formData = array('turnoscompletados.buscardetalles', $id);
        $formData = array('route' => $formData, 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formBusqueda'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.detalle')->with(compact('turno', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function buscardetalles(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Turnodetalle';
        $turno_id         = Libreria::getParam($request->input('turno_id'));
        $lista            = array();
        $ingresos_repartidor = 0.00;
        $ingresos_credito = 0.00;
        $vueltos_repartidor = 0.00;
        $total_ingresos = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        if($turno_id != null){
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_id);
            $lista            = $resultado->get();

            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total');
                                                    
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            round($ingresos_repartidor,2);
            round($ingresos_credito,2);
            round($vueltos_repartidor,2);
            round($egresos_repartidor,2);

            $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

            $saldo_repartidor = $ingresos_repartidor + $ingresos_credito + $vueltos_repartidor - $egresos_repartidor;

            round($saldo_repartidor,2);

        }
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'TOTAL', 'numero' => '1');

        $tituloDetalle = $this->tituloDetalle;
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
            return view($this->folderview.'.listdetalle')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_credito', 'ingresos_repartidor', 'total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloDetalle', 'ruta'));
        }
        return view($this->folderview.'.listdetalle')->with(compact('lista', 'entidad'));
    }

    public function cargarnumerocaja(Request $request){
        $sucursal_id  = $request->input('sucursal_id');
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        return $num_caja;
    }

    public function generarSaldoRepartidor(Request $request){
        $persona_id         = Libreria::getParam($request->input('persona_id'));
        $ingresos_repartidor = 0.00;
        $egresos_repartidor = 0.00;
        $saldo_repartidor = 0.00;
        $turno_repartidor_max_id = Turnorepartidor::where('trabajador_id', $persona_id)->max("id");
        $turno_repartidor = Turnorepartidor::find($turno_repartidor_max_id);
        if($turno_repartidor != null){
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id);
            $lista            = $resultado->get();

            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3)->orwhere('concepto.id','=', 12)->orwhere('concepto.id','=', 15)->orwhere('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_repartidor->id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');

            $saldo_repartidor = $ingresos_repartidor - $egresos_repartidor;

        }
        return $saldo_repartidor;
    }

}
