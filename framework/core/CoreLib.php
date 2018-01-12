<?php

if (!defined('READFILE')) {
    exit("Не правильный вызов файла." . $_SERVER['SCRIPT_FILENAME'] . "<a href=\"/\">Вернуться на главную</a>.");
}

/**
 * Класс содержит небольшие общие методы, не пересекающиеся с основным функционалом.
 * * */
class CoreLib {

    public function test() {
        return "Ok!";
    }
    
    /**
     * Метод выполняет дополнение строки ведущими символами.
     * Пример: входная строка 1.txt результат 0001.txt
     * @param string $str_in		 - Входная строка
     * @param integer $count_symb - Количество символов
     * @param string_symb $symb	 - Символ
     * 
     * @return string
     */
    public function leading_symbols($str_in = "", $count_symb = 4, $symb = '0') {
        if (is_string($str_in) &&
                !empty($str_in) &&
                is_numeric($count_symb) &&
                (int) $count_symb > 0 &&
                is_string($symb) &&
                !empty($symb)
        )
        //Количество символов, которые в случае путоты будут дополнятся
            $count_symb = (int) $count_symb;
        //Получаем первый символ из строки
        $symb = mb_substr($symb, 0, 1);
        //Получаем количество символов в переданной строке
        $count_symb_str_in = mb_strlen($str_in);
        $res_symols = '';
        if ($count_symb_str_in < $count_symb) {

            for ($i_symb = 0; $i_symb < ($count_symb - $count_symb_str_in); $i_symb++) {
                $res_symols .= $symb;
            }
            $str_in = $res_symols . $str_in;
        }
        return $str_in;
    }
    
    /**
     * Метод возвращает массив из строки вида "значение1, значение2, значение3"
     * в одномерный массив с переданными значенями
     * @param string $string_or_array
     * @param string $separate
     * 
     * @return array
     */
    public function convert_string_to_array(&$string_or_array = "", $separate = ",") {
        $arr_param = array();

        //Если передана строка, то пытаемся распарсить ее с применением сепаратора
        if (is_string($string_or_array) && !empty($string_or_array) && is_string($separate) && mb_strlen($separate) == 1) {
            $arr_param = explode($separate, $string_or_array);
            $arr_param = array_map('trim', $arr_param);
            //Если передан массив или объект и его значения строковые, то отправляем в массив результатов значения переданного массива или объекта
        } elseif (!empty($string_or_array) && (is_array($string_or_array) || is_object($string_or_array))) {
            foreach ($string_or_array as $key => $value) {
                if (is_string($value)) {
                    $arr_param[] = $value;
                }
            }
        } else {
            return null;
        }
        $string_or_array = $arr_param;
        return $string_or_array;
    }
    
    /**
     * Метод выполняет поиска значения в массиве, и возвращает найденный элемент массива.
     * В зависимости от переданных ключей, может быть возвращен найденный элемент массива,
     * или искомое значение с измененным регистром.
     * 
     * @param string $value
     * @param array $array
     * @param bool $registr
     * @param variant $case_return По умолчанию вернет значение найденного элемента1 - Нижний регистр; 2 - Верхний регистр; 3 - Каждое слово с большой буквы
     * 
     * @return variant
     */
    public function in_array_case_ret_value($value = null, $array = null, $registr = 1, $case_return = null) {
        //Если передана строка, то пробуем конвертировать ее в массив
        $array = $this->convert_string_to_array($array);
        if ($registr == 1) {
            $in_array = $this->in_array_case_lower($value, $array);
        } else {
            $in_array = in_array($value, $array);
        }
        //print_r( $array);
        if ($in_array === true) {
            switch ($case_return) {
                case 1://Возвращаем элемент в нижнем регистре
                    return mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');
                    break;
                case 2://Возвращаем элемент в верхнем регистре
                    return mb_convert_case($value, MB_CASE_UPPER, 'UTF-8');
                    break;
                case 3://Возвращает элемент, где первый символ в верхнем регистр, а остальные в нижнем
                    return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
                    break;
                default://Вовзращает элемент, который найден в искомом массиве
                    if ($registr == 1) {
                        $array_search = array_search(mb_convert_case($value, MB_CASE_LOWER, 'UTF-8'), $this->mb_convert_case_array($array));
                    } else {
                        $array_search = array_search($value, $array);
                    }
                    //print('Номер найденного элемента: '.$array_search."\r\n");
                    return ($array_search !== false) ? $array[$array_search] : null;
                    break;
            }
        } else {
            return null;
        }
    }
    
    /**
     * Метод возвращает истину, если в массиве найден искомый элемент.
     * Иначе ложь.
     * Поиск проводится в одном, приведенном к нижнему, регистре.
     * @param string $value
     * @param array $array
     * 
     * @return bool
     */
    public function in_array_case_lower($value = null, $array = null) {
        if (is_string($value) && is_array($array) && !empty($value) && !empty($array)) {
            //print_r($this->mb_convert_case_array($array));
            return in_array(mb_convert_case($value, MB_CASE_LOWER, 'UTF-8'), $this->mb_convert_case_array($array));
        } else {
            return false;
        }
    }
    
    /**
     * Метод выполняет преобразование регистра символов в массиве
     * @param $array (array)
     * @param $type (MB_CASE_UPPER || MB_CASE_LOWER || MB_CASE_TITLE)
     * @param $encoding (UTF-8, Windows-1251...)
     * * */
    public function mb_convert_case_array($array, $type = MB_CASE_LOWER, $encoding = 'UTF-8') {
        if (is_array($array) == true && ($type == MB_CASE_UPPER || $type == MB_CASE_LOWER || $type == MB_CASE_TITLE)) {
            foreach ($array as $key => $val) {
                if (is_string($val)) {
                    $array[$key] = mb_convert_case($val, $type, $encoding);
                }
            }
            /*
              for($i=0;$i<count($array);$i++){
              if(is_string($array[$i])){
              $array[$i] = mb_convert_case($array[$i],$type);
              }
              }
             */
            return $array;
        } else {
            return null;
        }
    }
    
