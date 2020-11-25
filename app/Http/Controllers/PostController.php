<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function list(Request $request){
        $page_title = 'How to use Sticker Maker';
        $posts = Post::query();
        $posts = $posts->select('slug', 'title', 'subtitle', 'banner', 'banner_alt', 'author', 'short_description', 'published_date');
        $posts = $posts->where('type', 'how-to-use-sm');
        $posts = $posts->where('status', 1);
        $keyword = trim($request->get('keyword'));
        $query_string_arr = [];
        if(!empty($keyword)){
            $query_string_arr['keyword'] =  $request->get('keyword');
            $posts = $posts->where('type', 'how-to-use-sm');
            $posts = $posts->where('title', 'LIKE', '%' . $keyword . '%');
            $posts = $posts->orWhere('subtitle', 'LIKE', '%' . $keyword . '%');
            $posts = $posts->orWhere('author', 'LIKE', '%' . $keyword . '%');
            $posts = $posts->orWhere('short_description', 'LIKE', '%' . $keyword . '%');
            $posts = $posts->orWhere('description', 'LIKE', '%' . $keyword . '%');
        }
        if(!empty($request->get('year'))){
            $query_string_arr['year'] =  $request->get('year');
            $posts = $posts->whereYear('published_date', $request->get('year'));
        }
        if(!empty($request->get('month'))){
            $query_string_arr['month'] =  $request->get('month');
            $posts = $posts->whereMonth('published_date', $request->get('month'));
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $page_title = 'How to use Sticker Maker - '. $months[$request->get('month') - 1].', '. $request->get('year');
        }

        $posts = $posts->orderBy('published_date', 'desc');
        $posts = $posts->paginate(10);

        $data = [
            'page_title' => $page_title,
            'posts' => $posts,
            'query_string_arr' =>$query_string_arr
        ];

        return view('post.how-to-use-sm.list')->with($data);
    }

    public function getPost($slug){
        $post = Post::query();
        $post = $post->select('slug', 'title', 'subtitle', 'banner', 'banner_alt', 'author', 'meta_title', 'meta_description', 'short_description', 'description', 'published_date', 'related_posts');
        $post = $post->where('slug', $slug);

        if(strpos($_SERVER['HTTP_HOST'], 'admin') === false || Auth::guest()){
            $post = $post->where('status', 1);
        }
        $post = $post->first();
        if(!$post){
            return abort(404);
        }
        $related_posts = [];
        if(!empty($post->related_posts))
            $related_posts = Post::select('slug', 'title', 'subtitle', 'banner', 'banner_alt', 'author', 'short_description', 'published_date')->whereIn('id', unserialize($post->related_posts))->where('status', 1)->get();

        $recent_posts = Post::select('slug', 'title', 'banner', 'banner_alt')->where('slug', '!=', $post['slug'])->where('status', 1)->orderBy('published_date', 'desc')->offset(0)->limit(5)->get();

        $archives = DB::select('SELECT YEAR(published_date) year,
                                             MONTH(published_date) month,
                                             COUNT(*) post_count
                                        FROM posts
                                        WHERE status = 1
                                        GROUP BY year, month
                                        ORDER BY year DESC, month DESC;');

        $instagram = $this->getDataFromURL('https://instagram.com/_stickermaker/?__a=1');
        $instagram = !empty($instagram->graphql->user) ? $instagram->graphql->user : [];
        if(!empty($instagram)){
            $instagram = [
                'profile_pic' => $instagram->profile_pic_url,
                'following' => $instagram->edge_follow->count,
                'followers' => $instagram->edge_followed_by->count,
                'post_count' => $instagram->edge_owner_to_timeline_media->count,
                'posts' => $instagram->edge_owner_to_timeline_media->edges
            ];
        }

        $data = [
            'post' => $post,
            'related_posts' => $related_posts,
            'recent_posts' => $recent_posts,
            'archives' => $archives,
            'instagram' => $instagram,
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        ];

        return view('post.how-to-use-sm.details')->with($data);
    }
}
