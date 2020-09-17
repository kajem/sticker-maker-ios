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
        $items = Item::select('code', 'name', 'thumb', 'author', 'total_sticker')
                     ->where('status', 1)->limit(18)->get();
        $data = [
            'asset_base_url' => config('app.asset_base_url'),
            'items' => $items
        ];
        return view('welcome')->with($data);
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
