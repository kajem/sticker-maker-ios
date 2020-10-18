<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Http\Traits\ItemsTrait;

class ItemController extends Controller
{
    use ItemsTrait;
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
        unset($data['q']);

        if(!empty($request->input('id'))){
            $item_id = $request->input('id');
            Item::where('id', $request->input('id'))->update($data);

            $item = Item::find($item_id);
            $code = $item->code;
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
            $item_id = Item::insertGetId($data);
        }

        //Uploading thumb image to AWS S3 bucket
        if(!empty($request->file('thumb'))){
            $this->uploadFileToS3('items/'.$code.'/'.$thumb_name, file_get_contents($request->file('thumb')));
            $thumbnailImage = Image::make($request->file('thumb')->getRealPath())->widen(200, function ($constraint) {
                $constraint->upsize();
            });
            $this->uploadFileToS3('items/'.$code.'/200__'.$thumb_name, $thumbnailImage->stream()->__toString());
        }

        if(!empty($request->input('id'))){
            return redirect()->back()->with('success', 'Item has been updated successfully.');
        }else{
            return redirect(url('item/list'))->with('success', 'Item has been created successfully.');
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
}
