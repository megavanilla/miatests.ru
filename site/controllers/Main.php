<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace site\controllers;

use site\views;


Class Main
{
    public function show()
    {
        global $Configs;

        $Views = new views\View();
        $Views->showPage('show', $Configs, 'Тестовые задания');
    }
}