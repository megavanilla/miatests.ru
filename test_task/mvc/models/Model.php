<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 16:17
 */

namespace mvc\models;

use mvc\libs\Request;


class Model
{
  private $host = 'localhost';
  private $user = '045686016_test3';
  private $pass = 'xrWjwrJsU}5n';
  private $dbName = 'megavanilla_tasksxxx';
  private $tableName = '';
  private $connect;

  public function __construct($tableName = '')
  {
    global $Configs;

    $Request = new Request();
    $this->host = $Request->getVariable($Configs, [
        'conf',
        'db',
        'mysql',
        'host'
    ], $this->host);
    $this->user = $Request->getVariable($Configs, [
        'conf',
        'db',
        'mysql',
        'user'
    ], $this->user);
    $this->pass = $Request->getVariable($Configs, [
        'conf',
        'db',
        'mysql',
        'pass'
    ], $this->pass);
    $this->dbName = $Request->getVariable($Configs, [
        'conf',
        'db',
        'mysql',
        'dbName'
    ], $this->dbName);

    $this->tableName = (string)$tableName;

    $mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->dbName);

    // проверяем соединение
    if (mysqli_connect_errno())
    {
      printf("Ошибка соединения: %s\n", mysqli_connect_error());
      exit();
    }
    $mysqli->set_charset('utf8');

    $this->connect = $mysqli;
  }

  public function get($fields = [], $id = null, $one = false)
  {
    $f_array = '*';
    if (is_array($fields) && !empty($fields))
    {
      $f_array = '`' . implode($fields, '`, `') . '`';
    }

    $query = "SELECT $f_array FROM `$this->tableName`";
    if ($id !== null)
    {
      $query .= "WHERE id = '$id'";
    }
    if ($one === true)
    {
      $query .= ' LIMIT 0,1';
    }
    $query = $this->filterString($query);

    return $this->executeSQL($query, true);
  }

  public function insert($data = [])
  {
    if (!is_array($data) || empty($data))
    {
      return false;
    }

    $fields = [];
    $values = [];

    foreach ($data as $field => $value)
    {
      $fields[] = $this->filterString($field);
      $values[] = $this->filterString($value);
    }
    $impl_fields = '`' . implode($fields, '`, `') . '`';
    $impl_values = "'" . implode($values, "', '") . "'";
    $query = "INSERT INTO $this->tableName ($impl_fields) VALUES ($impl_values)";
    return $this->executeSQL($query);
  }

  public function update($data = [], $key_name = '', $key_value = '')
  {
    if (!is_array($data) || empty($data))
    {
      return false;
    }

    $SET = '';
    foreach ($data as $key => $value)
    {
      $SET .= '`' . $this->filterString($key) . '` = ' . $this->filterString($value);
    }

    $key_name = $this->filterString($key_name);
    $key_value = $this->filterString($key_value);
    $query = "UPDATE $this->tableName SET " . $SET . " WHERE `$key_name` = '$key_value''";

    return $this->executeSQL($query);
  }

  private function filterString($string = '')
  {
    if (!is_string($string) || empty($string))
    {
      return '';
    }

    return $this->connect->real_escape_string($string);
  }

  public function executeSimppleQuery($sql = '', $returnData = false)
  {
    if (!is_string($sql) || empty($sql))
    {
      return false;
    }
    return $this->executeSQL($sql, (bool)$returnData);
  }

  private function executeSQL($sql = '', $returnData = false)
  {
    $resultQuery = $this->connect->query($sql);

    if ($resultQuery)
    {
      if ($returnData === true)
      {
        $data = [];
        while ($row = $resultQuery->fetch_assoc())
        {
          $data[] = $row;
        }
        return $data;
      }
      else
      {
        return true;
      }
    }
    else
    {
      return false;
    }
  }

  public function checkFirstRow($rows = []){
    return ($rows && array_key_exists(0, $rows) && !empty($rows[0]));
  }

}