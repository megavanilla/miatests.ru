<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 30.12.2017
 * Time: 18:05
 */

namespace mvc\controllers;

class Auth extends User
{
    public function __construct($login)
    {
        parent::__construct($login);
    }

    public function login()
    {
        setcookie('uid', $this->userData, time() + 86400, '/',  $_SERVER['HTTP_HOST'], true, true);
    }

    public function logout()
    {
        setcookie('uid', $this->userData, time() + 86400, '/',  $_SERVER['HTTP_HOST'], true, true);
    }
}