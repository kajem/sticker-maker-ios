<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value'
    ];
}
