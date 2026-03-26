<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    protected $primaryKey = 'device_id';
    public $timestamps = false;
    protected $fillable = ['model_name', 'mac_address', 'inventory_number', 'device_type'];
}