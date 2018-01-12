<?php

set_time_limit(240);

function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // молчим, если ошибку подавили оператором @
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('handleError');

/**
 * Если не работает, читаем доку: http://php.net/manual/ru/function.set-time-limit.php,
 * Адекватно, но долго считает 500.000.000 - 500 миллиардов.
 **/
class CalculateArray
{

    //Предустановленные параметры
    const MAX_POSSIBLE_MIN_VALUE = -1000000;
    const MAX_POSSIBLE_MAX_VALUE = 1000000;
    const MAX_POSSIBLE_COUNT_NUMBERS = 500000000;// - Поставим лим на 10 миллиардов, 2097152; 2147483647 - максимально возможной для php x64, остальное режет до этого значения

    //Входные параметры
    private $min_value;
    private $max_value;
    private $count_numbers;

    //Внутренние параметры
    private $existError = false;
    private $arr_values = [];
    private $summ_square_values = 0;
    private $numbers_repetiotions = [];
    private $middle_left_val = 0;
    private $middle_right_val = 0;

    //Параметры расчёта
    private $summ_arr_values = 0;

    //Выходные параметры
    private $average = null;
    private $median = null;
    private $modes = null;
    private $deviation = null;

    private $arr_tests = [];

    public function __construct($min_value, $max_value, $count_numbers)
    {
        $this->min_value = (int)$this->limitedIntvalue($min_value, self::MAX_POSSIBLE_MIN_VALUE, true);
        $this->max_value = (int)$this->limitedIntvalue($max_value, self::MAX_POSSIBLE_MAX_VALUE);
        $this->count_numbers = (int)$this->limitedIntvalue($count_numbers, self::MAX_POSSIBLE_COUNT_NUMBERS);

        //Поставим ограничение на число
        $this->existError = ($this->errorMaxCountNumbers()) ? true : $this->existError;//На случай нескольких проверок

        if (!$this->existError) {
            $this->startTest('all');

            //Сделаем сразу расчёт всего необходимого для теста


            $this->startTest('generateRandomSelection');
            /*$this->arr_values = *//*[5,10,15,20,25];*/
            $this->generateRandomSelection();
            $this->stopTest('generateRandomSelection');


            $this->startTest('calcAverageDeviation');
            $this->calcAverageDeviation();
            $this->stopTest('calcAverageDeviation');

            $this->startTest('calcMedian');
            $this->calcMedian();
            $this->stopTest('calcMedian');

            $this->startTest('calcModes');
            $this->calcModes();
            $this->stopTest('calcModes');

            $this->startTest('calcStandardDeviation');
            $this->calcStandardDeviation();
            $this->stopTest('calcStandardDeviation');

            $this->stopTest('all');
        }

    }

    /**
     * Фильтры
     */

    /**
     * Обрезает значение до максимально разрешённого
     * @param $int int
     * @param $int_max int
     * @param $invert boolean
     * @return int
     */
    private function limitedIntvalue($int, $int_max, $invert = false)
    {
        if (!$invert) {
            return ($int > $int_max) ? $int_max : $int;
        } else {
            return ($int < $int_max) ? $int_max : $int;
        }
    }

    /**
     * Для замера производительности
     */
    /**
     * @param bool $time
     * @return int|mixed
     */
    private function getTime($time = false)
    {
        return ($time === false) ? microtime(true) : microtime(true) - $time;
    }

    private function getMemory($memory = false)
    {
        return $memory === false ? memory_get_usage() : memory_get_usage() - $memory;
    }

