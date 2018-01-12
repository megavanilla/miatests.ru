<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Тест грфиков</title>
    <meta name="title" content="Статистика сайта - Личный кабинет"/>
    <link rel="stylesheet" href="web/bundles/bootstrap-3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="web/css/main.css">
    <script type="text/javascript" src="web/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="web/bundles/bootstrap-3.3.7/js/bootstrap.js"></script>
    <script type="text/javascript" src="web/bundles/highcharts/js/highcharts.js"></script>
    <script type="text/javascript" src="web/js/app.js"></script>
    <script type="text/javascript" src="web/js/main.js"></script>
</head>
<!--header-->
<body class="desktop-menu-open">
<script>
  var reportWeather = new Reports('containerChart');
  reportWeather.getWeather();

</script>
<div class="header">
    <!--header-top-->
    <div class="header-top clearfix">
        <!--header-logo-->
        <div class="header-logo">
            <!--<a href="/"><img src="favicon.ico" alt=""></a>-->
        </div>
    </div>
</div>
<!--/header-->
<!--page-main-->
<div class="page-main">
    <!--page-main-container-->
    <div class="page-main-container">
        <div id="containerChart"></div>
    </div>
    <!--/page-main-container-->
</div>
<!--/page-main-->
</body>
</html>