<?php
require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[2]) && $uri[2] != 'fb_pixels') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

//echo  $uri[4] . "\n\r";
require PROJECT_ROOT_PATH . "/Controller/Api/PixelController.php";
$objFeedController = new PixelController();
$strMethodName = $uri[4] . 'Action';
//echo  $strMethodName . "\n\r";
$objFeedController->{$strMethodName}();
