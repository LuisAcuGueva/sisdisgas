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
use App\Tipodocumento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Elibyy\TCPDF\Facades\TCPDF;

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
            $resultado        = Detalleturnopedido::where('turno_id', '=', $turno_id)
            ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
            ->orderby('fecha', 'DESC');
            $lista            = $resultado->get();

            $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 3);
                                                            })
                                                        ->sum('total');
                                                    
            $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 16);
                                                            })
                                                        ->sum('total');

            $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                                                            })
                                                        ->sum('total');

            $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                                        ->where('estado',1)
                                                        ->where(function($subquery)
                                                            {
                                                                $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                                                            })
                                                        ->sum('total');


            $gastos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                                                        ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                                                        ->where('estado',1)
                                                        ->where('tipomovimiento_id',6)
                                                        ->sum('total');

            round($ingresos_repartidor,2);
            round($ingresos_credito,2);
            round($vueltos_repartidor,2);
            round($egresos_repartidor,2);
            round($gastos_repartidor,2);

            $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

            $saldo_repartidor = $ingresos_repartidor + $ingresos_credito + $vueltos_repartidor - $egresos_repartidor - $gastos_repartidor;

            round($saldo_repartidor,2);

        }
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'VER', 'numero' => '1');
        $cabecera[]       = array('valor' => 'FECHA Y HORA', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CONCEPTO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CLIENTE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DIRECCIÓN', 'numero' => '1');
        $cabecera[]       = array('valor' => 'VALE', 'numero' => '1');
        $cabecera[]       = array('valor' => 'SUCURSAL', 'numero' => '1');
        $cabecera[]       = array('valor' => 'COMENTARIO', 'numero' => '1');
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
            return view($this->folderview.'.listdetalle')->with(compact('lista', 'paginacion', 'inicio', 'ingresos_credito', 'ingresos_repartidor', 'gastos_repartidor','total_ingresos', 'egresos_repartidor', 'vueltos_repartidor','saldo_repartidor','fin', 'entidad', 'cabecera', 'tituloDetalle', 'ruta'));
        }
        return view($this->folderview.'.listdetalle')->with(compact('lista', 'entidad'));
    }

    public function pdfDetalleTurno(Request $request){

        $turno_id = $request->input('turno_id');
        $turno    = Turnorepartidor::find($turno_id);
        $rst         = Detalleturnopedido::where('turno_id', '=', $turno->id)
                                        ->get();

        $ingresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                ->where('estado',1)
                ->where(function($subquery)
                    {
                        $subquery->where('concepto.id','=', 3);
                    })
                ->sum('total');
            
        $ingresos_credito = Detalleturnopedido::where('turno_id', '=', $turno_id)
                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                ->where('estado',1)
                ->where(function($subquery)
                    {
                        $subquery->where('concepto.id','=', 16);
                    })
                ->sum('total');

        $vueltos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                ->where('estado',1)
                ->where(function($subquery)
                    {
                        $subquery->where('concepto.id','=', 12)->orwhere('concepto.id','=', 15);
                    })
                ->sum('total');

        $egresos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                ->where('estado',1)
                ->where(function($subquery)
                    {
                        $subquery->where('concepto.id','=', 13)->orwhere('concepto.id','=', 14);
                    })
                ->sum('total');

        $gastos_repartidor = Detalleturnopedido::where('turno_id', '=', $turno_id)
                ->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
                ->where('estado',1)
                ->where('tipomovimiento_id',6)
                ->sum('total');

        round($ingresos_repartidor,2);
        round($ingresos_credito,2);
        round($vueltos_repartidor,2);
        round($egresos_repartidor,2);
        round($gastos_repartidor,2);

        $total_ingresos = $ingresos_repartidor + $vueltos_repartidor + $ingresos_credito;

        $saldo_repartidor = $ingresos_repartidor + $ingresos_credito + $vueltos_repartidor - $egresos_repartidor - $gastos_repartidor;

        round($saldo_repartidor,2);

        $inicio = date("d/m/Y h:i:s a",strtotime($turno->inicio)) ;
        $fin = date("h:i:s a",strtotime($turno->fin)) ;

        $nombrepdf = "DETALLE DE TURNO DE REPARTIDOR: " . $turno->person->apellido_pat . " ". $turno->person->apellido_mat . " " .$turno->person->nombres. " - " . $inicio . " - " . $fin;

        $totalingresos     = 0;
        $totalvuelto   = 0;
        $totalegresos  = 0;
        
        $pdf = new TCPDF();
        $pdf::SetTitle($nombrepdf);
        $pdf::AddPage('L');
        $pdf::SetFont('helvetica','B',12);
        $pdf::Cell(0,10,$nombrepdf,0,0,'C');
        $pdf::Ln();
        $pdf::SetFont('helvetica','B',7);
        $pdf::Cell(10,7,utf8_decode("NRO"),1,0,'C');
        $pdf::Cell(25,7,utf8_decode("FECHA"),1,0,'C');
        $pdf::Cell(55,7,utf8_decode("CLIENTE"),1,0,'C');
        $pdf::Cell(28,7,utf8_decode("COMPROBANTE"),1,0,'C');
        $pdf::Cell(45,7,utf8_decode("CONCEPTO"),1,0,'C');
        $pdf::Cell(20,7,utf8_decode("SUCURSAL"),1,0,'C');
        $pdf::Cell(15,7,utf8_decode("VALE"),1,0,'C');
        $pdf::Cell(15,7,utf8_decode("INGRESO"),1,0,'C');
        $pdf::Cell(15,7,utf8_decode("EGRESO"),1,0,'C');
        $pdf::Cell(50,7,utf8_decode("COMENTARIO"),1,0,'C');

        $cont = 1;

        if(count($rst)>0){
            $pdf::SetFont('helvetica','B',8.5);
            $pdf::Ln();
            foreach ($rst as $row) { 
                $pdf::SetFont('helvetica','',6);   
                $pdf::Cell(10,7, $cont ,1,0,'C');   
                $cont++;                              
                $pdf::Cell(25,7,date("d/m/Y h:i:s a", strtotime($row->pedido->fecha)),1,0,'C');
                $nombrepersona = "";
                if(!is_null($row->pedido->persona_id)){
                    $persona = Person::find($row->pedido->persona_id);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }else if(!is_null($row['trabajador_id'])){
                    $persona = Person::find($row->pedido->trabajador_id);
                    if($persona->dni == null){
                        $nombrepersona = $persona->razon_social;
                    }else{
                        $nombrepersona = $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres;
                    }
                }
                $pdf::Cell(55,7,$nombrepersona,1,0,'L');
                $tipodoc = Tipodocumento::find($row->pedido->tipodocumento_id);
                if(!is_null($tipodoc)){
                    $pdf::Cell(10,7,$tipodoc->abreviatura,1,0,'C');
                }else{
                    $pdf::Cell(10,7,"",1,0,'C');
                }
                $pdf::Cell(18,7, $row->pedido->num_venta ,1,0,'C');
                $concepto = Concepto::find($row->pedido->concepto_id);
                $pdf::Cell(45,7,$concepto->concepto,1,0,'L');

                $pdf::Cell(20,7,$row->pedido->sucursal->nombre,1,0,'C');

                if($row->pedido->vale_balon_subcafae == 1){
                    $pdf::Cell(15,7,"SUBCAFAE",1,0,'C');
                }else if($row->pedido->vale_balon_fise == 1){
                    $pdf::Cell(15,7,"FISE",1,0,'C');
                }else if($row->pedido->vale_balon_monto == 1){
                    $pdf::Cell(15,7,"MONTO",1,0,'C');
                }else{
                    $pdf::Cell(15,7,"",1,0,'C');
                }

                if($row->pedido->estado == 1) {

                    if(($row->pedido->tipomovimiento_id == 1 || $row->pedido->tipomovimiento_id == 2 || $row->pedido->tipomovimiento_id == 5) && $row->pedido->concepto->tipo != 0 || $row->pedido->concepto_id == 3 || $row->pedido->concepto_id == 16  ){
                        $pdf::Cell(15,7,number_format($row->pedido->total,2,'.',''),1,0,'C');
                        $totalvuelto += number_format($row->pedido->total,2,'.','');
                        $pdf::Cell(15,7,"",1,0,'R');
                    }else if( $row->pedido->tipomovimiento_id == 6 ){
                        $pdf::Cell(15,7,"",1,0,'R');
                        $pdf::Cell(15,7,number_format($row->pedido->total,2,'.',''),1,0,'C');
                        $totalvuelto += number_format($row->pedido->total,2,'.','');
                    }else{
                        $pdf::Cell(15,7,"",1,0,'R');
                        $pdf::Cell(15,7,number_format($row->pedido->total,2,'.',''),1,0,'C');
                        $totalvuelto += number_format($row->pedido->total,2,'.','');
                    }
                    $pdf::Cell(50,7,$row->pedido->comentario,1,0,'L');
                }else{
                    $pdf::Cell(30,7,'ANULADO',1,0,'C');
                    $pdf::Cell(50,7,$row->pedido->comentario_anulado,1,0,'L');
                }
                $pdf::Ln();                  
            } 

        }
        
        
        $pdf::SetFont('helvetica','',7);   
        $pdf::Ln();

        $user = Auth::user();
        $persona = Person::find($user->person_id);

        $pdf::Cell(100,7,('RESPONSABLE: '. $persona->apellido_pat . " " . $persona->apellido_mat . " " . $persona->nombres),0,0,'L');
        $pdf::SetFont('helvetica','B',9);
        $pdf::Cell(80,7,utf8_decode("RESUMEN DE TURNO DEL REPARTIDOR"),1,0,'C');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("MONTO VUELTOS:"),1,0,'L');
        $pdf::Cell(20,7,number_format($vueltos_repartidor,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("INGRESO DE PEDIDOS:"),1,0,'L');
        $pdf::Cell(20,7,number_format($ingresos_repartidor,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("INGRESO DE PEDIDOS A CREDITO:"),1,0,'L');
        $pdf::Cell(20,7,number_format($ingresos_credito,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("TOTAL INGRESOS:"),1,0,'L');
        $pdf::Cell(20,7,number_format($total_ingresos,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("GASTOS DEL REPARTIDOR:"),1,0,'L');
        $pdf::Cell(20,7,number_format($gastos_repartidor ,2,'.',''),1,0,'R');
        $pdf::Ln();
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("EGRESOS A CAJA:"),1,0,'L');
        $pdf::Cell(20,7,number_format($egresos_repartidor ,2,'.',''),1,0,'R');
        $pdf::Ln();/*
        $pdf::Cell(100,7,utf8_decode(""),0,0,'C');
        $pdf::Cell(60,7,utf8_decode("SALDO:"),1,0,'L');
        $pdf::Cell(20,7,number_format($saldo_repartidor,2,'.',''),1,0,'R');
        $pdf::Ln();*/

        $pdf::Output('ReporteCierreTurno.pdf');
    }

}
