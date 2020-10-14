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

        //dd($new_sticker_set);


//        dd($add_sticker_to_set);




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

    /*
     * @desc This method crete a new sticker set.
     * If sticker set exist with current name then it will delete existing stickers and then again add new stickers to set
     */
    public function createNewStickerSet(Request $request){
        //Get the item
        $item = Item::select('name')->where('id', $request->get('id'))->first();
        if(empty($item->name)){
            return $this->errorOutput('Invalid Pack!');
        }
        $success_msg = '';
        $success_data = [];
        $telegram = new Telegram(config('services.telegram.bot_token'));

        if($request->get('is_first_request') == 1){
            $data = [
                'name' => $request->get('telegram_name'),
            ];
            $sticker_set = $telegram->getStickerSet($data); //get Sticker set

            if(!empty($sticker_set['ok'])){
                foreach ($sticker_set['result']['stickers'] as $sticker){
                    $data = [
                        'sticker' => $sticker['file_id']
                    ];
                    $telegram->deleteStickerFromSet($data);  //Deleting sticker from set
                }
                $this->addStickerToSet($request->get('telegram_name'), $request->get('png_sticker'));
            }else{
                //Create new Sticker Set
                $data = [
                    'user_id' => config('services.telegram.user_id'),
                    'name' => $request->get('telegram_name'),
                    'title' => $item->name,
                    'png_sticker' => $request->get('png_sticker'),
                    'emojis' => '🙂'
                ];
                $telegram->createNewStickerSet($data);
            }
        }else{

            $success_data = $this->addStickerToSet($request->get('telegram_name'), $request->get('png_sticker'));

            if($request->get('is_last_request') == 1){
                $data = [
                    'telegram_name' => $request->get('telegram_name'),
                    'is_telegram_set_completed' => 1
                ];
                Item::where('id', $request->get('id'))->update($data);

                $success_data = [
                    'telegram_url' => config('services.telegram.set_base_url').$request->get('telegram_name')
                ];
                $success_msg = 'Sticker set successfully created on Telegram';
            }
        }

        return $this->successOutput($success_data, $success_msg);
    }

    /*
     * @desc Telegram: Add sticker to set
     */
    private function addStickerToSet($telegram_name, $png_sticker){
        $telegram = new Telegram(config('services.telegram.bot_token'));
        $data = [
            'user_id' => config('services.telegram.user_id'),
            'name' => $telegram_name,
            'png_sticker' => $png_sticker,
            'emojis' => '🙂'
        ];
        return $telegram->addStickerToSet($data);
    }
}


