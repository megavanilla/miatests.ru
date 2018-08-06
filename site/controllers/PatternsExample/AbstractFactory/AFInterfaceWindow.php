<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 18:36
 */

namespace site\controllers\PatternsExample\AbstractFactory;


interface AFInterfaceWindow
{
  public function create();
  public function open();
  public function resize();
  public function close();
}