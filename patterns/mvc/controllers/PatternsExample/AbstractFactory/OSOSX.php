<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace mvc\controllers\PatternsExample\AbstractFactory;


class OSOSX implements AFInterfaceOS
{
  public function start()
  {
    // TODO: Implement start() method.
    print('Запуск ОС OSX');
  }

  public function hibernate()
  {
    // TODO: Implement hibernate() method.
    print('Гибернация ОС OSX');
  }

  public function awakening()
  {
    // TODO: Implement hibernate() method.
    print('Пробуждение ОС OSX');
  }

  public function shutdown()
  {
    // TODO: Implement shutdown() method.
    print('Выключение ОС OSX');
  }
}