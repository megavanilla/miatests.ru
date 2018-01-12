<?php
if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}

/**
 * Класс позволяет выводить в консоль браузера php объекты
 */
class Debug{
  
    protected $is_init = false;
    function __construct(){
		//Объявляем конструктор статичным, чтобы нельзя было создавать экземпляры класса
	}    

    public function debug($name, $var = null, $type = LOG){
    	$NL = "\r\n";
        $str_debug = '<script type="text/javascript">' . $NL;
        switch ($type){
            case 'LOG':
            $str_debug .= 'console.log("' . $name . '");' . $NL;
            break;
            case 'INFO':
            $str_debug .= 'console.info("' . $name . '");' . $NL;
            break;
            case 'WARN':
            $str_debug .= 'console.warn("' . $name . '");' . $NL;
            break;
            case 'ERROR':
            $str_debug .= 'console.error("' . $name . '");' . $NL;
            break;
        }

        if (!empty($var)){
            if (is_object($var) || is_array($var)){
                $object = json_encode($var);
                $str_debug .= 'var object' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ' = \'' . str_replace("'", "\'", $object) . '\';' . $NL;
                $str_debug .= 'var val' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ' = eval("(" + object' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ' + ")" );' . $NL;
                switch ($type){
                    case 'LOG':
                    $str_debug .= 'console.debug(val' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ');' . $NL;
                    break;
                    case 'INFO':
                    $str_debug .= 'console.info(val' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ');' . $NL;
                    break;
                    case 'WARN':
                    $str_debug .= 'console.warn(val' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ');' . $NL;
                    break;
                    case 'ERROR':
                    $str_debug .= 'console.error(val' . preg_replace('~[^A-Z|0-9]~i', "_", $name) . ');' . $NL;
                    break;
                }
            }else{
                switch ($type){
                    case 'LOG':
                    $str_debug .= 'console.debug("' . str_replace('"', '\\"', $var) . '");' . $NL;
                    break;
                    case 'INFO':
                    $str_debug .= 'console.info("' . str_replace('"', '\\"', $var) . '");' . $NL;
                    break;
                    case 'WARN':
                    $str_debug .= 'console.warn("' . str_replace('"', '\\"', $var) . '");' . $NL;
                    break;
                    case 'ERROR':
                    $str_debug .= 'console.error("' . str_replace('"', '\\"', $var) . '");' . $NL;
                    break;
                }
            }
        }
        $str_debug .= '</script>' . $NL;
        return (string)$str_debug;
    } //End debug

    public function init(){
    	$str_construct = "";

        $this->is_init = true;
        
        $NL = "\r\n";
        $str_construct .= '<script type="text/javascript">' . $NL;

        /// Данный код предназначен для браузеров без консоли
        $str_construct .= 'if (!window.console) console = {};';
        $str_construct .= 'console.log = console.log || function(){};';
        $str_construct .= 'console.warn = console.warn || function(){};';
        $str_construct .= 'console.error = console.error || function(){};';
        $str_construct .= 'console.info = console.info || function(){};';
        $str_construct .= 'console.debug = console.debug || function(){};';
        $str_construct .= '</script>' . $NL;
        /// Конец секции для браузеров без консоли
        return $str_construct;
    }
}
/**Применение:
 * echo ($this->debug->debug("Простая информация:", ['var_1'=>1,'var_2'=>'stroka'], 'INFO'));
 */