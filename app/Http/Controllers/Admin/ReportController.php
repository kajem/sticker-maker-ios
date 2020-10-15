<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SearchKeyword;

class ReportController extends Controller
{
    public function searchKeyword(Request $request)
    {
        $search_keywords = SearchKeyword::query();
        $search_keywords = $search_keywords->select('name', 'count', 'is_item_found', 'updated_at');
        $search_keywords = $search_keywords->where('name', 'LIKE', '%' . $request->get('search')['value'] . '%');
        if(!empty($request->get('date_from'))){
            $search_keywords = $search_keywords->whereDate('updated_at', '>=', $request->get('date_from'));
        }
        if(!empty($request->get('date_to'))){
            $search_keywords = $search_keywords->whereDate('updated_at', '<', $request->get('date_to'));
        }

        $total = $search_keywords->count();

        $search_keywords = $search_keywords->orderBy($request->get('columns')[$request->get('order')[0]['column']]['name'], $request->get('order')[0]['dir']);
        $search_keywords = $search_keywords->offset($request->get('start'));
        $search_keywords = $search_keywords->limit($request->get('length'));
        $search_keywords = $search_keywords->get();

        $data = [];
        $data['draw'] = 1 + ((int)request()->get('draw'));
        $data['data'] = $search_keywords;
        $data['recordsFiltered'] = $total;
        $data['recordsTotal'] = $total;

        return $data;
    }
}
