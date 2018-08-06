<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 18:36
 */

namespace site\controllers\PatternsExample\AbstractFactory;


interface AFInterface
{
  public function createWindow();
  public function createMenu();
}