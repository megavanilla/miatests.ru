<!DOCTYPE html>
<html>
  <head>
    <title>Тестовое задание</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  </head>
  <style>
    body{
        width: 750px;
        margin: 0 auto;
    }
  </style>
  <body>
      <fieldset>
        <legend>Поставленные задачи:</legend>
        <section>
          <h1><a href="./files/myfile.exe">Задача 1:</a></h1>
          <p>
            <strong>Описание:</strong>
            Для проверки достаточно разместить ссылку: <a href="./files/myfile.exe">Скачать файл myfile.exe.</a>
            на другом хосте.
            <br />
            Обращение по ссылке /files/&lt;имя файла&gt;.exe должно возвратить EXE файл расположенный
            по адресу /file.exe, а также установить cookie с параметром referrer равным домену, с
            которого пришел данный пользователь для закачки этого файла.
            Например, если на сайте www.cnet.com поместили прямую ссылку на
            http://www.auslogics.com/files/myfile.exe и посетитель щелкает по ней, то он сможет скачать
            файл /files/myfile.exe (а на самом деле /file.exe) и на его компьютере будет оставлена cookie с
            referrer = cnet.com.
            Написать скрипт (PHP), реализующий этот функционал, привести текст .htaccess, если нужен.
          </p>
        </section>
        <section>
          <h1><a href="./test_2.php">Задача 2:</a></h1>
          <p>
            <strong>Описание:</strong>
            Дан массив произвольного размера с числами в пределах от 1 до 1,000,000. В этом массиве
            все числа уникальные, кроме одного числа, которое повторяется два раза. Найти это число.
            Решить эту задачу с минимальным использованием процессорного времени. Решить на PHP
            и выслать рабочий код.
          </p>
        </section>
        <section>
          <h1><a href="./test_3.html">Задача 3:</a></h1>
          <p>
            <strong>Описание:</strong>
            Есть страница, на которой расположены два блока А и В неизвестной высоты (мокап
            страницы представлен на Рис. 1, стр. 2). Необходимо сверстать страницу так, что бы блоки
            между собой оказались выровненными по нижнему краю. Общее оформление страницы и
            блоков значения не имеет. Результат должен быть представлен в виде одного html файла
          </p>
        </section>
        <section>
          <h1><a href="./test_4.php">Задача 4:</a></h1>
          <p>
            <strong>Описание:</strong>
            Создать страницу на PHP реализующую функции отображенные на Рис. 2 (стр. 2)
            Информация должна безопасно храниться в SQL. Злоумышленники не должны видеть
            электронные адреса и телефоны пользователей, даже если они взломают сайт и получат
            полный доступ к базе данных и всем файлам. Решение должно использовать только
            стандартный набор PHP библиотек.
          </p>
        </section>
      </fieldset>
  </body>
</html>
