<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 18:37
 */

namespace mvc\controllers\PatternsExample\PrintText;


class PrintSimple implements PrintInerface
{
  private $str;

  public function __construct($str)
  {
    $this->str = filter_var($str, FILTER_SANITIZE_STRING);
  }

  public function print()
  {
    print($this->str);
  }
}