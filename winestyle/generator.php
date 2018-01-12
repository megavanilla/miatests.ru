<?php

  //Устанавливаем тип содержимого  
  header('Content-Type: image/jpeg');
  
  /**
   * Логика:
   * 1) Получаем переменные name и size,
   * 2) Проверяем существует ли файл в каталоге ./gallery/.
   * 3) Проверяем существует ли этот файл в каталоге ./cache/,
   *      3.1.)  Если там его нет, то создаём самое большое допустимое изображение,
   *      с размером, согласно константам DEFAULT_HEIGHT и DEFAULT_WIDTH и сохраняем его в папке ./cache/.
   * 4) Если файл в каталоге ./cache/ уже сущесвтует, то пропускаем шаг 3.1.
   * 5) Выполняем дополнительную проверку на наличие файла в папке ./cache/,
   *      5.1.) Если не удалось создать кэшированное изображение, то выведем ошибку.
   * 6) Если ошибок нет, то
   *      6.1.) Если параметр size равен константе DEFAULT_MAX_SIZE, то не изменяем изображение,
   *      а просто выводим из каталога ./cache/
   *      6.2.) Если не удалось получить размеры изображения
   *            из переменной size, то выведем ошибку
   *      6.3.) Выводим изменённое изображение.
   * 7) Если в процессе возникли ошибки,
   *    то выводим эту ошибку в виде изображения.
   *  
   */

  //База данных
  define("DB_HOST", "localhost"); //Адрес сервера
  define("DB_USER", "megavanilla"); //Пользователь
  define("DB_PASS", "Gfhjkm_jino_ru"); //Пароль
  define("DB_BASE", "megavanilla"); //Имя БД
  define("DB_PORT", null); //Порт
  define("DB_CHARSET", "utf8"); //Кодировка
  
  //Размерность изображения по умолчанию
  define("DEFAULT_MAX_SIZE", 'big');
  define("DEFAULT_HEIGHT", '600');
  define("DEFAULT_WIDTH", '800');

  /**
   * Возвращает соединение mysqli
   * @return \mysqli
   */
  function open_connect() {
    $db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE, DB_PORT);
    if ($db_conn->connect_error) {
      die('Ошибка соединения с БД (' . $db_conn->connect_errno . ') ' . $db_conn->connect_error);
    }
    $db_conn->set_charset(DB_CHARSET);
    return $db_conn;
  }

  $last_query = ''; //Текст последнего запроса - для отладки

  /**
   * Выполняет мультизапрос к базе даннхы MySQL.
   * Мультизапросы пишутся через запятую, к примеру:
   * $query = "SELECT CURRENT_USER();";
   * $query .= "SELECT Name FROM City ORDER BY ID LIMIT 20, 5";
   * 
   * @global string $last_query - переменная, содержит последний выполненный запрос
   * @param string $query - Строка запроса
   * @return array - результат запроса
   */
  function multi_query($query) {
    global $last_query;
    //Вызываем коннектор соединения с БД
    $mysqli = open_connect();
    //Создаем отчет о последнем запросе
    $last_query = $query;
    $cnt_query = 0;
    $res = array();
    /* запускаем мультизапрос */
    if ($mysqli->multi_query($query)) {
      do {
        /* получаем первый результирующий набор */
        if ($result = $mysqli->store_result()) {
          while ($row = $result->fetch_assoc()) {
            $res[$cnt_query][] = $row;
          }
          $result->free();
        }
        $cnt_query++;
      } while ($mysqli->more_results() && $mysqli->next_result());
    }
    /* закрываем соединение */
    $mysqli->close();
    return $res;
  }

  /**
   * Выполняет преобразование изображения, и сохранение результата в указанный файл
   * @param type $src - полный путь используемого файла
   * @param type $dst - полный путь сохраняемого файла
   * @param type $height - высота изображения
   * @param type $width- ширина изображения
   * @param type $crop - если false, то выполняется масштабарование, иначе изображенние будет растянуто
   * @param type $get - если true, то изображение выводится на экран
   * @return img_resource || null
   */
  function resize_img($src, $dst, $height = 150, $width = 150, $crop = 0, $get = false) {
    if (!list($w, $h) = getimagesize($src))
      return null;// "Неподдерживаемый тип изображения!";
    //Получаем расширение файла
    //$type = strtolower(mb_substr(strrchr($src, "."), 1));
    $type = mb_strtolower(mb_substr(mb_strrchr($src, "."), 1), 'UTF-8');
    if ($type == 'jpeg')
      $type = 'jpg';
    //Создаем новое изображение, в зависимости от расширения файла
    switch ($type) {
      case 'bmp':
        $img = imagecreatefromwbmp($src) or null;// die('Не удалось создать bmp изображение');
        break;
      case 'gif':
        $img = imagecreatefromgif($src) or null;// die('Не удалось создать gif изображение');
        break;
      case 'jpg':
        $img = imagecreatefromjpeg($src) or null;// die('Не удалось создать jpg изображение');
        break;
      case 'png':
        $img = imagecreatefrompng($src) or null;// die('Не удалось создать png изображение');
        break;
      default:
        return null;// "Неподдерживаемый тип изображения!";
    }
    // Изменение размера
    if ($crop) {//Растягивает изображение по размерам картинки
      if ($w < $width or $h < $height)
        return null;// "Изображение и так маленькое!";
      $ratio = max($width / $w, $height / $h);
      $h = $height / $ratio;
      $x = ($w - $width / $ratio) / 2;
      $w = $width / $ratio;
    }
    else {
      if ($w < $width and $h < $height)
        return null;// "Изображение и так маленькое!";
      $ratio = min($width / $w, $height / $h);
      $width = $w * $ratio;
      $height = $h * $ratio;
      $x = 0;
    }
    $new = imagecreatetruecolor($width, $height);
    // Сохранение прозрачности
    if ($type == "gif" or $type == "png") {
      imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
      imagealphablending($new, false);
      imagesavealpha($new, true);
    }
    //Преобразование размеров
    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
    switch ($type) {
      case 'bmp':
        if($get){imagewbmp($new, null, 100);}else{imagewbmp($new, $dst);}
        break;
      case 'gif':
        if($get){imagegif($new, null, 100);}else{imagegif($new, $dst);}
        break;
      case 'jpg':
        if($get){imagejpeg($new, null, 100);}else{imagejpeg($new, $dst);}
        break;
      case 'png':
        if($get){imagepng($new, null, 100);}else{imagepng($new, $dst);}
        break;
    }
    return $img;
  }

  //Фильтруем и преобразуем GET переменные
  $name = (string) filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING);
  $size = (string) filter_input(INPUT_GET, 'size', FILTER_SANITIZE_STRING);

  //Получаем размеры, по указанному виду изображения
  $query = "SELECT `width`, `height` FROM `img_size_types` WHERE `size` ='$size' LIMIT 0, 1;";
  $db_wine = multi_query($query);
  $error_message = '';
  
  if(is_file('./gallery/'.$name.'.jpg')){//Если исходный файл найден
    if(!is_file('./cache/'.$name.'.jpg')){//Если кэшированный файл не найден
       //Создаём самое большое допустимое изображение
       $img = resize_img('./gallery/'.$name.'.jpg',
                  './cache/'.$name.'.jpg',
                  DEFAULT_HEIGHT,
                  DEFAULT_WIDTH,
                  false);
    }
    //Если удалось найти или сохранить в кэш изображение
    if(is_file('./cache/'.$name.'.jpg')){
      if($size == DEFAULT_MAX_SIZE){
        // Устанавливаем размер содержимого в заголовок
        $file = './cache/'.$name.'.jpg';
        header('Content-Length: ' . filesize($file));
        readfile($file);
      }else{
        if (!empty($db_wine) && !empty($db_wine[0][0])
        && isset($db_wine[0][0]['width'])
        && isset($db_wine[0][0]['height'])){
          //Используем метод изменения размера изображения
          $img = resize_img('./gallery/'.$name.'.jpg',
                  './cache/'.$name.'.jpg',
                  (int)$db_wine[0][0]['height'],
                  (int)$db_wine[0][0]['width'],
                  false,
                  true
                  );
        }else{
          $error_message .= "Не удалось получить размеры изображения.";
        }
      }
    }else{
      $error_message .= "Не удалось найти или сохранить кэшируемое изображение\r\n";
    }
  }else{
    $error_message .= "Не удалось найти изображение:\r\n ./gallery/$name.jpg\r\n";
  }
  
  if(!empty($error_message)){//Если есть ошибки
    // Создание изображения
    $im = imagecreatetruecolor(DEFAULT_HEIGHT, DEFAULT_WIDTH);

    // Создание белого фона
    imagefilledrectangle($im, 0, 0, DEFAULT_HEIGHT-1, DEFAULT_WIDTH-1, 0xFFFFFF);
    // Создание цвета
    $black = imagecolorallocate($im, 0, 0, 0);
    // Указание пути к шрифту на пользовательский
    $font = null;
    if(is_file('./fonts/arial.ttf')){
      $font = './fonts/arial.ttf';
    }
    if($font){
      imagettftext($im, 14, 0, 20, 20, $black, $font, $error_message);
    }else{
      imagestring($im, 3, DEFAULT_WIDTH/2, DEFAULT_HEIGHT/2, 'error', $black);
    }
    // Вывод изображения в броузер
    header('Content-Type: image/jpeg');
    imagejpeg($im,null,100);
    imagedestroy($im);
  }
?>