<?php // Передаем заголовки
//header('Content - type: text / html; charset = utf - 8');
//header('Access - Control - Allow - Origin: * ');
if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
* Класс - cURL
* **/
class cURL
{
    var $ch;
	var $httpget = '';	
	var $head = '';
	var $is_post = false;
	var $postparams = array ();
	var $httpheader = array ();
	var $cookie = array ();
	var $proxy = '';
	var $proxy_user_data = '';
	var $verbose = 0;
	var $referer = '';
	var $autoreferer = 0;
	var $writeheader = '';
	var $agent = 'Mozilla/5.0 (Windows NT 5.1; rv:23.0) Gecko/20100101 Firefox/23.0';
	var $url = '';	
	var $followlocation = 1;
	var $returntransfer = 1;
	var $ssl_verifypeer = 0;
	var $ssl_verifyhost = 2;
	var $sslcert = '';
	var $sslkey = '';
	var $cainfo = '';
	var $cookiefile = '';
	var $timeout = 0;
	var $connect_time = 0;
	var $encoding = 'deflate';	
	var $interface = '';
	
	function __construct (){
		$this->ch = curl_init();
		$this->set_httpheader(array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language: ru-ru,ru;q=0.8,en-us;q=0.5,en;q=0.3','Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'));
	}
	
	function get ($url){
		$this->url = $url;
		return $this->exec();
	}
	
	function post ($url, $postparams = '', $enctype = false){
		$this->url = $url;		
		$this->is_post = true;
		
		if (!$enctype) {
			$this->postparams = $postparams;
		}
		else {
			$this->postparams = array ();
			$params = explode("&", $postparams);
			for($i = 0; $i < count($params); $i++) {
				list($name, $value) = explode("=", $params[$i]);
				$this->postparams[$name] = $value;
			}
		}
		
		return $this->exec();
	}
	
	function set_httpget ($httpget){
		$this->httpget = $httpget;
	}
	
	function set_referer ($referer){
		$this->referer = $referer;
	}
	
	function set_autoreferer ($autoreferer){
		$this->autoreferer = $autoreferer;
	}
	
	function set_useragent ($agent){
		$this->agent = $agent;
	}	
	
	function set_cookie (){
		preg_match_all('/Set-Cookie: (.*?)=(.*?);/i', $this->head, $matches, PREG_SET_ORDER);
		
		for ($i = 0; $i < count($matches); $i++) {
			if ($matches[$i][2] == 'deleted') {
				$this->delete_cookie($matches[$i][1]);
			} else {
				$this->cookie[$matches[$i][1]] = $matches[$i][2];
			}
		}	
	}
	
	function add_cookie ($cookie){
		foreach ($cookie as $name => $value) {
			$this->cookie[$name] = $value;
		}
	}
	
	function delete_cookie ($name){
		if (isset($this->cookie[$name]))
			unset($this->cookie[$name]);
	}
	
	function get_cookie (){
		return $this->cookie;
	}
	
	function clear_cookie (){
		$this->cookie = array ();
	}
	
	function set_httpheader ($httpheader){
		$this->httpheader = $httpheader;
	}
	
	function clear_httpheader (){
		$this->httpheader = array ();
	}
	
	function set_head ($head){
		$this->head = $head;
	}
	
	function set_encoding ($encoding){
		$this->encoding = $encoding;
	}	
	
	function set_interface ($interface){
		$this->interface = $interface;
	}

	function set_writeheader ($writeheader){	
		$this->writeheader = $writeheader;
	}

	function set_followlocation ($followlocation){
		$this->followlocation = $followlocation;
	}

	function set_returntransfer ($returntransfer){
		$this->returntransfer = $returntransfer;
	}
	
	function set_ssl_verifypeer ($ssl_verifypeer){
		$this->ssl_verifypeer = $ssl_verifypeer;
	}
	
	function set_ssl_verifyhost ($ssl_verifyhost){
		$this->ssl_verifyhost = $ssl_verifyhost;
	}
	
	function set_sslcert ($sslcert) {
		$this->sslcert = $sslcert;
	}
	
	function set_sslkey ($sslkey) {
		$this->sslkey = $sslkey;
	}
	
	function set_cainfo ($cainfo) {
		$this->cainfo = $cainfo;
	}
	
	function set_timeout ($timeout){
		$this->timeout = $timeout;
	}
	
	function set_connect_time ($connect_time){
		$this->connect_time = $connect_time;
	}
	
	function set_cookiefile ($cookiefile){
		$this->cookiefile = $cookiefile;
	}

	function set_proxy ($proxy){
		$this->proxy = $proxy;
	}
	
	function set_proxy_auth ($proxy_user_data){
		$this->proxy_user_data = $proxy_user_data;
	}
	
	function set_verbose ($verbose){
		$this->verbose = $verbose;
	}
	
	function get_error (){
		return curl_errno($this->ch);
	}
	
	function get_location (){
		$result = '';
		
		if (preg_match("/Location: (.*?)\r\n/is", $this->head, $matches)) {
			$result = end($matches);
		}
	
		return $result;
	}
	
	function get_http_state (){
		if (curl_getinfo($this->ch, CURLINFO_HTTP_CODE) == 200) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_speed_download (){
		return curl_getinfo($this->ch, CURLINFO_SPEED_DOWNLOAD);
	}
	
	function get_content_type (){
		return curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);
	}
	
	function get_url (){
		return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
	}
	
	function join_cookie() {
		$result = array ();
		foreach ($this->cookie as $key => $value)
			$result[] = "$key=$value";
		return join('; ', $result);
	}
	
	function exec (){
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, $this->autoreferer);
		curl_setopt($this->ch, CURLOPT_ENCODING, $this->encoding);
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_POST, $this->is_post);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION , $this->followlocation);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,$this->returntransfer);	
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
		curl_setopt($this->ch, CURLOPT_HEADER, 1);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->connect_time);
		curl_setopt($this->ch, CURLOPT_VERBOSE, $this->verbose);
		
		if ($this->referer)
			curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);			
		
		if ($this->interface)
			curl_setopt($this->ch, CURLOPT_INTERFACE, $this->interface);
		
		if ($this->httpget)
			curl_setopt($this->ch, CURLOPT_HTTPGET, $this->httpget);
		
		if ($this->writeheader != '')
			curl_setopt($this->ch, CURLOPT_WRITEHEADER, $this->writeheader);		

		if ($this->is_post) {
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->postparams);
		}
		
		if ($this->proxy)
			curl_setopt($this->ch, CURLOPT_PROXY, $this->proxy);
		
		if ($this->proxy_user_data)
			curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $this->proxy_user_data);

		if ($this->cookie)
			curl_setopt($this->ch, CURLOPT_COOKIE, $this->join_cookie());
		
		if (count($this->httpheader))
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpheader);

		if ($this->sslcert)
			curl_setopt($this->ch, CURLOPT_SSLCERT, $this->sslcert);
			
		if ($this->sslkey)
			curl_setopt($this->ch, CURLOPT_SSLKEY, $this->sslkey);
			
		if ($this->cainfo)
			curl_setopt($this->ch, CURLOPT_CAINFO, $this->cainfo);
		
		if ($this->cookiefile) {		
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		}		
		
		$response = curl_exec($this->ch);
		$this->set_head(substr($response, 0, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE)));
		$response = substr($response, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
		$this->set_cookie();
		
		$this->postparams = array ();
		$this->is_post = false;
		
		return $response;
	}
	
	function __destruct (){
		curl_close($this->ch);
	}
}


