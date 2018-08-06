<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 023 23.07.18
 * Time: 9:39
 */

namespace site\controllers\PatternsExample;

use site\controllers\Controller;
use site\controllers\PatternsExample\Fruits\Apple;
use site\controllers\PatternsExample\Fruits\ApplePropertiesDecorator;
use site\controllers\PatternsExample\Fruits\Banana;
use site\controllers\PatternsExample\Fruits\Pear;
use site\views\View;

use site\controllers\Traits\TraitOne;
use site\controllers\Traits\TraitTwo;

use site\libs\Simple;

class Patterns extends Controller
{
  use TraitOne, TraitTwo {
    //Разруливание приоритета, в случае дублирующихся методов
    TraitOne::printHello insteadof TraitTwo;
  }

  protected $Simple;

  public function __construct()
  {
    $this->Simple = Simple::getInstance();
  }

  public function show()
  {
    $Views = new View();
    $Views->showPage('patterns/show', [], 'Паттерны проектирования');
  }

  public function traits()
  {
    //Затестим трейты
    $this->printHello();
  }

  public function singleton()
  {
    $variableOne = Singleton::getInstance();
    $variableOne->setProperty('var1', 11);
    $this->Simple::dump($variableOne->getProperty('var1'));

    $variableTwo = Singleton::getInstance();
    $this->Simple::dump($variableTwo->getProperty('var1'));
  }

  public function factory()
  {
    $objPrintOne = Factory::print('simple', 'It\'s print simple from factory');
    $objPrintTwo = Factory::print('simple', 'It\'s print pre from factory');
    $objPrintOne->print();
    $objPrintTwo->print();
  }

  public function abstractFactory()
  {
    $objOSX = AbstractFactoryOS::getInstance('osx');
    $objSolaris = AbstractFactoryOS::getInstance('solaris');

    $windowOSX = $objOSX->createWindow();
    $menuOSX = $objOSX->createMenu();

    $windowSolaris = $objSolaris->createWindow();
    $menuSolaris = $objSolaris->createMenu();

    $windowOSX->start();
    print('<hr />');
    $menuOSX->create();
    print('<hr />');
    $menuOSX->open();
    print('<hr />');
    $menuOSX->resize();
    print('<hr />');
    $windowOSX->hibernate();
    print('<hr />');
    $windowSolaris->start();
    print('<hr />');
    $menuSolaris->create();
    print('<hr />');
    $menuSolaris->open();
    print('<hr />');
    $menuSolaris->close();
    print('<hr />');
    $windowSolaris->shutdown();
    print('<hr />');
    $windowOSX->awakening();
    print('<hr />');
    $windowOSX->shutdown();

  }

  public function strategy()
  {
    $strategy = new Strategy();
    $strategy->print('simple', 'It\'s print simple from strategy');
    $strategy->print('simple', 'It\'s print pre from strategy');
  }

  public function decorator()
  {

    $apple = new Apple();
    print($apple->getFruit() . '<hr />');

    $redApple = new ApplePropertiesDecorator(new Apple());
    print($redApple->getFruit() . '<hr />');

    $banana = new Banana();
    print($banana->getFruit() . '<hr />');

    $pear = new Pear();
    print($pear->getFruit() . '<hr />');

  }
}