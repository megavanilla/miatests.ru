<!DOCTYPE html>
<html>
<!--header-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Тестовое задание</title>
    <meta name="title" content="Статистика сайта - Личный кабинет"/>
    <link rel="stylesheet" href="./web/bundles/bootstrap-3.3.7/css/bootstrap.css">
    <!--<link rel="stylesheet" href="./web/bundles/dataTables/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="./web/css/main.css">
    <script type="text/javascript" src="./web/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="./web/bundles/bootstrap-3.3.7/js/bootstrap.js"></script>
    <!--<script type="text/javascript" src="./web/bundles/dataTables/jquery.dataTables.min.js"></script>-->
    <script type="text/javascript" src="./web/js/main.js"></script>
</head>
<!--/header-->
<!--body-->
<body>
<div id="wrap" class="page-main-container">
    <div class="params">
        <h3>Параметры:</h3>
        <div>
            <div class="form-group">
                <label for="min_value">Минимальное значение:</label>
                <input type="text" class="form-control" id="min_value" name="min_value"
                       placeholder="Минимальное значение">
            </div>
            <div class="form-group">
                <label for="max_value">Максимальное значение:</label>
                <input type="text" class="form-control" id="max_value" name="max_value"
                       placeholder="Максимальное значение">
            </div>
            <div class="form-group">
                <label for="count_numbers">Количество чисел:</label>
                <input type="text" class="form-control" id="count_numbers" name="count_numbers"
                       placeholder="Количество чисел">
            </div>
            <div class="form-group">
                <input type="button" id="btn_submit" value="Расчитать" onclick="calculate();">
                <span id="status"></span>
            </div>
        </div>
    </div>
    <br>
    <br>
    <hr>
    <div class="results">
        <h3>Результаты:</h3>
        <h5>Ряд: <span id="ranks">-</span></h5>
        <h5>Среднее значение: <span id="average">-</span></h5>
        <h5>Стандартное отклонение: <span id="deviation">-</span></h5>
        <h5>Моды(могут отсутствовать, или быть несколько через запятую): <span id="mode">-</span></h5>
        <h5>Медиана: <span id="median">-</span></h5>
    </div>
    <br>
    <br>
    <hr>
    <div class="params">
        <h3>Пинговка:</h3>
        <div>
            <div class="form-group">
                <label for="min_value">Хост:</label>
                <input type="text" class="form-control" id="host" placeholder="Хост в виде: localhost или ya.ru">
            </div>
            <div class="form-group">
                <input type="button" id="btn_submit_ping" value="Пинговать" onclick="ping();">
            </div>
            <div class="form-group">
                <label for="console">Командная строка:</label>
                <textarea id="console" class="form-control console_log" disabled="disabled" rows="6"></textarea>
            </div>
        </div>
    </div>
</div>
<script>
        $('#min_value').keyup(function (event) {
            if (event.keyCode === 13) {
                $("#max_value").focus();
            }
        });
        $('#max_value').keyup(function (event) {
            if (event.keyCode === 13) {
                $("#count_numbers").focus();
            }
        });
        $('#count_numbers').keyup(function (event) {
            if (event.keyCode === 13) {
                calculate();
            }
        });

    function ping() {
        var host = geVal('host');
        if (!host) {
            return false;
        }
        if (!ValidURL(host)) {
            alert('Введите корректный хост');
            return false;
        }

        var pinger = new Pinger('console');
        pinger.ping('http://' + host + ':80');
    }

    function calculate() {
        var CalculaterObj = new Calculater();
        CalculaterObj.calculate();
    }
</script>
</body>
<!--body-->
</html>