//Вот так выглядит использование класса:


/**
* 

* $cURL   = new SC_cURL();
        
        $cURL->set_encoding('Windows-1251');
        
        $headers = array(
        		'Accept-Encoding:   gzip, deflate',
    			'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded; charset=windows-1251',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36',
                //'Accept - Encoding: gzip, deflate',
                'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4');
        $cURL->set_httpheader($headers);
		
        
        $cURL->set_cookiefile(NEWFC_DIR_SYSTEM . 'my.txt');
        
        $cURL->set_autoreferer(true);
        * 
        * $login_data = array(
            "do"  => "logon",
            "user"=> "support",
            "psw" => "equifaxSUP___2019",
            "x"   => "9",
            "y"   => "10");
            $cURL->set_followlocation(1);
		$cURL->set_returntransfer(1); //Возвращать результат в переменную
		
		//Установка кук
        $cookie = array (
        	'PHPSESSID' => session_id(),
            'user'    => 'support',
            'psw'=> 'equifaxSUP___2019'
        );
        $cURL->add_cookie($cookie);
        
        //Открываем страницу добавления договора, предварительно авторизовавшись
        $login = $cURL->post('http://10.130.1.2/chb.php?do=fki_load',$login_data);
        * 
        //Указываем рефера
        $cURL->set_referer('http://10.130.1.2/chb.php?do=fki_load');
        
        * //Перекодирование из windows-1251 в utf-8
        * $res = $this->core_lib->win_to_utf($login);
        * 
        * //Перекодирование json строки из UTF-8 формата '\u044b' в 'ы', '\u042b' в 'Ы'
        * ($this->core_lib->json_fix_cyr($res))
* Примеры использования
Рассмотрим основные примеры по использования класса CURL. А именно работу с GET и POST запросами, обработку cookies, работу с HTTP заголовками и настройкой прокси.

Инициализация
В начале мы должны указать путь к модулю и создать экземпляр класса cURL.

1
2
require_once ('путь к модулю');
$curl = new curl ();
GET запрос
В результате это примера мы получим с тело документа расположенного по адресу: http://ya.ru.

1
echo $curl->get('http://ya.ru');
POST запрос
Для отправки данных методом POST с использованием типа application/x-www-form-urlencoded, следует использовать ниже приведенный пример.

1
echo $curl->post('http://example/test.php', 'password=12345&login=mylogin');
Однако, если необходимо отправить данные, используя тип multipart/form-data, то следует использовать другой подход. Ниже описанный пример демонстрирует отправку файла:

1
2
3
4
5
6
$zip = dirname ( __FILE__ ) . "/test.zip";
 
// Проверяем существует ли файл
if ( @file_exists($zip)) {  
    echo $curl->post('http://example/test.php', "file=@$zip", true);
}
HTTP заголовки
Объявляем массив и добавляем в него все необходимые заголовки

1
2
3
4
5
6
$headers = array (
    'Accept-Encoding:   gzip, deflate',
    'Connection: keep-alive'
);
 
$curl->set_httpheader($headers);
Работа с cookie
Объявляем массив и добавляем в него все необходимые cookies

1
2
3
4
$cookie = array (
    'fdi' => '1',
    'session' => 'a7103cffc24c3c45683dc338b060000e'
);
Устанавливаем массив с cookies

1
$curl->add_cookie($cookie);
Смотрим какие cookies установлены

1
$result = $curl->get_cookie();
Результат:

array
(
    [fdi] => 1
    [session] => a7103cffc24c3c45683dc338b060000e
)
1
2
// Удаляем cookie fdi
$curl->delete_cookie('fdi');
Смотрим какие cookies установлены

1
$result = $curl->get_cookie();
Результат:

array
(
    [session] => a7103cffc24c3c45683dc338b060000e
)
Также следует отметить. Что cookie указанные в заголовке сервера Set-Cookie, добавляются в коллекцию автоматически.

Настройка прокси-сервера
Указываем адрес прокси-сервера

1
$curl->set_proxy("217.42.14.1:8080");
Иногда может потребоваться указать логин и пароль

1
$curl->proxy_user_data("login:password");
* 
* 
**/
?>