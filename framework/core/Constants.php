<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}

/**
 * Класс содержит основные константы
 */
class Constants{
  protected static $PARAM;
  protected static $PATH;
  protected static $URL;
  protected static $SERVER;
  protected static $DB;
  
  public static function get(){
    $CONSTANTS = new stdClass();
    $CONSTANTS->PARAM = self::param();
    $CONSTANTS->PATH = self::path();
    $CONSTANTS->URL = self::url();
    $CONSTANTS->SERVER = self::server();
    $CONSTANTS->DB = self::db();
    
    return $CONSTANTS;
  }
  
  public static function param(){
    self::$PARAM = new stdClass();
    self::$PARAM->PRODUCTION = FALSE;
    
    return self::$PARAM;
  }
  
  public static function path(){
    self::$PATH = new stdClass();
    self::$PATH->ROOT = $_SERVER['DOCUMENT_ROOT'].'/framework';
    self::$PATH->DIR_CORE = self::$PATH->ROOT.'/core/';
    self::$PATH->DIR_JSON = self::$PATH->ROOT.'/json_objs/';
    self::$PATH->DIR_LIBS = self::$PATH->ROOT.'/core/libs/';
    self::$PATH->DIR_DB_CORE = self::$PATH->ROOT.'/core/db/';
    self::$PATH->DIR_DB_CORE_BASES = self::$PATH->ROOT.'/core/db/bases/';
    self::$PATH->DIR_DB_CORE_SQL = self::$PATH->ROOT.'/core/db/sql/';
    /*Публичные каталоги*/
    self::$PATH->DIR_PUB = self::$PATH->ROOT.'/pub/';
    self::$PATH->DIR_CSS = self::$PATH->ROOT.'/pub/css/';
    self::$PATH->DIR_IMG = self::$PATH->ROOT.'/pub/img/';
    self::$PATH->DIR_JS = self::$PATH->ROOT.'/pub/js/';
    /*Каталог шаблонов*/
    self::$PATH->DIR_VIEWS = self::$PATH->ROOT.'/views/';
    self::$PATH->DIR_LAYOUTS = self::$PATH->ROOT.'/views/layouts/';
    
    return self::$PATH;
  }
  
  public static function url(){
    $PORT = ($_SERVER['SERVER_PORT'] == 443)?"https://":"http://";
    self::$URL = new stdClass();
    self::$URL->ROOT = $PORT.$_SERVER['HTTP_HOST'].'/framework';
    self::$URL->DIR_CORE = self::$URL->ROOT.'/core/';
    self::$URL->DIR_JSON = self::$URL->ROOT.'/json_objs/';
    /*Публичные каталоги*/
    self::$URL->DIR_PUB = self::$URL->ROOT.'/pub/';
    self::$URL->DIR_CSS = self::$URL->ROOT.'/pub/css/';
    self::$URL->DIR_IMG = self::$URL->ROOT.'/pub/img/';
    self::$URL->DIR_JS = self::$URL->ROOT.'/pub/js/';
    
    return self::$URL;
  }
  
  public static function server(){
    $PORT = ($_SERVER['SERVER_PORT'] == 443)?"https://":"http://";
    self::$SERVER = new stdClass();
    self::$SERVER->NAME = 'Фреймворк';
    self::$SERVER->DESCRIPTION = 'Фреймворк - основной фундамент проектов';
    self::$SERVER->KEYWORDS = 'Ключевые слова проекта';
    self::$SERVER->HOST = $PORT.$_SERVER['HTTP_HOST'];
    self::$SERVER->LAYOUT = 'main';
    
    return self::$SERVER;
  }
  
  public static function db(){
    self::$DB = new stdClass();
    self::$DB->TYPE = 'mysql';
    self::$DB->HOST = 'localhost';
    self::$DB->USER = '045686016_test2';
    self::$DB->PASS = '8tju257t_tNd';
    self::$DB->BASE = 'framework';
    self::$DB->PORT = 3306;
    self::$DB->CHARSET = 'UTF8';
    self::$DB->ARRAY_PARAM = [];
    self::$DB->CHARSET = 'utf8';
    
    return self::$DB;
  }
  
}

?>