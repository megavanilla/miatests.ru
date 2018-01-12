<?php

if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
 * Класс выполняет генерацию страниц
 */
class View {
  protected $layoutPath = 'layouts';
  protected $viewPath = 'views';
  protected $title = 'title';

  public function __construct () {
    $this->viewPath = Constants::path()->DIR_VIEWS;
    $this->layoutPath = Constants::path()->DIR_LAYOUTS;
    $this->title = Constants::server()->NAME;
    $this->description = Constants::server()->DESCRIPTION;
    $this->keywords = Constants::server()->KEYWORDS;
  }

  public function __get($name) {
    return null;
  }

  /**
  * Метод устанавливает каталог местоположения представлений
  * @param string $path
  *
  * @return bool
  */
  public function setViewPath($path)
  {
    //Если каталог существует,
    //то устанавливаем его как каталог для представлений
    if (is_dir($path)) {
      $this->viewPath = $path;
    }
    return is_dir($path);
  }

  /**
  * Возвращает адрес к каталогу с представлениями
  *
  * @return string path
  */
  public function getViewPath()
  {
    return $this->viewPath;
  }

  /**
  * Метод устанавливает каталог местоположения макетами
  * @param string $path
  *
  * @return bool
  */
  public function setLayoutPath($path)
  {
    //Если каталог существует,
    //то устанавливаем его как каталог для макетов
    if (is_dir($path)) {
      $this->layoutPath = $path;
    }
    return is_dir($path);
  }
  /**
  * Возвращает адрес к каталогу с макетами
  *
  * @return string path
  */
  public function getLayoutPath()
  {
    return $this->layoutPath;
  }
  /**
   * Вовзвращает название страницы
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
  /**
   * Устанавливает название страницы,
   * и возвращает его новое название
   * @param string $title
   * @return string
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this->title;
  }
  /**
   * Возвращает описани страницы
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
  /**
   * Устанавливает описание страницы
   * @param string $description
   * @return string
   */
  public function setDescription($description)
  {
    $this->description = $description;
    return $this->description;
  }
  /**
   * Возвращает ключевые слова страницы
   * @return string
   */
  public function getKeyWords()
  {
    return $this->keywords;
  }
  /**
   * Устанавливает ключевые слова страницы
   * @param string $keywords
   * @return string
   */
  public function setKeyWords($keywords)
  {
    $this->keywords = $keywords;
    return $this->keywords;
  }
  
  public function render() {
    // скрипт представления
    $this->content = $this->getContent();
    
    // отправляем html
    $layout = property_exists( $this, 'layout' ) ? $this->layout : Constants::server()->LAYOUT;
    if ($layout) {
      $layout = realpath( $this->layoutPath . $layout . '.phtml' );
      if (! $layout) {
        trigger_error( 'Отсутствует layout', E_USER_ERROR );
        return null;
      }
      include $layout;
    } else {
      echo $this->content;
    }
  }

  public function getContent() {
    $view = property_exists( $this, 'view' ) ? $this->view : '';
    
    if(is_file( $this->viewPath . $view . '.phtml')){
      ob_start();
      include $this->viewPath . $view . '.phtml';
      return ob_get_clean();
    }else{
      return '';
    }
  }
}