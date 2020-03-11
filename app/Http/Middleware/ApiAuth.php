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

        $project_password = ApiPassword::select('id')->where('username', $request->header('UserName'))
                            ->where('password', $password)
                            ->first();
        
        if(empty($project_password->id)){
            return $this->errorOutput();
        }
        return $next($request);
    }

    private function errorOutput($code = 401, $message = 'Unauthorized access!'){
        $data = array(
            'status' => $code,
            'message' => $message
        );
        return response()->json($data);
    }
}
