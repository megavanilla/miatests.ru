<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 18:36
 */

namespace mvc\controllers\PatternsExample\AbstractFactory;


interface AFInterfaceOS
{
  public function start();
  public function hibernate();
  public function awakening();
  public function shutdown();
}