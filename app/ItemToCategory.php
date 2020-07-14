<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemToCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'category_id', 'sort'
    ];
}
