<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace mvc\controllers;

use mvc;


Class Controller
{
  public function redirect($href)
  {
    header($href, true, 303);
  }
}