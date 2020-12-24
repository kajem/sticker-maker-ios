<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\Request;

use App\Item;
use App\StaticValue;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{

    public function index(){
        $home_sticker_packages = StaticValue::select('value')->where('name', 'website_home_page_packages')->first();
        $items = Item::select('name', 'slug', 'code', 'thumb', 'author', 'total_sticker')
                     ->whereIn('id', explode(",", $home_sticker_packages->value))
                     ->where('status', 1)
                     ->orderByRaw(DB::raw("FIELD(id, $home_sticker_packages->value)"))
                     ->get();
        //dd($home_sticker_packages->value);
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
