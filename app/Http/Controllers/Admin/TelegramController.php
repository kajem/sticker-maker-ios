<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Telegram;
use App\Item;

class TelegramController extends Controller
{
    public function telegramPack($id){
        $telegram = new Telegram(config('services.telegram.bot_token'));
        //Create new Sticker Set
//        $data = [
//            'user_id' => config('services.telegram.user_id'),
//            'name' => 'test_chicken_cj7170_by_brain_2015_bot',
//            'title' => 'Chicken',
//            'png_sticker' => 'https://static.stickermakerpro.com/items/CJ7170/ChickenA1.png',
//            'emojis' => '🙂'
//        ];

        //$new_sticker_set = $telegram->createNewStickerSet($data);
        //dd($new_sticker_set);

        //Add sticker to set
//        $data = [
//            'user_id' => config('services.telegram.user_id'),
//            'name' => 'test_chicken_cj7170_by_brain_2015_bot',
//            'png_sticker' => 'https://static.stickermakerpro.com/items/CJ7170/ChickenA10.png',
//            'emojis' => '🙂'
//        ];
//        $add_sticker_to_set = $telegram->addStickerToSet($data);
//        dd($add_sticker_to_set);



        //get Sticker set
//        $data = [
//            'name' => 'test_chicken_cj7170_by_brain_2015_bot',
//        ];
//        $get_sticker_set = $telegram->getStickerSet($data);
//        dd($get_sticker_set);

        //Delete sticker from set
//        $data = [
//            'sticker' => 'CAACAgUAAxUAAV99yma6Y116yECI-enCGjtq4rH6AAIpAAPW9w40uZoXRBbdLw8bBA'
//        ];
//        $delete_sticker_from_set = $telegram->deleteStickerFromSet($data);
//        dd($delete_sticker_from_set);

        //Set sticker position in set
//        $data = [
//            'sticker' => 'CAACAgUAAxUAAV9-2o3UouW1PFK8DfzY9AqWNeAYAAIpAAPW9w40uZoXRBbdLw8bBA',
//            'position' => 1
//        ];
//        $set_sticker_position_in_set = $telegram->setStickerPositionInSet($data);
//        dd($set_sticker_position_in_set);




        $item = Item::select('id', 'name', 'telegram_name', 'code', 'stickers')->where('id', $id)->first();
        if (empty($item)) {
            return back()->with('error', 'Invalid action!');
        }

        $name = $item->telegram_name;
        if(empty($item->telegram_name)){
            $name = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], trim(preg_replace('/[0-9]+/', '', $item->name))).'_'.$item->code.'_by_'.config('services.telegram.user_name'));
        }

        $data = [
            'title' => 'Telegram Pack: ' . $item->name,
            'telegram_name' => $name,
            'pack_root_folder' => config('app.asset_base_url').'items/',
            'item' => $item
        ];
        return view('admin.telegram.form')->with($data);
    }

    public function createNewStickerSet(Request $request){
        echo "<pre>"; print_r($request->all());exit;
    }
}


