<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 23:22
 */
use mvc\libs;
$Request = new libs\Request();

$folderImgs = $Request->getVariable($params, ['conf', 'main', 'uploads'], '');
?>
<div class="page-main">
    <!--page-main-container-->
    <div class="page-main-container">
    </div>
    <!--/page-main-container-->
</div>
<!--/page-main-->

<!---Модальное окно-->
<div id="modal"></div>
<!---/Модальное окно-->
