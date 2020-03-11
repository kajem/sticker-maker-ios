<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StickerPack;

class StickerPackController extends Controller
{
    public function getPack($code){
        $pack = StickerPack::where('code', $code)->first();
        return view('stickerpack.details')->with(['pack' => $pack]);
    }
}
