<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSticker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'file_name', 'path'
    ];
}
