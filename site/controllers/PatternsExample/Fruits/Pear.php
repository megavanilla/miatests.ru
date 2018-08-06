<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 026 26.07.18
 * Time: 18:14
 */

namespace site\controllers\PatternsExample\Fruits;


class Pear implements Fruits
{
  public function getFruit()
  {
    return 'Это груша';
  }
}