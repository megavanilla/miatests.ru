<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace mvc\controllers\PatternsExample\AbstractFactory;


class FactoryOSX implements AFInterface
{

  public function createWindow()
  {
    // TODO: Implement createWindow() method.
    return new OSOSX();
  }

  public function createMenu()
  {
    // TODO: Implement createMenu() method.
    return new WindowOSX();
  }
}