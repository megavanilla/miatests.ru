<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 026 26.07.18
 * Time: 18:14
 */

namespace site\controllers\PatternsExample\Fruits;


class ApplePropertiesDecorator extends FruitsDecorator
{
  public function __construct(Fruits $decoratedFruits)
  {
    parent::__construct($decoratedFruits);
  }

  private function redApple()
  {
    return ' красного цвета';
  }

  private function getGreen()
  {
    return ' c листочком';
  }

  public function getFruit()
  {
    return
      $this->decoratedFruits->getFruit()
      . $this->redApple()
      . $this->getGreen();
  }
}