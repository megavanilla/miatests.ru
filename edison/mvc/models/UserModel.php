<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 2:22
 */

namespace mvc\models;


class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct('user');
    }

    public function getUserFromId($id)
    {
        return $this->get(['user', 'pass'], $id);
    }

    public function getUserFromLogin($login)
    {
        $query = "SELECT `id`, `login`, `pass`, `hash` FROM `user` WHERE `login` = '$login'";
        $result = $this->query($query);
        return (count($result))?$result[0]:null;
    }


    public function addUser($login, $pass)
    {
        $data = [
                'login' => $login,
                'pass' => $pass
        ];
        return $this->insert($data);
    }
}