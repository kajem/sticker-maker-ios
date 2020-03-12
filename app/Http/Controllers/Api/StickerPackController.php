<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StickerPack;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StickerPackController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('api_auth');
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
                            'name'=>'required',
                            'author' => 'required',
                            'stickers' => 'array|required',
                            'stickers.*' => 'image|mimes:jpeg,png,jpg,gif,bmp,webp'
                        ]);
        if($validator->fails())
        {
            return $this->errorOutput($validator->errors()->all());
        }

        $stickers = $request->file('stickers');
        $code = $this->uniqueCode();
        $count = 1;
        $sticker_names = [];
        foreach ($stickers as $sticker){
            $imageName = $count.'.'.$sticker->getClientOriginalExtension();
            $destination_thumb_path = 'public/sticker-packs/'.$code.'/'.$imageName;
            $uploaded = Storage::put($destination_thumb_path, file_get_contents($sticker->getRealPath()));
            if($uploaded){
                $sticker_names[] = $imageName;
                $count++;
            }else{
                return $this->errorOutput('Something went wrong with sticker images');
            }
        }

        $data = [
            'name' => $request->get('name'),
            'author' => $request->get('author'),
            'code' => $code,
            'stickers' => json_encode($sticker_names)
        ];

        $pack = StickerPack::create($data);

        if(!empty($pack)){
            return $this->successOutput([
                'code' => $code,
                'url' => url('/').'/pack/'.$code
            ]);
        }
    }

    private function uniqueCode($size = 6){
        $alpha_key = '';
        $keys = range('A', 'Z');
        
        for ($i = 0; $i < 2; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }
        
        $length = $size - 2;
        
        $key = '';
        $keys = range(0, 9);
        
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        $code = $alpha_key . $key;

        if(!empty(StickerPack::select('id')->where('code', $code)->first()->id)){
            $this->uniqueCode();
        }
        
        return $code;
    }

    public function getPack($code){
        $pack = StickerPack::select('name', 'author',  'stickers')->where('code', $code)->first();
        if(empty($pack->name))
            return $this->errorOutput('Invalid code!');

        $data = [
            'name' => $pack->name,
            'code' => $code,
            'stickers_path' => url('/').'/storage/sticker-packs/'.$code.'/',
            'stickers' => json_decode($pack->stickers),
        ];

        return $this->successOutput($data);
    }
}
