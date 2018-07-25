<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace mvc\controllers\PatternsExample;

use mvc\controllers\PatternsExample\PrintText;

class Strategy
{
  public function print($type, $string)
  {
    $type = filter_var($type, FILTER_SANITIZE_STRING);
    $string = filter_var($string, FILTER_SANITIZE_STRING);

    switch ($type) {
      case 'pres':
        $obj = new PrintText\PrintSimple($string);
        break;
      default:
        $obj = new PrintText\PrintPre($string);
    }

    $obj->print();
  }
}