<?php // Передаем заголовки
//header('Content - type: text / html; charset = utf - 8');
//header('Access - Control - Allow - Origin: * ');
if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
* Класс - геолокации
* **/
class Geo
{
    public function __construct($options = null)
    {
        $this->dirname = dirname(__file__);
        // ip
        if (!isset($options['ip']) or !$this->is_valid_ip($options['ip']))
        $this->ip = $this->get_ip();
        elseif ($this->is_valid_ip($options['ip']))
        $this->ip = $options['ip'];
        // кодировка
        if (isset($options['charset']) && $options['charset'] && $options['charset'] != 'windows-1251')
        $this->charset = $options['charset'];
    }
    /**
    * функция возвращет конкретное значение из полученного массива данных по ip
    * @param string - ключ массива. Если интересует конкретное значение.
    * Ключ может быть равным 'inetnum', 'country', 'city', 'region', 'district', 'lat', 'lng'
    * @param bolean - устанавливаем хранить данные в куки или нет
    * Если true, то в куки будут записаны данные по ip и повторные запросы на ipgeobase происходить не будут.
    * Если false, то данные постоянно будут запрашиваться с ipgeobase
    * @return array OR string - дополнительно читайте комментарии внутри функции.
    */
    function get_value($key = false, $cookie = true)
    {
        $key_array = array(
            'inetnum',
            'country',
            'city',
            'region',
            'district',
            'lat',
            'lng');
        if (!in_array($key, $key_array))
        $key = false;
        // если используем куки и параметр уже получен, то достаем и возвращаем данные из куки
        if ($cookie && isset($_COOKIE['geobase'])) {
            $data = unserialize($_COOKIE['geobase']);
        }
        else
        {
            $data = $this->get_geobase_data();
            setcookie('geobase', serialize($data), time() + 3600 * 24 * 7); //устанавливаем куки на неделю
        }
        if ($key)
        return $data[$key]; // если указан ключ, возвращаем строку с нужными данными
        else
        return $data; // иначе возвращаем массив со всеми данными
    }
    /**
    * функция получает данные по ip.
    * @return array - возвращает массив с данными
    */
    function get_geobase_data()
    {
        // получаем данные по ip
        $link = 'ipgeobase.ru:7020/geo?ip=' . $this->ip;
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        $string = curl_exec($ch);
        // если указана кодировка отличная от windows - 1251, изменяем кодировку
        if ($this->charset)
        $string = iconv('windows-1251', $this->charset, $string);
        $data   = $this->parse_string($string);
        return $data;
    }
    /**
    * функция парсит полученные в XML данные в случае, если на сервере не установлено расширение Simplexml
    * @return array - возвращает массив с данными
    */
    function parse_string($string)
    {
        $pa['inetnum'] = '#<inetnum>(.*)</inetnum>#is';
        $pa['country'] = '#<country>(.*)</country>#is';
        $pa['city'] = '#<city>(.*)</city>#is';
        $pa['region'] = '#<region>(.*)</region>#is';
        $pa['district'] = '#<district>(.*)</district>#is';
        $pa['lat'] = '#<lat>(.*)</lat>#is';
        $pa['lng'] = '#<lng>(.*)</lng>#is';
        $data = array();
        foreach ($pa as $key => $pattern) {
            if (preg_match($pattern, $string, $out)) {
                $data[$key] = trim($out[1]);
            }
        }
        return $data;
    }
    /**
    * функция определяет ip адрес по глобальному массиву $_SERVER
    * ip адреса проверяются начиная с приоритетного, для определения возможного использования прокси
    * @return ip-адрес
    */
    function get_ip()
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipa[] = trim(strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ','));
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipa[] = $_SERVER['HTTP_CLIENT_IP'];
        if (isset($_SERVER['REMOTE_ADDR']))
        $ipa[] = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_REAL_IP']))
        $ipa[] = $_SERVER['HTTP_X_REAL_IP'];
        // проверяем ip - адреса на валидность начиная с приоритетного.
        foreach ($ipa as $ips) {
            //  если ip валидный обрываем цикл, назначаем ip адрес и возвращаем его
            if ($this->is_valid_ip($ips)) {
                $ip = $ips;
                break;
            }
        }
        return $ip;
    }
    /**
    * функция для проверки валидности ip адреса
    * @param ip адрес в формате 1.2.3.4
    * @return bolean : true - если ip валидный, иначе false
    */
    function is_valid_ip($ip = null)
    {
        if (preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#", $ip))
        return true; // если ip - адрес попадает под регулярное выражение, возвращаем true
        return false; // иначе возвращаем false
    }
}
/*
header('Content-type: text/html; charset=UTF-8');


include('geo.php');
$o = array(); // опции. необзятательно.
$o['charset'] = 'utf-8'; // нужно указать требуемую кодировку, если она отличается от windows-1251

$geo = new Geo($o); // запускаем класс

// этот метод позволяет получить все данные по ip в виде массива.
// массив имеет ключи 'inetnum', 'country', 'city', 'region', 'district', 'lat', 'lng'
$data = $geo->get_value();

// если нужен какой то отдельный параметр, передаем его в функцию в виде первого значения
//$data = $geo->get_value('city'); // например, вернет название города
# $data = $geo->get_value('country'); // вернет название страны
# $data = $geo->get_value('region'); // вернет название региона
# $data = $geo->get_value('district'); // вернет название района
# lat - географическая ширина и lng - долгота
# inetnum - диапазон ip адресов, в который входит проверяемый ip адрес

// чтобы использовать кеширование нужно в функцию передать второй параметр - true или false
# пример
//$data = $geo->get_value('city', true);
// если true, то данные о городе пользователя сохранятся в куки браузера
// в этом случае повторный запрос для проверки происходить не будет.
// это рекомендуется и поэтому по-умолчанию кешеривание включено
# пример
//$data = $geo->get_value('city', false);
// если false, то данные каждый раз будут запрашиваться с сервера ipgeobase
//также кеширование используется и для других параметров


// показ информации в зависимости от города
# пример

$city = $geo->get_value('city', true);
if($city == 'Казань')
{
echo 'Наш телефон для Казани 123123123';
}elseif($city == 'Москва')
{
echo 'Телефон для Москвы 98989898';
}elseif($city == 'Тюмень')
{
echo 'Телефон для Тюмени 65656565';
}else
{
echo 'Ваш город '. $city .'.';
}


echo '<pre>';
echo 'Все данные: '."\n";
print_r($data);
echo '</pre>';

// также данный класс можно использовать для получения и проверки валидности реального ip адреса посетителя

$city = $geo->get_value('city', true); // получаем название города
echo 'Достаем город<br />';
echo $city .'<br />';

$ip = $geo->get_ip(); // получаем ip адрес
echo 'Достаем IP<br />';
echo $ip .'<br />';

if($geo->is_valid_ip($ip))
{
echo 'IP адрес валиден';
}else
{
echo 'IP адрес не валиден';
}

// Пробуем достать данные из куки
echo "<hr/>Данные из Cookie<br/>";
$geobase = $_COOKIE['geobase'];
echo '<pre>';
print_r(unserialize($geobase));
echo '</pre>';

$city = $geo->get_value('city', false);
echo $city;
exit();
**/
/**
* с вопросами
* @site http://faniska.ru
* @email ya.faniska@gmail.com
*/ ?>
