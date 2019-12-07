<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'id_rol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relación Usuarios
    public function reportes()
	{
	    return $this->belongsToMany('App\Models\User');
	}

    //Relación Roles
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();
    }
    
    //Comprobar si tiene un rol asignado
    public function hasRole($rol)
    {
        if ($this->roles()->where('name', $rol)->first()) {
            return true;
        }
        return false;
    }

    //Comprobar si tiene algún rol asignado
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
    }

    //Autorizar o negar el acceso
    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        return false;
    }


}
