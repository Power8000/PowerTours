<?php

namespace App\Model\Departmento\Provincia\Distrito;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
   protected $table      = 'tbl_distrito';
   protected $primaryKey = 'id';
   protected $hidden     = ['deleted_at','created_at','updated_at'];

   public function scopeGetData($query){
       return $query->select('id','nombre')->get();
   }

}
