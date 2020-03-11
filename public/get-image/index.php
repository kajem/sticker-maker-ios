<?php
$code = $_GET['code'];
$file_name = $_GET['file_name'];
//$root = '/var/www/html/sticker/';
$root = '/home3/ie04lzfd/stickermaker.addtexts.com/';
$root_path = $root.'storage/app/items/'.$code.'/';
$path = $root_path.$file_name;
if(!file_exists($path)){
    echo json_encode(['status' => 400, 'message' => 'File not found']);exit;
}


//$storage_path = 'items/'.$code.'/'.$file_name;;

header('Content-Type:'.mime_content_type($path));
header('Content-Length: ' . filesize($path));
readfile($path);
