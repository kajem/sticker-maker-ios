<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SearchKeyword;

class SearchKeywordController extends Controller
{
    public function list(Request $request)
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

    public function downloadReport(Request $request){
        $filename = 'pack-search-keywords';
        $search_keywords = SearchKeyword::query();
        $search_keywords = $search_keywords->select('name', 'count', 'is_item_found', 'updated_at', 'created_at');

        if(!empty($request->get('search'))){
            $search_keywords = $search_keywords->where('name', 'LIKE', '%' . $request->get('search') . '%');
            $filename .= '-keyword='.$request->get('search');
        }
        if(!empty($request->get('date_from'))){
            $search_keywords = $search_keywords->whereDate('updated_at', '>=', $request->get('date_from'));
            $filename .= '-date-from='.$request->get('date_from');
        }
        if(!empty($request->get('date_to'))){
            $search_keywords = $search_keywords->whereDate('updated_at', '<', $request->get('date_to'));
            $filename .= '-date-to='.$request->get('date_to');
        }

        $search_keywords = $search_keywords->orderBy('count', 'desc');
        $search_keywords = $search_keywords->get();

        if($search_keywords->isEmpty()){
            return redirect('search-keyword/list')->with('error', 'No keywords`  found to download!');
        }

        header( 'Content-Type: application/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '.csv";' );

        $handle = fopen( 'php://output', 'w' );
        //dd($search_keywords);
        //use keys as column titles
        fputcsv( $handle, ['name', 'count', 'is_item_found', 'updated_at', 'created_at'] );

        foreach ( $search_keywords as $search_keyword ) {
            $value = [
                $search_keyword->name,
                $search_keyword->count,
                $search_keyword->is_item_found,
                $search_keyword->updated_at,
                $search_keyword->created_at,
                ];
            fputcsv( $handle, $value, ';');
        }

        fclose( $handle );

        // use exit to get rid of unexpected output afterward
        exit();
    }
}
