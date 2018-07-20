<?php

use mvc\libs\Request;

$Request = new Request();
global $Configs;

$uploadDir = '/m1-shop/'.$Request->getVariable($Configs, ['conf', 'main', 'uploads'], '');
$params = (!empty($params) && is_array($params)) ? $params[0] : null;
if (!$params) {
  exit;
}
$imgSrc = ($params['href_img']) ? $uploadDir . $params['href_img'] : '/m1-shop/web/img/no-ava.png';

?>
<a href="/m1-shop/" class="btn btn-success" role="button" aria-pressed="true">На главную</a>
<hr />
<div class="get-blog">
  <div class="get-blog-header">
    <img src="<?php print($imgSrc); ?>" class="img-mini">
    <h4><?php print($params['description']); ?></h4>
  </div>
  <hr />
  <div class="get-blog-content"><?php print($params['text']); ?></div>
  <hr />
  <div class="get-blog-footer">
    <div></div>
    <small class="form-text text-muted"><?php print($params['datetime_update']); ?></small>
  </div>
</div>
