<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}

/**
 * PDO обёртка для работы с БД
 */
class Db{
  protected $dbConnection = null;
  protected $dbUser = 'megavanilla';
  protected $dbPass = 'Gfhjkm_jino_ru';
  protected $dbStringConnect = 'mysql:host=localhost;dbname=megavanilla;port=3306';
  protected $dbArrayParam = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
  
  /**
   * Сетеры и геттеры соединения
   */
  public function getdbConnection() {
    return $this->dbConnection;
  }
  /*
  private function setdbConnection($dbConnection){
    $this->dbConnection = $dbConnection;
  }*/
  public function getDbStringConnect() {
    return $this->dbStringConnect;
  }
  public function setDbStringConnect($dbStringConnect){
    $this->dbStringConnect = $dbStringConnect;
  }
  public function getDbUser() {
    return $this->dbUser;
  }
  public function setDbUser($dbUser){
    $this->dbUser = $dbUser;
  }
  public function getdbPass() {
    return $this->dbPass;
  }
  public function setDbPass($dbPass){
    $this->dbPass = $dbPass;
  }
  public function getDbArrayParam() {
    return $this->dbArrayParam;
  }
  public function setDbArrayParam($dbArrayParam){
    $this->dbArrayParam = $dbArrayParam;
  }
  /**
   * Возвращает соединение с БД
   * @param type $persistent - Сохранять постоянное соединение
   */
  public function connect($persistent = false){
    try {
      $this->dbConnection = new PDO($this->getDbStringConnect(), $this->getDbUser(), $this->getdbPass());
      //$this->dbConnection = new PDO('mysql:host=localhost;dbname=win_style_test;port=3306', $this->getDbUser(), $this->getdbPass());
      $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if($persistent){
        $this->dbConnection->setAttribute(PDO::ATTR_PERSISTENT, true);
      }
      $this->dbConnection;
    } catch (PDOException $e) {
      trigger_error('Подключение не удалось: '.$this->getDbStringConnect(). $e->getMessage());
    }
  }
      
  public function exec_query($sql, $param = null, $mode = PDO::FETCH_OBJ){
    if($this->dbConnection){
      try{
          $sth = $this->dbConnection->prepare($sql);
          $sth->setFetchMode($mode);
          $sth->execute($param);
          $res = $sth->fetchAll();
          return $res;
      } catch (PDOException $e) {
        trigger_error('Запрос не удался: '. "\r\n<br />" . $sql. "\r\n<br />Ошибка:\r\n<br />" . $e->getMessage());
      }
    }else{
      return null;
    }
  }
  
  /**
   * Проверяет существование соединения
   * @return boolean
   */
  public function is_exists_connection(){
    return ($this->dbConnection)?true:false;
  }
  
  /**
   * Проверяет существование базы данных
   * @param string $db - имя базы данных
   * @return boolean
   */
  public function is_exists_db($db_name){
    if($this->is_exists_connection()){
      $db_name = filter_var($db_name, FILTER_SANITIZE_STRING);
      $sth = $this->dbConnection->prepare("SHOW DATABASES LIKE :db_name");
      $sth->setFetchMode(PDO::FETCH_OBJ);
      try {
        $sth->execute(array(':db_name' => $db_name));
        $res = $sth->fetchAll();
        return (!empty($res))?true:false;
      } catch (PDOException $e) {
        return false;
        //trigger_error("Запрос не удался: \r\n" . $e->getMessage());
      }
    }else{return false;}
  }
  
  public function is_exists_table($db_name, $table_name){
    if($this->is_exists_db($db_name)){
      $table_name = filter_var($table_name, FILTER_SANITIZE_STRING);
      $sth = $this->dbConnection->prepare("SHOW TABLES LIKE :table_name");
      $sth->setFetchMode(PDO::FETCH_OBJ);
      try {
        $sth->execute(array('table_name' => $table_name));
        $res = $sth->fetchAll();
        return (!empty($res))?true:false;
      } catch (PDOException $e) {
        return null;
      }
    }else{return false;}
    return true;
  }
  
  public function is_exists_procedure($db_name, $procedure_name){
    if(is_exists_db($db_name)){
      
    }else{return false;}
  }
  
  public function is_exists_function($db_name, $function_name){
    if(is_exists_db($db_name)){
      
    }else{return false;}
  }
  
  public function is_exists_event($db_name, $event_name){
    if(is_exists_db($db_name)){
      
    }else{return false;}
  }
}

?>