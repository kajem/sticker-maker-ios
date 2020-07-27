<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;

class ItemController extends Controller
{
    public function list(Request $request)
    {
        $items = Item::query();
        $items = $items->select('items.id', 'items.name', 'items.code', 'items.total_sticker', 'items.author', 'items.is_premium', 'items.status', 'items.category_id', 'items.updated_at', 'categories.name as category_name', 'categories.type as category_type');

        if(!empty($request->get('search')['value'])){
            $items = $items->where('items.name','LIKE','%'.$request->get('search')['value'].'%');
            $items = $items->orWhere('items.code','LIKE','%'.$request->get('search')['value'].'%');
            $items = $items->orWhere('items.author','LIKE','%'.$request->get('search')['value'].'%');
            $items = $items->orWhere('categories.name','LIKE','%'.$request->get('search')['value'].'%');
        }

        $items = $items->join('categories', 'categories.id', '=', 'items.category_id');

        $total = $items->count();

        $items = $items->orderBy($request->get('columns')[$request->get('order')[0]['column']]['name'], $request->get('order')[0]['dir']);
        $items = $items->offset($request->get('start'));
        $items = $items->limit($request->get('length'));
        $items = $items->get();

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
        if(empty($item)){
            return back()->with('error', 'Invalid action!');
        }
        dd($item);exit;
    }
}
