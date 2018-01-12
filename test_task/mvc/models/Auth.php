<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 2:22
 */

namespace mvc\models;


class Auth extends Model
{
  public function __construct()
  {
    parent::__construct('tasks_users');
  }

  public function checkpass($login = '', $pass =  ''){
    if(!is_string($login) || !is_string($pass) || empty($login)){return false;}

    $query = "SELECT `id`, `login` FROM `tasks_users` WHERE `login` = '$login' AND `pass` = '$pass'";
    return $this->executeSimppleQuery($query, true);
  }
}