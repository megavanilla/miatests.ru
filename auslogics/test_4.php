<?php
session_start();

class EmailWork
{
    /**
     * Параметры для подключения к БД
     */
    const DB_HOST = 'localhost';
    const DB_USER = '045686016_test';
    const DB_PASS = '8tju257t_tNd';
    const DB_NAME = 'megavanilla_auslogics';
    /**
     * Параметры для работы шифрования openssl
     */
    const CHIPHER_TYPE = 'AES-128-CBC';
    private static $iv;
    private static $separator  = ':';
    /**
     * Эти глобальные переменные нужны, для того,
     * чтобы на форме можно было отражать статус действий пользователя,
     * а также оставлять корректно заполненые поля.
     */
    private static $status = '';
    private static $add_email = '';
    private static $add_phone = '';
    private static $ret_email = '';

    /**
     * Конструктор закрыт
     */
    private function __construct()
    {
    }

    /**
     * Клонирование запрещено
     */
    private function __clone()
    {
    }

    private static function genIv()
    {
        if (in_array(self::CHIPHER_TYPE, openssl_get_cipher_methods())) {
            self::$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CHIPHER_TYPE));
        }
    }

    /**
     * Метод шифрует строку
     * @param string $key
     * @param string $text
     * @return array|string
     */
    private static function encrypt($key, $text)
    {
        return openssl_encrypt($text, 'aes128', $key, 0, self::$iv);
    }

    /**
     * Метод дешифрует строку
     * @param string $key
     * @param string $text
     * @param string $iv
     * @return array|string
     */
    private static function decrypt($key, $text, $iv)
    {
        return openssl_decrypt($text, 'aes128', $key, 0, $iv);
    }

    private static function getConnectDb()
    {
        $db = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        if ($db->connect_errno) {
            return false;
        }
        return $db;
    }

    /**
     * @return string
     */
    public static function getStatus(): string
    {
        return self::$status;
    }

    /**
     * @return string
     */
    public static function getAddEmail(): string
    {
        return self::$add_email;
    }

    /**
     * @return string
     */
    public static function getAddPhone(): string
    {
        return self::$add_phone;
    }

    /**
     * @return string
     */
    public static function getRetEmail(): string
    {
        return self::$ret_email;
    }

    public static function addContact()
    {
        self::genIv();

        //Отфильтруем данные
        /**
         * Проверку телефона выполним так:
         * 1) Уберём все символы кроме цифр
         * 2) Проверим количество символов, полагаю для сотовых достаточно будет от 11 до 15
         */
        self::$add_phone = filter_input(INPUT_POST, 'add_phone',
                FILTER_SANITIZE_NUMBER_INT); //Такой подход оставляет плюсы и минусы, тем не менее, если переменная не передана, то вместо ошибки будет FALSE
        self::$add_phone = preg_replace('/[^0-9]/', '', $_POST['add_phone']); //Оставляем только цифры
        self::$add_email = filter_input(INPUT_POST, 'add_email', FILTER_SANITIZE_EMAIL);

        //Проверим данные
        $add_email = filter_var(self::$add_email, FILTER_VALIDATE_EMAIL);
        $add_phone = (strlen(self::$add_phone) >= 11 && strlen(self::$add_phone) <= 15) ? self::$add_phone : false;

        //Если данные введены корректно
        if (self::$add_email !== false && self::$add_phone !== false) {
            $db = self::getConnectDb();

            //Шифруем данные, ключом применяется email, для уникальности каждого пльзователя.
            $add_email_crypt = self::encrypt(self::$add_email, self::$add_email);
            $add_phone_crypt = self::encrypt(self::$add_email, self::$add_phone);

            //Записываем шифрованные данные
            $sql = "INSERT INTO email_phone (email,phone,iv) VALUES ('$add_email_crypt','$add_phone_crypt','" . self::$iv . "') ON DUPLICATE KEY UPDATE phone='$add_phone_crypt'";
            $stat_query = $db->query($sql);
            $db->close();//Закрываем соединение
            if ($stat_query) {
                self::$status = 'Запись успешна выполнена.';
            } else {
                self::$status = 'Не удалось сохранить изменения.' . $sql;
            }
        } else {
            self::$status = (!$add_email) ? 'Не корректно указан почтовый адрес в форме добавления.<br />' : '';
            self::$status .= (!$add_phone) ? 'Не корректно указан номер телефона в форме добавления.' : '';
        }
    }

    public static function retrieveContact()
    {
        //Статус поиска данных
        $stat_find = false;

        //Отфильтруем данные
        self::$ret_email = filter_input(INPUT_POST, 'ret_email', FILTER_SANITIZE_EMAIL);

        //Проверим данные
        self::$ret_email = filter_var(self::$ret_email, FILTER_VALIDATE_EMAIL);

        //Если данные введены корректно
        if (self::$ret_email !== false) {
            $db = self::getConnectDb();

            /**
             * Ищем данные, для этого:
             * Извлекаем данные частями по 10 строк
             */
            //Получаем количество записей в БД
            $stat_query = $db->query('SELECT COUNT(*) FROM email_phone');
            $count_table = $stat_query->num_rows;

            for ($i = 0; $i < $count_table; $i++) {
                /**
                 * Получаем по одной записи,
                 * в случае большого объема данных есть вероятность,
                 * что мы закончим работу раньше, чем дойдём до конца строк.
                 * Обычной выборкой не получается, т.к. данные в БД зашифрованы,
                 * и их следует расшифровать прежде, чем проверять.
                 */
                $stat_query = $db->query("SELECT email, phone, iv FROM email_phone LIMIT $i,1");
                $row = $stat_query->fetch_assoc();
                if (!empty($row['email']) && !empty($row['phone']) && !empty($row['iv'])) {
                    //Попробуем расшифровать email
                    $decrypt_email = self::decrypt(self::$ret_email, $row['email'], $row['iv']);

                    //Проверяем совпадение введённого занчения с зашифрованным
                    if (self::$ret_email == $decrypt_email) {
                        //Дешифруем номер телефона
                        $decrypt_phone = self::decrypt(self::$ret_email, $row['phone'], $row['iv']);

                        $to = self::$ret_email;
                        $subject = 'Phone retrieve';
                        $message = "Your phone number: {$decrypt_phone}";
                        $headers = 'From: admin@localhost.ru' . "\r\n" .
                                'Reply-To: admin@localhost.ru' . "\r\n" .
                                'X-Mailer: PHP/' . phpversion();

                        $status_mail = mail($to, $subject, $message, $headers);

                        if ($status_mail) {
                            self::$status = 'Ваш номер телефона успешно отправлен на Ваш электронный адрес.';
                        } else {
                            self::$status = 'Не удалось отправить письмо на Ваш электронный адрес.';
                        }
                        $stat_find = true;
                        break;//Выходим из цикла, если удалось найти запись
                    } else {
                        //Если не совпали данные, то смотрим следующую строку
                        continue;
                    }
                } else {
                    //Если полей нет, то выйдем цикла
                    self::$status = 'Не удалось получить данные.';
                    break;//Выходим из цикла, если не нашли нужных полей и таблице
                }
            }
            //Если полей нет, то выйдем цикла
            self::$status = ($stat_find == false) ? 'Не удалось найти Ваш электронный адрес.' : self::$status;

            $db->close();//Закрываем соединение
        } else {
            self::$status = (!self::$ret_email) ? 'Не корректно указан почтовый адрес в форме восстановления.' : '';
        }
    }
}

