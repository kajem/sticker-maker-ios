<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiPassword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'created_by'
    ];
}
