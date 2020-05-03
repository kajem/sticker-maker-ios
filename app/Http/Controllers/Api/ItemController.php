<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use App\ItemSticker;
use App\ItemStickerThumbnail;
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
        if(!Redis::exists($key)){
            $this->getCategories($request, $key);
        }

        $categories = unserialize(Redis::get($key));
        if(empty($categories)){
            return $this->successOutput(['next_page' => -1], 'No catagories found.');
        }

        $data = [];
        $data['next_page'] = !empty($categories->next_page) ? $categories->next_page : -1;
        foreach($categories as $category){
            $data['categories'][] = [
                'id' => $category->id,
                'name' => $category->name,
                'items' => $this->getItemsByCategory($request, $category->id, $key)
            ];
        }

        return $this->successOutput($data);
    }

    private function getCategories(Request $request, $key){
        $page = !empty($request->get('page')) ? $request->get('page') : 0;
        if(!is_numeric($page))
            return $this->errorOutput('Page should be numeric.');

        if(!empty($request->get('item_limit')) && !is_numeric($request->get('item_limit')))
            return $this->errorOutput('Item Limit should be numeric.');

        $category_limit = !empty($request->get('category_limit')) ? $request->get('category_limit') : 0;
        if(!is_numeric($category_limit))
            return $this->errorOutput('Category Limit should be numeric.');

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

        //Get the next page id
        $categories->next_page = -1;
        if(!empty($category_limit) && is_numeric($category_limit) && Category::count() > ($category_limit*$page+$category_limit)){
            $categories->next_page = $page + 1;
        }

        Redis::setEx($key, $this->redis_ttl, serialize($categories));

        return $categories;
    }

    private function getItemsByCategory(Request $request, $category_id, $key){
        $key = $key.'&category_id='.$category_id;

        if(!Redis::exists($key)){
            $this->getItems($request, $category_id, $key);
        }

        $items = unserialize(Redis::get($key));

        $item_arr = [];
        if(!$items->isEmpty()){
            foreach($items as $item){
                $thumb_arr = explode("/",$item->thumb);
                $stickers = unserialize($item->stickers);

                $item_arr[] = [
                    'name' => $item->name,
                    'code' => $item->code,
                    'thumb' => end($thumb_arr),
                    'author' => !empty($item->author->name) ? $item->author->name : '',
                    'total_stickers' => count($stickers),
                    'stickers' => $stickers
                ];
            }
        }
        return $item_arr;
    }

    private function getItems(Request $request, $category_id, $key){
        $items = Item::query();
        $items = $items->select('id', 'name', 'thumb', 'stickers', 'code', 'author_id');
        $items = $items->where('category_id', $category_id);
        if(!empty($request->get('item_limit'))){
            $items = $items->offset(0);
            $items = $items->limit($request->get('item_limit'));
        }
        $items = $items->orderBy('sort', 'asc');
        $items = $items->get();

        Redis::setEx($key, $this->redis_ttl, serialize($items));

        return $items;
    }

    public function getItemsByCategoryId(Request $request, $category_id){
        $key = urldecode(url()->full());
        if(!Redis::exists($key)){
            if(!is_numeric($category_id)){
                $category = Category::where('type', $category_id)->first();
    
                if(empty($category->id))
                    return $this->errorOutput('Invalid category ID.');
    
                $category_id = $category->id;
            }
            
            $category = Category::find($category_id);
            if(empty($category))
                return $this->errorOutput('Category not found.');

            Redis::setEx($key, $this->redis_ttl, serialize($category)); //Writing to radis
        }else{
            $category = unserialize(Redis::get($key));
        }
        

        $data = [
            'id'    => $category->id,
            'name'  => $category->name,
            'items' => $this->getItemsByCategory($request, $category->id, $key)
        ];

        return $this->successOutput($data);
    }

    public function getStickersByItemId($code){
        $key = urldecode(url()->full());
        if(!Redis::exists($key)){
            $item = Item::where('code', $code)->first();
            if(empty($item->id))
                return $this->errorOutput('Item not found.');

            Redis::setEx($key, $this->redis_ttl, serialize($item)); //Writing to radis
        }else{
            $item = unserialize(Redis::get($key));
        }

        $thumb_arr = explode("/",$item->thumb);
        $stickers = unserialize($item->stickers);
        $data = [
            'name' => $item->name,
            'code' => $item->code,
            'thumb' => end($thumb_arr),
            'author' => !empty($item->author->name) ? $item->author->name : '',
            'total_stickers' => count($stickers),
            'stickers' => $stickers
        ];

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
}