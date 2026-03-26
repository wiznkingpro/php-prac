<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   // Имя таблицы в вашей базе данных
   protected $table = 'departments';

   // Название первичного ключа
   protected $primaryKey = 'departments_id';

   // Отключаем автоматические колонки времени (created_at, updated_at), 
   // если их нет в вашей таблице
   public $timestamps = false;

   // Поля, разрешенные для массового заполнения (через создание)
   protected $fillable = [
       'type',
       'name'
   ];
}