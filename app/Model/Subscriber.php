<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $primaryKey = 'subscribers_id'; 

    public $timestamps = false;

   protected $fillable = [
    'last_name',
    'first_name',
    'middle_name',
    'birth_date',
    'status',
    'rooms_id',
    'users_id' 
];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    // Связь с телефонами
    public function phones()
    {
        return $this->hasMany(Phone::class, 'subscribers_id', 'subscribers_id');
    }
}