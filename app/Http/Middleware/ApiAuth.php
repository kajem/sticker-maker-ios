<?php

namespace App\Http\Middleware;

use Closure;

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
        if(empty($request->header('UserName')) || empty($request->header('Password'))){
            return $this->errorOutput();
        }

        $usernames = [
            '7liAmLyJLU05u4Dfy9CYKpXWqXaFtMD6EU6d2uGfgB2qi7',
            'nocropRigE2sa4KJ8hzUOx5GlELitZjmod5ILd',
            'photoCollageHwW3EZ5FRhBZdUVWv5z6'
    ];
        $passwords = [
            '54jdKKFG8u9JwACVbLbHk5GsT8h5nckaMGeQEntV8zRdFIRxYHeIO',
            'lXtSxVsKmpQ&MjFzn!$9a#JUcrhpk&hlsw%J5gTtycKmLAUMOcwS2GykpHLIO!wZ',
            '*8xa,J#&,fy35^p.yKU<3&5r)X>Q,zw&@tq}+X?Y2&>A[@n<[{]n^cDwp((8:A_{'
        ];

        $username_index = array_search($request->header('UserName'), $usernames);
        if($username_index === false)
            return $this->errorOutput();

        if($request->header('Password') !== $passwords[$username_index])
            return $this->errorOutput();

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