    private function convertMemory($size)
    {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = (int)floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    private function startTest($name = 'all')
    {
        $this->arr_tests[$name]['time']['start'] = (int)$this->getTime();
        $this->arr_tests[$name]['memory']['start'] = $this->getMemory();
    }

    private function stopTest($name = 'all')
    {
        if (
                array_key_exists($name, $this->arr_tests)
                && array_key_exists('time', $this->arr_tests[$name])
                && array_key_exists('start', $this->arr_tests[$name]['time'])
        ) {
            $this->arr_tests[$name]['time'] = round($this->getTime($this->arr_tests[$name]['time']['start']),
                            4) . ' sec';
        }
        if (
                array_key_exists($name, $this->arr_tests)
                && array_key_exists('memory', $this->arr_tests[$name])
                && array_key_exists('start', $this->arr_tests[$name]['memory'])
        ) {
            $this->arr_tests[$name]['memory'] = $this->convertMemory($this->getMemory($this->arr_tests[$name]['memory']['start']));
        }
    }

    /**
     * Обработка ошибок
     */

    private function errorMaxCountNumbers()
    {
        return ($this->count_numbers > self::MAX_POSSIBLE_COUNT_NUMBERS);
    }

    public function isExistsError()
    {
        return $this->existError;
    }


    /**
     * Расчёты
     */

    /**
     * Генерация ряда случайных чисел
     */
    private function generateRandomSelection()
    {
        if ($this->min_value > $this->max_value) {
            return;
        }
        for ($i = 0; $i < $this->count_numbers; $i++) {
            $rnd = rand($this->min_value, $this->max_value);

            //Предварительнаяподготовка длярасчётов медианы
            if ($this->count_numbers % 2 == 0) {
                $middle_left = $this->count_numbers / 2 - 1;
                if ($i === ($middle_left)) {
                    $this->middle_left_val = $rnd;
                } elseif ($i === ($middle_left + 1)) {
                    $this->middle_right_val = $rnd;
                }
            } else {
                if ($i === ((int)floor($this->count_numbers / 2))) {
                    $this->middle_left_val = $rnd;
                }
            }

            if ($i <= 10) {
                $this->arr_values[] = $rnd;
            }
            if ($this->count_numbers > 10 && $i === ($this->count_numbers - 1)) {
                $this->arr_values[] = $rnd;
            }


            //Выполняем дополнительные операции
            $this->operation((int)$rnd);

        }
    }

    private function operation($int)
    {
        //Считаем общую сумму
        $this->summ_arr_values += $int;
        //Считаем сумму квдаратов
        $this->summ_square_values += pow($int, 2);

        //Группируем повторения - чтобы не юзать array_key_exists, который перебирает каждый раз массив
        /*
         * Так каждый раз нарастающий массив будет перепроверяться, избавимсяот этого кодом  ниже.
        if (array_key_exists($int, $this->numbers_repetiotions)) {
            $this->numbers_repetiotions[$int] = $this->numbers_repetiotions[$int] + 1;
        } else {
            $this->numbers_repetiotions[$int] = 1;
        }
        */

        try {
            $this->numbers_repetiotions[$int]++;
        } catch (Exception $e) {
            $this->numbers_repetiotions[$int] = 1;
        } catch (Error $e) {
            print($e->getMessage());
            $this->numbers_repetiotions[$int] = 1;
        }

    }

    /**
     * Расчёт медианы
     */
    private function calcMedian()
    {
        //В зависимости от того, чётное ли количество чисел в ряду или нет, выполняем расчёт

        if ($this->count_numbers % 2 == 0) {
            $this->median = round(($this->middle_left_val + $this->middle_right_val) / 2, 2);
        } else {
            $this->median = $this->middle_left_val;
        }
    }

    /**
     * Расчёт моды/мод
     */
    public function calcModes()
    {
        //Получим количество повторений для каждого элемента в массиве
        $count_values = $this->numbers_repetiotions;
        //Если количество значений в повторениях равно количетсву элементов сгенерированного ряда,
        //то моды отсутствуют
        if (count($count_values) === $this->count_numbers) {
            return;
        }
        //Отсортируемв обратном порядке, сохранив ключи
        arsort($count_values);

        //Количество модов
        $count_modes_slice = array_slice(array_count_values($count_values), 0, 1, true);

        $modes = [];
        $max_val_from_array = array_search(max($count_modes_slice), $count_modes_slice);
        foreach ($count_values as $key => $val) {
            if ($val == $max_val_from_array) {
                $modes[] = $key;
            }
        }
        $this->modes = array_values($modes);
    }

    /**
     * Расчёт среднего значения
     */
    public function calcAverageDeviation()
    {
        $this->average = round($this->summ_arr_values / $this->count_numbers, 4);
    }

    /**
     * Расчёт стандартного отклонения
     */
    public function calcStandardDeviation()
    {
        /**
         * Формула преобразована, для оптимальной работы,
         * и будет приложена фотка формулы, или в маткаде наберу
         */
        $rate_y = $this->count_numbers * (pow($this->average, 2));
        $summ_square = $rate_y - $this->summ_square_values;

        $this->deviation = round(sqrt(abs($summ_square / ($this->count_numbers - 1))), 4);
    }


    /**
     * Геттеры
     */
    /**
     * @return array
     */
    public function getArrValues(): array
    {
        return $this->arr_values;
    }

    /**
     * @return null|float
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * @return null|float|int
     */
    public function getMedian()
    {
        return $this->median;
    }

    /**
     * @return null|array
     */
    public function getModes()
    {
        return $this->modes;
    }

    /**
     * @return null|float
     */
    public function getDeviation()
    {
        return $this->deviation;
    }

    public function getArrTests()
    {
        return $this->arr_tests;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
                'min_value' => $this->min_value,
                'max_value' => $this->max_value,
                'count_numbers' => $this->count_numbers,
                'max_count_numbs' => self::MAX_POSSIBLE_COUNT_NUMBERS,
                'arr_tests' => $this->getArrTests()
        ];
    }
}

$min_value = filter_input(INPUT_POST, 'min_value', FILTER_SANITIZE_NUMBER_INT);

$max_value = filter_input(INPUT_POST, 'max_value', FILTER_SANITIZE_NUMBER_INT);

$count_numbers = filter_input(INPUT_POST, 'count_numbers', FILTER_SANITIZE_NUMBER_INT);

$calc = new CalculateArray($min_value, $max_value, $count_numbers);

$res = [
        'status' => ((!$calc->isExistsError()) ? 'ok' : 'error'),
        'ranks' => $calc->getArrValues(),
        'average' => $calc->getAverage(),
        'median' => $calc->getMedian(),
        'mode' => $calc->getModes(),
        'deviation' => $calc->getDeviation(),
        'params' => $calc->getParams()
];

print(json_encode(['res' => $res], JSON_UNESCAPED_UNICODE));