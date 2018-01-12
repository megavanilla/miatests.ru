<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 30.12.2017
 * Time: 18:05
 */

namespace mvc\controllers;

class Registration extends User
{
    public function __construct($login)
    {
        parent::__construct($login);
    }

    public function addUser(){
        return $this->UserModel->addUser($this->login, $this->pass);
    }

    public  function changePass(){

    }
}