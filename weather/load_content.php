<?php
// создание нового cURL ресурса
$ch = curl_init();

// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, "https://yandex.ru/");
curl_setopt($ch, CURLOPT_HEADER, 0);

// загрузка страницы и выдача её браузеру
curl_exec($ch);

// завершение сеанса и освобождение ресурсов
curl_close($ch);

?>

<script type="text/javascript" src="./web/js/main.js"></script>
<script>
        var pP = new parsePage('pP');

        //Переведём нижнюю часть текста
        pP.translateText(pP.findText.parentNode.parentNode);
        pP.translateText(document.querySelector('.container__heap'));
        pP.translateText(document.querySelector('.rows__row_last'));

        pP.findText.value = 'Внимание, текст переводиться только несколько раз в день ;)';

        pP.initHack();

</script>
