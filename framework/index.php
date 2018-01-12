<?php

//Разрешаем странице работать в системе
define('READFILE', true);


//Инициируем ядро
if (is_file(__DIR__.'/core/init.php')) {
    require_once (__DIR__.'/core/init.php');
}
else
{
    exit('Не удалось инициировать ядро');
}


$view = new View();
$view->view = 'index';
$view->debug = $debug;
$view->render();

?>