<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::select('id', 'name', 'items', 'stickers')->where('type', 'general')->orderBy('sort', 'asc')->get();
        return view('admin.category.list')->with(['categories' => $categories]);
    }

    public function updateOrder(Request $request){
        if($request->has('ids')){
            $arr = explode(',',$request->input('ids'));
            
            foreach($arr as $sortOrder => $id){
                $menu = Category::find($id);
                $menu->sort = $sortOrder;
                $menu->save();
            }
            return parent::successOutput([], 'Order updated successfully.');
        }
    }
}
