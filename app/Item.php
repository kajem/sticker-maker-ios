<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'code', 'category_id', 'meta_title', 'meta_description', 'author', 'thumb', 'thumb_bg_color', 'stickers', 'total_sticker', 'sort', 'status', 'created_by'
    ];

    // public function author(){
    //     return $this->hasOne('App\Author', 'id', 'author_id')->select('name');
    // }

    // public function item_stickers()
    // {
    //     return $this->hasMany('App\ItemSticker');
    // }

    // public function total_stickers()
    // {
    //     return $this->item_stickers()
    //     ->selectRaw('count("item_id") as total')
    //     ->groupBy('item_id');
    // }
}
