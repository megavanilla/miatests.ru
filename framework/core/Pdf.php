<?php
if (!defined('READFILE'))
{
    exit("Не правильный вызов файла.".$_SERVER['SCRIPT_FILENAME']."<a href=\"/\">Вернуться на главную</a>.");
}
/**
* Класс - работы с pdf документами
 * Внимание - класс нужно переработать
* **/
class SC_Pdf
{
    function __construct()
    {
        //parent::__construct();
    } //End __construct
    /**
    * Метод выполняет создание pdf файла,
    * исходя из переданных ему параметров.
    * Помимо этого он подписывает документ сертификатом сайта
    * @param file_name                 string - Имя файла, куда будет сохранятся pdf документ
    * @param text_html                 string - HTML текст, который будет преобразован в страницу pdf
    * @param set_creator               string - Создатеь документа
    * @param set_author                string - Автор документа
    * @param set_subject               string - Заголовок документа
    * @param set_title                 string - Название документа
    * @param set_keywords              string - Ключевые слова документа
    * @param text_qr_code              string - QR_code текст документа, устанавливается в левом верхнем углу
    * @param sign_sign_protected       boolean - Если TRUE, то документ подписывается сертификатом сайта, и защищается от всех изменений
    * @return return_object
    * return_object->error - текст ошибки
    * return_object->file_name - ссылка на созданный файл
    * **/
    public function create_document($file_name, $text_html, $set_creator = "", $set_author = "", $set_subject = "", $set_title = "", $set_keywords = "", $text_qr_code = null)
    {
        /*Возвращаемый объект*/
        $return_object = new stdClass();
        $return_object->error = null;
        $return_object->file_name = null;
        /**
        * Устанавливаем путь к QR кода в NULL
        */
        $img_qr_code          = "";
        $img_qr_code_creditor = "";
        $img_qr_code_borrower = "";
        $file_name_qr_code    = null;
        $file_name_qr_code_creditor = null;
        $file_name_qr_code_borrower = null;
        /**
        * Проверяем существование пути к сохраняемому файлу,
        * если пути нет, и неудалось создать каталог,
        * то возвращаем ошибку.
        */
        $path_parts = pathinfo($file_name);
        if (file_exists($path_parts['dirname']) == true || mkdir($path_parts['dirname']) == true) {
            /*Имя сохраняемого файла*/
            $file_name_in = $path_parts['basename'];
            /*Каталог сохраняемого файла*/
            $path_name_in = $path_parts['dirname'];
            /**
            * Если передан текст для QR_code
            */
            if ($text_qr_code != null && is_string($text_qr_code)) {
                /*Генерируем случайное имя файла*/
                $file_name_qr_code = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                /**
                * Если имя файла с путем уже существует, то генерируем новое
                */
                while (file_exists(NEWFC_DIR_ROOT . "system/documents/" . $file_name_qr_code) == true) {
                    /*Генерируем случайное имя файла*/
                    $file_name_qr_code = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                }
                $this->qr_code->png($text_qr_code, NEWFC_DIR_ROOT . "system/documents/" . $file_name_qr_code, "L", 3, 1);
                /*Получаем текс ссылки на QR_code картинку*/
                $img_qr_code = "<img style=\"text-align:left;\" src=\"" . NEWFC_DIR_ROOT . "system/documents/" . $file_name_qr_code . "\" alt=\"QR код.\" height=\"65\" width=\"65\" />";
            }
            /**
            * Генерируем PDF файл
            * **/
            //Поключаем библиотеку работы с pdf
            if (is_file(NEWFC_DIR_CLASSES . 'tcpdf/tcpdf.php') === true) {
                require_once (NEWFC_DIR_CLASSES . 'tcpdf/tcpdf.php');
            }
            else
            {
                die("\n<br />Не удалось подключить библиотеку: \"TCPDF\"\n<br />");
            }
            $tcpdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, false);
            // выключаем заголовки, т.к. они нам не нужны
            $tcpdf->setPrintHeader(false);
            $tcpdf->setPrintFooter(false);
            // устанавливаем описание документа
            $tcpdf->SetCreator($set_creator);
            $tcpdf->SetAuthor($set_author);
            $tcpdf->SetSubject($set_subject);
            $tcpdf->SetTitle($set_title);
            $tcpdf->SetKeywords($set_keywords);
            # Указываем метаданные документа
            $tcpdf->SetCreator(PDF_CREATOR);
            $tcpdf->SetAuthor('Chris Herborth (chrish@pobox.com)');
            $tcpdf->SetTitle('Invoice for ');
            $tcpdf->SetSubject("A simple invoice example for 'Creating PDFs on the fly with TCPDF' on IBM's developerWorks");
            $tcpdf->SetKeywords('PHP, sample, invoice, PDF, TCPDF');
            // устанавливаем поля
            $tcpdf->SetMargins(10, 10, 10, true);
            // автоперенос на новую страницу
            $tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            // пропорционирование картинок
            $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            // языковые настройки
            $tcpdf->setLanguageArray("rus");
            // Устанавливаем шрифт
            $tcpdf->setFontSubsetting(true);
            $tcpdf->SetFont('dejavusans', '', 9, '', '', true);
            $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            // Вывод данных из HTML в PDF
            $tcpdf->AddPage();
            //Пишем HTML страницу
            $tcpdf->writeHTML($text_html, true, true, true, false, '');
            //Вид защиты документа
            //$tcpdf->SetProtection(array('modify', 'copy'), '', null, 0, null);
            //Получаем количество страниц в документе
            $page_cnt = $tcpdf->PageNo();
            //Сохраняем
            $tcpdf->Output($file_name, 'F');
            $return_object->file_name = $file_name_in;
            /*После работы метода, удаляем все временные файлы*/
            if ($file_name_qr_code != null && file_exists(NEWFC_DIR_ROOT . "system/documents/" . $file_name_qr_code)) {
                unlink(NEWFC_DIR_ROOT . "system/documents/" . $file_name_qr_code);
            }
            if (!is_file($file_name)) {
                return "Не удалось создать документ";
            }
        }
        else
        {
            $return_object->error = "Указанная директория не существует, и нет возможности ее создать.";
        }
        return $return_object;
    } //End create_document
    /**
    * Метод возвращает уникальный сгенерированный ключ для поля
    * @table_name  -   название таблицы
    * @field       -   поле таблицы, для которого генерируется значение
    * @Key         -   сгенерированный ключ
    * @length      -   общее количество вариантов
    * @chartypes   -   возможные символы
    * **/
    function gen_auto_increment($table_name, $field, $Key, $length, $chartypes)
    {
        $file_name = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
        /**
        * Если имя файла с путем уже существует, то генерируем новое
        */
        while (file_exists($file_name) == true) {
        }
        $this->filterdata->filter($table_name, 'string');
        $this->filterdata->filter($field, 'string');
        $this->filterdata->filter($Key, 'string');
        $this->filterdata->filter($length, 'int');
        $this->filterdata->filter($chartypes, 'string');
        $chartypes_array = explode("|", $chartypes);
        $maxIteration    = 0;
        for ($i = 0; $i < count($chartypes_array); $i++) {
            //Получаем максимальное количество возможных вероятностей
            //print("char = $chartypes_array[$i] < br />");
            $maxIter = 0;
            switch ($chartypes_array[$i]) {
                case "lowerEn":
                $maxIter = pow(26, $length);
                break;
                case "upperEn":
                $maxIter = pow(26, $length);
                break;
                case "lowerRu":
                $maxIter = pow(33, $length);
                break;
                case "upperRu":
                $maxIter = pow(33, $length);
                break;
                case "numeric":
                $maxIter = pow(10, $length);
                break;
                case "special":
                $maxIter = pow(24, $length);
                break;
                case "all":
                $maxIter = pow(80, $length);
                break;
            }
            $maxIteration = $maxIteration + $maxIter;
        } //End for
        //print("maxIteration = " . $maxIteration . " < br />");
        /**
        * Если это уникальное значение, то добавляем его в массив
        * использованных значений.
        * В противном случае: Если еще не все возможные варианты перебраны,
        * то рекурсвино ищем следующий возможный.
        * Иначе возвращаем NULL
        * **/
        //print("Количество строк в таблице : ". $this->cnt_table($table_name) ." < br />");
        if ($this->count($table_name) < $maxIteration) {
            //Генерируем новое значение
            $increment_new = $this->random_string($length, $chartypes);
            //print("Текущее сгенерированное значение : ". $increment_new ." < br />");
            //print("Наличие совпадений в таблице: ". $this->dublicate_bill($table_name, $Key, $increment_new) ." < br />");
            if ($increment_new != null) {
                $this->where($field, '=', $increment_new, "string");
                if ($this->dublicate($table_name) == true) {
                    $increment_new = $this->gen_auto_increment($table_name, $field, $Key, $length, $chartypes);
                }
            }
            return $increment_new;
        }
        else
        {
            return null;
        }
    } //End gen_auto_increment
    /**
    * Методы выполняют отправку претензий и исков от кредитора заемщику
    * **/
    public function send_pret($data)
    {
        $bill = $this->session->get_bill();
        $ai   = (property_exists($data, "ai")) ? $data->ai : null;
        if ($bill != "no_sess") {
            if ($ai != null) {
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->where('Bill_Creditor', '=', $bill, 'string');
                $this->db->limit(0, 1);
                $data_select = $this->db->select('treatments');
                if (is_array($data_select) && array_key_exists(0, $data_select) && !empty($data_select[0])) {
                    //Получаем данные о заемщике
                    $vis_data_cred = $this->session->get_data_visitor_from_bill($data_select[0]['Bill_Creditor']);
                    $vis_data_borr = $this->session->get_data_visitor_from_bill($data_select[0]['Bill_Borrower']);
                    $subject       = "Претензия по договору #$ai";
                    $msg           = "Вам пришла претензия по договору #$ai";
                    //Генерируем претензию
                    $pret          = $this->gen_doc_pret($ai);

                    if ($vis_data_cred != null && $vis_data_borr != null && $subject != null && $msg != null) {
                        //Обновляем запись о признаке претензии
                        $this->db->where('ai', '=', $ai, 'string');
                        $this->db->where('Bill_Creditor', '=', $bill, 'string');
                        $arr = array('Claim'=> 1);
                        if ($this->db->update("treatment", $arr) == true) {
                            $this->sms->sendFull((object)array('to'     => $vis_data_borr->tel,'message'=> $msg));
                            /**
                            * Отправка сообщений/я
                            * **/
                            $data = $this->email->Send_Def_Param($vis_data_borr->email, null, $subject, $msg, $vis_data_borr->email, array(0 => array('file_name'        => $pret->href_tmp_file,'new_name_filename'=> "pret_{$ai}.pdf")), 1, "documents_treatment_{$ai}" . date('j_m_Y_h_i_s'));
                        }
                        return "ok";
                    }
                    else
                    {
                        return "Не удалось получить сведения о посетителе";
                    }
                    if (!empty($pret->href_tmp_file) && is_file($pret->href_tmp_file)) {
                        return $pret->href_tmp_file;
                    }
                    else
                    {
                        return "Не удалось создать претензию";
                    }
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End send_pret
    public function send_isk($data)
    {
        $bill = $this->session->get_bill();
        $ai   = (property_exists($data, "ai")) ? $data->ai : null;
        if ($bill != "no_sess") {
            if ($ai != null) {
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->where('Bill_Creditor', '=', $bill, 'string');
                $this->db->limit(0, 1);
                $data_select = $this->db->select('treatments');
                if (is_array($data_select) && array_key_exists(0, $data_select) && !empty($data_select[0])) {
                    //Получаем данные о заемщике
                    $vis_data_cred = $this->session->get_data_visitor_from_bill($data_select[0]['Bill_Creditor']);
                    $vis_data_borr = $this->session->get_data_visitor_from_bill($data_select[0]['Bill_Borrower']);
                    $subject       = "Сформирован иск по договору #$ai. Ответчик: " . $vis_data_cred->surname . ' ' . $vis_data_cred->name . ' ' . $vis_data_cred->middle_name;
                    $msg           = "Сформирован иск по договору #$ai. Ответчик: " . $vis_data_cred->surname . ' ' . $vis_data_cred->name . ' ' . $vis_data_cred->middle_name;
                    //Генерируем чистый шаблон иска
                    //$isk_clear = $this->gen_doc_isk_clear($ai);
                    //Генерируем иск
                    $isk           = $this->gen_doc_isk($ai);
                    $fileName      = null;
                    //Создаем zip архив с документами
                    /*
                    $zip = new ZipArchive();
                    //имя файла архива
                    $fileName = NEWFC_DIR_TREATMENTS."documents_treatment_{$ai}".date('j_m_Y_h_i_s').".zip";

                    if ($zip->open($fileName, ZIPARCHIVE::CREATE) === true) {
                    if(!empty($isk->href_tmp_file) && is_file($isk->href_tmp_file)){
                    $zip->addFile($isk->href_tmp_file, pathinfo($isk->href_tmp_file, PATHINFO_BASENAME));
                    }
                    }
                    //закрываем архив
                    $zip->close();
                    */
                    if ($vis_data_borr != null && $subject != null && $msg != null) {
                        //Обновляем запись о признаке претензии
                        $this->db->where('ai', '=', $ai, 'string');
                        $this->db->where('Bill_Creditor', '=', $bill, 'string');
                        $arr = array('Claim'=> 2);
                        if ($this->db->update("treatment", $arr) == true) {
                            $this->sms->sendFull((object)array('to'     => $vis_data_borr->tel,'message'=> $msg));
                            /**
                            * Отправка сообщений/я
                            * **/
                            $data = $this->email->Send_Def_Param($vis_data_borr->email, null, $subject, $msg);
                        }
                        return "ok";
                    }
                    else
                    {
                        return "Не удалось получить сведения о посетителе";
                    }
                    if (!empty($isk->href_tmp_file) && is_file($isk->href_tmp_file)) {
                        return $isk->href_tmp_file;
                    }
                    else
                    {
                        return "Не удалось создать иск";
                    }
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End send_isk
    public function send_isk_clear($data)
    {
        $bill = $this->session->get_bill();
        $ai   = (property_exists($data, "ai")) ? $data->ai : null;
        if ($bill != "no_sess") {
            if ($ai != null) {
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->where('Bill_Creditor', '=', $bill, 'string');
                $this->db->limit(0, 1);
                $data_select = $this->db->select('treatments');
                if (is_array($data_select) && array_key_exists(0, $data_select) && !empty($data_select[0])) {
                    //Генерируем чистый бланк иска
                    $isk_clear = $this->gen_doc_isk_clear($ai);
                    if (!empty($isk_clear->href_tmp_file) && is_file($isk_clear->href_tmp_file)) {
                        return $isk_clear->href_tmp_file;
                    }
                    else
                    {
                        return "Не удалось создать чистый бланк иска";
                    }
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End send_isk_clear
    /**
    * Метод выполняет генерацию документа займа для договора,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_zaym($ai = null)
    {
        $bill = $this->session->get_bill();
        if ($bill != "no_sess") {
            if ($ai != null) {
                //Получаем данные по договору
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->limit(0, 1);
                $data_treatment = $this->db->select('treatments');
                if (is_array($data_treatment) && !empty($data_treatment) && array_key_exists(0, $data_treatment) && array_key_exists('ai', $data_treatment[0])) {
                    $data_treatment = (object)$data_treatment[0];
                    //Получаем дополнительные данные о кредиторе и заемщике
                    $data_creditor         = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Creditor);
                    $data_borrower         = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Borrower);
                    /**
                    * Генерируем QR коды для подписей посетителей
                    * **/
                    $text_qr_code_creditor = "Договор займа номер " . $data_treatment->ai . ";\r\nДата начала: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_Start) . ";\r\nДата окончания: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_End) . ";\r\nСумма: " . $data_treatment->Summ . ' ' . $this->core_lib->numberEnd($data_treatment->Summ, array(
                            'рубль',
                            'рубля',
                            'рублей')) . ";\r\nСтавка: " . $data_treatment->Percent . ' ' . $this->core_lib->numberEnd($data_treatment->Percent, array(
                            'процент',
                            'процента',
                            'процентов')) . ";\r\nКредитор: " . $data_treatment->FIO_Creditor . ";\r\nЗаемщик: " . $data_treatment->FIO_Borrower;
                    $text_qr_code_borrower = "Договор займа номер " . $data_treatment->ai . ";\r\nДата начала: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_Start) . ";\r\nДата окончания: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_End) . ";\r\nСумма: " . $data_treatment->Summ . ' ' . $this->core_lib->numberEnd($data_treatment->Summ, array(
                            'рубль',
                            'рубля',
                            'рублей')) . ";\r\nСтавка: " . $data_treatment->Percent . ' ' . $this->core_lib->numberEnd($data_treatment->Percent, array(
                            'процент',
                            'процента',
                            'процентов')) . ";\r\nКредитор: " . $data_treatment->FIO_Creditor . ";\r\nЗаемщик: " . $data_treatment->FIO_Borrower;
                    $file_name_qr_code_creditor = null;
                    $file_name_qr_code_borrower = null;
                    /*Генерируем случайное имя файла*/
                    $file_name_qr_code_creditor = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    /**
                    * Если имя файла с путем уже существует, то генерируем новое
                    */
                    while (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_creditor) == true) {
                        /*Генерируем случайное имя файла*/
                        $file_name_qr_code_creditor = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    }
                    $this->qr_code->png($text_qr_code_creditor, NEWFC_DIR_TMP . $file_name_qr_code_creditor, "L", 3, 1);
                    /*Генерируем случайное имя файла*/
                    $file_name_qr_code_borrower = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    /**
                    * Если имя файла с путем уже существует, то генерируем новое
                    */
                    while (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_borrower) == true) {
                        /*Генерируем случайное имя файла*/
                        $file_name_qr_code_borrower = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    }
                    $this->qr_code->png($text_qr_code_borrower, NEWFC_DIR_TMP . $file_name_qr_code_borrower, "L", 3, 1);
                    //Подготавливаем данные
                    $arr_data = array(
                        "{number}"                         => $data_treatment->ai,//.' / 1',
                        "{date_start}"    => $this->core_lib->re_convert_date_standart($data_treatment->Date_Start),
                        "{date_end}"                       => $this->core_lib->re_convert_date_standart($data_treatment->Date_End),
                        "{date_delivery}"                  => $this->core_lib->re_convert_date_standart($data_treatment->Date_End),
                        "{fio_creditor}"                   => $data_treatment->FIO_Creditor,
                        "{fio_borrower}"                   => $data_treatment->FIO_Borrower,
                        "{summ}"                           => $data_treatment->Summ,
                        "{summ_is_word}"                   => $this->core_lib->num2str($data_treatment->Summ),
                        "{count_day_is_word}"              => $this->core_lib->day2str($data_treatment->Count_Day),
                        "{fine}"                           => $data_treatment->Fine,
                        "{fine_is_word}"                   => $this->core_lib->day2str($data_treatment->Fine),
                        "{creditor_AddressRegistration}"   => $data_creditor->address_reg,
                        "{creditor_AddressResiding}"       => $data_creditor->address,
                        "{creditor_Tel}"                   => $data_creditor->tel,
                        "{creditor_Email}"                 => $data_creditor->email,
                        "{card_creditor}"                  => '',
                        "{creditor_Serial}"                => $data_creditor->serial,
                        "{creditor_Number}"                => $data_creditor->number,
                        "{passport_date_delivery_creditor}"=> $this->core_lib->re_convert_date_standart($data_creditor->date_delivery),
                        "{creditor_Delivery}"              => $data_creditor->delivery,
                        "{borrower_AddressRegistration}"   => $data_borrower->address_reg,
                        "{borrower_AddressResiding}"       => $data_borrower->address,
                        "{borrower_Tel}"                   => $data_borrower->tel,
                        "{borrower_Email}"                 => $data_borrower->email,
                        "{card_borrower}"                  => '',
                        "{borrower_Serial}"                => $data_borrower->serial,
                        "{borrower_Number}"                => $data_borrower->number,
                        "{passport_date_delivery_borrower}"=> $this->core_lib->re_convert_date_standart($data_borrower->date_delivery),
                        "{borrower_Delivery}"              => $data_borrower->delivery,
                        "{abbr_creditor}"                  => mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_substr($data_creditor->name, 0, 1, 'UTF-8') . '. ' . mb_substr($data_creditor->middle_name, 0, 1, 'UTF-8') . '. ',
                        "{abbr_borrower}"                  => mb_convert_case($data_borrower->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_substr($data_borrower->name, 0, 1, 'UTF-8') . '. ' . mb_substr($data_borrower->middle_name, 0, 1, 'UTF-8') . '. ');
                    $res_file   = $this->gen_doc_treatment($ai, 'zaym', $arr_data);
                    $sign_param = array(
                        '3' => array(
                            'cert_in'     => NEWFC_CA_CERT_PEM,
                            'cert_key_in' => NEWFC_CA_CERT_PEM,
                            'cert_pass_in'=> '',
                            'page_numb'   => 1,
                            'reason'      => 'Sign Zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_ROOT_IMG . 'misk/logo_stamp.png',
                            'location'    => 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Site',
                            'position'    => 'lt',
                            'x_pos'       => 25,
                            'y_pos'       => - 25,
                            'width'       => 150,
                            'height'      => 65),
                        '1' => array(
                            'cert_in'     => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_borrower->bill . '.pem',
                            'cert_key_in' => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_borrower->bill . '.pem',
                            'cert_pass_in'=> '',
                            'page_numb'   => 3,
                            'reason'      => 'Registration zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_TMP . $file_name_qr_code_borrower,// NEWFC_DIR_ROOT_IMG . 'misk / logo_stamp.png',
                            'location'=> 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Borrower',
                            'position'    => 'lt',
                            'x_pos'       => + 30,
                            'y_pos'       => - 125,
                            'width'       => 180,
                            'height'      => 50),
                        '2' => array(
                            'cert_in'     => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_creditor->bill . '.pem',
                            'cert_key_in' => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_creditor->bill . '.pem',
                            'cert_pass_in'=> '',
                            'page_numb'   => 3,
                            'reason'      => 'Registration zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_TMP . $file_name_qr_code_creditor,//NEWFC_DIR_ROOT_IMG . 'misk / logo_stamp.png',
                            'location'=> 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Creditor',
                            'position'    => 'rt',
                            'x_pos'       => - 110,
                            'y_pos'       => - 125,
                            'width'       => 180,
                            'height'      => 50));
                    $this->pdf_sign->sign_visible($res_file->href_file, 1, $sign_param);
                    /**
                    * Удаляем файлы изображений для подписей, если они были успешно созданы
                    * **/
                    if (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_creditor) == true) {
                        unlink(NEWFC_DIR_TMP . $file_name_qr_code_creditor);
                    }
                    if (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_borrower) == true) {
                        unlink(NEWFC_DIR_TMP . $file_name_qr_code_borrower);
                    }
                    return $res_file;
                    /*$files/*$this->db->last_query*/
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_zaym
    /**
    * Метод выполняет генерацию документа акта передачи денежных средств для договора,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_act($ai = null)
    {
        $bill = $this->session->get_bill();
        if ($bill != "no_sess") {
            if ($ai != null) {
                //Получаем данные по договору
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->limit(0, 1);
                $data_treatment = $this->db->select('treatments');
                if (is_array($data_treatment) && !empty($data_treatment) && array_key_exists(0, $data_treatment) && array_key_exists('ai', $data_treatment[0])) {
                    $data_treatment = (object)$data_treatment[0];
                    //Получаем дополнительные данные о кредиторе и заемщике
                    $data_creditor         = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Creditor);
                    $data_borrower         = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Borrower);
                    /**
                    * Генерируем QR коды для подписей посетителей
                    * **/
                    $text_qr_code_creditor = "Договор займа номер " . $data_treatment->ai . ";\r\nДата начала: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_Start) . ";\r\nДата окончания: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_End) . ";\r\nСумма: " . $data_treatment->Summ . ' ' . $this->core_lib->numberEnd($data_treatment->Summ, array(
                            'рубль',
                            'рубля',
                            'рублей')) . ";\r\nСтавка: " . $data_treatment->Percent . ' ' . $this->core_lib->numberEnd($data_treatment->Percent, array(
                            'процент',
                            'процента',
                            'процентов')) . ";\r\nКредитор: " . $data_treatment->FIO_Creditor . ";\r\nЗаемщик: " . $data_treatment->FIO_Borrower;
                    $text_qr_code_borrower = "Договор займа номер " . $data_treatment->ai . ";\r\nДата начала: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_Start) . ";\r\nДата окончания: " . $this->core_lib->re_convert_date_standart($data_treatment->Date_End) . ";\r\nСумма: " . $data_treatment->Summ . ' ' . $this->core_lib->numberEnd($data_treatment->Summ, array(
                            'рубль',
                            'рубля',
                            'рублей')) . ";\r\nСтавка: " . $data_treatment->Percent . ' ' . $this->core_lib->numberEnd($data_treatment->Percent, array(
                            'процент',
                            'процента',
                            'процентов')) . ";\r\nКредитор: " . $data_treatment->FIO_Creditor . ";\r\nЗаемщик: " . $data_treatment->FIO_Borrower;
                    $file_name_qr_code_creditor = null;
                    $file_name_qr_code_borrower = null;
                    /*Генерируем случайное имя файла*/
                    $file_name_qr_code_creditor = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    /**
                    * Если имя файла с путем уже существует, то генерируем новое
                    */
                    while (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_creditor) == true) {
                        /*Генерируем случайное имя файла*/
                        $file_name_qr_code_creditor = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    }
                    $this->qr_code->png($text_qr_code_creditor, NEWFC_DIR_TMP . $file_name_qr_code_creditor, "L", 3, 1);
                    /*Генерируем случайное имя файла*/
                    $file_name_qr_code_borrower = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    /**
                    * Если имя файла с путем уже существует, то генерируем новое
                    */
                    while (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_borrower) == true) {
                        /*Генерируем случайное имя файла*/
                        $file_name_qr_code_borrower = $this->core_lib->random_string(7, "lowerEn|upperEn|numeric") . ".png";
                    }
                    $this->qr_code->png($text_qr_code_borrower, NEWFC_DIR_TMP . $file_name_qr_code_borrower, "L", 3, 1);
                    //Подготавливаем данные
                    $arr_data = array(
                        "{number}"       => $data_treatment->ai,//.' / 2',
                        "{date_start}"=> $this->core_lib->re_convert_date_standart($data_treatment->Date_Start),
                        "{fio_creditor}" => $data_treatment->FIO_Creditor,
                        "{fio_borrower}" => $data_treatment->FIO_Borrower,
                        "{summ}"         => $data_treatment->Summ,
                        "{summ_is_word}" => $this->core_lib->num2str($data_treatment->Summ),
                        "{abbr_creditor}"=> mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_substr($data_creditor->name, 0, 1, 'UTF-8') . '. ' . mb_substr($data_creditor->middle_name, 0, 1, 'UTF-8') . '. ',
                        "{abbr_borrower}"=> mb_convert_case($data_borrower->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_substr($data_borrower->name, 0, 1, 'UTF-8') . '. ' . mb_substr($data_borrower->middle_name, 0, 1, 'UTF-8') . '.');
                    $res_file   = $this->gen_doc_treatment($ai, 'act', $arr_data);
                    $sign_param = array(
                        '3' => array(
                            'cert_in'     => NEWFC_CA_CERT_PEM,
                            'cert_key_in' => NEWFC_CA_CERT_PEM,
                            'cert_pass_in'=> '',
                            'page_numb'   => 1,
                            'reason'      => 'Sign Zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_ROOT_IMG . 'misk/logo_stamp.png',
                            'location'    => 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Site',
                            'position'    => 'lt',
                            'x_pos'       => 25,
                            'y_pos'       => - 10,
                            'width'       => 150,
                            'height'      => 65),
                        '1' => array(
                            'cert_in'     => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_borrower->bill . '.pem',
                            'cert_key_in' => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_borrower->bill . '.pem',
                            'cert_pass_in'=> '',
                            'page_numb'   => 1,
                            'reason'      => 'Registration zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_TMP . $file_name_qr_code_borrower,// NEWFC_DIR_ROOT_IMG . 'misk / logo_stamp.png',
                            'location'=> 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Borrower',
                            'position'    => 'lt',
                            'x_pos'       => + 25,
                            'y_pos'       => - 415,
                            'width'       => 180,
                            'height'      => 50),
                        '2' => array(
                            'cert_in'     => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_creditor->bill . '.pem',
                            'cert_key_in' => NEWFC_DIR_SSL_USERS . 'visitors/' . $data_creditor->bill . '.pem',
                            'cert_pass_in'=> '',
                            'page_numb'   => 1,
                            'reason'      => 'Registration zaym',
                            'file_bkg'    => NEWFC_DIR_ROOT_IMG . 'misk/bkg_stamp.png',
                            'file_logo'   => NEWFC_DIR_TMP . $file_name_qr_code_creditor,//NEWFC_DIR_ROOT_IMG . 'misk / logo_stamp.png',
                            'location'=> 'newfc.ru',
                            'contact_info'=> 'admin@newfc.ru',
                            'field_name'  => 'Creditor',
                            'position'    => 'rt',
                            'x_pos'       => - 120,
                            'y_pos'       => - 415,
                            'width'       => 180,
                            'height'      => 50));
                    $this->pdf_sign->sign_visible($res_file->href_file, 1, $sign_param);
                    /**
                    * Удаляем файлы изображений для подписей, если они были успешно созданы
                    * **/
                    if (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_creditor) == true) {
                        unlink(NEWFC_DIR_TMP . $file_name_qr_code_creditor);
                    }
                    if (file_exists(NEWFC_DIR_TMP . $file_name_qr_code_borrower) == true) {
                        unlink(NEWFC_DIR_TMP . $file_name_qr_code_borrower);
                    }
                    return $res_file;
                    /*$files/*$this->db->last_query*/
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_act
    /**
    * Метод выполняет генерацию документа претензии,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_pret($ai = null)
    {
        $bill = $this->session->get_bill();
        if ($bill != "no_sess") {
            if ($ai != null) {
                //Получаем данные по договору
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->limit(0, 1);
                $data_treatment = $this->db->select('treatments');
                if (is_array($data_treatment) && !empty($data_treatment) && array_key_exists(0, $data_treatment) && array_key_exists('ai', $data_treatment[0])) {
                    $data_treatment = (object)$data_treatment[0];
                    //Получаем дополнительные данные о кредиторе и заемщике
                    $data_creditor = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Creditor);
                    $data_borrower = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Borrower);
                    //Подготавливаем данные
                    $arr_data      = array(
                        "{number}"          => $data_treatment->ai,//.' / 3',
                        "{date_start}"=> $this->core_lib->re_convert_date_standart($data_treatment->Date_Start),
                        "{date_curr}"       => date("d-m-Y"),
                        "{fio_creditor}"    => $data_treatment->FIO_Creditor,
                        "{fio_borrower}"    => $data_treatment->FIO_Borrower,
                        "{fio_creditor_dat}"=> $this->name_case_ru->q(mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->name, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->middle_name, MB_CASE_TITLE, "UTF-8"), 2),
                        "{feedback}"        => NEWFC_BASE_URL . "feedback.html",
                        "{abbr_creditor}"   => mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_substr($data_creditor->name, 0, 1, 'UTF-8') . '. ' . mb_substr($data_creditor->middle_name, 0, 1, 'UTF-8') . '. ');
                    $res_file = $this->gen_doc_treatment($ai, 'pret', $arr_data);
                    return $res_file;
                    /*$files/*$this->db->last_query*/
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_pret
    /**
    * Метод выполняет генерацию иска по договору,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_isk($ai = null)
    {
        $bill = $this->session->get_bill();
        if ($bill != "no_sess") {
            if ($ai != null) {
                //Получаем данные по договору
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->limit(0, 1);
                $data_treatment = $this->db->select('treatments');
                if (is_array($data_treatment) && !empty($data_treatment) && array_key_exists(0, $data_treatment) && array_key_exists('ai', $data_treatment[0])) {
                    $data_treatment = (object)$data_treatment[0];
                    //Получаем дополнительные данные о кредиторе и заемщике
                    $data_creditor = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Creditor);
                    $data_borrower = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Borrower);
                    $date_pret     = null;
                    if (!empty($data_treatment->Href_Pret) && is_file($data_treatment->Href_Pret)) {
                        $date_pret = date("d-m-Y", filemtime($data_treatment->Href_Pret));
                    }
                    $date_act = null;
                    if (!empty($data_treatment->Href_Act) && is_file($data_treatment->Href_Act)) {
                        $date_pret = date("d-m-Y", filemtime($data_treatment->Href_Act));
                    }
                    //Подготавливаем данные
                    $arr_data = array(
                        "{number}"                         => $data_treatment->ai,//.' / 4',
                        "{date_start}"    => $this->core_lib->re_convert_date_standart($data_treatment->Date_Start),
                        "{date_end}"                       => $this->core_lib->re_convert_date_standart($data_treatment->Date_End),
                        "{date_curr}"                      => date("d-m-Y"),
                        "{date_pret}"                      => $date_pret,//Заменить на дату претензии
                        "{date_act}"=> $date_pret,//Заменить на дату претензии
                        //"{date_delivery}" => $data_treatment->Date_End,
                        "{fio_creditor}"=> $data_treatment->FIO_Creditor,
                        "{fio_borrower}"                   => $data_treatment->FIO_Borrower,
                        "{full_summ}"                      => $data_treatment->Full_Summ_Passed_Day,
                        "{state_tax}"                      => $data_treatment->State_Tax,
                        "{state_tax_is_word}"              => $this->core_lib->num2str($data_treatment->State_Tax),
                        "{fio_creditor_tvorit}"            => $this->name_case_ru->q(mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->name, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->middle_name, MB_CASE_TITLE, "UTF-8"), 4),
                        "{fio_borrower_tvorit}"            => $this->name_case_ru->q(mb_convert_case($data_borrower->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_borrower->name, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_borrower->middle_name, MB_CASE_TITLE, "UTF-8"), 4),
                        "{fio_creditor_vinit}"             => $this->name_case_ru->q(mb_convert_case($data_creditor->surname, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->name, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($data_creditor->middle_name, MB_CASE_TITLE, "UTF-8"), 3),
                        "{summ}"                           => $data_treatment->Summ,
                        "{summ_is_word}"                   => $this->core_lib->num2str($data_treatment->Summ),
                        "{percent}"                        => $data_treatment->Percent,
                        "{percent_is_word}"                => $this->core_lib->num2str($data_treatment->Percent),
                        "{passed_day}"                     => $data_treatment->Passed_Day,
                        "{summ_percent_passed_day}"        => $data_treatment->Summ_Percent_Passed_Day,
                        "{summ_percent_passed_day_is_word}"=> $this->core_lib->num2str($data_treatment->Summ_Percent_Passed_Day),
                        "{penalty}"                        => $data_treatment->Penalty,
                        "{penalty_is_word}"                => $this->core_lib->num2str($data_treatment->Penalty),
                        "{creditor_AddressRegistration_1}" => '(' . $data_creditor->registration_zipcode . ') ' . $data_creditor->registration_region . ' ' . $data_creditor->registration_city,
                        "{creditor_AddressRegistration_2}" => '' . $data_creditor->registration_street . ' ' . $data_creditor->registration_house . (!empty($data_creditor->registration_block) ? ' к/стр. ' . $data_creditor->registration_block : '') . ' кв. ' . $data_creditor->registration_place,
                        "{borrower_AddressRegistration_1}" => '(' . $data_borrower->registration_zipcode . ') ' . $data_borrower->registration_region . ' ' . $data_borrower->registration_city,
                        "{borrower_AddressRegistration_2}" => '' . $data_borrower->registration_street . ' ' . $data_borrower->registration_house . (!empty($data_creditor->registration_block) ? ' к/стр. ' . $data_creditor->registration_block : '') . ' кв. ' . $data_borrower->registration_place,
                        //"{count_day_is_word}" => $this->core_lib->day2str($data_treatment->Count_Day),
                        "{fine}"=> $data_treatment->Fine,
                        "{fine_day}"                       => $data_treatment->Fine_Day,
                        "{summ_percent_fine_day}"          => $data_treatment->Summ_Percent_Fine_Day,
                        "{summ_percent_fine_day_is_word}"  => $this->core_lib->num2str($data_treatment->Summ_Percent_Fine_Day));
                    $res_file = $this->gen_doc_treatment($ai, 'isk', $arr_data);
                    return $res_file;
                    /*$files/*$this->db->last_query*/
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_isk
    /**
    * Метод выполняет генерацию иска по договору,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_isk_clear($ai = null)
    {
        $bill = $this->session->get_bill();
        if ($bill != "no_sess") {
            if ($ai != null) {
                //Получаем данные по договору
                $this->db->where('ai', '=', $ai, 'string');
                $this->db->limit(0, 1);
                $data_treatment = $this->db->select('treatments');
                if (is_array($data_treatment) && !empty($data_treatment) && array_key_exists(0, $data_treatment) && array_key_exists('ai', $data_treatment[0])) {
                    $data_treatment = (object)$data_treatment[0];
                    //Получаем дополнительные данные о кредиторе и заемщике
                    $data_creditor = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Creditor);
                    $data_borrower = $this->session->get_data_visitor_from_bill($data_treatment->Bill_Borrower);
                    //Подготавливаем данные
                    $arr_data      = array();
                    return $this->gen_doc_treatment($ai, 'isk_clear', $arr_data);
                    /*$files/*$this->db->last_query*/
                }
                else
                {
                    return "Не удалось получить сведения о договоре";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_isk_clear
    /**
    * Метод выполняет генерацию документа,
    * и создает в договоре соответствующую запись об этом в БД
    * **/
    public function gen_doc_treatment($ai = null, $type = 'zaym', $arr_data_tpl = array())
    {
        $bill = $this->session->get_bill();
        $files= array(
            'href_file'    => null,
            'href_tmp_file'=> null,
            'file_name'    => null);
        $files = (object)$files;
        /*Генерируем случайное имя файла*/
        $file_name_doc = $this->dir_file->gen_file_name(NEWFC_DIR_TREATMENTS);
        $file_name_out = null;
        if ($bill != "no_sess") {
            if ($ai != null) {
                if ($type == 'zaym' || $type == 'act' || $type == 'agent' || $type == 'pret' || $type == 'isk' || $type == 'isk_clear') {
                    if (is_array($arr_data_tpl)) {
                        switch ($type) {
                            case 'zaym':
                            $file_name_out = 'zaym_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'zaym.tpl', $arr_data_tpl);
                            break;
                            case 'act':
                            $file_name_out = 'act_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'act.tpl', $arr_data_tpl);
                            break;
                            case 'agent':
                            $file_name_out = 'agent_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'agent.tpl', $arr_data_tpl);
                            break;
                            case 'pret':
                            $file_name_out = 'pret_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'pret.tpl', $arr_data_tpl);
                            break;
                            case 'isk':
                            $file_name_out = 'isk_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'isk.tpl', $arr_data_tpl);
                            break;
                            case 'isk_clear':
                            $file_name_out = 'isk_clear_' . $ai . '.pdf';
                            $text_pdf      = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'isk_clear.tpl', $arr_data_tpl);
                            break;
                            default:
                            $text_pdf = $this->core_lib->load_tpl_with_pattern(NEWFC_DIR_TPL . 'zaym.tpl', $arr_data_tpl);
                        }
                        $res = $this->create_document(NEWFC_DIR_TREATMENTS . $file_name_doc, //NEWFC_BASE_URL."tmp / "."gen_bill_document_1.pdf",
                            $text_pdf, "Ltd. NyuEfSi", "Ltd. NyuEfSi", "pay bill", "pay bill", "pay bill", null);
                        $files->href_file = NEWFC_DIR_TREATMENTS . $file_name_doc;
                        $files->file_name = $file_name_doc;
                        $files->href_tmp_file = $this->dir_file->copy_file(NEWFC_DIR_TREATMENTS . $file_name_doc, NEWFC_DIR_TMP, $file_name_out);
                        /**
                        * Создаем запись о местоположении документа
                        * **/
                        $this->db->free();
                        $arr_documents = array(
                            'ai_treatments'=> $ai,
                            'type'         => $type,
                            'href'         => NEWFC_DIR_TREATMENTS . $file_name_doc);
                        $arr_documents = (object)$arr_documents;
                        $query_documents_from_treaatments = "INSERT INTO `documents_from_treatments` SET `ai_treatments` = '" . $ai . "', `type` = '{$arr_documents->type}'," . " `href` = '{$arr_documents->href}' ";
                        $query_documents_from_treaatments .= "ON DUPLICATE KEY UPDATE `href` = '{$arr_documents->href}'";
                        $this->db->multi_query($query_documents_from_treaatments);
                        //var_dump($this->db->last_query);
                        return $files /*$this->db->last_query*/;
                    }
                    else
                    {
                        return "Не корректно передан массив паттернов";
                    }
                }
                else
                {
                    return "Не корректно передан тип договора";
                }
            }
            else
            {
                return "Не передан номер договора";
            }
        }
        else
        {
            return "no_sess";
        }
    } //End gen_doc_treatment
} //End SC_Pdf
?>