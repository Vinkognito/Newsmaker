<?php

// FRONT CONTROLLER

// Общие настройки

ini_set('display_errors',1);
error_reporting(E_ALL);

// Подключение файлов

define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Router.php');
require_once (ROOT.'/components/Db.php');

// роутер

$router1 = new Router();
$router1->run();
