<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $primaryKey = 'subscribers_id'; // Ваш ПК
    public $timestamps = false;

    public function phones()
    {

        return $this->hasMany(Phone::class, 'subscribers_id', 'subscribers_id');
    }
}
