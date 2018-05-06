<?php

namespace App\Model\Departmento\Provincia;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
   protected $table      = 'tbl_provincia';
   protected $primaryKey = 'id';
   protected $hidden     = ['deleted_at','created_at','updated_at'];

}
