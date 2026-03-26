<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $primaryKey = 'phones_id';
    public $timestamps = false;

    // Укажите колонку с номером телефона, если она не 'number'
    // protected $fillable = ['number', 'subscriber_id'];
}
