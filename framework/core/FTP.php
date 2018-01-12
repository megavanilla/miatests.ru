<?php // Передаем заголовки
//header('Content - type: text / html; charset = utf - 8');
//header('Access - Control - Allow - Origin: * ');
if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
* Класс - FTP
* **/
class FTP
{
    public $Host;
    public $Port;
    public $User;
    public $Pass;
    public $Dir;

    public $Error;

    public $Proxy;
    public $ConnectTimeOut = 30;
    public $WorkTimeOut = 35;

    public function connect($Host = '', $Port = '', $TimeOut = '')
    {
        if ($Host) $this->Host = $Host;
        if ($Port) $this->Port = $Port;
        if ($TimeOut) $this->ConnectTimeOut = $TimeOut;
        if ($this->Proxy) $this->CmdSocket = $this->fs5sockopen($this->Host, $this->Port, $this->Proxy, $tmp, $this->Error, $this->ConnectTimeOut);
        else $this->CmdSocket = fsockopen($this->Host, $this->Port, $tmp, $this->Error, $this->ConnectTimeOut);
        stream_set_timeout($this->CmdSocket, $this->WorkTimeOut);
        if (!$this->CmdSocket) return false;
        if ($this->doCmd() != '220') return false;
        return true;
    }

    public function login($User = '', $Pass = '')
    {
        if ($User) $this->User = $User;
        if ($Pass) $this->Pass = $Pass;
        if ($this->doCmd('USER ' . $this->User) != '331') return false;
        if ($this->doCmd('PASS ' . $this->Pass) != '230') return false;
        return true;
    }

    public function close()
    {
        if ($this->doCmd('QUIT') != '221') return false;
        return true;
    }

    public function pwd()
    {
        if ($this->doCmd('PWD') != '257') return false;
        return $this->substr_smart($this->Error, '"', '"');
    }

    public function chdir($Dir = '')
    {
        if ($Dir) $this->Dir = $Dir;
        if ($this->doCmd('CWD ' . $this->Dir) != '250') return false;
        return true;
    }

    public function rawlist($Dir = '')
    {
        if ($Dir) $this->Dir = $Dir;
        if ($this->doCmd('TYPE A') != '200') return false;
        if ($this->doCmd('PASV') != '227') return false;
        if (!$this->connect_data()) return false;
        $ListCmd = $this->doCmd('LIST ' . $this->Dir);
        if ($ListCmd != '150' and $ListCmd != '125') return false;
        if (($Data = $this->read()) === false) return false;
        //$this->doCmd();
        return explode("\n", $Data);
    }

    public function get($LocalFile, $RemoteFile = '', $Mode = 'FTP_BINARY')
    {
        if (!$RemoteFile) $RemoteFile = $this->Dir;
        if ($Mode == 'FTP_BINARY') $Mode = 'TYPE I'; else $Mode = 'TYPE A';
        if ($this->doCmd($Mode) != '200') return false;
        if ($this->doCmd('PASV') != '227') return false;
        if (!$this->connect_data()) return false;
        if ($this->doCmd('RETR ' . $RemoteFile) != '150') return false;
        if (($Data = $this->read()) === false) return false;
        //$this->doCmd();
        if (!$f = fopen($LocalFile, "w+"))
        {
            $this->Error = 'Cant save file'; return false;
        }
        fwrite($f, $Data);
        fclose($f);
        return true;
    }

    public function put($LocalFile, $RemoteFile = '', $Mode = 'FTP_BINARY')
    {
        if (!$RemoteFile) $RemoteFile = $this->Dir;
        if (($Data = file_get_contents($LocalFile)) === false)
        {
            $this->Error = 'Cant open file'; return false;
        }
        if ($Mode == 'FTP_BINARY') $Mode = 'TYPE I'; else $Mode = 'TYPE A';
        $Ans  = $this->doCmd($Mode);
        if ($Ans != '200' and $Ans != '226') return false;
        if ($this->doCmd('PASV') != '227') return false;
        if (!$this->connect_data()) return false;
        if ($this->doCmd('STOR ' . $RemoteFile) != '150') return false;
        if (!$this->write($Data)) return false;
        $this->doCmd();
        return true;
    }

    // ********************************************************************************************************
    // ** DEVELOPMENT SECTION

    private $DataHost;
    private $DataPort;
    private $CmdSocket;
    private $DataSocket;

    function __construct($FtpURI = '', $Proxy = '')
    {
        if ($FtpURI) $this->Error = $this->DispatchAddr($FtpURI);
        $this->Proxy = $Proxy;
    }

    function __destruct()
    {
        $this->close();
    }

    private function DispatchAddr($FtpURI)
    {
        if (substr($FtpURI, 0, 6) != 'ftp://') $FtpURI = 'ftp://' . $FtpURI;
        if (!$URI = parse_url($FtpURI)) return 'Bad URI Format';
        if (!$this->Host = gethostbyname($URI['host'])) return 'Cant locate host';
        if (!$this->Port = $URI['port']) $this->Port = 21;
        if (!$this->User = $URI['user']) $this->User = 'anonymous';
        if (!$this->Pass = $URI['pass']) $this->Pass = 'someone@email';
        if (!$this->Dir = $URI['path']) $this->Dir = '/';
        return 'SUCCESS';
    }

