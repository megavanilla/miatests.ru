<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 026 26.07.18
 * Time: 18:14
 */

namespace mvc\controllers\PatternsExample\Fruits;


class Banana implements Fruits
{
  public function getFruit()
  {
    return 'Это банан';
  }
}