<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
 * Класс выполняет обработку ошибок на уровне сервера
 */
class Errors{
    /**
	 * Счетчик сообщений, нужен для того, чтобы можно было выводить много сообщений и закрывать их по одному
	 *
	 * @var int
	 */
	private $message_counter = 0;
  
	/**
	 * Переменная, в которую накапливаются сообщения для вывода
	 *
	 * @var string
	 */
	private $out = '';
    
  /**
   * Конструктор, в котором назначаются функции, перехватывающие ошибки.
   */
  public function __construct() {
    if(Constants::param()->PRODUCTION) {
      set_error_handler(array($this, 'CaptureNormal'), E_ALL ^ E_NOTICE);
    } else {
      set_error_handler(array($this, 'CaptureNormal'), E_ALL);
    }
    set_exception_handler(array($this, 'captureException'));
    register_shutdown_function(array( $this, 'captureShutdown'));
  }
  
  /**
   * Функция, вызываемая при возникновении обычных ошибок разных категорий.
   */
  public function CaptureNormal ($number, $message, $file, $line) {
    $error = array(
      'number' => $number,
      'type' => $this->GetFriendlyErrorType($number),
      'message' => $message,
      'file' => $file,
      'line' => $line,
      'trace' => debug_backtrace()
    );
    if ($number == E_USER_ERROR) {
      // прекращаем выполнение скрипта
      $this->reportFatalError($error);
      exit();
    } else {
      // показываем сообщение
      $this->ReportError($error);
    }
  }
  
  /**
   * Функция, вызываемая при возникновении исключений.
   */
  public function CaptureException ($exception) {
    $error = array(
      'type' => 'Exception',
      'message' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'trace' => array_merge(debug_backtrace(), $exception->getTrace())
    );
    $this->reportFatalError($error);
    exit;
  }
  
  /**
   * Функция, вызываемая любом случае в конце работы всех скриптов и выводит все,
   * что накопилось в $this->out, также она работает для перехвата фатальных ошибок.
   */
  public function CaptureShutdown () {
    $error = error_get_last();
    if ($error) {
      $error['number'] = $error['type'];
      $error['type'] = $this->GetFriendlyErrorType($error['number']);
      $error['trace'] = debug_backtrace();
      $this->reportFatalError($error);
      echo $this->out;
      exit;
    } else {
      echo $this->out;
      return true;
    }
  }
  
  private function ReportError($error) {
    global $dom_ready;
    if (! isset( $error['number'] ))
      $error['number'] = 0;
    
    $err_obj = new stdClass();
    $err_obj->no = (isset($error['number']) ? $error['number'] : 0 );
    $err_obj->mess = $error['message'];
    $err_obj->file = $error['file'];
    $err_obj->line = $error['line'];

    $this->out .= <<<ERROR
<div class='error' style='height:150px; width:650px; margin:0 auto; margin-top:5%;'>
  <h3>Ошибка № <span style='color:red;'>$err_obj->no</span>.</h3>
  <h4>Сообщение: <i>$err_obj->mess.</i></h4>
  <h4>Файл: <i>$err_obj->file</i></h4>
  <h4>Строка: <i>$err_obj->line</i></h4>
  <br/><b>Трассировка:</b><br/>
ERROR;
    $i=1;
    foreach(array_reverse($error['trace']) as $ar){
    	$this->out .='&nbsp;&nbsp;&nbsp;'.$i++.") ";
    	if(isset($ar['file']))
    		$this->out .="файл: {$ar['file']}, ";
    	if(isset($ar['line']))
    		$this->out .="строка: {$ar['line']}, ";
    	if(isset($ar['function']))
    		$this->out .="функция: {$ar['function']}, ";
    	$this->out=mb_substr($this->out,0,-2).'<br/>';
    }
    $this->out .= "<br/><div><a href='/'>Вернуться на главную</a></div></div>";
  }
  
  /**
  * Функция выводит человекопонятное описание ошибки по номеру типа.
  * 
  * @param int $type Тип ошибки.
  */
  private function GetFriendlyErrorType($type) 
  { 
    switch($type){ 
      case E_ERROR: // 1 // 
        return 'Fatal run-time error'; 
      case E_WARNING: // 2 // 
        return 'Run-time warning'; 
      case E_PARSE: // 4 // 
        return 'Compile-time parse error'; 
      case E_NOTICE: // 8 // 
        return 'Run-time notice'; 
      case E_CORE_ERROR: // 16 // 
        return 'Fatal error during PHP initial startup'; 
      case E_CORE_WARNING: // 32 // 
        return 'Warnings during PHP initial startup'; 
      case E_CORE_ERROR: // 64 // 
        return 'Fatal compile-time error'; 
      case E_CORE_WARNING: // 128 // 
        return 'Compile-time warning'; 
      case E_USER_ERROR: // 256 // 
        return 'Произошла ошибка'; 
      case E_USER_WARNING: // 512 // 
        return 'Сгенерировано предупреждение'; 
      case E_USER_NOTICE: // 1024 // 
        return 'Сгенерировано информационное сообщение'; 
      case E_STRICT: // 2048 // 
        return 'PHP suggest changes to our code'; 
      case E_RECOVERABLE_ERROR: // 4096 // 
        return 'Catchable fatal error'; 
      case E_DEPRECATED: // 8192 // 
        return 'Run-time notice on deprecated feature'; 
      case E_USER_DEPRECATED: // 16384 // 
        return 'User-generated warning message on deprecated feature'; 
      } 
    return "undefined"; 
  }
}

?>