<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   protected $table = 'departments';
   protected $primaryKey = 'departments_id';
   public $timestamps = false;
   protected $fillable = [
       'type',
       'name'
   ];
}