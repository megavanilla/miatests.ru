<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 30.12.2017
 * Time: 18:05
 */

namespace mvc\controllers;

use \mvc\models\UserModel;

class User
{
    protected $UserModel;
    protected $login;
    protected $pass;
    protected $userData;

    public function __construct($login)
    {
        $this->UserModel = new UserModel();
        $this->login = $this->checkString($login);
    }

    private function checkString($string){
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    public function getUserFromLogin()
    {
        $this->userData = $this->UserModel->getUserFromLogin($this->login);
        return $this->userData;
    }

    /**
     * @return array
     */
    public function getPass()
    {
        return $this->UserModel->getUserFromLogin($this->login);
    }

    public function checkPass($pass, $hash)
    {
        return password_verify(($this->login.$pass), $hash);
    }

    public function setPass($pass)
    {
        return password_hash($this->login.$pass, PASSWORD_DEFAULT);
    }
}