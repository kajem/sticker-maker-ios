<?php
/**
 * This class handle the following types of tasks: resource upload, re-upload, category wise resource upload
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use App\ItemToCategory;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ResourceController extends Controller
{
    public function upload(){
        $root_folder = 'stickers';
        $categories = Storage::directories($root_folder); //Get the list of category directories
        if(empty($categories)){
            echo "No categoires found"; exit;
        }
        //START: Truncate the tables
        //Author::truncate();
        //Category::truncate();
        //Item::truncate();
       // ItemToCategory::truncate();
        //END: Truncate the tables

        //Re-creating items directory
        $root_item_folder = 'public/items';
        //Storage::deleteDirectory($root_item_folder);
        //Storage::disk('local')->makeDirectory($root_item_folder);

        //Re-creating category-thumbs directory
        $root_category_folder = 'public/category-thumbs';
        //Storage::deleteDirectory($root_category_folder);
        //Storage::disk('local')->makeDirectory($root_category_folder);

        $this->processCategories($categories, $root_folder);

        //Category::where('name', 'Emoji')->update(['type' => 'emoji']);

        $category = Category::all();

        echo $category->count()." categories created successfully.<br/>";

        $this->createZipFiles();  //Creating thumb.zip and main.zip files for all items/packs

        //$this->createEmojiZipFiles();

        exit;
    }

    private function processCategories($categories, $root_folder){
        $date = date('Y-m-d H:i:s');
        $category_sort2 = 5000;
        foreach($categories as $category){
            $category_temp = str_replace($root_folder."/", "", $category);
            $category_arr = explode("__", $category_temp);
            $category_order = !empty($category_arr[0]) && is_numeric($category_arr[0]) ? $category_arr[0] : 0;
            $category_name = !empty($category_arr[1]) ? $category_arr[1] : $category_arr[0];
            $category_name = implode(" ", explode("--", $category_name));

            $items = Storage::directories($category); //Get the Item directories

            $category_data = [
                'name' => $category_name,
                'text' => $category_name." text goes here...",
                'items' => count($items),
                'sort' => $category_order,
                'sort2' => $category_sort2,
                'status' => 1,
                'created_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ];
            $category_sort2 -= 100;
            $category_id = Category::insertGetId($category_data); //Insert Category and get the inserted ID

            //START: copy category thumb images
            $category_thumbs = Storage::files($category);
            $storage_path = storage_path().'/app/';
            if(!empty($category_thumbs)){
                $root_category_folder = 'public/category-thumbs/';

                $cat_thumb_1_path = $root_category_folder.'cat_'.$category_id.'_tmb.png';
                $compressed_png_content1 = $this->compressPNG( $storage_path.$category_thumbs[0]); //Getting compressed png content
                Storage::disk('local')->put($cat_thumb_1_path, $compressed_png_content1); //Writing compressed png

                $cat_thumb_2_path = $root_category_folder.'cat_'.$category_id.'_v_tmb.png';
                $compressed_png_content2 = $this->compressPNG( $storage_path.$category_thumbs[1]); //Getting compressed png content
                Storage::disk('local')->put($cat_thumb_2_path, $compressed_png_content2); //Writing compressed png
            }
            //END: copy category thumb images

            if(empty($items)) continue;

            $total_stickers = $this->processItems($items, $category, $category_id);

            Category::where('id', $category_id)->update(['stickers' => $total_stickers]);
        }
    }

    private function processItems($items, $category, $category_id){
        $date = date('Y-m-d H:i:s');
        $total_stickers = 0;
        $storage_path = storage_path().'/app/';
        foreach($items as $item){
            $item_temp = str_replace($category."/", "", $item);

            $item_arr = explode("__", $item_temp);
            $item_order = !empty($item_arr[0]) && is_numeric($item_arr[0]) ? $item_arr[0] : 0;
            $item_name = !empty($item_arr[1]) ? $item_arr[1] : $item_arr[0];
            $item_name = implode(" ", explode("--", $item_name));
            $thumb = Storage::files($item);
            $code = $this->uniqueCode();

            $item_author = 'Braincraft';

            $thumb_path = '';
            if(!empty($thumb[0])){
                $thumb_img_arr = explode(".", $thumb[0]);
                $extension = end($thumb_img_arr);
                $thumb_path = 'public/items/'.$code.'/'.$code.'.'.$extension;
                //echo $storage_path.$thumb[0];exit;
                $compressed_png_content3 = $this->compressPNG($storage_path.$thumb[0]); //Getting compressed png content
                Storage::disk('local')->put($thumb_path, $compressed_png_content3); //Writing compressed png
            }

            $sticker_names = [];
            $stickers = Storage::files($item."/files"); //Get the sticker files
            if(!empty($stickers))
                $sticker_names = $this->processStickers2($stickers, $code);

            $total_stickers += count($sticker_names);

            $item_data = [
                'name' => $item_name,
                'code' => $code,
                'category_id' => $category_id,
                //'sort' => $item_order,
                'author' => $item_author,
                'thumb' => $thumb_path,
                'stickers' => serialize($sticker_names),
                'total_sticker' => count($sticker_names),
                'status' => 1,
                'created_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $item_id = Item::insertGetId($item_data); //Insert Item and get the inserted ID
            $item_to_cat_data = [
                'item_id' => $item_id,
                'category_id' => $category_id,
                'sort' => $item_order,
            ];
            ItemToCategory::create($item_to_cat_data);

            // $stickers = Storage::files($item."/files"); //Get the sticker files
            // if(empty($stickers)) continue;
            // $this->processStickers($stickers, $item_id, $code);
        }
        return $total_stickers;
    }

    private function processStickers2($stickers, $code){
        $sticker_path = 'public/items/'.$code.'/';
        $sticker_names = [];
        $storage_path = storage_path().'/app/';
        foreach($stickers as $sticker){
            $sticker_path_arr = explode("/", $sticker);
            $file_name = str_replace([" ", "(", ")"], ["-", "", ""], trim(end($sticker_path_arr)));
            //Storage::copy($sticker, $sticker_path.$file_name);
            $compressed_png_content4 = $this->compressPNG($storage_path.$sticker); //Getting compressed png content
            Storage::disk('local')->put($sticker_path.$file_name, $compressed_png_content4); //Writing compressed png
            $sticker_names[] = $file_name;
        }
        return $sticker_names;
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

    /**
     * @desc creating new thumbnails of all stickers with specified width
     * @params $width
     * @return \Illuminate\Http\Response
     */
    public function createNewThumbnails($width){
        $items = Item::get();
        if($items->isEmpty()){
            return back()->withInput($request->all())->with('error', 'No stickers found to create new thubmnails.');
        }

        $successful = $unsuccessful = 0;
        $root_folder = base_path().'/storage/app/public/items/';
        $storage_path = storage_path().'/app/';
        $unsuccessful_list = '';
        foreach($items as $item){
            //$stickers = unserialize($item->stickers);
            $thumb_arr = explode("/", $item->thumb);
            $item_thumb_name = end($thumb_arr);
            // if(!empty($thumb_arr[3])){
            //     $stickers[] = $thumb_arr[3];
            // }

            $item_root_folder = $root_folder.$item->code."/";

            $resized_thumb_path = $item_root_folder.$width."__".$item_thumb_name;

            $thumbnailImage = Image::make($storage_path.$item->thumb)->widen($width, function ($constraint) {
                $constraint->upsize();
            })->save($resized_thumb_path);

            $compressed_png_content = $this->compressPNG($resized_thumb_path); //Getting compressed png content
            Storage::disk('local')->put('public/items/'.$item->code."/"."200__".$item_thumb_name, $compressed_png_content); //Writing compressed png

            // foreach($stickers as $sticker){
            //     $original_file_path = $item_root_folder.$sticker;

            //     if(file_exists($original_file_path)){
            //         $thumb_name = $width.'__'.$sticker;

            //         //Resize image for desired width
            //         if (mime_content_type ( $original_file_path ) == 'image/gif') {
            //             $im = new \Imagick($original_file_path);
            //             $im = $im->coalesceImages();

            //             do {
            //                 $im->resizeImage($width, null, \Imagick::FILTER_BOX, 1);
            //             } while ($im->nextImage());

            //             $im = $im->deconstructImages();

            //             $im->writeImages($item_root_folder.$thumb_name, true);
            //         } else {
            //             $thumbnailImage = Image::make($original_file_path)->widen($width, function ($constraint) {
            //                 $constraint->upsize();
            //             })->save($item_root_folder.$thumb_name);
            //         }

            //         $successful++;
            //     }else{
            //         $unsuccessful++;
            //         $unsuccessful_list .= '<br/>'.$original_file_path;
            //     }
            // }
        }

        echo 'Successful: '.$successful.' Unsuccessful: '.$unsuccessful;
        if(!empty($unsuccessful_list)) echo $unsuccessful_list;
        exit;
    }

    /**
     * @desc creating new thumbnails of all stickers with specified width
     * @params $width
     * @return \Illuminate\Http\Response
     */
    public function createNewThumbnailsBkp($width){

        $stickers = ItemSticker::select('path')->get()->toArray();
        $items = Item::select('thumb as path')->where('thumb', '!=', '')->get()->toArray();

        $stickers = array_merge($stickers, $items);

        if(count($stickers) < 1){
            return back()->withInput($request->all())->with('error', 'No stickers found to create new thubmnails.');
        }
        $successful = $unsuccessful = 0;
        $root_folder = base_path().'/storage/app/public/items/';
        $unsuccessful_list = '';
        foreach($stickers as $sticker){
            $original_file_path = base_path().'/storage/app/public/'.$sticker['path'];

            if(file_exists($original_file_path)){
                $file_path_arr = explode("/", $sticker['path']);
                $thumb_name = $file_path_arr[1]."/".$width.'__'.$file_path_arr[2];

                //Resize image for desired width
                if (mime_content_type ( $original_file_path ) == 'image/gif') {
                    $im = new \Imagick($original_file_path);
                    $im = $im->coalesceImages();

                    do {
                        $im->resizeImage($width, null, \Imagick::FILTER_BOX, 1);
                    } while ($im->nextImage());

                    $im = $im->deconstructImages();

                    $im->writeImages($root_folder.$thumb_name, true);
                } else {
                    $thumbnailImage = Image::make($original_file_path)->widen($width, function ($constraint) {
                        $constraint->upsize();
                    })->save($root_folder.$thumb_name);
                }
                $successful++;
            }else{
                $unsuccessful++;
                $unsuccessful_list .= '<br/>'.$original_file_path;
            }
        }

        echo 'Successful: '.$successful.' Unsuccessful: '.$unsuccessful;
        if(!empty($unsuccessful_list)) echo $unsuccessful_list;
        exit;
    }


    /**
     * @desc creating new zip files for all sticker packs
     * @params $width
     * @return \Illuminate\Http\Response
     */
    private function createZipFiles(){
        $items = Item::get();
        if($items->isEmpty()){
            return back()->withInput($request->all())->with('error', 'No stickers found to create new thubmnails.');
        }

        $successful = $unsuccessful = 0;
        $storage_path = storage_path().'/app/';
        $unsuccessful_list = '';
        foreach($items as $item){
            $item_path = 'public/items/'.$item->code."/";
            $item_thumb_path = $item_path."thumb/";

            Storage::deleteDirectory($item_thumb_path); //Deleting current thumb directory
            Storage::disk('local')->makeDirectory($item_thumb_path);

            $stickers = unserialize($item->stickers);

            //Main zip file
            $zip = new ZipArchive;
            $main_zip_file_path = $storage_path.$item_path."main.zip";
            $zip->open($main_zip_file_path, ZipArchive::CREATE);

            foreach($stickers as $sticker){
                $original_file_path = $storage_path.$item_path.$sticker;

                if(file_exists($original_file_path)){
                    $thumbnailImage = Image::make($original_file_path)->widen(200, function ($constraint) {
                        $constraint->upsize();
                    })->save($storage_path.$item_thumb_path."/".$sticker); //Saving 200*200 thumb image

                    $zip->addFile($storage_path.$item_path.$sticker, $sticker); //Add file to main zip archive
                }
            }
            // All files are added, so close the zip file.
            $zip->close();

            //START: Creating Thumb zip file
            $zip = new ZipArchive;
            $thumb_zip_file_path = $storage_path.$item_path."thumb.zip";
            if ($zip->open($thumb_zip_file_path, ZipArchive::CREATE) === TRUE)
            {
                $stickers = Storage::files($item_thumb_path); //Get the sticker files
                if(!empty($stickers)){
                    foreach($stickers as $sticker){
                        $compressed_png_content = $this->compressPNG($storage_path.$sticker); //Getting compressed png content
                        Storage::disk('local')->put($sticker, $compressed_png_content); //Writing compressed png


                        $file_name = explode("/", $sticker);
                        $new_file_name = array_pop($file_name);
                        $zip->addFile($storage_path.$sticker, $new_file_name);
                    }
                }
                // All files are added, so close the zip file.
                $zip->close();
                $successful++;
            }
            //END: Creating Thumb zip file
        }

        echo $successful." packs created successfully.<br/>";
    }

    /**
     * @desc creating new thumbnails of all stickers with specified width
     * @params $width
     * @return \Illuminate\Http\Response
     */
    public function createEmojiZipFiles($width = 200){
        $category = Category::select('id')->where('type', 'emoji')->first();
        if(empty($category->id)){
            return back()->with('error', 'Emoji category not found');
        }

        $item_ids = ItemToCategory::where('category_id', $category->id)->pluck('item_id')->toArray();

        $items = Item::select('name', 'thumb', 'code', 'stickers')->whereIn('id', $item_ids)->get()->toArray();

        if(count($items) < 1){
            return back()->withInput($request->all())->with('error', 'No stickers found to create new thubmnails.');
        }

        $storage_path = storage_path().'/app/';
        $emoji_folder = 'public/emoji/';
        $emoji_thumb_zip_name = 'emoji_thumbs.zip';
        Storage::deleteDirectory($emoji_folder); //Deleting current emoji directory
        Storage::disk('local')->makeDirectory($emoji_folder); //Creating emoji directory
        Storage::disk('local')->delete($emoji_folder.$emoji_thumb_zip_name);

        $zip = new ZipArchive;
        $emoji_thumbs_zip_file_path = $storage_path.$emoji_folder.$emoji_thumb_zip_name;
        if ($zip->open($emoji_thumbs_zip_file_path, ZipArchive::CREATE) === TRUE){ //Creating emoji_thumbs.zip file
            foreach($items as $item){
                //Creating main zip files
                $item_path = 'public/items/'.$item['code']."/";
                $main_zip_path = $item_path.'/main.zip';
                Storage::copy($main_zip_path, $emoji_folder.$item['name'].'.zip');

                //Creating emoji_thumbs.zip file
                $item_thumb_path = $item_path."thumb/";
                $stickers = Storage::files($item_thumb_path); //Get the sticker files
                $thumb_arr = explode("/",$item['thumb']);
                $zip->addFile($storage_path.$item_path.'200__'.end($thumb_arr), 'all_sticker_thumbs/'.'200__'.end($thumb_arr));
                if(!empty($stickers)){
                    foreach($stickers as $sticker){
                        $file_name = explode("/", $sticker);
                        $new_file_name = array_pop($file_name);
                        $zip->addFile($storage_path.$sticker, $item['name'].'/'.$new_file_name);
                    }
                }
            }
        }
        $zip->close();

        echo count($items). ' emoji pack\'s main zip files created successfully.<br/>';
        echo ' emoji_thumbs.zip file created successfully.<br/>';
    }

    /**
     * Read all zip files of a folder and compress with PNGQUANT and export to another folder
     */
    public function compressZipWithPngQuant(){
        $storage_path = storage_path().'/app/';
        $source_folder = 'public/pngquant/original/';
        $destination_folder = 'public/pngquant/compressed/';
        $zip_extract_folder = 'public/pngquant/extract/';

        $zip_files = Storage::files($source_folder); //Get the zip files
        $successful = 0;
        if(!empty($zip_files)){
            //re-creating the directory where compressed zip will be saved
            Storage::deleteDirectory($destination_folder);
            Storage::disk('local')->makeDirectory($destination_folder);

            //re-creating the folder where zip files will be extracted
            Storage::deleteDirectory($zip_extract_folder);
            Storage::disk('local')->makeDirectory($zip_extract_folder);

            foreach($zip_files as $zip_file){
                $zip = new ZipArchive; //creating ZipArchive object for original zip file
                if ($zip->open($storage_path.$zip_file) === TRUE) {
                    $extract_folder_name = explode("/", $zip_file);
                    $extract_folder_name = str_replace(".zip", "", array_pop($extract_folder_name)); //getting zip extraction folder name
                    $zip->extractTo($storage_path.$zip_extract_folder.$extract_folder_name); //Extracting the zip file
                    $zip->close();

                    $images = Storage::files($zip_extract_folder.$extract_folder_name); //Get the png image files
                    if(!empty($images)){
                        $compressed_zip_path = $storage_path.$destination_folder.$extract_folder_name.".zip";

                        $compressed_zip = new ZipArchive; //creating ZipArchive object for compressed zip file

                        if ($compressed_zip->open($compressed_zip_path, ZipArchive::CREATE) === TRUE){
                            foreach($images as $image){
                                $file_name = explode("/", $image);
                                $file_name = array_pop($file_name);


                                $compressed_png_content = $this->compressPNG($storage_path.$image); //Getting compressed png content

                                $extract_thumb_folder = $zip_extract_folder.$extract_folder_name."/thumb/";
                                Storage::disk('local')->makeDirectory($extract_thumb_folder); //creating thumb folder

                                $compressed_file_path = $extract_thumb_folder.$file_name;
                                Storage::disk('local')->put($compressed_file_path, $compressed_png_content); //Writing compressed png

                                $compressed_zip->addFile($storage_path.$compressed_file_path, $file_name); //adding compressed png to zip archive
                            }
                        }
                        $successful++;
                        $compressed_zip->close();
                    }
                }
            }
        }
        echo "Total ". $successful. " zip file compressed.";
        exit;
    }

    /**
     * Optimizes PNG file with pngquant 1.8 or later (reduces file size of 24-bit/32-bit PNG images).
     *
     * You need to install pngquant 1.8 on the server (ancient version 1.0 won't work).
     * There's package for Debian/Ubuntu and RPM for other distributions on http://pngquant.org
     *
     * @param $path_to_png_file string - path to any PNG file, e.g. $_FILE['file']['tmp_name']
     * @param $max_quality int - conversion quality, useful values from 60 to 100 (smaller number = smaller file)
     * @return string - content of PNG file after conversion
     */
    private function compressPNG($path_to_png_file, $max_quality = 90){
        if (!file_exists($path_to_png_file)) {
            throw new Exception("File does not exist: $path_to_png_file");
        }

        // guarantee that quality won't be worse than that.
        $min_quality = 40;

        // '-' makes it use stdout, required to save to $compressed_png_content variable
        // '<' makes it read from the given file path
        // escapeshellarg() makes this safe to use with any path
        $compressed_png_content = shell_exec("pngquant --quality=$min_quality-$max_quality - < ".escapeshellarg(    $path_to_png_file));

        if (!$compressed_png_content) {
            throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
        }

        return $compressed_png_content;
    }

    public function populateItemToCategoriesTableData(){
        $items =  Item::select('id', 'category_id', 'sort')->get();
        if(!$items->isEmpty()){
            foreach($items as $item){
                $data = [
                    'item_id' => $item->id,
                    'category_id' => $item->category_id,
                    'sort' => $item->sort,
                ];
                ItemToCategory::create($data);
            }
        }
    }
}
