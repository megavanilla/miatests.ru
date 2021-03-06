<?php

use \site\router\Router;
use \site\configs\Config;

$Configs = [];

// загрузка классов из lib
spl_autoload_register('Autoload7a23ewc2345f40c203fc95b0725b4e378');

/**
 * Функция автозагрузки.
 *
 * @param $class
 */
function Autoload7a23ewc2345f40c203fc95b0725b4e378($class)
{
  $folder = [
      __DIR__ . '/'
  ];

  for ($i = 0; $i < $ic = count($folder); $i++)
  {
    loadFromLibs($class, $folder[$i]);
  }
}

/**
 * Функция определения пути к классу.
 *
 * @param $class
 * @param $base_dir
 */
function loadFromLibs($class, $base_dir)
{
  $relative_class = $class;
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  if (file_exists($file))
  {
    include_once($file);
  }
}

//Загрузим конфиг параметры
$Config = new Config();
$Config->getConfig('main/main', 'main');
$Config->getConfig('db/mysql/connect', 'db');

//Запустим роутинг
$Router = new Router();
$Router->route();