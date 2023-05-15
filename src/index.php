<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';

use NikitinUser\perceptronPHP\Router;

$route = $_SERVER['REQUEST_URI'];
$route = explode('?', $route);
$route = $route[0];
$route = trim($route);

if ($route[strlen($route) - 1] != "/") {
    $route .= "/";
}

$result = null;

$router = new Router();

try {
    $result = $router->main($route);
} catch (\Throwable $tMain) {
    echo $tMain->getTraceAsString();
    echo "<br>";
    echo $tMain->getMessage();
}

echo $result;
