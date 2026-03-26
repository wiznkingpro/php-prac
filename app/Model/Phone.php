<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $primaryKey = 'phones_id';
    public $timestamps = false;
    protected $fillable = ['phone_number', 'subscribers_id', 'device_id', 'status'];

}
