<?php

// Общие настройки
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

// Подключение файлов системы
define('BASEPATH', __DIR__);
require_once(BASEPATH.'/framework/Autoload.php');

// Вызов Router
$router = new Router();
$router->run();