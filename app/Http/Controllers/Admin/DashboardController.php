<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Item;
use App\SearchKeyword;
use App\ItemToCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $category_count = Category::where('type', 'general')->count();

        $emoji = Category::select('id')->where('type', 'emoji')->first();
        $emoji_id = !empty($emoji->id) ? $emoji->id : 0;
        $emoji_item_count = ItemToCategory::where('category_id', '=', $emoji_id)->count();

        $item_count = Item::where('category_id', '!=', $emoji_id)->count();
        $search_keyword_count = SearchKeyword::count();

        $data = [
            'category_count' => $category_count,
            'emoji_item_count' => $emoji_item_count,
            'item_count' => $item_count,
            'search_keyword_count' => $search_keyword_count,
            'emoji_id' => $emoji_id
        ];

        return view('admin.dashboard.dashboard')->with($data);

    }
}
