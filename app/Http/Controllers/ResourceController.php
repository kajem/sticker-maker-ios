<?php
/**
 * This class handle the following types of tasks: resource upload, re-upload, category wise resource upload
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use App\ItemSticker;
use App\Author;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function upload(){
        $root_folder = 'stickers';
        $categories = Storage::directories($root_folder); //Get the list of category directories
        if(empty($categories)){
            echo "No categoires found"; exit;
        }
        //Truncate the tables
        Author::truncate();
        Category::truncate();
        Item::truncate();
        ItemSticker::truncate(); 

        $this->processCategories($categories, $root_folder);

        echo "Script execution completed.";exit;
    }

    private function processCategories($categories, $root_folder){
        $date = date('Y-m-d H:i:s');

        foreach($categories as $category){
            $category_temp = str_replace($root_folder."/", "", $category);
            $category_arr = explode("__", $category_temp);
            $category_order = !empty($category_arr[0]) && is_numeric($category_arr[0]) ? $category_arr[0] : 0;
            $category_name = !empty($category_arr[1]) ? $category_arr[1] : $category_arr[0];
            $category_name = implode(" ", explode("--", $category_name));

            $category_data = [
                'name' => $category_name,
                'sort' => $category_order,
                'status' => 1,
                'created_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ];
            $category_id = Category::insertGetId($category_data); //Insert Category and get the inserted ID
            $items = Storage::directories($category); //Get the Item directories
            if(empty($items)) continue;

            $this->processItems($items, $category, $category_id);
        }
    }

    private function processItems($items, $category, $category_id){
        $date = date('Y-m-d H:i:s');
        foreach($items as $item){
            $item_temp = str_replace($category."/", "", $item);

            $item_arr = explode("__", $item_temp);
            $item_order = !empty($item_arr[0]) && is_numeric($item_arr[0]) ? $item_arr[0] : 0;
            $item_name = !empty($item_arr[1]) ? $item_arr[1] : $item_arr[0];
            $item_name = implode(" ", explode("--", $item_name));
            $thumb = Storage::files($item);
            $code = $this->uniqueCode();

            $item_author = !empty($item_arr[2]) ? implode(" ", explode("--", $item_arr[2])) : '';
            $author = Author::select('id')->where('name', $item_author)->first();
            if(empty($author->id)){
                $author_data = [
                    'name' => $item_author,
                    'status' => 1,
                    'created_by' => 1,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                $author_id = Author::insertGetId($author_data); //Insert Author and get the inserted ID
            }else{
                $author_id = $author->id;
            }

            $thumb_path = '';
            if(!empty($thumb[0])){
                $thumb_img_arr = explode(".", $thumb[0]);
                $extension = end($thumb_img_arr);
                $thumb_path = 'items/'.$code.'/'.$code.'.'.$extension;
                Storage::copy($thumb[0], $thumb_path);
            }

            $item_data = [
                'name' => $item_name,
                'code' => $code,
                'category_id' => $category_id,
                'sort' => $item_order,
                'author_id' => $author_id,
                'thumb' => $thumb_path,
                'status' => 1,
                'created_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $item_id = Item::insertGetId($item_data); //Insert Item and get the inserted ID
            $stickers = Storage::files($item."/files"); //Get the sticker files
            if(empty($stickers)) continue;
            $this->processStickers($stickers, $item_id, $code);
        }
    }

    private function processStickers($stickers, $item_id, $code){
        $sticker_path = 'items/'.$code.'/';
        foreach($stickers as $sticker){
            $sticker_path_arr = explode("/", $sticker);
            $file_name = str_replace(" ", "-", trim(end($sticker_path_arr)));
            Storage::copy($sticker, $sticker_path.$file_name);
            $sticker_data = [
                'item_id' => $item_id,
                'file_name' => $file_name,
                'path' => $sticker_path.$file_name,
            ];
            ItemSticker::create($sticker_data);
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

        if(!empty(Item::select('id')->where('code', $code)->first()->id)){
            $this->uniqueCode();
        }
        
        return $code;
    }

    public function uploadNewCategory(){
        $root_folder = 'new_categories';
        $categories = Storage::directories($root_folder); //Get the list of category directories
        if(empty($categories)){
            echo "No categoires found"; exit;
        }

        $this->processCategories($categories, $root_folder);

        echo "Script execution completed.";exit;
    }

    // public function updateCategory(){
    //     $root_folder = 'new_categories';
    //     $categories = Storage::directories($root_folder); //Get the list of category directories
    //     if(empty($categories)){
    //         echo "No categoires found"; exit;
    //     }

    //     $this->processCategories($categories, $root_folder);

    //     echo "Script execution completed.";exit;
    // }
}
