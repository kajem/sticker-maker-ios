<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'subtitle', 'type', 'slug', 'author', 'tags', 'meta_title', 'meta_description', 'banner', 'banner_alt',
        'short_description', 'description', 'published_date', 'related_posts', 'status', 'created_by'
    ];
}
