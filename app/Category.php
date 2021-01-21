<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'text', 'thumb', 'thumb_v', 'thumb_bg_color', 'items', 'stickers', 'sort', 'sort2', 'status', 'created_by'
    ];
}
