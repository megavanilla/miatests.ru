<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 025 25.07.18
 * Time: 17:17
 */

namespace site\controllers\PatternsExample\AbstractFactory;


class WindowSolaris implements AFInterfaceWindow
{
  public function create()
  {
    // TODO: Implement create() method.
    print('Создание окна в ОС Solaris');
  }

  public function open()
  {
    // TODO: Implement open() method.
    print('Открытие окна в ОС Solaris');
  }

  public function resize()
  {
    // TODO: Implement resize() method.
    print('Изменение размеров окна в ОС Solaris');
  }

  public function close()
  {
    // TODO: Implement close() method.
    print('Закрытие окна в ОС Solaris');
  }
}