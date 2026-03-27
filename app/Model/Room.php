<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms'; 
    
    protected $primaryKey = 'rooms_id'; 

    public $timestamps = false;

    protected $fillable = [
        'room_name', 
        'room_type', 
        'departments_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'departments_id');
    }
}