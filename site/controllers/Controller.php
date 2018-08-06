<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace site\controllers;

use site;

use site\configs\Config;


Class Controller
{
  public $Config;
  public $ConfigsData;

  public function __construct()
  {
    global $Configs;
    $this->Config = new Config();
    $this->ConfigsData = $Configs;
  }

  public function redirect($href)
  {
    header($href, true, 303);
  }
}