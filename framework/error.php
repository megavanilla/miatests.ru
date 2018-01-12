<?php
//Разрешаем странице работать в системе
define('READFILE', true);

//Инициируем ядро
if (is_file($_SERVER['DOCUMENT_ROOT'].'/core/init.php')) {
    require_once ($_SERVER['DOCUMENT_ROOT'].'/core/init.php');
}
else
{
    exit('Не удалось инициировать ядро');
}

$err_obj_json = new stdClass();
$err_obj = new stdClass();

if (is_file(Constants::path()->DIR_JSON.'http_status.json')) {
    $err_obj_json = json_decode(file_get_contents(Constants::path()->DIR_JSON.'http_status.json'));
}
$error = (filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS))?filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS):http_response_code();

$err_obj->no = $error;
$err_obj->mess = 'Неопределённая ошибка';
$err_obj->desc = 'Ошибка не распознана';
  if(!empty($err_obj_json) && property_exists($err_obj_json, $error)){
    $err_obj->mess = $err_obj_json->$error->message_russ;
    $err_obj->desc = $err_obj_json->$error->desc;
  }else{
    switch ($error) {
      case '404':
        $err_obj->mess = 'Не найдена страница';
        $err_obj->desc = 'Указанный адрес страницы не обнаружен';
        break;
    }
  }
  
$errorMessage = <<<ERROR
<div class='error' style='height:150px; width:650px; margin:0 auto; margin-top:5%;'>
  <h3>Ошибка № <span style='color:red;'>$err_obj->no</span>.</h3>
  <h4>Сообщение: <i>$err_obj->mess.</i></h4>
  <h4>Описание: <i>$err_obj->desc</i></h4>
  <div><a href='/'>Вернуться на главную</a></div>
</div>
ERROR;

exit($errorMessage);
?>