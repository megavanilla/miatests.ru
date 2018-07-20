<?php

namespace mvc\libs;

class Base
{
  public function dump($variable)
  {
    ob_start();
    $out = ob_get_clean();
    print $out;
  }

  /**
   * Метод выполняет запаковку результата в json объект
   * В случае, если результат являетсяя булевым, то будет преобразован к 1 или 0
   *
   * @param array|object|string|int|float|bool $resp - Преобразуемый ответ
   *
   * @return string JSON
   */
  public function parse_res_route($resp)
  {
    if (is_object($resp) || is_array($resp)) {
      $res_arr = [];
      foreach ($resp as $key_resp => $val_resp) {
        $res_arr[$key_resp] = clear_scalar($val_resp);
      }
      $resp = (is_object($resp)) ? (object)$res_arr : $res_arr;
    } elseif (is_integer($resp) || is_float($resp) || is_string($resp)) {
      //
    } elseif (is_bool($resp) || is_null($resp) || empty($resp)) {
      $resp = clear_scalar($resp);
    } else {
      //Если это другой какой тип, то вернём null
      $resp = clear_scalar(null);
    }

    $data = new stdClass();
    $data->sc_resp = $resp;
    $result = trim(json_encode($data, JSON_UNESCAPED_UNICODE), '"');

    return $result;

  }

  /**
   * Метод выполняет фильтрацию значения, в зависимости от типа данных
   * Если значение булево, то вернёт 1 или 0,
   * если значение NULL, то вернёт 'null',
   * если значение пустое, то вернёт '',
   * иначе вернет, переданное значение обратно
   *
   * @param array|object|string|int|float|bool $val - Очищаемое значение
   *
   * @return
   */
  public function clear_scalar($val)
  {
    if (is_bool($val)) {
      $val = ($val == true) ? 1 : 0;
    } elseif (is_null($val)) {
      $val = 'null';
    } elseif (empty($val)) {
      $val = '';
    }
    return $val;
  }
}