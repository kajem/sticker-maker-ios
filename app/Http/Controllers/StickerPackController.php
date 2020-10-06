<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StickerPack;
use App\Item;
//use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redis;

class StickerPackController extends Controller
{
    public function getPack($code){
        $pack_root_folder = config('app.asset_base_url').'items/';
        $is_braincraft_pack = true;
        $param = request()->segment(2);

        $pack = Item::select('id', 'name', 'code', 'tag', 'author', 'stickers')->where('code', $code)->first();

        if(!$pack){
            return abort(404);
        }

        if($param != 'braincraft'){
            $pack_root_folder = url('/').'/storage/sticker-packs/';
            $is_braincraft_pack = false;
            $pack = StickerPack::where('code', $code)->first();
        }

        $data = [
            'pack' => $pack,
            'pack_root_folder' => $pack_root_folder,
            'is_braincraft_pack' => $is_braincraft_pack
        ];

        return view('stickerpack.details')->with($data);
    }

    public function getPackBySlug($slug){
        $pack = Item::select('id', 'name', 'code', 'tag', 'author', 'stickers')->where('slug', $slug)->first();
        if(!$pack){
            return abort(404);
        }

        $data = [
            'pack' => $pack,
            'pack_root_folder' => config('app.asset_base_url').'items/',
            'is_braincraft_pack' => true
        ];

        return view('stickerpack.details')->with($data);
    }

    public function redirectToAppStore($code){
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $android = stripos($useragent, "Android");
        $url = 'https://apps.apple.com/app/id1499262674';
        //if($android){
        //    $url = 'https://play.google.com/store/apps';
        //}
        return redirect($url);
    }
}
