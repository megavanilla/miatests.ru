<?php
    if(is_file('./header.tpl.php')){
        require_once('./header.tpl.php');
    }
?>
        <h3>Тестовые задания:</h3>
        <hr>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Проект:</th>
                <th>GitHub</th>
                <th>Используемые языки, библиотеки, фреймворки</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="./patterns">Паттерны</a>:</td>
                    <td><a href="https://github.com/megavanilla/miatests.ru/tree/master/patterns">.../megavanilla/patterns</a></td>
                    <td>PHP</td>
                </tr>
                <tr>
                    <td><a href="./m1-shop">m1-shop</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/m1-shop</a></td>
                    <td>PHP, JS, CSS, Bootstrap, Jquery</td>
                </tr>
                <tr>
                    <td><a href="./profit">profit</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/profit</a></td>
                    <td>PHP, JS, CSS, Bootstrap, Jquery</td>
                </tr>
                <tr>
                    <td><a href="./auslogics">auslogics</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/auslogics</a></td>
                    <td>PHP, JS, CSS, MySQL</td>
                </tr>
                <tr>
                    <td><a href="./weather">weather</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/weather</a></td>
                    <td>PHP, JS, Jquery, CSS, Bootstrap, highcharts.js</td>
                </tr>
                <tr>
                    <td><a href="./winestyle">winestyle</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/winestyle</a></td>
                    <td>PHP, JS, Jquery, CSS, MySQL</td>
                </tr>
                <tr>
                    <td><a href="./plarson">plarson</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/plarson</a></td>
                    <td>CSS, JS, Jquery</td>
                </tr>
                <tr>
                    <td><a href="./framework">framework</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/framework</a></td>
                    <td>PHP, JS, CSS, Bootstrap, MySQL, Sqlite</td>
                </tr>
                <tr>
                    <td><a href="./fronend">fronend, чисто ради теста</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/fronend</a></td>
                    <td>CSS</td>
                </tr>
                <!--
                <tr>
                    <td><a href="./react-app">reactApp</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/reactApp</a></td>
                    <td>CSS</td>
                </tr>
                -->
                <!--
                <tr>
                    <td><a href="./test_task">test_task</a></td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/test_task</a></td>
                    <td>...восстанавливается</td>
                </tr>
                -->
                <!--
                <tr>
                    <td><a href="./">mirafox</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/mirafox</a></td>
                    <td>...в очереди на восстановление</td>
                </tr>
                <tr>
                    <td><a href="./">edison</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/edison</a></td>
                    <td>...в очереди на восстановление</td>
                </tr>
                <tr>
                    <td><a href="./">izum</a>:</td>
                    <td><a href="https://github.com/megavanilla/">.../megavanilla/izum</a></td>
                    <td>...в очереди на восстановление</td>
                </tr>
            -->
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">Общий репозиторий: <a href="https://github.com/megavanilla/">https://github.com/megavanilla/</a></td>
            </tr>
            </tfoot>
        </table>
<?php
if(is_file('./footer.tpl.php')){
    require_once('./footer.tpl.php');
}
?>