    private function doCmd($Cmd = '')
    {
        if ($Cmd)
        if (!@fputs($this->CmdSocket, $Cmd . "\r\n"))
        {
            $this->Error = 'Connect lost'; return false;
        }
        if ($this->Proxy) sleep(3); else sleep(1);
        if (!$this->Error = trim(@fread($this->CmdSocket, 4096)))
        {
            $this->Error = 'No answer'; return false;
        }
        return substr($this->Error,0,3);
    }

    private function fs5sockopen($Host, $Port, $Proxy, & $Errnumb, & $Errstr, $TimeOut)
    {
        list($SocksHost, $SocksPort) = explode(":", $this->Proxy);
        if (!$Socket = @fsockopen($SocksHost, $SocksPort, $Errnumb, $Errstr, $TimeOut)) return false;
        $Errstr = 'Bad proxy';
        if (!fwrite($Socket, "\x5\x1\x0")) return false;
        if (!$Ans = fread($Socket, 2)) return false;
        if ($Ans[1] != "\x0") return false;
        list($A1, $A2, $A3, $A4) = explode(".", $this->Host);
        $P2 = $Port - (($P1 = $Port >> 8) << 8);
        $Cmd= chr($A1) . chr($A2) . chr($A3) . chr($A4) . chr($P1) . chr($P2);
        if (!fwrite($Socket, "\x05\x01\x00\x01".$Cmd)) return false;
        if (!$Ans = fread($Socket, 10)) return false;
        if ($Ans[1] != "\x0") return false;
        $Errstr = 'SUCCESS';
        return $Socket;
    }

    private function connect_data()
    {
        list($tmp, $DestAddr) = explode("(", $this->Error);
        $DestAddr = substr($DestAddr,0, strpos($DestAddr, ')'));
        list($A1, $A2, $A3, $A4, $P1, $P2) = explode(",", $DestAddr);
        $this->DataHost = $A1 . '.' . $A2 . '.' . $A3 . '.' . $A4;
        $this->DataPort = $P1 * 256 + $P2;
        if ($this->Proxy) $this->DataSocket = $this->fs5sockopen($this->DataHost, $this->DataPort, $this->Proxy, $tmp, $this->Error, $this->ConnectTimeOut);
        else $this->DataSocket = fsockopen($this->DataHost, $this->DataPort, $tmp, $this->Error, $this->ConnectTimeOut);
        if (!$this->DataSocket) return false;
        return true;
    }

    private function read()
    {
        $Data = '';
        while (!feof($this->DataSocket)) {
            if (!$NextLine = fgets($this->DataSocket)) return false;
            $Data .= $NextLine;
        }
        fclose($this->DataSocket);
        return trim($Data);
    }

    private function write($Data)
    {
        if (fputs($this->DataSocket, $Data) < strlen($Data)) return false;
        fclose($this->DataSocket);
        return true;
    }

    private function substr_smart($Source, $From, $To = '"')
    {
        if ($From == '') $SeekA = 0; else
        if (($SeekA = @strpos($Source, $From)) === false) return false;
        else $SeekA += strlen($From);
        if (($SeekB = @strpos($Source, $To, $SeekA)) === false) $SeekB = strlen($Source);
        return substr($Source, $SeekA, $SeekB - $SeekA);
    }
}


//Вот так выглядит использование класса:

/**
* 
require_once 'ftp.class.php';
// Создание объекта, через который будем управлять по ftp
$fc1 = new ftpcom();
// Можно при создании сразу указать, с каким ftp будем работать
$fc2 = new ftpcom('ftp://login:pass@domain.com');
// Можно сразу указать socks-5 прокси, например 127.0.0.1:3128
$fc3 = new ftpcom('ftp://login:pass@domain.com', '127.0.0.1:3128');
// Впрочем, можно параметры присвоить и позже:
$fc1->Host = 'domain.com';
$fc1->Port = '21'; // По умолчанию порт и так 21
$fc1->User = 'login';
$fc1->Pass = 'pass';
// Сколько секунд ждать ответа от сервера, по умолчанию 30
$fc1->ConnectTimeOut = 10;
// Коннект и логин осуществляется вот так:
// Connect без параметров подключает к domain.com, как задано
if(!$fc1->connect()) exit($fc1->Error);
$fc1->login(); // Ну и логинимся с логином и паролем, заданным выше
// Подконнектится к domain3.com, к порту 21, ждать максимум 200 секунд
if(!$fc2->connect('domain3.com', 21, 200)) exit($fc1->Error);
// При логине тоже можно задавать напрямую логин и пароль
$fc2->login('Login2', 'mysecretpass');
// Ну удобней всего конечно так:
if(!$fc3->connect() or !$fc3->login()) exit($fc3->Error);
// Остальные операции соответствуют синтаксису встроенных в пхп функций работы с ftp. Например:
$fc1->rawlist(); // Возвращает список файлов в текущей директории
$fc1->chdir('http_docs'); // поменяет текущцю директорию
// Скачает file.php с текущей директории и сохранит как file.php
$fc1->get('file.php');
// Скачает файл index.php и сохранит в temp.php.
// Режим скачивания - текстовый
$fc1->get('temp.txt', 'index.php', 'FTP_ASCII');
echo $fc1->pwd(); // Возвращает текущую директорию
// Локальный файл temp.php зальет на ftp с именем index.php
$fc1->put('temp.php', 'index.php');
// В переменной Error хранится результат последней операции
echo $fc->Error;

**/
?>