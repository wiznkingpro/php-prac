<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
   use HasFactory;

   protected $primaryKey = 'users_id'; 

   public $timestamps = false;
   protected $fillable = [
        'login',
        'last_name',
       'first_name',
       'middle_name',
       'role',
       'departments_id',
       'status',
       'password'
   ];

   protected static function booted()
   {
       static::created(function ($user) {
           $user->password = md5($user->password);
           $user->save();
       });
   }

   public function findIdentity(int $id)
   {
       return self::where('users_id', $id)->first();
   }

   public function getId(): int
   {
       return $this->users_id;
   }

    public function attemptIdentity(array $credentials)
    {
        return self::where([
            'last_name' => $credentials['last_name'], 
            'password' => md5($credentials['password'])
        ])->first();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'departments_id');
    }


}