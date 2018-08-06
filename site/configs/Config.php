<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 17:57
 */

namespace site\configs;


class Config
{
  public function getConfig($path, $name)
  {
    global $Configs;

    $pathConf = __DIR__ . '/' . $path . '.php';

    if (is_file($pathConf)) {
      $Configs['conf'][$name] = include_once($pathConf);
    }

    return $Configs;
  }

  public function getParam($argv = [], $index = 0, $type = 'string')
  {
    if (!is_array($argv) || !array_key_exists($index, $argv)) {
      return null;
    }

    switch ($type) {
      case 'int':
        $filter = FILTER_SANITIZE_NUMBER_INT;
        break;
      case 'float':
        $filter = FILTER_SANITIZE_NUMBER_FLOAT;
        break;
      case 'email':
        $filter = FILTER_SANITIZE_EMAIL;
        break;
      case 'url':
        $filter = FILTER_SANITIZE_URL;
        break;
      case 'string':
      default:
        $filter = FILTER_SANITIZE_STRING;
    }
    return filter_var($argv[$index], $filter);
  }
}