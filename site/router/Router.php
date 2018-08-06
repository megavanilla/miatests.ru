<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 21:58
 */

namespace site\router;

use site\configs\Config;
use site\libs\Request;

class Router
{
  private $Config;
  private $Request;
  private $method = '';
  private $controller = '';
  private $params = [];

  public function __construct()
  {
    global $Configs;

    $this->Config = new Config();

    $this->Request = new Request();
    $this->params = $this->Request->getRequest();
    $this->controller = $this->Request->getVariable($this->params, ['controller'], '');
    $this->controller = str_replace('/', '\\', $this->controller);
    $this->method = $this->Request->getVariable($this->params, ['method'], '');

    if (!empty($this->controller)) {
      unset($this->params['controller']);
    }

    if (!empty($this->method)) {
      unset($this->params['method']);
    }

    if (empty($this->controller) && empty($this->method)) {
      $this->controller = $this->Request->getVariable($Configs, ['conf', 'main', 'default_controller'], '');
      $this->method = $this->Request->getVariable($Configs, ['conf', 'main', 'default_method'], '');
    }
  }

  public function route()
  {
    $arrControllers = explode('\\', $this->controller);
    array_walk(
      $arrControllers,
      function (&$currentValue) {
        $currentValue = ucwords($currentValue);
      });
    $controller = "site\controllers\\" . rtrim(implode('\\', $arrControllers), '/\\');

    $method = $this->method;
    if ($controller == '' || $method == '') {
      trigger_error("Не указан контроллер или его метод.", E_USER_WARNING);
      exit;
    }
    if (!class_exists($controller)) {
      trigger_error("Не удалось определить контроллер \"'.$controller.'\".", E_USER_WARNING);
      exit;
    }
    if (!method_exists(new $controller, $method)) {
      trigger_error('Не удалось определить метод контроллера "' . $method . '"".', E_USER_WARNING);
      exit;
    }

    call_user_func_array([new $controller(), $method], [$this->params]);
  }
}