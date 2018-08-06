<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace site\controllers\PatternsExample\AbstractFactory;


class FactorySolaris implements AFInterface
{
  public function createWindow()
  {
    // TODO: Implement createWindow() method.
    return new OSSolaris();
  }

  public function createMenu()
  {
    // TODO: Implement createMenu() method.
    return new WindowSolaris();
  }
}