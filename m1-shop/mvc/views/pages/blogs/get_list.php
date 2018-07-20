<?php

$params = ($params)?$params:[];

?>
<a href="../../m1-shop/blog/addNote/" class="btn btn-success" role="button" aria-pressed="true">Добавить</a>
<hr/>
<table id="listNotes" class="table table-striped table-bordered" style="width:100%">
  <thead>
  <tr>
    <th>Id</th>
    <th>Последнее обновление</th>
    <th>Описание</th>
    <th>Ава</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php for ($i = 0; $i < count($params); $i++): ?>
    <?php
    $imgSrc = ($params[$i]['href_img']) ? '/m1-shop/web/uploads/'.$uploadDir . $params[$i]['href_img'] : '/m1-shop/web/img/no-ava.png';
    $desc = $params[$i]['description'];
    ?>
    <tr>
      <td><?php print($params[$i]['id']); ?></td>
      <td><?php print($params[$i]['datetime_update']); ?></td>
      <td><a href="../../m1-shop/blog/get?id=<?php print($params[$i]['id']); ?>" class="" role="button"
             aria-pressed="true"><?php print($desc); ?></a></td>
      <td><img src="<?php print($imgSrc); ?>" class="img-mini img-responsive img-thumbnail"
               alt="<?php print($desc); ?>"></td>
      <td class="text-nowrap">
        <a href="../../m1-shop/blog/get?id=<?php print($params[$i]['id']); ?>" class="btn btn-primary" role="button"
           aria-pressed="true">Просмотреть</a>
        <a href="../../m1-shop/blog/editNote?id=<?php print($params[$i]['id']); ?>" class="btn btn-primary" role="button"
           aria-pressed="true">Изменить</a>
        <button class="btn btn-danger" onclick="delNote('<?php print($params[$i]['id']); ?>');">Удалить</button>
      </td>
    </tr>
  <?php endfor; ?>
  </tbody>
  <tfoot>
  <tr>
    <th>Id</th>
    <th>Последнее обновление</th>
    <th>Описание</th>
    <th>Ава</th>
    <th>Действия</th>
  </tr>
  </tfoot>
</table>
