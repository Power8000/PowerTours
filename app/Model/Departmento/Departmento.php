<?php

namespace App\Model\Department0;

use Illuminate\Database\Eloquent\Model;

class Departmento extends Model
{
   protected $table      = 'tbl_departmento';
   protected $primaryKey = 'id';
   protected $hidden     = ['deleted_at','created_at','updated_at'];

}
