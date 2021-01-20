<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function list(Request $request, User $model)
    {
        $model = $model::query();
        $model = $model->select('id', 'name', 'email', 'permissions', 'updated_at', 'created_at');

        if (!empty($request->get('search')['value'])) {
            $model = $model->where('name', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $model = $model->orWhere('email', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $model = $model->orWhere('updated_at', 'LIKE', '%' . $request->get('search')['value'] . '%');
            $model = $model->orWhere('created_at', 'LIKE', '%' . $request->get('search')['value'] . '%');
        }

        $total = $model->count();

        $model = $model->orderBy($request->get('columns')[$request->get('order')[0]['column']]['name'], $request->get('order')[0]['dir']);
        $model = $model->offset($request->get('start'));
        $model = $model->limit($request->get('length'));
        $users = $model->get();

        if(!$users->isEmpty()){
            for($i = 0; $i < count($users); $i++){
                if(!empty($users[$i]->permissions)){
                    $users[$i]->permissions = unserialize($users[$i]->permissions);
                }
            }
        }

        $data = [];
        $data['draw'] = 1 + ((int)$request->get('draw'));
        $data['data'] = $users;
        $data['recordsFiltered'] = $total;
        $data['recordsTotal'] = $total;

        return $data;
    }

    public function edit(User $user)
    {
        $data = [
            'title' => 'Editing  user <i>' . $user->name.'</i>',
            'user' => $user
        ];
        return view('admin.user.form')->with($data);
    }

    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'user' => Auth::user(),
            'profile_update' => true
        ];
        return view('admin.user.form')->with($data);
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create(
            $request->merge(
            [
                'password' => Hash::make($request->get('password')),
                'permissions' => !empty($request->get('permissions')) ? $this->processUserPermissions($request->get('permissions')) : null
            ]
        )->all());

        return redirect('/user/list')->with('success', __('User successfully created.'));
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $msg = 'User successfully updated.';
        if(!empty($request->get('password'))){
            $user->password = Hash::make($request->get('password'));
        }
        if(!empty($request->get('profile_update'))){
            $msg = 'Profile successfully updated';
            unset($user->permissions);
        }else{
            $user->permissions = !empty($request->get('permissions')) ? $this->processUserPermissions($request->get('permissions')) : null;
        }

        $user->name = $request->get('name');
        $user->update();
        return back()->with('success', __($msg));
    }

    private function processUserPermissions($permissions){
        //If category listing permission is given then we giving permission to category reorder also
        if(in_array('category.list', $permissions) || in_array('category.sort2', $permissions)){
            $permissions[] = 'category.update-order';
        }

        //If category add or edit permission is provided then we giving access to category save method so that user can save category
        if(in_array('category.add', $permissions) || in_array('category.edit', $permissions)){
            $permissions[] = 'category.save';
        }
        //If category details view permission is given then we are giving item reordering, add to category, remove from category permission too
        if(in_array('category.details', $permissions)){
            $permissions[] = 'category.update-item-order';
            $permissions[] = 'category.add-item-to-category';
            $permissions[] = 'category.remove-item-from-category';
        }
        //If sticker package listing permission is given then we giving access to sticker packages list fetch method so that user can see the list
        if(in_array('item.list', $permissions)){
            $permissions[] = 'item.get-list';
        }
        //If sticker package add or edit permission is provided then we giving access to sticker package save method so that user can save sticker package
        if(in_array('item.add', $permissions) || in_array('item.edit', $permissions)){
            $permissions[] = 'item.save';
        }
        //If searched keyword list permission is given then we giving access to search keyword list fetch method so that user can see the list
        if(in_array('item.search-keyword.list', $permissions)){
            $permissions[] = 'item.search-keyword.get-list';
        }
        //Website home sticker package permission
        if(in_array('item.website-home-sticker-packages', $permissions)){
            $permissions[] = 'item.add-stickage-package-to-website-home';
            $permissions[] = 'item.remove-stickage-package-from-website-home';
            $permissions[] = 'item.update-website-home-package-order';
        }
        //Telegram create new sticker set permission
        if(in_array('telegram.pack', $permissions)){
            $permissions[] = 'telegram.create-new-sticker-set';
        }
        //If post listing permission is given then we giving access to post list fetch method so that user can see the list
        if(in_array('post.list', $permissions)){
            $permissions[] = 'post.get-list';
        }
        //If post add or edit permission is provided then we giving access to post save method so that user can save sticker package
        if(in_array('post.add', $permissions) || in_array('post.edit', $permissions)){
            $permissions[] = 'post.save';
        }

        return serialize($permissions);
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }
}
