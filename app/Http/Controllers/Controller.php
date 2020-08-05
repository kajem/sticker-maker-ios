<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GuzzleHttp\Client;
use Exception;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    static $BASE_URL_API = 'http://sticker.local/api/';
    static $PAGINATION_LIMIT = 10;

    protected  function uploadFileToS3($file_path, $file_content){
        //$compressed_png_content = $this->compressPNG($file->getPathName()); //Getting compressed png content
        Storage::disk('s3')->put($file_path, $file_content);
    }

    /**
     * Optimizes PNG file with pngquant 1.8 or later (reduces file size of 24-bit/32-bit PNG images).
     *
     * You need to install pngquant 1.8 on the server (ancient version 1.0 won't work).
     * There's package for Debian/Ubuntu and RPM for other distributions on http://pngquant.org
     *
     * @param $path_to_png_file string - path to any PNG file, e.g. $_FILE['file']['tmp_name']
     * @param $max_quality int - conversion quality, useful values from 60 to 100 (smaller number = smaller file)
     * @return string - content of PNG file after conversion
     */
    private function compressPNG($path_to_png_file, $max_quality = 90){
        if (!file_exists($path_to_png_file)) {
            throw new Exception("File does not exist: $path_to_png_file");
        }

        // guarantee that quality won't be worse than that.
        $min_quality = 40;

        // '-' makes it use stdout, required to save to $compressed_png_content variable
        // '<' makes it read from the given file path
        // escapeshellarg() makes this safe to use with any path
        $compressed_png_content = shell_exec("pngquant --quality=$min_quality-$max_quality - < ".escapeshellarg(    $path_to_png_file));

        if (!$compressed_png_content) {
            throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
        }

        return $compressed_png_content;
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
