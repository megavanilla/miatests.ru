<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 026 26.07.18
 * Time: 18:14
 */

namespace mvc\controllers\PatternsExample\Fruits;


abstract class FruitsDecorator implements Fruits
{
  protected $decoratedFruits;

  public function __construct(Fruits $decoratedFruits)
  {
    $this->decoratedFruits = $decoratedFruits;
  }

  public function getFruit()
  {
    $this->decoratedFruits->getFruit();
  }
}