<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $primaryKey = 'phones_id';
    public $timestamps = false;
    protected $fillable = ['phone_number', 'subscribers_id', 'device_id', 'status'];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscribers_id', 'subscribers_id');
    }
}