<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace mvc\controllers\PatternsExample\AbstractFactory;


class OSSolaris implements AFInterfaceOS
{
  public function start()
  {
    // TODO: Implement start() method.
    print('Запуск ОС Solaris');
  }

  public function hibernate()
  {
    // TODO: Implement hibernate() method.
    print('Гибернация ОС Solaris');
  }

  public function awakening()
  {
    // TODO: Implement hibernate() method.
    print('Пробуждение ОС Solaris');
  }

  public function shutdown()
  {
    // TODO: Implement shutdown() method.
    print('Выключение ОС Solaris');
  }
}