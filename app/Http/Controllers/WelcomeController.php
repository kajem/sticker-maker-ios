<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;

use App\Item;
use App\ItemSticker;

class WelcomeController extends Controller
{
    public function index(){
        $items = Item::all();
        $sticker_arr = [];
        foreach($items as $item){
            $stickers = ItemSticker::where('item_id', $item->id)->get();
            foreach($stickers as $sticker){
                if(!empty($sticker->path))
                    $sticker_arr[] = $sticker->path;
            }
        }
        $data = [
            'stickers' => $sticker_arr
        ];
        return view('home')->with($data);
    }

    public function getSticker($code, $name){
        //echo $name;exit;
        return $this->getDataFromAPI("items/".$code."/".$name);
    }

    public function cacheClear()
    {
        //Artisan::call('storage:link');
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        echo "Cache cleared successfully. Thank you.";exit;
    }
}
