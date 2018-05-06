<?php

namespace App\Http\Controllers\intranet\ruta;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Lugar\Ruta\Ruta as ruta;
use Exception;
use Session;
use DB;

class cRuta extends Controller
{

    function __construct(){
        // $this->notification = new cNotification();
    }

    public function getResponse(Request $request)
    {
        if($request->has(['origen_id','destino_id','categoria_id','fecha','transporte_id'])){
            $origenId   = $request->origen_id;
            $destinoId  = $request->destino_id;
            $categoryId = $request->categoria_id;
            $fecha      = $request->fecha;
            $transporte = $request->transporte_id;
        }else{
            $origenId   = 0;
            $destinoId  = 0;
            $categoryId = 0;
            $fecha      = 0;
            $transporte = 0;
        }
        $data = ruta::GetResponseData($origenId,$destinoId,$categoryId,$fecha,$transporte);
        return response()->json($data);
    }
    public function getDepartmentOrigin(Request $request)
    {
        $data = ruta::GetDepartmentByRoute(true);
        return response()->json($data);
    }
    public function getPlacesOrigin(Request $request)
    {
        if($request->has('departamento_id')){
            $departamentoId = $request->departamento_id;
        }else{
            $departamentoId = 0;
        }
        $data = ruta::GetPlacesByRoute($departamentoId,true);
        return response()->json($data);
    }
    public function getDepartmentDestination(Request $request)
    {
        $data = ruta::GetDepartmentByRoute(false);
        return response()->json($data);
    }
    public function getPlacesDestination(Request $request)
    {
        if($request->has('departamento_id')){
            $departamentoId = $request->departamento_id;
        }else{
            $departamentoId = 0;
        }
        $data = ruta::GetPlacesByRoute($departamentoId,false);
        return response()->json($data);
    }
    public function getRoute(Request $request){
        if($request->has(['origen_id','destino_id'])){
            $origenId  = $request->origen_id;
            $destinoId = $request->destino_id;
        }else{
            $origenId = 0;
            $destinoId = 0;
        }
        $data = ruta::GetRoute($origenId,$destinoId);
        return response()->json($data);
    }
    public function getTypeOfTransport(Request $request){
        $data = ruta::GetTypeOfTransport();
        return response()->json($data);
    }
    public function getCategoryTransport(Request $request){
        if($request->has('category_id')){
            $caategoryId  = $request->category_id;
        }else{
            $caategoryId = 0;
        }
        $data = ruta::GetCategoryTransport($caategoryId);
        return response()->json($data);
    }
}
