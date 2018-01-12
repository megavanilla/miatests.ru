<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace mvc\controllers;

use mvc;


Class Auth
{
  public function login($request = [])
  {
    $Request = new mvc\libs\Request();
    $Auth = new mvc\models\Auth();
    $login = $Request->getVariable($request, ['login'], null);
    $pass = $Request->getVariable($request, ['pass'], null);
    $res = $Auth->checkpass($login, $pass);
    if ($Auth->checkFirstRow($res))
    {
      $code = self::generateCode();

      // ставим куки
      @setcookie('l', "$login", time() + 3600 * 24 * 30 * 12, '/');
      @setcookie('h', $code, time() + 3600 * 24 * 30 * 12, '/');
      // обновляем значение хэша
      $Auth->update(
          [
              'hash' => $code
          ], 'login', $login);


    }
    else
    {
      self::logout();
      print(json_encode('Не удалось авторизоваться', JSON_UNESCAPED_UNICODE));
      exit;
    }
    print(json_encode($res, JSON_UNESCAPED_UNICODE));
  }

  public static function loginCoockies(){
    return ['user' => 1];
  }

  public function logout()
  {

  }

  private function generateCode()
  {
    return sha1(uniqid() . time());
  }
}