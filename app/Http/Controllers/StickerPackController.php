<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StickerPack;
use App\Item;
use Intervention\Image\Facades\Image;

class StickerPackController extends Controller
{
    public function getPack($code){
        $pack = StickerPack::where('code', $code)->first();

        //START: Generating Thumbnails if not exist
        if(!empty($pack->stickers)){
            $root_folder = base_path().'/storage/app/public/sticker-packs/'.$code.'/';
            $width = 256;
            $stickers = json_decode($pack->stickers);

            if(!file_exists($root_folder.$width.'__'.$stickers[0])){
                foreach($stickers as $sticker){
                    $original_file_path = $root_folder.$sticker;
                    $thumb_file_path = $root_folder.$width.'__'.$sticker;

                    //Resize image for desired width
                    if (mime_content_type ( $original_file_path ) == 'image/gif') {
                        $im = new \Imagick($original_file_path);
                        $im = $im->coalesceImages();
        
                        do {
                            $im->resizeImage($width, null, \Imagick::FILTER_BOX, 1);
                        } while ($im->nextImage());
        
                        $im = $im->deconstructImages();
        
                        $im->writeImages($thumb_file_path, true);
                    } else {
                        $thumbnailImage = Image::make($original_file_path)->widen($width, function ($constraint) {
                            $constraint->upsize();
                        })->save($thumb_file_path);
                    }
                }
            }
        }
        //END: Generating Thumbnails if not exist

        return view('stickerpack.details')->with(['pack' => $pack]);
    }

    public function getBraincraftPack($code){
        $pack = Item::where('code', $code)->first();

       // echo "<pre>"; print_r($pack);exit;

        //START: Generating Thumbnails if not exist
        if(!empty($pack->stickers)){
            $root_folder = base_path().'/storage/app/public/items/'.$code.'/';
            $width = 256;
            $stickers = unserialize($pack->stickers);

            if(!file_exists($root_folder.$width.'__'.$stickers[0])){
                foreach($stickers as $sticker){
                    $original_file_path = $root_folder.$sticker;
                    $thumb_file_path = $root_folder.$width.'__'.$sticker;

                    //Resize image for desired width
                    if (mime_content_type ( $original_file_path ) == 'image/gif') {
                        $im = new \Imagick($original_file_path);
                        $im = $im->coalesceImages();
        
                        do {
                            $im->resizeImage($width, null, \Imagick::FILTER_BOX, 1);
                        } while ($im->nextImage());
        
                        $im = $im->deconstructImages();
        
                        $im->writeImages($thumb_file_path, true);
                    } else {
                        $thumbnailImage = Image::make($original_file_path)->widen($width, function ($constraint) {
                            $constraint->upsize();
                        })->save($thumb_file_path);
                    }
                }
            }
        }
        //END: Generating Thumbnails if not exist

        return view('stickerpack.braincraft-pack-details')->with(['pack' => $pack]);
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
