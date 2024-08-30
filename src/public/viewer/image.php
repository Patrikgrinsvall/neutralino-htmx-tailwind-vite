<?php
if ($_GET['path']) {
    $path = urldecode($_GET['path']);
    if(!file_exists($path)) {
        die("asd$path");
    }

    header("Content-Type: ".mime_content_type($path));
    $data = file($path);
    die();


}