    /**
     * Методв выполняет парсинг строки с определенными делиметрами и преобразует ее в объект
     * @param string $str
     * @param string $group_delim
     * @param string $param_delim
     * @param string $value_delim
     * 
     * @return string
     */
    public function parse_explode_string_to_object($str, $group_delim = '|', $param_delim = ',', $value_delim = ':') {
        $arr_res = array();
        if (is_string($str) && !empty($str)) {
            $arr_explode_group_param = explode($group_delim, $str);
            if (!empty($arr_explode_group_param)) {
                for ($i_group_params = 0; $i_group_params < count($arr_explode_group_param); $i_group_params++) {
                    $arr_explode_param = explode($param_delim, $arr_explode_group_param[$i_group_params]);
                    if (!empty($arr_explode_param)) {
                        for ($i_params = 0; $i_params < count($arr_explode_param); $i_params++) {
                            $arr_values = explode($value_delim, $arr_explode_param[$i_params]);
                            if (array_key_exists(0, $arr_values) && array_key_exists(1, $arr_values)) {
                                $arr_res[$arr_values[0]] = $arr_values[1];
                            } else {
                                continue; //Пробуем следующий элемент
                            }
                        }
                    }
                }
            } else {//Если в строке нет групп параметров, то пробуем распарсить параметры
                $arr_explode_param = explode($param_delim, $str);
                if (!empty($arr_explode_param)) {
                    for ($i_params = 0; $i_params < count($arr_explode_param); $i_params++) {
                        $arr_values = explode($value_delim, $arr_explode_param[$i_params]);
                        if (array_key_exists(0, $arr_values) && array_key_exists(1, $arr_values)) {
                            $arr_res[$arr_values[0]] = $arr_values[1];
                        } else {
                            continue; //Пробуем следующий элемент
                        }
                    }
                }
            }
        }
        if (!empty($arr_res)) {
            $arr_res = (object) $arr_res;
        } else {
            $arr_res = null;
        }
        return $arr_res;
    }
    
    /**
     * Метод преобразует текст из кодировки Windows-1251 в UTF-8
     *
     * @param string $text
     * @return string
     */
    public function win_to_utf($text = '') {
        //Если переданная строка не является строкой, или пуста,
        //то попробуем преобразовать к строке,
        //если преобразовать не удалось, то вернем то что пришло в метод
        if (!is_string($text) || !empty($text)) {
            try {
                $text = (string) $text;
            } catch (exception $e) {
                return $text;
            }
        }
        try {
            $text = iconv('Windows-1251', 'UTF-8', $text);
            return $text;
        } catch (exception $e) {
            return $text;
        }
    }
    
    /**
     * Метод преобразует текст из кодировки UTF-8 в Windows-1251 
     *
     * @param string $text
     * @return string
     */
    public function utf_to_win($text = '') {
        //Если переданная строка не является строкой, или пуста,
        //то попробуем преобразовать к строке,
        //если преобразовать не удалось, то вернем то что пришло в метод
        if (!is_string($text) || !empty($text)) {
            try {
                $text = (string) $text;
            } catch (exception $e) {
                return $text;
            }
        }
        try {
            $text = iconv('UTF-8', 'Windows-1251', $text);
            return $text;
        } catch (exception $e) {
            return $text;
        }
    }
    
