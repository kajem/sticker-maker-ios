<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use App\ItemSticker;
use App\ItemStickerThumbnail;
use App\StaticValue;
use App\SearchKeyword;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

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

        //getting values of category_list_position and search_tags from static_values table
        $static_values = StaticValue::select('name','value')->whereIn('name', ['category_list_position', 'search_tags', 'trial_row_position', 'category_limit', 'item_limit'])->get()->pluck('value', 'name')->toArray();

        if(!empty($request->get('item_limit')) && !is_numeric($request->get('item_limit')))
            return $this->errorOutput('Item Limit should be numeric.');

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
        $categories = $categories->orderBy('sort', 'asc');
        $categories = $categories->get();

        if($categories->isEmpty()){
            return $this->successOutput(['next_page' => -1], 'No catagories found.');
        }

        $data = [];
        $data['asset_base_url'] = config('app.asset_base_url');
        $data['next_page'] = -1; //Set the next page id
        $data['search_tags'] = !empty($static_values['search_tags']) ? $static_values['search_tags'] : '';
        $data['number_of_category'] = Category::where('type', 'general')->count();
        $data['trial_row_position'] = !empty($static_values['trial_row_position']) ? $static_values['trial_row_position'] : 0;

        //START: Category List Limt
        if(is_numeric($request->get('category_list_limit')) && $request->get('category_list_limit') >= 0){
            $data['category_list_position'] = !empty($static_values['category_list_position']) ? $static_values['category_list_position'] : 0;

            $category_lists = Category::query();
            $category_lists = $category_lists->select('id', 'name', 'text', 'items', 'stickers');
            if(!empty($request->get('category_list_limit'))){
                $category_lists = $category_lists->offset(0);
                $category_lists = $category_lists->limit($request->get('category_list_limit'));
            }
            $category_lists = $category_lists->where('type', 'general');
            $category_lists = $category_lists->orderBy('sort2', 'asc');
            $category_lists = $category_lists->get();
            if(!$category_lists->isEmpty()){
                foreach($category_lists as $category){
                    $data['category_list'][] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'text' => $category->text,
                        'number_of_item' => $category->items,
                        'number_of_sticker' => $category->stickers,
                    ];
                }
            }
        }
        //END: Category List Limt

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
        $items = $items->select('id', 'name', 'thumb', 'stickers', 'code', 'author', 'is_premium');
        $items = $items->where('category_id', $category_id);
        if(!empty($item_limit)){
            $items = $items->offset(0);
            $items = $items->limit($item_limit);
        }
        $items = $items->orderBy('sort', 'asc');
        $items = $items->get();
        $item_arr = [];
        if(!$items->isEmpty()){
            foreach($items as $item){
                $thumb_arr = explode("/",$item->thumb);
                $stickers = unserialize($item->stickers);

                $item_arr[] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'thumb' => end($thumb_arr),
                    'author' => $item->author,
                    'is_premium' => $item->is_premium,
                    'total_stickers' => count($stickers),
                    'stickers' => $stickers
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

        if(!is_numeric($category_id)){
            $category = Category::where('type', $category_id)->first();

            if(empty($category->id))
                return $this->errorOutput('Invalid category ID.');

            $category_id = $category->id;
        }
        
        $category = Category::find($category_id);
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

    public function getStickersByItemId($code){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            return $this->successOutput(unserialize(Redis::get($key)));
        }

        $item = Item::where('code', $code)->first();
        if(empty($item->id))
            return $this->errorOutput('Item not found.');

        $thumb_arr = explode("/",$item->thumb);
        $stickers = unserialize($item->stickers);

        $data = [
            'name' => $item->name,
            'code' => $item->code,
            'thumb' => end($thumb_arr),
            'author' => $item->author,
            'total_stickers' => count($stickers),
            'stickers' => $stickers
        ];

        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis

        return $this->successOutput($data);
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
        $category = Category::select('id')->where('type', 'emoji')->first();
        $category_id = !empty($category->id) ? $category->id : 0;
        $items = Item::select('id', 'name', 'thumb', 'stickers', 'code', 'author', 'is_premium')
                      ->where('name','LIKE','%'.$request->q.'%')
                      ->where('category_id', '!=', $category_id)->get();
        $data = [];
        if(!$items->isEmpty()){
            foreach($items as $item){
                $thumb_arr = explode("/",$item->thumb);
                $stickers = unserialize($item->stickers);
                $data[] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'thumb' => end($thumb_arr),
                    'author' => $item->author,
                    'is_premium' => $item->is_premium,
                    'total_stickers' => count($stickers),
                    'stickers' => $stickers
                ];
            }
        }
        return $this->successOutput($data);
    }

    public function saveSearchKeyword(Request $request){
        $name = strtolower($request->q);
        $keyword = SearchKeyword::select('id', 'count')->where('name', $name)->first();
        if(!empty($keyword->id)){
            SearchKeyword::where('id', $keyword->id)->update(['count' => $keyword->count + 1]);
        }else{
            SearchKeyword::create(['name' => $name]);
        }
        return $this->successOutput(); 
    }

    public function getCategories(){
        $key = urldecode(url()->full());
        if(Redis::exists($key)){
            return $this->successOutput(unserialize(Redis::get($key)));
        }
        
        $categories = Category::select('id', 'name', 'text', 'items', 'stickers')->where('type', 'general')->orderBy('sort2', 'asc')->get();
        $data = [];
        if(!$categories->isEmpty()){
            foreach($categories as $category){
                $data[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'text' => $category->text,
                    'number_of_item' => $category->items,
                    'number_of_sticker' => $category->stickers,
                ];
            }
        }
        Redis::setEx($key, $this->redis_ttl, serialize($data)); //Writing to Redis
        return $this->successOutput($data);
    }
}