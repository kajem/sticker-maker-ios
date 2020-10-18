<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Telegram;
use App\Item;
use App\TelegramSticker;

class TelegramController extends Controller
{
    public function telegramPack($id){
        $item = Item::select('id', 'name', 'telegram_name', 'code', 'stickers', 'total_sticker', 'is_telegram_set_completed')->where('id', $id)->first();
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
        $item = Item::select('name', 'total_sticker')->where('id', $request->get('id'))->first();
        if(empty($item->name)){
            return $this->errorOutput('Invalid Pack!');
        }
        $success_msg = '';
        $success_data = [];
        $telegram = new Telegram(config('services.telegram.bot_token'));

        if($request->get('is_first_request') == 1){
            //Check if telegram set is already created
            $sticker_set = $this->getStickerSet($request->get('telegram_name'));
            if(!empty($sticker_set['ok'])){
                foreach ($sticker_set['result']['stickers'] as $sticker){
                    $data = [
                        'sticker' => $sticker['file_id']
                    ];
                    $telegram->deleteStickerFromSet($data);  //Deleting sticker from set
                }
                $success_data = $this->addStickerToSet($request->get('telegram_name'), $request->get('png_sticker'));
                TelegramSticker::where('item_id', $request->get('id'))->delete(); //Delete all rows form TelegramSticker table
            }else{
                //Create new Sticker Set
                $data = [
                    'user_id' => config('services.telegram.user_id'),
                    'name' => $request->get('telegram_name'),
                    'title' => $item->name,
                    'png_sticker' => $request->get('png_sticker'),
                    'emojis' => '🙂'
                ];
                $success_data = $telegram->createNewStickerSet($data);
            }
            //Update the item with telegram name
            $data = [
                'telegram_name' => $request->get('telegram_name')
            ];
            Item::where('id', $request->get('id'))->update($data);
        }else{

            $success_data = $this->addStickerToSet($request->get('telegram_name'), $request->get('png_sticker'));

            //For last sticker of the set, checking few validation
            if($request->get('is_last_request') == 1){
                //Get the sticker
                $sticker_set = $this->getStickerSet($request->get('telegram_name'));
                if(!empty($sticker_set['ok'])) {
                    //If pack sticker count and telegram sticker set count is same then update item table
                    if(count($sticker_set['result']['stickers']) === $item->total_sticker){
                        $data = [
                            'is_telegram_set_completed' => 1
                        ];
                        Item::where('id', $request->get('id'))->update($data);
                    }
                }

                $success_data ['telegram_sticker_set_url'] = config('services.telegram.set_base_url').$request->get('telegram_name');
                $success_msg = 'Sticker set successfully created on Telegram.';
            }
        }

        $sticker = explode('/', $request->get('png_sticker'));
        $telegram_sticker_data = [
            'item_id' => $request->get('id'),
            'sticker' => end($sticker),
        ];

        if($success_data['ok'] === false){
            $telegram_sticker_data['description'] = $success_data['description'];
            $telegram_sticker_data['is_success'] = 0;
        }

        TelegramSticker::create($telegram_sticker_data);

        return $this->successOutput($success_data, $success_msg);
    }

    /*
     * @desc Telegram: Add sticker to set
     */
    private function getStickerSet($telegram_name){
        $telegram = new Telegram(config('services.telegram.bot_token'));
        $data = [
            'name' => $telegram_name,
        ];
        return $sticker_set = $telegram->getStickerSet($data); //get Sticker set
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


