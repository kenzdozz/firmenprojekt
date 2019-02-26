<?php
error_reporting(1);
ini_set("display_errors", 1);

require __DIR__ .'/vendor/autoload.php';

use DB\Model;
use DB\Migration;
use CTRL\Firmen;

try {
  $dotenv = Dotenv\Dotenv::create(__DIR__);
  $dotenv->load();
} catch (\Throwable $th) {
  echo "NOTICE: '.env' file is required in the root folder to use Environment variables.";
}

$path = (explode('/', $_SERVER['REQUEST_URI']));

if ($path[1] == 'create') Firmen::createRecord();
else if ($path[1] == 'update' && $path[2] != '') Firmen::updateRecord($path[2]);
else if ($path[1] == 'delete' && $path[2] != '') Firmen::deleteRecord($path[2]);
else if ($path[1] != '' && $path[2] == '') {
    echo 'Page not found';
    die();
}

$records = Firmen::getAll();
foreach($records as $key => $rec){
    $records[$key] = (object) $rec;
}

require __DIR__ .'/home.php';
