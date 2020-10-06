<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Telegram;
use App\Item;

class TelegramController extends Controller
{
    public function createNewStickerSetView($id){
//        $telegram = new Telegram(config('services.telegram.bot_token'));
//        $set = $telegram->getStickerSet(['Cute_Boy_by_brain_2015_bot']);
        $item = Item::find($id);
        if (empty($item)) {
            return back()->with('error', 'Invalid action!');
        }
        dd($item);
        $data = [
            'title' => 'Editing item: ' . $item->name,
            'item' => $item
        ];
        return view('admin.item.form')->with($data);
    }
}
