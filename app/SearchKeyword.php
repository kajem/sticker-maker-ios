<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchKeyword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_item_found', 'count'
    ];
}
