<?php

namespace App\Model\Lugar;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
   protected $table      = 'tbl_lugar';
   protected $primaryKey = 'id';
   protected $hidden     = ['deleted_at','created_at','updated_at'];

   public function scopeGetData($query){
       return $query->select()->get();
   }

}
