<?php

namespace mvc\libs;

/**
 * Класс содержит мелкие вспомогательные методы...
 */
final class Simple
{
  // экземпляр самого себя
  private static $_instance;

  private function __construct()
  {
  }

  private function __clone()
  {
  }

  private function __wakeup()
  {
  }

  public static function getInstance()
  {
    if (empty(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public static function dump($variable)
  {
    print('<pre>');
    var_dump($variable);
    print('</pre>');
  }
}