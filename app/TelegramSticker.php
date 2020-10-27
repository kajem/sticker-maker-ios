<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramSticker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'sticker', 'error_code', 'description', 'is_success'
    ];
}
