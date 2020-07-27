<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Category;
use App\Item;
use App\ItemToCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'items', 'stickers')->where('type', 'general')->orderBy('sort', 'asc')->get();
        $data = [
            'title' => 'Category',
            'categories' => $categories,
            'sort_field' => 'sort'
        ];
        return view('admin.category.list')->with($data);
    }

    public function orderCategoryBySort2Field()
    {
        $categories = Category::select('id', 'name', 'items', 'stickers')->where('type', 'general')->orderBy('sort2', 'asc')->get();
        $data = [
            'title' => 'Category ordering by Sort2 field',
            'categories' => $categories,
            'sort_field' => 'sort2'
        ];
        return view('admin.category.list')->with($data);
    }

    public function details($id)
    {
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
        $rules = ['name' => 'required'];
        if(!empty($request->input('version')))
            $rules['version'] = 'numeric';
        Validator::make($request->all(), $rules)->validate();

        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);
        unset($data['q']);
        if(!empty($request->input('id'))){
            Category::where('id', $request->input('id'))->update($data);
            return redirect()->back()->with('success', 'Category has been updated successfully.');
        }
        else
        {
            $order = Category::max('sort');
            $data['sort'] = $order + 1;
            $data['sort2'] = $order + 1;
            $data['created_by'] = Auth::user()->id;
            $data['version'] = empty($request->input('version')) ? 1 : $request->input('version');
            Category::create($data);
            return redirect(url('category/list'))->with('success', 'Category has been created successfully.');
        }


    }
}
