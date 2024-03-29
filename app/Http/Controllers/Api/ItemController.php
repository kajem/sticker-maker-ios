<?php

namespace App\Http\Controllers\Api;

use http\Params;
use Illuminate\Http\Request;
use App\Category;
use App\Item;
use App\ItemToCategory;
use App\StaticValue;
use App\SearchKeyword;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @return void
     */
    public $redis_ttl;

    public function __construct()
    {
        $this->middleware('api_auth');
        $this->redis_ttl = config('services.redis.ttl');
    }
    public function getCategoryAndItems(Request $request){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            return $this->successOutput(unserialize(Redis::get($key)));
        }

        $page = !empty($request->get('page')) ? $request->get('page') : 0;
        if(!is_numeric($page))
            return $this->errorOutput('Page should be numeric.');

        //getting values from static_values table
        if($page == 0){
            $static_values = StaticValue::select('name','value')->get()->pluck('value', 'name')->toArray();
        }else{
            $static_values = StaticValue::select('name','value')->whereIn('name', ['category_limit', 'item_limit'])->get()->pluck('value', 'name')->toArray();
        }

        $category_limit = !empty($request->get('category_limit')) ? $request->get('category_limit') : 0;
        if(!is_numeric($category_limit))
            return $this->errorOutput('Category Limit should be numeric.');

        if(!isset($_GET['category_limit'])){
            $category_limit = !empty($static_values['category_limit']) ? $static_values['category_limit'] : 4;
        }

        $item_limit = !empty($request->get('item_limit')) ? $request->get('item_limit') : 0;
        if(!is_numeric($item_limit))
            return $this->errorOutput('Item Limit should be numeric.');

        if(!isset($_GET['item_limit'])){
            $item_limit = !empty($static_values['item_limit']) ? $static_values['item_limit'] : 4;
        }

        $categories = Category::query();
        if(!empty($category_limit)){
            $categories = $categories->offset($page*$category_limit);
            $categories = $categories->limit($category_limit);
        }
        $categories = $categories->where('type', 'general');
        $categories = $categories->where('status', 1);
        $categories = $categories->orderBy('sort', 'asc');
        $categories = $categories->get();

        if($categories->isEmpty()){
            return $this->successOutput(['next_page' => -1], 'No catagories found.');
        }

        $data = [];
        $data['next_page'] = -1; //Set the next page id

        //START: Category List Limit and static values
        if($page == 0){
            $data['asset_base_url'] = config('app.asset_base_url');
            $data['search_tags'] = !empty($static_values['search_tags']) ? $static_values['search_tags'] : '';
            $data['number_of_category'] = Category::where('type', 'general')->where('status', 1)->count();
            $data['trial_row_position'] = !empty($static_values['trial_row_position']) ? (int) $static_values['trial_row_position'] : 0;
            $data['app_subs'] = !empty($static_values['app_subs']) ? (int) $static_values['app_subs'] : 0;
            $data['f_cat'] = !empty($static_values['f_cat']) ? (int) $static_values['f_cat'] : 0;
            $data['f_item'] = !empty($static_values['f_item']) ? (int) $static_values['f_item'] : 0;
            $data['landing_subs'] = !empty($static_values['landing_subs']) ? (int) $static_values['landing_subs'] : 0;
            $data['save'] = !empty($static_values['save']) ? (int) $static_values['save'] : 0;

            $category_list_limit = !empty($request->get('category_list_limit')) ? $request->get('category_list_limit') : 0;
            if(!is_numeric($category_list_limit))
                return $this->errorOutput('Category list limit should be numeric.');

            if(!isset($_GET['category_list_limit'])){
                $category_list_limit = !empty($static_values['category_list_limit']) ? $static_values['category_list_limit'] : 4;
            }

            if($category_list_limit >= 0){
                $data['category_list_position'] = !empty($static_values['category_list_position']) ? (int) $static_values['category_list_position'] : 0;

                $category_lists = Category::query();
                $category_lists = $category_lists->select('id', 'name', 'text', 'items', 'stickers', 'thumb', 'thumb_v', 'thumb_bg_color');
                if($category_list_limit > 0){
                    $category_lists = $category_lists->offset(0);
                    $category_lists = $category_lists->limit($category_list_limit);
                }
                $category_lists = $category_lists->where('type', 'general');
                $category_lists = $category_lists->where('status', 1);
                $category_lists = $category_lists->orderBy('sort2', 'asc');
                $category_lists = $category_lists->get();
                if(!$category_lists->isEmpty()){
                    foreach($category_lists as $category){
                        $data['category_list'][] = [
                            'id' => $category->id,
                            'name' => $category->name,
                            'thumb' => $category->thumb,
                            'thumb_v' => $category->thumb_v,
                            'thumb_bg_color' => $category->thumb_bg_color,
                            'text' => $category->text,
                            'number_of_item' => $category->items,
                            'number_of_sticker' => $category->stickers,
                        ];
                    }
                }
            }
        }
        //END: Category List Limit and static values

        if(!empty($category_limit) && is_numeric($category_limit) && Category::count() > ($category_limit*$page+$category_limit)){
            $data['next_page'] = $page + 1;
        }

        foreach($categories as $category){
            $data['categories'][] = [
                'id' => $category->id,
                'name' => $category->name,
                'number_of_sticker' =>  $category->stickers,
                'number_of_item' => $category->items,
                'items' => $this->getItemsByCategory($request, $category->id, $item_limit)
            ];
        }

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        return $this->successOutput($data);
    }

    private function getItemsByCategory(Request $request, $category_id, $item_limit = 0){

        $items = Item::query();
        $items = $items->select('items.id', 'items.name', 'items.thumb', 'items.thumb_bg_color', 'items.stickers', 'items.code', 'items.author', 'items.telegram_name', 'items.is_telegram_set_completed', 'items.is_premium', 'items.is_animated', 'items.version');
        $items = $items->join('item_to_categories', 'item_to_categories.item_id', '=', 'items.id');
        $items = $items->where('item_to_categories.category_id', $category_id);
        $items = $items->where('items.status', 1);
        if(!empty($item_limit)){
            $items = $items->offset(0);
            $items = $items->limit($item_limit);
        }
        $items = $items->orderBy('item_to_categories.sort', 'asc');
        $items = $items->get();
        $item_arr = [];
        if(!$items->isEmpty()){
            foreach($items as $item){
                $thumb_arr = explode("/",$item->thumb);
                $stickers = unserialize($item->stickers);

                if(count($stickers) > 28){
                    $stickers = $this->truncateItemStickersToMaxTwentyEight($stickers);
                }

                $telegram_url = !empty($item->is_telegram_set_completed) ? config('services.telegram.set_base_url').$item->telegram_name : '';

                $item_arr[] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'thumb' => '200__'.end($thumb_arr),
                    'thumb_bg_color' => $item->thumb_bg_color,
                    'author' => $item->author,
                    'is_premium' => $item->is_premium,
                    'is_animated' => $item->is_animated,
                    'telegram_url' => $telegram_url,
                    'total_stickers' => count($stickers),
                    'version' => $item->version,
                    'stickers' => $stickers,
                ];
            }
        }
        return $item_arr;
    }

    public function getItemsByCategoryId(Request $request, $category_id){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            $redis_data = unserialize(Redis::get($key));
            if($request->version > 0 && $request->version == $redis_data['version']){
                unset($redis_data['items']);
            }
            return $this->successOutput($redis_data);
        }

        $category = Category::where('status', 1)->find($category_id);
        if(empty($category))
            return $this->errorOutput('Category not found.');

        $data = [
            'id'    => $category->id,
            'name'  => $category->name,
            'version'  => $category->version,
            'number_of_sticker' =>  $category->stickers,
            'number_of_item' => $category->items,
            'items' => $this->getItemsByCategory($request, $category->id)
        ];

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        if($request->version > 0 && $request->version == $category->version){
            unset($data['items']);
        }

        return $this->successOutput($data);
    }

    public function getEmojiItems(Request $request){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            $redis_data = unserialize(Redis::get($key));
            if($request->version > 0 && $request->version == $redis_data['version']){
                unset($redis_data['items']);
            }
            return $this->successOutput($redis_data);
        }

        $category = Category::where('type', 'emoji')->first();
        if(empty($category))
            return $this->errorOutput('Category not found.');


        $data = [
            'id'    => $category->id,
            'name'  => $category->name,
            'version'  => $category->version,
            'number_of_sticker' =>  $category->stickers,
            'number_of_item' => $category->items,
            'items' => $this->getItemsByCategory($request, $category->id)
        ];

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        if($request->version > 0 && $request->version == $category->version){
            unset($data['items']);
        }

        return $this->successOutput($data);
    }
    //This method returns all items of special types of category like emoji, nocrop
    public function getSpecialCategoryItems(Request $request, $type){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            $redis_data = unserialize(Redis::get($key));
            if($request->version > 0 && $request->version == $redis_data['version']){
                unset($redis_data['items']);
            }
            return $this->successOutput($redis_data);
        }

        $category = Category::where('type', $type)->first();
        if(empty($category))
            return $this->errorOutput('Category not found.');


        $data = [
            'asset_base_url' => config('app.asset_base_url'),
            'id'    => $category->id,
            'name'  => $category->name,
            'version'  => $category->version,
            'number_of_sticker' =>  $category->stickers,
            'number_of_item' => $category->items,
            'items' => $this->getItemsByCategory($request, $category->id)
        ];

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        if($request->version > 0 && $request->version == $category->version){
            unset($data['items']);
        }

        return $this->successOutput($data);
    }

    public function getStickersByItemId($code){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            return $this->successOutput(unserialize(Redis::get($key)));
        }

        $item = Item::where('code', $code)->where('status', 1)->first();
        if(empty($item->id))
            return $this->errorOutput('Item not found.');

        $thumb_arr = explode("/",$item->thumb);
        $stickers = unserialize($item->stickers);

        if(count($stickers) > 28){
            $stickers = $this->truncateItemStickersToMaxTwentyEight($stickers);
        }

        $telegram_url = !empty($item->is_telegram_set_completed) ? config('services.telegram.set_base_url').$item->telegram_name : '';

        $data = [
            'name' => $item->name,
            'code' => $item->code,
            'thumb' => '200__'.end($thumb_arr),
            'thumb_bg_color' => $item->thumb_bg_color,
            'author' => $item->author,
            'is_premium' => $item->is_premium,
            'is_animated' => $item->is_animated,
            'telegram_url' => $telegram_url,
            'total_stickers' => count($stickers),
            'version' => $item->version,
            'stickers' => $stickers,
            'updated_at' => strtotime($item->updated_at)
        ];

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        return $this->successOutput($data);
    }

    private function truncateItemStickersToMaxTwentyEight($stickers){
        $stickers_arr = $stickers;
        $stickers = [];
        $count = 1;
        foreach ($stickers_arr as $sticker){
            $stickers[] = $sticker;
            $count++;
            if($count > 28) break;
        }
        return $stickers;
    }

    /**
     * @param $code //Item's code
     *        $file_name //name of the image
     */
    public function getImage($code, $file_name){
        $root_path = storage_path().'/app/items/'.$code.'/';
        $path = $root_path.$file_name;

        if(!file_exists($path)){
            $name_arr = explode("__", $file_name);
            if(count($name_arr) == 1){
                return $this->errorOutput('Original file not found.');
            }

            if(count($name_arr) == 2){

                $original_file_path = $root_path.$name_arr[1];
                if(!file_exists($original_file_path))
                    return $this->errorOutput('Original file not found to create new thumb.');

                //Resize image for desired width
                $im = new \Imagick($original_file_path);

                if ($im->getImageFormat() == 'GIF') {
                    $im = $im->coalesceImages();

                    do {
                        $im->resizeImage($name_arr[0], null, \Imagick::FILTER_BOX, 1);
                    } while ($im->nextImage());

                    $im = $im->deconstructImages();

                    $im->writeImages($path, true);
                } else {
                    $thumbnailImage = Image::make($original_file_path)->widen($name_arr[0], function ($constraint) {
                        $constraint->upsize();
                    })->save($path);
                }
            }else{
                $this->errorOutput('Invalid action!');
            }
        }



        $storage_path = 'items/'.$code.'/'.$file_name;
        //echo json_encode(base64_encode(Storage::get($storage_path)));exit;

        // $data = [
        //     'mime_type' => Storage::mimeType($storage_path),
        //     'image' => base64_encode(Storage::get($storage_path))
        // ];

        // return $this->successOutput($data);

        header('Content-Type:'.Storage::mimeType($storage_path));
        header('Content-Length: ' . filesize($path));
        readfile($path);
    }

    public function search(Request $request){
        $items = $this->getSearchItems($request);
        $data = [];
        if(!empty($items)){
            foreach($items as $item){
                $thumb_arr = explode("/",$item->thumb);
                $stickers = unserialize($item->stickers);
                $telegram_url = !empty($item->is_telegram_set_completed) ? config('services.telegram.set_base_url').$item->telegram_name : '';
                $data[] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'thumb' => '200__'.end($thumb_arr),
                    'thumb_bg_color' => $item->thumb_bg_color,
                    'author' => $item->author,
                    'is_premium' => $item->is_premium,
                    'is_animated' => $item->is_animated,
                    'telegram_url' => $telegram_url,
                    'total_stickers' => !empty($stickers) ? count($stickers) : 0,
                    'version' => $item->version,
                    'stickers' => $stickers
                ];
            }
        }
        return $this->successOutput($data);
    }

    private function getSearchItems(Request $request){
        $emoji_cat = Category::select('id')->where('type', 'emoji')->first();
        $emoji_cat_id = !empty($emoji_cat->id) ? $emoji_cat->id : 0;
        $emoji_items = ItemToCategory::where('category_id', $emoji_cat_id)->get();
        $emoji_item_ids = [];
        if(!empty($emoji_items)){
            foreach ($emoji_items as $emoji_item){
                $emoji_item_ids[] = $emoji_item->item_id;
            }
        }
        $query = "
            SELECT
                `id`,
                `name`,
                `thumb`,
                `thumb_bg_color`,
                `stickers`,
                `code`,
                `author`,
                `is_premium`,
                `is_animated`,
                `telegram_name`,
                `is_telegram_set_completed`,
                `version`,
                MATCH(`name`, `tag`) AGAINST(? IN BOOLEAN MODE) as `score`
            FROM
                `items`
            WHERE
                `status` = 1
            AND
                id NOT IN ( '" . implode("','", $emoji_item_ids) .
            "' )
            AND
                ( MATCH( `name`, `tag` ) AGAINST( ? IN BOOLEAN MODE ) > 0 )
            ORDER BY `score` DESC";

        return $items = DB::select($query, [$request->q, $request->q]);
    }

    public function saveSearchKeyword(Request $request){
        $data = [];
        $name = strtolower($request->q);

        $items = $this->getSearchItems($request);

        $data['is_item_found'] = 1;
        if(empty($items)){
            $data['is_item_found'] = 0;
        }

        $keyword = SearchKeyword::select('id', 'count')->where('name', $name)->first();

        if(!empty($keyword->id)){
            $data['count'] = $keyword->count + 1;
            SearchKeyword::where('id', $keyword->id)->update($data);
        }else{
            $data['name'] = $name;
            SearchKeyword::create($data);
        }
        return $this->successOutput();
    }

    public function getCategories(){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            return $this->successOutput(unserialize(Redis::get($key)));
        }

        $categories = Category::select('id', 'name', 'text', 'items', 'stickers', 'thumb', 'thumb_v', 'thumb_bg_color')
        ->where('type', 'general')
        ->where('status', 1)
        ->orderBy('sort2', 'asc')
        ->get();
        $data = [];
        if(!$categories->isEmpty()){
            foreach($categories as $category){
                $data[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'thumb' => $category->thumb,
                    'thumb_v' => $category->thumb_v,
                    'thumb_bg_color' => $category->thumb_bg_color,
                    'text' => $category->text,
                    'number_of_item' => $category->items,
                    'number_of_sticker' => $category->stickers,
                ];
            }
        }
        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis
        return $this->successOutput($data);
    }

    public function getPack($code){
        $pack = Item::select('name', 'thumb', 'thumb_bg_color',  'stickers')->where('code', $code)->first();
        if(empty($pack->name))
            return $this->errorOutput('Invalid code!');

        $thumb_arr = explode("/",$pack->thumb);
        $data = [
            'pack_base_url' => config('app.asset_base_url').'items/'.$code.'/',
            'name' => $pack->name,
            'thumb' => end($thumb_arr),
            'thumb_bg_color' => $pack->thumb_bg_color,
            'stickers' => unserialize($pack->stickers),
        ];

        return $this->successOutput($data);
    }

    public function updateItemViewCount($code, $platform = ''){
        if(!empty($platform) && in_array($platform, ['android', 'web'])){
            $platform = $platform.'_';
        }else{
            $platform = '';
        }
        $field_view_count = $platform.'view_count';
        $item = Item::select('id', $field_view_count)->where('code', $code)->first();
        if(empty($item->id))
            return $this->errorOutput('Invalid code!');

        $data = [
            $field_view_count => $item->$field_view_count + 1
        ];

        Item::where('code', $code)->update($data);

        return $this->successOutput();
    }

    public function updateItemDownloadCount($code, $platform = ''){
        if(!empty($platform) && in_array($platform, ['android', 'web'])){
            $platform = $platform.'_';
        }else{
            $platform = '';
        }
        $field_download_count = $platform.'download_count';
        $item = Item::select('id', $field_download_count)->where('code', $code)->first();
        if(empty($item->id))
            return $this->errorOutput('Invalid code!');

        $data = [
            $field_download_count => $item->$field_download_count + 1
        ];

        Item::where('code', $code)->update($data);

        return $this->successOutput();
    }
}
