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
//sleep(1);//Чтобы посмотреть на прелоадер
  $res_obj = (object)['params'=>
    [
      'param1'=>1,
      'param2'=>2
    ]
  ];
  print(parse_res_route($res_obj));
  
?>