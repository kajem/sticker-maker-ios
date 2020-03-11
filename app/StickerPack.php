<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StickerPack extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'author', 'code', 'stickers'
    ];
}
