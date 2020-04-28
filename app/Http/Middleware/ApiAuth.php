<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiPassword;
use App\Project;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $today = date('Ymd');
        if(empty($request->header('UserName')) || empty($request->header('Password')) || strpos($request->header('Password'),$today) === false){
            return $this->errorOutput();
        }
        $password = str_replace($today, "", $request->header('Password'));

        $usernames = ['7liAmLyJLU05u4Dfy9CYKpXWqXaFtMD6EU6d2uGfgB2qi7'];
        $passwords = ['54jdKKFG8u9JwACVbLbHk5GsT8h5nckaMGeQEntV8zRdFIRxYHeIO'];
        $username_index = array_search($request->header('UserName'), $usernames);

        if($username_index === false)
            return $this->errorOutput();

        $cond = $password !== $passwords[0] ? "note matched" : "matched";

        if($password !== $passwords[$username_index])
            return $this->errorOutput();

        // $project_password = ApiPassword::select('id')->where('username', $request->header('UserName'))
        //                     ->where('password', $password)
        //                     ->first();
        // if(empty($project_password->id)){
        //     return $this->errorOutput();
        // }

        return $next($request);
    }

    private function errorOutput($message = 'Unauthorized access!', $code = 401){
        $data = array(
            'status' => $code,
            'message' => $message
        );
        return response()->json($data);
    }
}
