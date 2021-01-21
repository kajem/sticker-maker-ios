<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Category;
use App\Item;
use App\ItemToCategory;
use App\Http\Traits\ItemsTrait;
class CategoryController extends Controller
{
    use ItemsTrait;
    public function index()
    {
        $categories = Category::select('id', 'name', 'thumb_bg_color', 'items', 'stickers', 'status')->where('type', 'general')->orderBy('sort', 'asc')->get();
        $data = [
            'title' => 'Category',
            'categories' => $categories,
            'sort_field' => 'sort',
            'asset_base_url' => config('app.asset_base_url')
        ];
        return view('admin.category.list')->with($data);
    }

    public function orderCategoryBySort2Field()
    {
        $categories = Category::select('id', 'name', 'thumb_bg_color', 'items', 'stickers', 'status')->where('type', 'general')->orderBy('sort2', 'asc')->get();
        $data = [
            'title' => 'Category ordering by Sort2 field',
            'categories' => $categories,
            'sort_field' => 'sort2',
            'asset_base_url' => config('app.asset_base_url')
        ];
        return view('admin.category.list')->with($data);
    }

    public function details($id)
    {
        $emoji = Category::select('id')->where('type', 'emoji')->first();
        $emoji_cat_id = !empty($emoji->id) ? $emoji->id : 0;

        $category = Category::find($id);

        $items = Item::query();
        $items = $items->select('items.id', 'items.name', 'items.thumb', 'items.total_sticker', 'items.code');
        $items = $items->join('item_to_categories', 'item_to_categories.item_id', '=', 'items.id');
        $items = $items->where('item_to_categories.category_id', $id);
        $items = $items->orderBy('item_to_categories.sort', 'asc');
        $items = $items->get();
        $item_ids = [];
        if(!empty($items)){
            foreach ($items as $index => $item){
                $items[$index]->categories = $this->getCategoriesOfItem($item->id, $id);
                $item_ids[] = $item->id;
            }
        }

        $all_items = Item::where('category_id', '!=', $emoji_cat_id)->whereNotIn('id', $item_ids)->orderBy('name', 'asc')->get();
        if(!empty($all_items)){
            foreach ($all_items as $index => $item){
                $all_items[$index]->categories = $this->getCategoriesOfItem($item->id, $id);
            }
        }

        $data = [
            'category' => $category,
            'items' => $items,
            'all_items' => $all_items
        ];

        return view('admin.category.details')->with($data);
    }

    public function updateOrder(Request $request)
    {
        if ($request->has('ids')) {
            $arr = explode(',', $request->input('ids'));

            foreach ($arr as $sortOrder => $id) {
                $category = Category::find($id);

                if ($request->input('sort_field') == 'sort')
                    $category->sort = $sortOrder;
                else
                    $category->sort2 = $sortOrder;

                $category->save();
            }
            return parent::successOutput([], 'Order updated successfully.');
        }
    }

    public function updateItemOrder(Request $request)
    {
        if ($request->has('ids')) {
            $arr = explode(',', $request->input('ids'));

            foreach ($arr as $sortOrder => $id) {
                ItemToCategory::where('item_id', $id)->where('category_id', $request->input('category_id'))->update(['sort' => $sortOrder]);
            }
            return parent::successOutput([], 'Order updated successfully.');
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $data = [
            'title' => 'Editing category: ' . $category->name,
            'category' => $category
        ];
        return view('admin.category.form')->with($data);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required',
            'thumb' => 'mimes:png',
            'thumb_v' => 'mimes:png'
        ];
        if(!empty($request->input('version')))
            $rules['version'] = 'numeric';
        Validator::make($request->all(), $rules)->validate();

        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);
        unset($data['thumb']);
        unset($data['q']);

        if(!empty($request->input('id'))){
            $category_id = $request->input('id');
            Category::where('id', $request->input('id'))->update($data);
        }
        else
        {
            $order = Category::max('sort');
            $data['sort'] = $order + 1;
            $data['sort2'] = $order + 1;
            $data['created_by'] = Auth::user()->id;
            $data['version'] = empty($request->input('version')) ? 1 : $request->input('version');
            $category_id = Category::insertGetId($data);
        }

        //Uploading thumb image to AWS S3 bucket
        if(!empty($request->file('thumb'))){
            $thumb_name = 'cat_'.$category_id.'_tmb.png';
            $this->uploadFileToS3('category-thumbs/'.$thumb_name, file_get_contents($request->file('thumb')));
            Category::where('id', $category_id)->update(['thumb' => $thumb_name]);
        }
        //Uploading thumb landscape image to AWS S3 bucket
        if(!empty($request->file('thumb_v'))){
            $thumb_v_name = 'cat_'.$category_id.'_v_tmb.png';
            $this->uploadFileToS3('category-thumbs/'.$thumb_v_name, file_get_contents($request->file('thumb_v')));
            Category::where('id', $category_id)->update(['thumb_v' => $thumb_v_name]);
        }

        if(!empty($request->input('id'))){
            return redirect()->back()->with('success', 'Category has been updated successfully.');
        }else{
            return redirect(url('category/list'))->with('success', 'Category has been created successfully.');
        }
    }

    public function addItemToCategory(Request $request){
        $category_id = $request->input('category_id');
        $category = Category::find($category_id);
        if(empty($category))
            return $this->errorOutput('Invalid action. Something went wrong.');

        $item_id = $request->input('item_id');
        $item = Item::find($item_id);
        if(empty($item))
            return $this->errorOutput('Invalid action. Something went wrong.');

        $item_to_category = ItemToCategory::where('category_id', $category_id)->where('item_id', $item_id)->first();
        if(!empty($item_to_category))
            return $this->successOutput([], 'Item already in the category.');

        $sort = ItemToCategory::where('category_id', $category_id)->max('sort');
        $data = [
            'category_id' => $category_id,
            'item_id' => $item_id,
            'sort' => $sort + 1
        ];
        ItemToCategory::create($data);

        $this->calculateTotalItemSticker($category_id);

        return $this->successOutput([], 'Pack added successfully.');
    }

    /**
     * @desc Updating the items and stickers field in Category table
     * @param $category_id
     */
    public function calculateTotalItemSticker($category_id){
        $items = Item::query();
        $items = $items->select('items.total_sticker');
        $items = $items->join('item_to_categories', 'item_to_categories.item_id', '=', 'items.id');
        $items = $items->where('item_to_categories.category_id', $category_id);
        $items = $items->get();

        $sticker_count = 0;
        $item_count = 0;
        if($items->isNotEmpty()){
            foreach ($items as $item){
                $sticker_count += $item->total_sticker;
                $item_count++;
            }
        }
        Category::where('id', $category_id)->update(['items' => $item_count, 'stickers' => $sticker_count]);
    }

    public function removeItemFromCategory(Request $request){
        ItemToCategory::where('category_id', $request->input('category_id'))
            ->where('item_id', $request->input('item_id'))
            ->delete();

        $this->calculateTotalItemSticker($request->input('category_id'));

        return $this->successOutput([], 'Pack removed successfully.');
    }
}
