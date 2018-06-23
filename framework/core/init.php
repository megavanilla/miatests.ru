<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
//Инициируем класс констант
if (is_file(__DIR__.'/Constants.php')) {
    require_once (__DIR__.'/Constants.php');
}
else
{
    print(__DIR__.'/Constants.php');
    exit('Не удалось инициировать класс констант');
}

//Инициируем переменные системы
if (is_file(Constants::path()->DIR_CORE.'config.php')) {
    require_once (Constants::path()->DIR_CORE.'config.php');
}
else
{
    exit('Не удалось инициировать переменные системы');
}

//Инициируем обработчик ошибок
if (is_file(Constants::path()->DIR_CORE.'Errors.php')) {
    require_once (Constants::path()->DIR_CORE.'Errors.php');
}
else
{
    exit('Не удалось инициировать обработчик ошибок');
}

$error = new Errors();

//Инициируем отладчик ядра
if (is_file(Constants::path()->DIR_CORE.'Debug.php')) {
    require_once (Constants::path()->DIR_CORE.'Debug.php');
}
else
{
    exit('Не удалось инициировать отладчик ядра');
}

$debug = new Debug();

//Инициируем обработчик представлений
if (is_file(Constants::path()->DIR_CORE.'View.php')) {
    require_once (Constants::path()->DIR_CORE.'View.php');
}
else
{
    exit('Не удалось инициировать обработчик представлений');
}

//Инициируем базовые методы ядра
if (is_file(Constants::path()->DIR_LIBS.'basis.php')) {
    require_once (Constants::path()->DIR_LIBS.'basis.php');
}
else
{
    exit('Не удалось инициировать базовые методы ядра');
}

//Инициируем обработчик БД
if (is_file(Constants::path()->DIR_CORE.'Db.php')) {
    require_once (Constants::path()->DIR_CORE.'Db.php');
}
else
{
    exit('Не удалось инициировать обработчик БД');
}

$db_pdo_mysql = new Db();
$db_pdo_mysql->setDbPass(Constants::db()->PASS);
$db_pdo_mysql->setDbStringConnect(Constants::db()->TYPE.
    ':host='.Constants::db()->HOST.
    ';dbname='.Constants::db()->BASE.
    ';port='.Constants::db()->PORT.
    ';charset='.Constants::db()->CHARSET);
$db_pdo_mysql->connect(TRUE);

$db_mysql = $db_pdo_mysql->getdbConnection();

//opa($db_pdo->getDbStringConnect());

$db_pdo_sqlite = new Db();
$db_pdo_sqlite->setDbStringConnect('sqlite:' . Constants::path()->DIR_DB_CORE_BASES . 'db_sessions.sq3');
$db_pdo_sqlite->connect(false);

$db_sqlite = $db_pdo_sqlite->getdbConnection();


//Получаем скрипт создания таблицы сессий
$sql = null;

if(is_file(Constants::path()->DIR_DB_CORE_SQL.'create_sessions_table.sql')){
  $sql = file_get_contents(Constants::path()->DIR_DB_CORE_SQL.'create_sessions_table.sql');
}
//Если таблицы с сессиями нет, то создадим её
//$res = $db_pdo_mysql->exec_query($sql);

?>