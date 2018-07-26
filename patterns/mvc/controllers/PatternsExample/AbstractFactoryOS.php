<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace mvc\controllers\PatternsExample;

use mvc\controllers\PatternsExample\AbstractFactory;


class AbstractFactoryOS
{
  public static function getInstance($type)
  {
    $type = filter_var($type, FILTER_SANITIZE_STRING);

    switch ($type) {
      case 'osx':
        return new AbstractFactory\FactoryOSX();
        break;
      default:
        return new AbstractFactory\FactorySolaris();
    }
  }
}