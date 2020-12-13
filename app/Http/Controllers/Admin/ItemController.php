<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Traits\PngQuantTrait;
use App\ItemToCategory;use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Http\Traits\ItemsTrait;
use ZipArchive;

class ItemController extends Controller
{
    use ItemsTrait, PngQuantTrait;
    public function list(Request $request)
    {
        $emoji = Category::select('id')->where('type', 'emoji')->first();
        $emoji_cat_id = !empty($emoji->id) ? $emoji->id : 0;

        $items = Item::query();
        $items = $items->select('items.id', 'items.name', 'items.slug', 'items.code', 'items.thumb', 'items.tag', 'items.total_sticker', 'items.author', 'items.is_animated', 'items.telegram_name', 'items.is_telegram_set_completed', 'items.updated_at', 'items.status');

        if (!empty($request->get('search')['value'])) {
            $items = $items->where('items.name', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $items = $items->orWhere('items.code', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $items = $items->orWhere('items.author', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $items = $items->orWhere('items.tag', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $items = $items->orWhere('categories.name', 'LIKE', '%' . $request->get('search')['value'] . '%');
        }

        $items = $items->leftJoin('categories', 'categories.id', '=', 'items.category_id');
        $items = $items->where('items.category_id', '!=', $emoji_cat_id);

        $total = $items->count();

        $items = $items->orderBy($request->get('columns')[$request->get('order')[0]['column']]['name'], $request->get('order')[0]['dir']);
        $items = $items->offset($request->get('start'));
        $items = $items->limit($request->get('length'));
        $items = $items->get();

        if(!empty($items)){
            foreach ($items as $index => $item){
                $items[$index]->categories = $this->getCategoriesOfItem($item->id);
                $thumb = explode("/", $item->thumb);
                $items[$index]->thumb = "200__".end($thumb);
            }
        }

        $data = [];
        $data['draw'] = 1 + ((int)$request->get('draw'));
        $data['data'] = $items;
        $data['recordsFiltered'] = $total;
        $data['recordsTotal'] = $total;

        return $data;
    }

    public function editView($id)
    {
        $item = Item::find($id);
        if (empty($item)) {
            return back()->with('error', 'Invalid action!');
        }
        $data = [
            'title' => 'Editing item: ' . $item->name,
            'item' => $item
        ];
        return view('admin.item.form')->with($data);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required',
            'thumb' => 'mimes:png'
        ];

        if(!empty(trim($request->input('slug')))){
            $id = empty($request->input('id')) ? 0 : $request->input('id');
            $rules['slug'] = 'unique:items,slug,'.$id;
        }

        Validator::make($request->all(), $rules)->validate();

        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);
        unset($data['thumb']);
        unset($data['compress_with_pngquant']);
        unset($data['stickers']);
        unset($data['q']);
        $stickers = [];
        if(!empty($request->input('id'))){
            $item_id = $request->input('id');
            Item::where('id', $request->input('id'))->update($data);

            $item = Item::find($item_id);
            $code = $item->code;
            if(!empty($item->stickers)){
                $stickers = unserialize($item->stickers);
            }
            $thumb_arr = explode("/", $item->thumb);
            $thumb_name = end($thumb_arr);
        }
        else
        {
            $code = $this->uniqueCode();
            $extension = $request->file('thumb')->getClientOriginalExtension();
            $thumb_name = $code.'.'.$extension;
            $thumb_path = 'public/items/'.$code.'/'.$thumb_name;
            $data['code'] = $code;
            $data['thumb'] = $thumb_path;
            $data['created_by'] = Auth::user()->id;
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            $item_id = Item::insertGetId($data);
        }

        $storage_path = storage_path().'/app/';
        $item_path = 'public/items/';

        if(!empty($request->file('thumb'))){
            $this->processThumb($request, $item_path, $code, $thumb_name, $storage_path);
        }

        //Uploading Stickers to AWS S3 bucket
        if(!empty($request->file('stickers'))){
            $this->processStickers($request, $item_id, $stickers, $item_path, $code, $storage_path);
        }

        if(!empty($request->input('id'))){
            return redirect()->back()->with('success', 'Item has been updated successfully.');
        }else{
            return redirect(url('item/list'))->with('success', 'Item has been created successfully.');
        }
    }

    private function processThumb($request, $item_path, $code, $thumb_name, $storage_path){
        //Uploading thumb image to AWS S3 bucket
        if(!empty($request->get('compress_with_pngquant'))){
            $thumb_file_content = $this->compressPNG($request->file('thumb')->getPathname()); //Getting compressed png content
        }else{
            $thumb_file_content = file_get_contents($request->file('thumb'));
        }
        $main_thumb_path = $item_path.$code.'/'.$thumb_name;
        $main_thumb_s3_path = 'items/'.$code.'/'.$thumb_name;
        Storage::disk('local')->put($main_thumb_path, $thumb_file_content); //Saving thumb main image to local storage
        $this->uploadFileToS3($main_thumb_s3_path, $thumb_file_content);

        $thumb_200_path = $item_path.$code.'/200__'.$thumb_name;
        $thumb_200_s3_path = 'items/'.$code.'/200__'.$thumb_name;

        if(!$request->get('is_animated')){
            $thumbnailImage = Image::make($request->file('thumb')->getRealPath())->widen(200, function ($constraint) {
                $constraint->upsize();
            });
            Storage::disk('local')->put($thumb_200_path, $thumbnailImage->stream()->__toString()); //Saving thumb 200x200 image to local storage

            if(!empty($request->get('compress_with_pngquant'))){
                $thumb_200_file_content = $this->compressPNG($storage_path.$thumb_200_path); //Getting compressed png content
                Storage::disk('local')->put($thumb_200_path, $thumb_200_file_content);
                $this->uploadFileToS3($thumb_200_s3_path, $thumb_200_file_content);
            }else{
                $this->uploadFileToS3($thumb_200_s3_path, $thumbnailImage->stream()->__toString());
            }
        }else{
            Storage::disk('local')->put($thumb_200_path, file_get_contents($request->file('thumb'))); //Saving thumb 200x200 image to local storage
            $this->uploadFileToS3($thumb_200_s3_path, file_get_contents($request->file('thumb')));
        }
    }

    private function processStickers($request, $item_id, $stickers, $item_path, $code, $storage_path){
        $sticker_names = [];
        Storage::disk('local')->delete($item_path.$code.'/main.zip');
        Storage::disk('local')->delete($item_path.$code.'/thumb.zip');
        //START: Delete the existing stickers from the S3 bucket and local storage
        if(!empty($stickers)){
            foreach ($stickers as $sticker){
                //Deleting from local storage
                Storage::disk('local')->delete($item_path.$code.'/'.$sticker);
                Storage::disk('local')->delete($item_path.$code.'/thumb/'.$sticker);

                //Deleting from s3 bucket
                Storage::disk('s3')->delete('items/'.$code.'/'.$sticker);
                Storage::disk('s3')->delete('items/'.$code.'/thumb/'.$sticker);
            }
            Item::where('id', $item_id)->update(['stickers' => null]);
        }
        //END: Delete the existing stickers from the S3 bucket and local storage

        //main.zip file
        $main_zip = new ZipArchive;
        $main_zip_file_path = $storage_path.$item_path.$code.'/main.zip';
        $main_zip->open($main_zip_file_path, ZipArchive::CREATE);

        //thumb.zip file
        $thumb_zip = new ZipArchive;
        $thumb_zip_file_path = $storage_path.$item_path.$code.'/thumb.zip';
        $thumb_zip->open($thumb_zip_file_path, ZipArchive::CREATE);

        $count = 1;
        foreach ($request->file('stickers') as $sticker){
            $milliseconds = round(microtime(true) * 1000);
            $extension = $sticker->extension();
            $file_name = $request->input('id').$milliseconds.$count.'.'.$extension;
            $s3_path = 'items/'.$code.'/';
            if(!empty($request->get('compress_with_pngquant'))){
                $sticker_main_file_content = $this->compressPNG($sticker->getPathname()); //Getting compressed png content
            }else{
                $sticker_main_file_content = file_get_contents($sticker);
            }
            Storage::disk('local')->put($item_path.$code.'/'.$file_name, $sticker_main_file_content); //Saving original image to local storage
            $this->uploadFileToS3($s3_path.$file_name, $sticker_main_file_content); //Uploading original image to s3 bucket

            //processing thumb image
            $sticker_thumb_200_path = $item_path.$code.'/thumb/'.$file_name;
            $sticker_thumb_200_s3_path = $s3_path.'thumb/'.$file_name;
            if(!$request->get('is_animated')){
                $thumbnailImage = Image::make($sticker->getRealPath())->widen(200, function ($constraint) {
                    $constraint->upsize();
                });
                Storage::disk('local')->put($sticker_thumb_200_path, $thumbnailImage->stream()->__toString()); //Saving original image to local storage

                if(!empty($request->get('compress_with_pngquant'))){
                    $sticker_thumb_file_content = $this->compressPNG($storage_path.$sticker_thumb_200_path); //Getting compressed png content
                    Storage::disk('local')->put($sticker_thumb_200_path, $sticker_thumb_file_content);
                    $this->uploadFileToS3($sticker_thumb_200_s3_path, $sticker_thumb_file_content);
                }else{
                    $this->uploadFileToS3($sticker_thumb_200_s3_path, $thumbnailImage->stream()->__toString());
                }
            }else{
                Storage::disk('local')->put($sticker_thumb_200_path, file_get_contents($sticker)); //Saving original image to local storage
                $this->uploadFileToS3($sticker_thumb_200_s3_path, file_get_contents($sticker));
            }

            //Adding images to main.zip and thumb.zip
            $main_zip->addFile($storage_path.$item_path.$code.'/'.$file_name, $file_name); //Adding file to main zip archive
            $thumb_zip->addFile($storage_path.$item_path.$code.'/thumb/'.$file_name, $file_name); //Adding file to main zip archive

            $sticker_names[] = $file_name;
            $count++;
        }

        $main_zip->close(); // All files are added, so close the zip file.
        $thumb_zip->close(); // All files are added, so close the zip file.
        $this->uploadFileToS3($s3_path.'main.zip', file_get_contents($main_zip_file_path)); //Uploading the main.zip file to the s3 bucket
        $this->uploadFileToS3($s3_path.'thumb.zip', file_get_contents($thumb_zip_file_path)); //Uploading the main.zip file to the s3 bucket

        $update_item = [
            'total_sticker' => count($sticker_names),
            'stickers' => serialize($sticker_names)
        ];
        Item::where('id', $item_id)->update($update_item);

        $item_to_category = ItemToCategory::select('category_id')->where('item_id', $item_id)->get();
        if(!$item_to_category->isEmpty()){
            $categoryController = new CategoryController();
            foreach ($item_to_category as $cat){
                $categoryController->calculateTotalItemSticker($cat->category_id);
            }
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

    public function downloadReport(Request $request){
        $filename = 'sticker-packages-'.date('m-d-Y');
        $items = Item::query();
        $items = $items->orderBy('view_count', 'desc');
        $items = $items->get();

        if($items->isEmpty()){
            return redirect('search-keyword/list')->with('error', 'No keywords`  found to download!');
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: octet/stream");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-disposition: attachment; filename=" .$filename.".csv");

        $handle = fopen( 'php://output', 'w' );

        $columns = ['name', 'code', 'total_sticker', 'view_count', 'download_count', 'is_premium', 'is_animated',
            'telegram_name', 'slug', 'tag', 'meta_title', 'meta_description', 'author', 'status'];
        fputcsv( $handle,  $columns);

        foreach ( $items as $item ) {
            $value = [];
            foreach ($columns as $column){
                $value[] = $item->$column;
            }
            fputcsv( $handle, $value);
        }

        fclose( $handle );

        // use exit to get rid of unexpected output afterward
        exit();
    }
}