//Отфильтруем данные
$operation = filter_input(INPUT_POST, 'operation', FILTER_SANITIZE_STRING);

/**
 * С помощью токена избавляемся от csrf атак.
 * Операцию добавления или изменения,
 * выполняем только в случае успешной проверки токена.
 * Если не выполнялась никакой операции то генерируем/обновляем токен.
 */
switch ($operation) {
    case 'add':
        EmailWork::addContact();
        break;
    case 'retrieve':
        EmailWork::retrieveContact();
        break;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Тестовое задание</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<style>
    body {
        width: 750px;
        margin: 0 auto;
    }

    fieldset {
        vertical-align: top;
        height: 300px;
        width: 250px;
        display: inline-block;
        margin: 15px 10px;
    }

    fieldset legend {
        color: #4090FF;
    }

    fieldset p {
        font-weight: bold;
        width: 200px;
    }

    fieldset form input[type="text"] {
        height: 20px;
        width: 220px;
        border: 1px solid #4090FF;
    }

    .p_note {
        width: 225px;
        font-weight: normal;
        float: right;
    }
</style>
<body>
<fieldset>
    <legend>Add your phone number</legend>
    <p>Option 1. Add your phone number</p>
    <form method="POST">
        <label for="add_phone">Enter your PHONE:</label><br/>
        <input type="text" id="add_phone" name="add_phone" value="<?php print(EmailWork::getAddPhone()); ?>"/>
        <br/>
        <br/>
        <label for="add_email">Enter your e-mail*:</label><br/>
        <input type="text" id="add_email" name="add_email" value="<?php print(EmailWork::getAddEmail()); ?>"/>
        <p class="p_note">You will be able to retrieve your phone number later on using your e-mail.</p>
        <input type="hidden" name="operation" value="add"/>
        <input type="submit"/>
    </form>

</fieldset>
<fieldset>
    <legend>Retrieve your phone number</legend>
    <p>Option 2. Retrieve your phone number</p>
    <form method="POST">
        <label for="ret_email">Enter your e-mail*:</label><br/>
        <input type="text" id="ret_email" name="ret_email" value="<?php print(EmailWork::getRetEmail()); ?>"/>
        <p class="p_note">The phone number will be e-mailed to you.</p>
        <input type="hidden" name="operation" value="retrieve"/>
        <input type="submit"/>
    </form>
</fieldset>
<div id="status">
    <?php print(EmailWork::getStatus()); ?>
</div>
</body>
</html>
