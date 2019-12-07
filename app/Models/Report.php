<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'description', 'user_id'
    ];

    //Relación Usuarios
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
}