    function json_fix_cyr($json_str) {
        if (!is_string($json_str) || !empty($json_str)) {
            try {
                $json_str = (string) $json_str;
            } catch (exception $e) {
                return $json_str;
            }
        }
        $cyr_chars = array(
            '\u0430' => 'а', '\u0410' => 'А',
            '\u0431' => 'б', '\u0411' => 'Б',
            '\u0432' => 'в', '\u0412' => 'В',
            '\u0433' => 'г', '\u0413' => 'Г',
            '\u0434' => 'д', '\u0414' => 'Д',
            '\u0435' => 'е', '\u0415' => 'Е',
            '\u0451' => 'ё', '\u0401' => 'Ё',
            '\u0436' => 'ж', '\u0416' => 'Ж',
            '\u0437' => 'з', '\u0417' => 'З',
            '\u0438' => 'и', '\u0418' => 'И',
            '\u0439' => 'й', '\u0419' => 'Й',
            '\u043a' => 'к', '\u041a' => 'К',
            '\u043b' => 'л', '\u041b' => 'Л',
            '\u043c' => 'м', '\u041c' => 'М',
            '\u043d' => 'н', '\u041d' => 'Н',
            '\u043e' => 'о', '\u041e' => 'О',
            '\u043f' => 'п', '\u041f' => 'П',
            '\u0440' => 'р', '\u0420' => 'Р',
            '\u0441' => 'с', '\u0421' => 'С',
            '\u0442' => 'т', '\u0422' => 'Т',
            '\u0443' => 'у', '\u0423' => 'У',
            '\u0444' => 'ф', '\u0424' => 'Ф',
            '\u0445' => 'х', '\u0425' => 'Х',
            '\u0446' => 'ц', '\u0426' => 'Ц',
            '\u0447' => 'ч', '\u0427' => 'Ч',
            '\u0448' => 'ш', '\u0428' => 'Ш',
            '\u0449' => 'щ', '\u0429' => 'Щ',
            '\u044a' => 'ъ', '\u042a' => 'Ъ',
            '\u044b' => 'ы', '\u042b' => 'Ы',
            '\u044c' => 'ь', '\u042c' => 'Ь',
            '\u044d' => 'э', '\u042d' => 'Э',
            '\u044e' => 'ю', '\u042e' => 'Ю',
            '\u044f' => 'я', '\u042f' => 'Я',
            '\r' => '',
            '\n' => '<br />',
            '\t' => ''
        );

        foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
            $json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
        }
        return $json_str;
    }
    
    /**
     * Метод проверяет кодировку полученного текста и переводит к указанной
     * @param $text (string)
     * @param $encoding_in (UCS-4* || UCS-4BE || UCS-4LE* || UCS-2 || UCS-2BE || UCS-2LE || UTF-32* || UTF-32BE* || UTF-32LE* || UTF-16* || UTF-16BE* || UTF-16LE* || UTF-7 || UTF7-IMAP || UTF-8* || ASCII* || EUC-JP* || SJIS* || eucJP-win* || SJIS-win* || ISO-2022-JP || ISO-2022-JP-MS || CP932 || CP51932 || SJIS-mac** (alias: MacJapanese) || SJIS-Mobile#DOCOMO** (alias: SJIS-DOCOMO) || SJIS-Mobile#KDDI** (alias: SJIS-KDDI) || SJIS-Mobile#SOFTBANK** (alias: SJIS-SOFTBANK) || UTF-8-Mobile#DOCOMO** (alias: UTF-8-DOCOMO) || UTF-8-Mobile#KDDI-A** || UTF-8-Mobile#KDDI-B** (alias: UTF-8-KDDI) || UTF-8-Mobile#SOFTBANK** (alias: UTF-8-SOFTBANK) || ISO-2022-JP-MOBILE#KDDI** (alias: ISO-2022-JP-KDDI) || JIS ||JIS-ms || CP50220 || CP50220raw || CP50221 || CP50222 || ISO-8859-1* || ISO-8859-2* || ISO-8859-3* || ISO-8859-4* || ISO-8859-5* || ISO-8859-6* || ISO-8859-7* || ISO-8859-8* || ISO-8859-9* || ISO-8859-10* || ISO-8859-13* || ISO-8859-14* || ISO-8859-15* || byte2be || byte2le || byte4be || byte4le || BASE64 || HTML-ENTITIES || 7bit || 8bit || EUC-CN* || CP936 || GB18030** || HZ || EUC-TW* || CP950 || BIG-5* || EUC-KR* || UHC (CP949) || ISO-2022-KR || Windows-1251 (CP1251) || Windows-1252 (CP1252) || CP866 (IBM866) || KOI8-R*)
     * @param $encoding_out (UCS-4* || UCS-4BE || UCS-4LE* || UCS-2 || UCS-2BE || UCS-2LE || UTF-32* || UTF-32BE* || UTF-32LE* || UTF-16* || UTF-16BE* || UTF-16LE* || UTF-7 || UTF7-IMAP || UTF-8* || ASCII* || EUC-JP* || SJIS* || eucJP-win* || SJIS-win* || ISO-2022-JP || ISO-2022-JP-MS || CP932 || CP51932 || SJIS-mac** (alias: MacJapanese) || SJIS-Mobile#DOCOMO** (alias: SJIS-DOCOMO) || SJIS-Mobile#KDDI** (alias: SJIS-KDDI) || SJIS-Mobile#SOFTBANK** (alias: SJIS-SOFTBANK) || UTF-8-Mobile#DOCOMO** (alias: UTF-8-DOCOMO) || UTF-8-Mobile#KDDI-A** || UTF-8-Mobile#KDDI-B** (alias: UTF-8-KDDI) || UTF-8-Mobile#SOFTBANK** (alias: UTF-8-SOFTBANK) || ISO-2022-JP-MOBILE#KDDI** (alias: ISO-2022-JP-KDDI) || JIS ||JIS-ms || CP50220 || CP50220raw || CP50221 || CP50222 || ISO-8859-1* || ISO-8859-2* || ISO-8859-3* || ISO-8859-4* || ISO-8859-5* || ISO-8859-6* || ISO-8859-7* || ISO-8859-8* || ISO-8859-9* || ISO-8859-10* || ISO-8859-13* || ISO-8859-14* || ISO-8859-15* || byte2be || byte2le || byte4be || byte4le || BASE64 || HTML-ENTITIES || 7bit || 8bit || EUC-CN* || CP936 || GB18030** || HZ || EUC-TW* || CP950 || BIG-5* || EUC-KR* || UHC (CP949) || ISO-2022-KR || Windows-1251 (CP1251) || Windows-1252 (CP1252) || CP866 (IBM866) || KOI8-R*)
     * @return (string)
     * * */
    public function set_encoding($text, $encoding_in, $encoding_out) {
        //Массив возможных кодировок
        //Массив возможных кодировок в нижнем регистре
        $array_encodings = array('UTF-8', 'Windows-1251', 'Windows-1252', 'CP1252', 'CP866', 'IBM866', 'KOI8-R', 'KOI8-U'); //mb_list_encodings();
        $array_encodings_lower = $this->mb_convert_case_array($array_encodings, MB_CASE_LOWER);

        //var_dump($array_encodings_lower);

        /**
         * Если указанная кодировка найдена в любом регистре, то преобразуем её к необходимому виду
         * * */
        $find_encoding_in = array();
        $find_encoding_in = array_search(mb_convert_case($encoding_in, MB_CASE_LOWER), $array_encodings_lower);
        $encoding_in = ($find_encoding_in !== FALSE) ? $array_encodings[$find_encoding_in] : FALSE;
        $find_encoding_out = array();
        $find_encoding_out = array_search(mb_convert_case($encoding_out, MB_CASE_LOWER), $array_encodings_lower);
        $encoding_out = ($find_encoding_out !== FALSE) ? $array_encodings[$find_encoding_out] : FALSE;
        //print ($encoding_in . ' || ' . $encoding_out . " < br />\r\n");
        //var_dump($this->mb_convert_case_array(mb_list_encodings(),MB_CASE_LOWER));

        try {

            $enc = mb_detect_encoding($text, mb_list_encodings(), false);

            if ($encoding_in === FALSE || $encoding_out === FALSE) {
                //could not detect encoding
                //return $text;
            } else
            if ($encoding_in != $encoding_out && $enc !== $encoding_out) {
                //if($enc === $encoding_out){
                //print('encoding_text: '.$enc.' || encoding_in: '.$encoding_in.' || Encoding_out: '.$encoding_out." < br />\r\n");
                //}
                //$text = mb_convert_encoding($text, $encoding_in, $encoding_out);
                $text = iconv($encoding_in, $encoding_out, $text);
            } else {
                //UTF - 8 detected
            }


            /**
              if(iconv($encoding_in, $encoding_out, $text)){
              $text = iconv($encoding_in, $encoding_out, $text);
              }
             * */
            return $text;
        } catch (exception $e) {
            return $text;
        }
    }
    
    /**
     * Метод выполняет сортировку ассоциативного массива, типа таблица по убыванию или возрастанию
     * @param $data (array)
     * @param $field_name (string)
     * @param $sort_type (SORT_ASC/SORT_DESC)
     * @return (array||NULL)
     * * */
    public function sort_assoc_array($data, $field_name, $type_sort = SORT_ASC) {
        //Проверяем существуют ли ключи в массиве, и вообще сам массив, иначе вернем NULL
        if (is_array($data) && is_string($field_name)) {
            $keys = array();
            // Получение списка столбцов
            foreach ($data as $key => $row) {
                if (!array_key_exists($field_name, $row)) {
                    return null;
                } else {
                    $keys[$key] = $row[$field_name];
                }
            }
            // Сортируем данные по volume по убыванию и по edition по возрастанию
            // Добавляем $data в качестве последнего параметра, для сортировки по общему ключу
            array_multisort($keys, $type_sort, $data);
            return $data;
        } else {
            return null;
        }
    }
    
    /**
     * Метод заменяет все паттерны на значения для переданной строки
     * @param string [string] - Строка
     * @param arr_pattern [array] - Двумерный массив, где ключи - паттерн, а значения - заменяемое значение паттерна
     * * */
    public function load_text_with_pattern($string = "", $arr_pattern = array()) {
        if (is_array($arr_pattern) || is_object($arr_pattern)) {
            foreach ($arr_pattern as $i => $u) {
                $string = str_replace($i, $u, (string) $string);
            }
        }
        return $string;
    }
    
    /**
     * Метод заменяет все паттерны на значения для переданной строки
     * @param file_name [string] - Путь к файлу, содержащему шаблон
     * @param arr_pattern [array] - Двумерный массив, где ключи - паттерн, а значения - заменяемое значение паттерна
     * * */
    public function load_tpl_with_pattern($file_name = "", $arr_pattern = array()) {
        if (file_exists((string) $file_name) && is_file((string) $file_name)) {
            $string = file_get_contents($file_name);
            if (is_array($arr_pattern) || is_object($arr_pattern)) {
                foreach ($arr_pattern as $i => $u) {
                    $string = str_replace($i, $u, (string) $string);
                }
            }
            return $string;
        } else {
            return null;
        }
    }
    
    /**
     * Функция возвращает количество месяцев, в дробном формате, от текущей даты
     * @param integer $count_day Входное количество дней
     * 
     * @return float Выходное количество дней в формате: 2,5...
     */
    public function count_month_from_day($count_day) {
        $count_day = (int) $count_day;
        $day = date('j'); //Текущий день (по умолчанию)
        $month = date('m'); //Текущий месяц (по умолчанию)
        $year = date('Y'); //Текущий год (по умолчанию)

        $count_month_result = 0; //Целая часть от месяца
        $count_float_month = 0; //Дробная часть от месяца

        $max_counter = 0;
        do {
            $max_counter++;
            $count_day_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            //print('count_day_month = '.$count_day_month);
            //Выясняем, сколько дней осталось до конца текущего месяца
            $rest_day_from_mont = abs($count_day_month - $count_day);

            /**
             * Если количество указанных дней больше чем оставшихся в месяце,
             * то получаем их разницу, и прибавляем 1 месяц к счетчику?
             * а также, прибавляем сдвигаем интервал еще на 1 месяц,
             * если сдвигаемыймесяц уже был 12-м, то месяц устанавливаем в 1, а год увеличиваем на 1.

             */
            if ($count_day > $rest_day_from_mont) {
                //$count_day = $count_day - $rest_day_from_mont;
                $count_month_result ++;

                if ($month < 12) {
                    $month ++;
                } else {
                    $year ++;
                    $month = 1;
                }

                $count_day = $rest_day_from_mont;
            }
            $res_1 = array(
                'count_day' => $count_day,
                'count_day_month' => $count_day_month,
                'max_counter' => $max_counter,
                'count_month_result' => $count_month_result,
                'year' => $year,
                'month' => $month);

            //print_r($res_1);
        } while ($count_day_month < $count_day);

        /**
         * Если количество указанных дней меньше или равно количеству оставшихся в месяце,
         * то получаем долю от месяца.
         */
        $count_float_month = round(($count_day) / $count_day_month, 2); //Дробное число от остатка месяца

        $res_2 = array(
            'count_day' => $count_day,
            'count_day_month' => $count_day_month,
            'count_float_month' => $count_float_month);
        //print_r($res_2);

        return round($count_month_result + $count_float_month, 2);
    }
    
    /**
     * Функция возвращает строку, содержащую количество прошедших лет, месяцев, дней
     * - функция имеет погрешности, вследствии преобразований из чисел с плавающей точкой!
     * * */
    function humanDatePassed($start, $end = null) {
        if ($end != '0000-00-00' && $end != null) {
            $now = $this->get_unix_from_string_date($end);
        } else {
            $now = time();
        }
        $get_year = (60 * 60 * 24 * 365); //Выделяем года
        $get_month = (60 * 60 * 24 * 29); //Выделяем месяца
        $get_day = (60 * 60 * 24); //Выделяем дни
        $in = $this->get_unix_from_string_date($start);
        $seconds_diff = abs($now - $in);
        $year = round($seconds_diff / $get_year, 0);
        /**
         * из оставшихся секунд расчитываем количество месяцев
         * * */
        $months_diff = abs($seconds_diff - ($get_year * $year));
        $month = round($months_diff / $get_month, 0);
        /**
         * из оставшихся секунд расчитываем количество дней
         * * */
        $days_diff = abs($months_diff - ($get_month * $month));
        $day = round($days_diff / $get_day, 0);
        $year = "$year " . $this->numberEnd($year, array(
                    'год',
                    'года',
                    'лет'));
        $month = " $month " . $this->numberEnd($month, array(
                    'месяц',
                    'месяца',
                    'месяцев'));
        $day = " $day " . $this->numberEnd($day, array(
                    'день',
                    'дня',
                    'дней'));
        return $year . $month . $day;
    }
    
    /**
     * Метод возвращает окончание слова,
     * в зависимости от числа.
     * * */
    function numberEnd($number, $titles) {
        $cases = array(
            2,
            0,
            1,
            1,
            1,
            2);
        return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
    
    /**
     * Метод возвращает количество процентов,
     * в формате 1 процент, 2 процента, 5 процентов...
     * * */
    function percent2str($num) {
        $num_change_format = (string) $num;
        //if( mb_strlen($num_change_format, ['UTF - 8'] > 1)){
        //Берем последний символ
        $num_change_format = mb_substr($num_change_format, null, - 1, 'UTF-8');
        $num_str = $num . ' процен' . $this->numberEnd($num_change_format, array(
                    'т',
                    'та',
                    'тов'));
        return $num_str;
    }
    
    /**
     * Метод возвращает количество дней,
     * в формате 1 день, 2 дня, 5 дней...
     * * */
    function day2str($num) {
        $num = (integer) $num;
        $num_str = $num . " день";
        $num_str = $num . ' д' . $this->numberEnd($num, array(
                    'ень',
                    'ня',
                    'ней'));
        return $num_str;
    }
    
    /**
     * Метод возвращает дату в unix формате строки даты вида из Y-m-d
     * * */
    function get_unix_from_string_date($date) {
        $convert_obj = $this->parse_date2obj($date);
        $date = trim($date);
        //преобразуем в unix время
        return mktime(0, 0, 0, $convert_obj->MONTH, $convert_obj->DAY, $convert_obj->YEAR);
    }

    /**
     * Метод возвращает объект из переданной строки формата "ГГГГ-мм-дд" - Y-m-d
     * в объект.
     * * */
    function parse_date2obj($date) {
        $oj_date = new stdClass();
        $date = trim($date);
        $oj_date->YEAR = mb_substr($date, 0, 4, 'UTF-8');
        $oj_date->MONTH = mb_substr($date, 5, 2, 'UTF-8');
        $oj_date->DAY = mb_substr($date, 8, 2, 'UTF-8');
        return $oj_date;
    }
    
    /**
     * Метод возвращает дату в unix формате строки даты вида из Y-m-d H:i:s
     * * */
    function get_unix_from_string_unix($date) {
        $date = trim($date);
        $convert_obj = $this->parse_string_unix_date2obj($date);
        //преобразуем в unix время
        return mktime($convert_obj->HOUR, $convert_obj->MIN, $convert_obj->SEC, $convert_obj->MONTH, $convert_obj->DAY, $convert_obj->YEAR);
    }

    /**
     * Метод возвращает массив из UNIX время - переданной строки формата "ГГГГ-мм-дд ЧЧ:мм:сс" - Y-m-d H:i:s
     * в объект.
     * * */
    function parse_string_unix_date2obj($date) {
        $oj_date = new stdClass();
        $date = trim($date);
        $oj_date->YEAR = mb_substr($date, 0, 4, 'UTF-8');
        $oj_date->MONTH = mb_substr($date, 5, 2, 'UTF-8');
        $oj_date->DAY = mb_substr($date, 8, 2, 'UTF-8');
        $oj_date->HOUR = mb_substr($date, 11, 2, 'UTF-8');
        $oj_date->MIN = mb_substr($date, 14, 2, 'UTF-8');
        $oj_date->SEC = mb_substr($date, 17, 2, 'UTF-8');
        return $oj_date;
    }
    
    /**
     * Метод преобразует UNIX дату время в "ГГГГ-мм-дд ЧЧ:мм:сс"
     * * */
    function get_convert_date_time_standart($date_time, $symb_date = "-", $symb_time = ":") {
        $str_date = "";
        $date_obj = $this->parse_string_unix_date2obj($date_time);
        $str_date .= $date_obj->YEAR . $symb_date;
        $str_date .= $date_obj->MONTH . $symb_date;
        $str_date .= $date_obj->DAY . ' ';
        $str_date .= $date_obj->HOUR . $symb_time;
        $str_date .= $date_obj->MIN . $symb_time;
        $str_date .= $date_obj->SEC;
        return $str_date;
    }
    
    /**
     * Метод преобразует UNIX дату время в "дд-мм-ГГГГ ЧЧ:мм:сс"
     * * */
    function get_re_convert_date_time_standart($date_time, $symb_date = "-", $symb_time = ":") {
        $str_date = "";
        $date_obj = $this->parse_string_unix_date2obj($date_time);
        $str_date .= $date_obj->DAY . $symb_date;
        $str_date .= $date_obj->MONTH . $symb_date;
        $str_date .= $date_obj->YEAR . ' ';
        $str_date .= $date_obj->HOUR . $symb_time;
        $str_date .= $date_obj->MIN . $symb_time;
        $str_date .= $date_obj->SEC;
        return $str_date;
    }
    
    /**
     * Метод преобразует строку типа dd-mm-yyyy в yyyy-mm-dd
     * * */
    function convert_date_standart($date, $symb = "-") {
        $str_date = "";
        $date_obj = $this->convert_date_standart_obj($date);
        $str_date .= $date_obj->YEAR . $symb;
        $str_date .= $date_obj->MONTH . $symb;
        $str_date .= $date_obj->DAY;
        return $str_date;
    }
    
    /**
     * Метод преобразует строку типа dd-mm-yyyy
     * и возвращает в виде объекта
     * * */
    function convert_date_standart_obj($date) {
        $oj_date = new stdClass();
        $date = trim($date);
        $oj_date->DAY = mb_substr($date, 0, 2, 'UTF-8');
        $oj_date->MONTH = mb_substr($date, 3, 2, 'UTF-8');
        $oj_date->YEAR = mb_substr($date, 6, 4, 'UTF-8');
        return $oj_date;
    }
    
    /**
     * Метод преобразует строку типа yyyy-mm-dd в dd-mm-yyyy
     * * */
    function re_convert_date_standart($date, $symb = "-") {
        $str_date = "";
        $date_obj = $this->re_convert_date_standart_obj($date);
        $str_date .= $date_obj->DAY . $symb;
        $str_date .= $date_obj->MONTH . $symb;
        $str_date .= $date_obj->YEAR;
        return $str_date;
    }
    
    /**
     * Метод преобразует строку типа yyyy-mm-dd
     * и возвращает в виде объекта
     * * */
    function re_convert_date_standart_obj($date) {
        $oj_date = new stdClass();
        $date = trim($date);
        $oj_date->YEAR = mb_substr($date, 0, 4, 'UTF-8');
        $oj_date->MONTH = mb_substr($date, 5, 2, 'UTF-8');
        $oj_date->DAY = mb_substr($date, 8, 2, 'UTF-8');
        return $oj_date;
    }
    
    /**
     * Возвращает сумму прописью
     * @author runcore
     * @uses morph(...)
     */
    function num2str($num) {
        $nul = 'ноль';
        $ten = array(
            array(
                '',
                'один',
                'два',
                'три',
                'четыре',
                'пять',
                'шесть',
                'семь',
                'восемь',
                'девять'),
            array(
                '',
                'одна',
                'две',
                'три',
                'четыре',
                'пять',
                'шесть',
                'семь',
                'восемь',
                'девять'),
        );
        $a20 = array(
            'десять',
            'одиннадцать',
            'двенадцать',
            'тринадцать',
            'четырнадцать',
            'пятнадцать',
            'шестнадцать',
            'семнадцать',
            'восемнадцать',
            'девятнадцать');
        $tens = array(
            2 => 'двадцать',
            'тридцать',
            'сорок',
            'пятьдесят',
            'шестьдесят',
            'семьдесят',
            'восемьдесят',
            'девяносто');
        $hundred = array(
            '',
            'сто',
            'двести',
            'триста',
            'четыреста',
            'пятьсот',
            'шестьсот',
            'семьсот',
            'восемьсот',
            'девятьсот');
        $unit = array(// Units
            array(
                'копейка',
                'копейки',
                'копеек',
                1),
            array(
                'рубль',
                'рубля',
                'рублей',
                0),
            array(
                'тысяча',
                'тысячи',
                'тысяч',
                1),
            array(
                'миллион',
                'миллиона',
                'миллионов',
                0),
            array(
                'миллиард',
                'милиарда',
                'миллиардов',
                0),
        );
        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                // by 3 symbols
                if (!intval($v))
                    continue;
                $uk = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega - logic
                $out[] = $hundred[$i1]; # 1xx - 9xx
                if ($i2 > 1)
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];# 20 - 99
                else
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];# 10 - 19 | 1 - 9
                // units without rub & kop
                if ($uk > 1)
                    $out[] = $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            } //foreach
        } else
            $out[] = $nul;
        $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }
    
    /**
     * Склоняем словоформу
     * @ author runcore
     */
    protected function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20)
            return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5)
            return $f2;
        if ($n == 1)
            return $f1;
        return $f5;
    }
    
    /**
     * Метод генерации случайных чисел от min до max
     * @min -   Минимальное значение для генерации случайного числа, по умолчанию 0
     * @max -   Максимальное значение для генерации случайного числа, по умолчанию 255
     * * */
    function rnd($min, $max) {
        $max = ($max) ? (int) $max : 255;
        $min = ($min) ? (int) $min : 0;
        return rand($min, $max);
    }
    
    // Метод генерации случайной строки символов
    /**
     * Метод генерации случайной строки символов
     * @length      -   Количество символов в генерированной строке
     * @chartypes   -   Набор символов, используепмых при генерации
     * Можно указывать каждый набор, через |, numerator:|:32|numeric|lowerEn|upperEn
     * или использовать ключевое слово all, чтобы применить все наборы символов
     * * */
    function random_string($length, $chartypes) {
        $chartypes_array = explode("|", (string) $chartypes);
        // задаем строки символов.
        //Здесь вы можете редактировать наборы символов при необходимости
        $symbols['lowerEn'] = 'abcdefghijklmnopqrstuvwxyz';
        $symbols['upperEn'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbols['lowerRu'] = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
        $symbols['upperRu'] = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
        $symbols['numeric'] = '1234567890'; // numbers
        $symbols['special'] = '~`!@#$%^&*()-_+?/\|,.<>='; //special characters
        $chars = "";
        $string = "";
        //print_r($chartypes_array);
        // определяем на основе полученных параметров,
        //из чего будет сгенерирована наша строка.
        if (in_array('all', $chartypes_array)) {
            $chars = $lowerEn . $upperEn . $lowerRu . $upperRu . $numbers . $special;
        } else {
            foreach ($chartypes_array as $key => $val) {
                //Если переданный набор символов существует
                if (in_array($val, $chartypes_array)) {
                    //То добавляем этот набор
                    $chars .= $symbols[trim($val)];
                }
            }
        }
        //return $chars;
        // длина строки с символами
        $chars_length = mb_strlen($chars, 'UTF-8') - 1;
        // генерируем нашу строку
        for ($i = 0; $i < (int) $length; $i++) {
            //извлекаем из строки $chars случайный символ
            $symb = $chars
                    {
                    rand(0, $chars_length)
                    };
            // создаем нашу строку,
            $string .= $symb;
        }
        // возвращаем результат
        return trim($string);
    }
    
    /**
     * Метод фозвращает строку с оттранслированными кирилическими символами
     * Транислитерация выполняется в соответствии с ГОСТ Р 52535.1-2006,
     * который устанавливает общие требования к заграничному паспорту гражданина Российской Федерации.
     *
     * @param string $str_rus
     * @return string
     */
    
    function translitor($str_rus) {
        $str_rus = ($str_rus) ? (string) $str_rus : (string) "";
        $A = array(
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "e",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "i",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "kh",
            "ц" => "tc",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shch",
            "ъ" => "",
            "ы" => "y",
            "ь" => "",
            "э" => "e",
            "ю" => "iu",
            "я" => "ia",
            "А" => "A",
            "Б" => "B",
            "В" => "V",
            "Г" => "G",
            "Д" => "D",
            "Е" => "E",
            "Ё" => "E",
            "Ж" => "ZH",
            "З" => "Z",
            "И" => "I",
            "Й" => "I",
            "К" => "K",
            "Л" => "L",
            "М" => "M",
            "Н" => "N",
            "О" => "O",
            "П" => "P",
            "Р" => "R",
            "С" => "S",
            "Т" => "T",
            "У" => "U",
            "Ф" => "F",
            "Х" => "KH",
            "Ц" => "TC",
            "Ч" => "CH",
            "Ш" => "SH",
            "Щ" => "SHCH",
            "Ъ" => "",
            "Ы" => "Y",
            "Ь" => "",
            "Э" => "E",
            "Ю" => "IU",
            "Я" => "IA");
        $res = strtr($str_rus, $A);
        return $res;
    }
    
    //Функция возвращает сторовое значение размера файла

    function humanFileSize($size) {
        $size = ($size) ? $size : 0;
        $size = str_replace(",", ".", $size);
        $minus = false; //Признак отрицательности значения
        if ($size < 0) {
            $minus = true;
        }
        //Тут мы получаем целые размеры файла, например: в файле всего 12456789 байт
        $_B = floor($size / (pow(1024, 0)));
        $KB = floor($size / (pow(1024, 1)));
        $MB = floor($size / (pow(1024, 2)));
        $GB = floor($size / (pow(1024, 3)));
        $TB = floor($size / (pow(1024, 4)));
        $PB = floor($size / (pow(1024, 5)));
        $EB = floor($size / (pow(1024, 6)));
        $ZB = floor($size / (pow(1024, 7)));
        $YB = floor($size / (pow(1024, 8)));
        //Тут получаем общий размер файла, например: в файле всего 15 МБ 230 КБ 140 Б
        $n_YB = $YB;
        $n_ZB = $ZB - $YB * 1024;
        $n_EB = $EB - $ZB * 1024;
        $n_PB = $PB - $EB * 1024;
        $n_TB = $TB - $PB * 1024;
        $n_GB = $GB - $TB * 1024;
        $n_MB = $MB - $GB * 1024;
        $n_KB = $KB - $MB * 1024;
        $n__B = $_B - $KB * 1024;
        //Собираем результирующую строку, если в каком либо разряде нет значения,
        //т.е. 0, то этот разряд не выводим
        $Result = ($n_YB ? $n_YB . "ЙБ. " : ''); //Йотабайт
        $Result = $Result . ($n_ZB ? $n_ZB . " ЗБ. " : ''); //Зеттабайт
        $Result = $Result . ($n_EB ? $n_EB . " ЭБ. " : ''); //Эксабайт
        $Result = $Result . ($n_PB ? $n_PB . " ПБ. " : ''); //Петабайт
        $Result = $Result . ($n_TB ? $n_TB . " ТБ. " : ''); //Терабайт
        $Result = $Result . ($n_GB ? $n_GB . " ГБ. " : ''); //Гигабайт
        $Result = $Result . ($n_MB ? $n_MB . " МБ. " : ''); //Мегабайт
        $Result = $Result . ($n_KB ? $n_KB . " КБ. " : ''); //Килобайт
        $Result = $Result . ($n__B ? $n__B . " Байт " : ''); //Байт
        if ($size) {
            //Если передан размер
            if ($minus == true) {
                //Если передано отрицательной значение
                return "-" . $Result;
            } else {
                //Если передано положительное значение
                return $Result;
            }
        } else {
            //Если размер не передан
            return '0 Байт';
        }
    }
    
    //Функция возвращает преобразованное значение размера из переданного ей

    function convertSize($val, $inType, $outType) {
        $val = ($val) ? $val : 0;
        $inType = ($inType) ? (string) $inType : "b";
        $outType = ($outType) ? (string) $outType : "b";
        $val = str_replace(",", ".", $val);
        $inT = 0; //Начальный порядковый номер единицы измерения
        $outT = 0; //Конечный порядковый номер единицы измерения
        //Задаем массив размерностей
        $A = array(
            "bit" => 9,
            "kbit" => 8,
            "mbit" => 7,
            "gbit" => 6,
            "tbit" => 5,
            "pbit" => 4,
            "ebit" => 3,
            "zbit" => 2,
            "ybit" => 1,
            "b" => 10,
            "kb" => 11,
            "mb" => 12,
            "gb" => 13,
            "tb" => 14,
            "pb" => 15,
            "eb" => 16,
            "zb" => 17,
            "yb" => 18);
        if ($A[strtolower($inType)] && $A[strtolower($outType)]) {
            //Если указанные единицы измерения существуют,
            //то назначаем им номера и выполняем расчет
            $inT = (int) ($A[strtolower($inType)]);
            $outT = (int) ($A[strtolower($outType)]);
            if ($inT < $outT) {
                //Если входящая единица меньше исходящей
                for ($i = $inT; $i < $outT; $i++) {
                    switch ($i):
                        case 1:
                            $val = (float) $val * 1000;
                            break;
                        case 2:
                            $val = (float) $val * 1000;
                            break;
                        case 3:
                            $val = (float) $val * 1000;
                            break;
                        case 4:
                            $val = (float) $val * 1000;
                            break;
                        case 5:
                            $val = (float) $val * 1000;
                            break;
                        case 6:
                            $val = (float) $val * 1000;
                            break;
                        case 7:
                            $val = (float) $val * 1000;
                            break;
                        case 8:
                            $val = (float) $val * 1000;
                            break;
                        case 9:
                            $val = (float) $val / 8;
                            break;
                        case 10:
                            $val = (float) $val / 1024;
                            break;
                        case 11:
                            $val = (float) $val / 1024;
                            break;
                        case 12:
                            $val = (float) $val / 1024;
                            break;
                        case 13:
                            $val = (float) $val / 1024;
                            break;
                        case 14:
                            $val = (float) $val / 1024;
                            break;
                        case 15:
                            $val = (float) $val / 1024;
                            break;
                        case 16:
                            $val = (float) $val / 1024;
                            break;
                        case 17:
                            $val = (float) $val / 1024;
                            break;
                    endswitch;
                }
            }
            elseif ($inT > $outT) {
                for ($i = $inT; $i > $outT; $i--) {
                    switch ($i):
                        case 2:
                            $val = (float) $val / 1000;
                            break;
                        case 3:
                            $val = (float) $val / 1000;
                            break;
                        case 4:
                            $val = (float) $val / 1000;
                            break;
                        case 5:
                            $val = (float) $val / 1000;
                            break;
                        case 6:
                            $val = (float) $val / 1000;
                            break;
                        case 7:
                            $val = (float) $val / 1000;
                            break;
                        case 8:
                            $val = (float) $val / 1000;
                            break;
                        case 9:
                            $val = (float) $val / 1000;
                            break;
                        case 10:
                            $val = (float) $val * 8;
                            break;
                        case 11:
                            $val = (float) $val * 1024;
                            break;
                        case 12:
                            $val = (float) $val * 1024;
                            break;
                        case 13:
                            $val = (float) $val * 1024;
                            break;
                        case 14:
                            $val = (float) $val * 1024;
                            break;
                        case 15:
                            $val = (float) $val * 1024;
                            break;
                        case 16:
                            $val = (float) $val * 1024;
                            break;
                        case 17:
                            $val = (float) $val * 1024;
                            break;
                        case 18:
                            $val = (float) $val * 1024;
                            break;
                    endswitch;
                }
            }//Если единицы измерения одинаковы, то оставляем ее без изменений
        }
        return $val;
    }

    //Функция вовзращает преобразованное размеров из одной единицы в другую
    //и в случае необходимости переводит результат в читабелный вид

    function ConvSize($val, $inType, $outType, $human) {
        $val = ($val) ? $val : 0;
        $inType = ($inType) ? (string) $inType : "b";
        $outType = ($outType) ? (string) $outType : "b";
        $human = ($human) ? (bool) $human : false;
        $val = str_replace(",", ".", $val);
        if (!$human) {
            return $this->convertSize($val, $inType, $outType);
        } else {
            return $this->humanFileSize($this->convertSize($val, $inType, "b"));
        }
    }

    /**
     * Метод преобразует первый символ каждого слова к верхнему регистру, а остальные к нижнему
     * * */
    public function mb_ucwords($str, $charset = 'UTF-8') {
        $word = explode(' ', $str);
        for ($i = 0; $i < count($word); $i++) {
            //Заменяем первый символ заглавной буквой, а остальные делаем маленькими
            $word[$i] = mb_strtoupper(mb_substr($word[$i], 0, 1, 'UTF-8'), 'UTF-8') . mb_strtolower(mb_substr($word[$i], 1, null, 'UTF-8'), 'UTF-8');
        }
        return implode(' ', $word);
    }

}

?>