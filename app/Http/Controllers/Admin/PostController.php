<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Post;

class PostController extends Controller
{
    public function list(Request $request, $type)
    {
        $posts = Post::query();
        $posts = $posts->select('id', 'title', 'slug', 'banner', 'banner_alt', 'tag', 'author', 'published_date', 'updated_at', 'status');

        if (!empty($request->get('search')['value'])) {
            $posts = $posts->where('title', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $posts = $posts->orWhere('slug', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $posts = $posts->orWhere('author', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $posts = $posts->orWhere('tag', 'LIKE', '%' . $request->get('search')['value'] . '%');
        }

        $total = $posts->count();

        $posts = $posts->orderBy($request->get('columns')[$request->get('order')[0]['column']]['name'], $request->get('order')[0]['dir']);
        $posts = $posts->offset($request->get('start'));
        $posts = $posts->limit($request->get('length'));
        $posts = $posts->get();

        $data = [];
        $data['draw'] = 1 + ((int)$request->get('draw'));
        $data['data'] = $posts;
        $data['recordsFiltered'] = $total;
        $data['recordsTotal'] = $total;

        return $data;
    }

    public function addView($type)
    {
        $posts = Post::select('id', 'title')->where('type', $type)->get();
        $data = [
            'title' => 'Add new post',
            'type' => $type,
            'posts' => $posts
        ];
        return view('admin.post.form')->with($data);
    }

    public function editView($type, $id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            return back()->with('error', 'Invalid action!');
        }
        $posts = Post::select('id', 'title')->where('type', $type)->where('id', '!=', $post->id)->get();
        $data = [
            'title' => 'Editing  <i>' . $post->title.'</i>',
            'type' => $type,
            'post' => $post,
            'posts' => $posts
        ];
        return view('admin.post.form')->with($data);
    }

    public function save(Request $request, $type)
    {
        $rules = [
            'title' => 'required',
        ];

        if(!empty($request->file('banner'))){
            $rules['banner'] = 'mimes:png,jpg,jpeg';
        }

        if(!empty(trim($request->input('slug')))){
            $id = empty($request->input('id')) ? 0 : $request->input('id');
            $rules['slug'] = 'unique:posts,slug,'.$id;
        }

        Validator::make($request->all(), $rules)->validate();

        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);
        unset($data['banner']);
        unset($data['q']);

        $data['description'] = str_replace('<img src="'.config('app.url').'/', '<img src="'.config('app.asset_base_url2'), $data['description']);

        if(!empty($data['related_posts'])){
            $data['related_posts'] = serialize($data['related_posts']);
        }else{
            $data['related_posts'] = null;
        }

        if(!empty($request->file('banner'))){
            $data['banner'] = $this->processBanner($request);
        }

        if(!empty($request->input('id'))){
            $post = Post::where('id', $request->input('id'))->first();
            if(date('Y-m-d', strtotime($post->published_date)) != $data['published_date']){
                $data['published_date'] = $data['published_date'].' '.date('H:i:s');
            }else{
                unset($data['published_date']);
            }

            Post::where('id', $request->input('id'))->update($data);
        }else{
            $data['published_date'] = $data['published_date'].' '.date('H:i:s');
            $data['created_by'] = Auth::user()->id;
            Post::create($data);
        }

        if(!empty($request->input('id'))){
            return redirect()->back()->with('success', 'Post has been updated successfully.');
        }else{
            return redirect(url('post/'.$type.'/list'))->with('success', 'Post has been created successfully.');
        }
    }

    private function processBanner($request){
        $extension = $request->file('banner')->getClientOriginalExtension();
        $milliseconds = round(microtime(true) * 1000);
        $banner_name = Auth::user()->id.$milliseconds.'.'.$extension;
        //Deleting existing banner
        if($request->get('id')){
            $post = Post::select('banner')->where('id', $request->input('id'))->first();
            Storage::disk('s3')->delete('website_resource/post_images/'.$post->banner);
        }

        $this->uploadFileToS3('website_resource/post_images/'.$banner_name, file_get_contents($request->file('banner')));

        return $banner_name;
    }
}
