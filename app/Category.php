<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'text', 'thumb', 'thumb_v', 'thumb_bg_color', 'items', 'stickers', 'sort', 'sort2', 'status', 'created_by'
    ];

    public function routeNotificationForSlack($notification)
    {
        return config('app.slack_hook');
    }
}
