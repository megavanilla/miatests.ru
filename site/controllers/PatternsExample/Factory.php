<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace site\controllers\PatternsExample;

use site\controllers\PatternsExample\PrintText;


class Factory
{
  public static function print($type, $string)
  {
    $type = filter_var($type, FILTER_SANITIZE_STRING);
    $string = filter_var($string, FILTER_SANITIZE_STRING);

    switch ($type) {
      case 'pre':
        return new PrintText\PrintSimple($string);
        break;
      default:
        return new PrintText\PrintPre($string);
    }
  }
}