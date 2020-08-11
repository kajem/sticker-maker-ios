<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GuzzleHttp\Client;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    static $BASE_URL_API = 'http://sticker.local/api/';
    static $PAGINATION_LIMIT = 10;

    protected  function uploadFileToS3($file_path, $file_content){
        //$compressed_png_content = $this->compressPNG($file->getPathName()); //Getting compressed png content
        Storage::disk('s3')->put($file_path, $file_content);
    }

    protected function errorOutput($message, $status = 400){
        $data = [
            'status' => $status,
            'message' => $message
        ];

        return response()->json($data);
    }

    protected function successOutput($data = [], $message = 'Ok'){
        $data = [
            'status' => 200,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($data);
    }

    function getDataFromAPI($api_substring, $params = null){
        $client = new Client(['base_uri' => Controller::$BASE_URL_API]);
        $checked = $this->addCheckSum($params);

        $res = $client->request('GET', $api_substring, [
            'headers' => [
                'UserName' => '7liAmLyJLU05u4Dfy9CYKpXWqXaFtMD6EU6d2uGfgB2qi7',
                'Password' => '54jdKKFG8u9JwACVbLbHk5GsT8h5nckaMGeQEntV8zRdFIRxYHeIO20200227'
            ],
            'query' => $checked,
            'http_errors' => false
        ]);
        $obj = json_decode((string)$res->getBody());

        return $obj;
    }

    function addCheckSum($params){
        if($params && count($params) > 0){
            ksort($params);
            $arrVal = '';
            foreach ($params as $key => $val) {
                $arrVal .= $val;
            }
            $params['checksum'] = md5($arrVal);
        }else{
            $params['checksum'] = md5('');
        }
        return $params;
    }

}
