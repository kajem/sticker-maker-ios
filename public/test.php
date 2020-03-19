<?php
//echo "testing page";exit;
$path = "/var/www/html/stickermaker.braincraftapps.com/storage/app/items/".$_GET['file'];
//echo $path;exit;
//echo var_dump(mime_content_type($path));exit;
header('Content-Type:'.mime_content_type($path));
        header('Content-Length: ' . filesize($path));
        readfile($path);
exit;
