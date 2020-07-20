<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Item;
use App\ItemToCategory;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::select('id', 'name', 'items', 'stickers', 'sort2')->where('type', 'general')->orderBy('sort', 'asc')->get();
        return view('admin.category.list')->with(['categories' => $categories]);
    }

    public function details($id){
        $category = Category::find($id);

        $items = Item::query();
        $items = $items->select('items.id', 'items.name', 'items.thumb', 'items.total_sticker', 'items.code');
        $items = $items->join('item_to_categories', 'item_to_categories.item_id', '=', 'items.id');
        $items = $items->where('item_to_categories.category_id', $id);
        $items = $items->orderBy('item_to_categories.sort', 'asc');
        $items = $items->get();

        $data = [
            'category' => $category,
            'items' => $items
        ];

        return view('admin.category.details')->with($data);
    }

    public function updateOrder(Request $request){
        if($request->has('ids')){
            $arr = explode(',',$request->input('ids'));
            
            foreach($arr as $sortOrder => $id){
                $category = Category::find($id);
                $category->sort = $sortOrder;
                $category->save();
            }
            return parent::successOutput([], 'Order updated successfully.');
        }
    }
    
    public function updateItemOrder(Request $request){
        if($request->has('ids')){
            $arr = explode(',',$request->input('ids'));
            
            foreach($arr as $sortOrder => $id){
                ItemToCategory::where('item_id', $id)->where('category_id', $request->input('category_id'))->update(['sort' => $sortOrder]);
            }
            return parent::successOutput([], 'Order updated successfully.');
        }
    }
}
