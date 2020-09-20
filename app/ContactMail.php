<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'from_email', 'to_email', 'subject', 'message', 'unread'
    ];
}
