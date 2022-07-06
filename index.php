<?php
$action = array_filter(explode("/", isset($_GET['action']) ? $_GET['action'] : ''));

if (!isset($action[0])) {
    $action[0] = 'anasayfa';
    
}


function createDuration($duration = 0) {

    return date("Y-m-d H:i:s", (strtotime(date("Y-m-d H:i:s")) + ($duration * 86400)));

}

$realPath = "themes/white/";


if (!file_exists($realPath . "/" . strtolower($action[0] . ".php"))) {

    $action[0] = "404";
    
}

require $realPath . "" . $action[0] . ".php";
?>