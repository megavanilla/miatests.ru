<?php
/**
 * Created by PhpStorm.
 * User: MIA
 * Date: 023 23.06.18
 * Time: 23:48
 */

namespace server\WssServer;


$socket = stream_socket_server("tcp://127.0.0.1:8000", $errno, $errstr);
if (!$socket) {
  echo "$errstr ($errno)<br />\n";
} else {
  while ($conn = stream_socket_accept($socket)) {
    fwrite($conn, 'Локальное время ' . date('n/j/Y g:i a') . "\n");
    fclose($conn);
  }
  fclose($socket);
}

class WssServer
{

  private static $_SOCKET;
  private static $_SOCKET_ADDR = 'tcp://127.0.0.1:8000';

  public static function openConnect()
  {
    self::$_SOCKET = stream_socket_server(self::$_SOCKET_ADDR, $errNo, $errStr);
    if (!self::$_SOCKET) {
      echo "$errStr ($errNo)<br />\n";
    }
  }

  public static function closeConnect()
  {
    if (self::$_SOCKET) {
      fclose(self::$_SOCKET);
    }
  }


  public static function gatDate()
  {
    if(!self::$_SOCKET){print 'Не удалось организовать сокет соединение';}
    while ($conn = stream_socket_accept(self::$_SOCKET)) {
      fwrite($conn, 'Локальное время ' . date('n/j/Y g:i a') . "\n");
      //fclose($conn);
    }
  }

}