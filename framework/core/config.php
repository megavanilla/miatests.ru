<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}


class ConfigSystem extends Constants{
  
  public static function ConfigureSystem () {
    // TODO
    if (self::param()->PRODUCTION) {
      ini_set('error_reporting', E_ALL ^ E_NOTICE);
      ini_set('display_errors', false);
      ini_set('display_startup_errors', false);
      ini_set('log_errors', true); //писать ошибки в лог Apache, посмотреть: tail /etc/httpd/logs/error_log
      ini_set('html_errors', false);
      ini_set('ignore_repeated_errors', true);
      ini_set('ignore_repeated_source', true);
    } else {
      ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_PARSE ^ E_USER_ERROR ^ E_USER_WARNING ^ E_USER_NOTICE ^ E_ERROR);
      ini_set('display_errors', true);
      ini_set('display_startup_errors', true);
      ini_set('log_errors', false);
      ini_set('html_errors', true);
    }

    // управление сессией
    /*if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'clients.') === 0) {
      //ini_set('session.save_path', 'clients_sessions'); // не работает на рабочем сервере
      ini_set('session.gc_maxlifetime', 180 * 24 * 3600);
      ini_set('session.gc_probability', 1);
      ini_set('session.gc_divisor', 1);
      ini_set('session.cookie_lifetime', 180 * 24 * 3600);
      session_name('mz-clients-sid');
    }
    */
    session_start();

    // ...
    date_default_timezone_set("UTC");
    mb_internal_encoding("UTF-8");
    define("TIME_ZONE", 'Europe/Moscow');
  }
}

ConfigSystem::ConfigureSystem();

?>