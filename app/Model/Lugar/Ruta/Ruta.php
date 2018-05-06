<?php

namespace App\Model\Lugar\Ruta;

use Illuminate\Database\Eloquent\Model;
use DB;
class Ruta extends Model
{
   protected $table      = 'tbl_ruta';
   protected $primaryKey = 'id';
   protected $hidden     = ['deleted_at','created_at','updated_at'];

   public function scopeGetData($query){
       return $query->select()->get();
   }
   public function scopeGetDepartmentByRoute($query,$isOrigin = true){
       $place = $isOrigin == true ? 'origen_id' : 'destino_id';
       $data  = DB::select("
                       SELECT DISTINCT tbl_departamento.id,
                           tbl_departamento.nombre as name,
                           tbl_departamento.src,
                           (CASE WHEN tbl_ruta.id is NULL THEN '0'
                                 ELSE '1'
                            END) as status
                        FROM tbl_ruta
                        RIGHT JOIN tbl_lugar ON tbl_ruta.$place = tbl_lugar.id
                        RIGHT JOIN tbl_distrito ON tbl_lugar.distirito_id = tbl_distrito.id
                        RIGHT JOIN tbl_provincia ON tbl_distrito.provincia_id = tbl_provincia.id
                        RIGHT JOIN tbl_departamento ON tbl_provincia.departamento_id = tbl_departamento.id
                        ORDER BY tbl_departamento.id,status DESC",[]);
       $data = $this->prettyData($data);
       return $data;
   }
   public function scopeGetPlacesByRoute($query,$departamento_id,$isOrigin = true){
       $place = $isOrigin == true ? 'origen_id' : 'destino_id';
       return  DB::select("
                       SELECT DISTINCT
                            tbl_distrito.id,
                            tbl_distrito.nombre as name,
                            (CASE WHEN tbl_ruta.id is NULL THEN '0'
                            ELSE '1'
                            END) as status,
                            '' as src
                        FROM tbl_ruta
                        RIGHT JOIN tbl_lugar ON tbl_lugar.id = tbl_ruta.$place
                        RIGHT JOIN tbl_distrito ON tbl_distrito.id = tbl_lugar.distirito_id
                        RIGHT JOIN tbl_provincia ON tbl_distrito.provincia_id = tbl_provincia.id
                        RIGHT JOIN tbl_departamento ON tbl_provincia.departamento_id = tbl_departamento.id
                        WHERE tbl_departamento.id = ?
                        ORDER BY status desc",[$departamento_id]);
   }
   public function scopeGetRoute($query,$origen_id,$destino_id){
       return DB::select("
                        SELECT * FROM tbl_ruta
                        WHERE tbl_ruta.origen_id = ? and tbl_ruta.destino_id = ?
                        ",[$origen_id,$destino_id]);
   }
   public function scopeGetTypeOfTransport($query){
       return DB::select("
                        SELECT * FROM tbl_tipo_transporte");
   }
   public function scopeGetCategoryTransport($query,$category_id){
       $from = $category_id==1 ? 1 : 4; $to = $category_id==1 ? 3 : 6;
       return DB::select("
                        SELECT * FROM tbl_categoria
                        WHERE tbl_categoria.id BETWEEN $from AND $to");
   }
   public function scopeGetResponseData($query,$origenId,$destinoId,$categoryId,$fecha,$transporte){
       return DB::select("
                        SELECT
                            Ori.nombre AS origen,
                            Des.nombre AS destino,
                            tbl_ruta.distancia_terrestre AS distancia,
                            tbl_ruta.src,
                            tbl_agencia.nombre AS agencia,
                            tbl_servicio_transporte_categoria.costo,
                            tbl_servicio_transporte_categoria.duracion,
                            CONCAT('2018-05-17 ',tbl_horario.hora) AS fecha,
                            tbl_categoria.nombre AS categoria
                        FROM tbl_servicio_transporte
                        INNER JOIN tbl_ruta ON tbl_ruta.id = tbl_servicio_transporte.ruta_id
                        INNER JOIN tbl_distrito AS Ori ON Ori.id = tbl_ruta.origen_id
                        INNER JOIN tbl_distrito AS Des ON Des.id = tbl_ruta.destino_id
                        INNER JOIN tbl_agencia ON tbl_agencia.id = tbl_servicio_transporte.agencia_id
                        INNER JOIN tbl_servicio_transporte_horario ON tbl_servicio_transporte_horario.servicio_transporte_id = tbl_servicio_transporte.id
                        INNER JOIN tbl_servicio_transporte_categoria ON tbl_servicio_transporte_categoria.servicio_transporte_id =tbl_servicio_transporte.id
                        INNER JOIN tbl_categoria on tbl_categoria.id = tbl_servicio_transporte_categoria.categoria_id
                        INNER JOIN tbl_horario ON tbl_horario.id = tbl_servicio_transporte_horario.horario_id
                        INNER JOIN tbl_dia_laboral ON tbl_dia_laboral.id = tbl_horario.dia_laboral_id
                        WHERE tbl_ruta.origen_id = $origenId AND
                        tbl_ruta.destino_id = $destinoId and WEEKDAY('$fecha')+1 = tbl_dia_laboral.id AND
                        tbl_servicio_transporte_categoria.categoria_id = $categoryId AND
                        tbl_agencia.transporte_id = $transporte AND
                        HOUR(tbl_horario.hora) = HOUR('$fecha') AND
                        (MINUTE(tbl_horario.hora)= MINUTE('$fecha') OR
                        (MINUTE(tbl_horario.hora) - MINUTE('$fecha') >=-7 AND MINUTE(tbl_horario.hora) - MINUTE('$fecha') <=7 ))
                        ",[]);
   }
   public function prettyData($data)
   {
       $idTemp     = -1;
       $statusTemp = 0;
       $array      = [];
       foreach ($data as $key => $value) {
           if(!($idTemp == $value->id && $statusTemp == '1')){
               $array[] = $value;
           }
           $idTemp      = $value->id;
           $statusTemp  = $value->status;
       }
       return collect($array);
   }

}
