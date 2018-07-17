<?php
use mvc\libs\Request;
$Request = new Request();
global $Configs;

$uploadDir = $Request->getVariable($Configs, ['conf', 'main', 'uploads'], '');

?>
<a href="/blog/addNote/" class="btn btn-success" role="button" aria-pressed="true">Добавить</a>
<hr />
<table id="listNotes" class="table table-striped table-bordered" style="width:100%">
  <thead>
  <tr>
    <th>Id</th>
    <th>Последнее обновление</th>
    <th>Описание</th>
    <th>Текст</th>
    <th>Ава</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php for ($i = 0; $i < count($params); $i++): ?>
    <?php
      $imgSrc = ($params[$i]['href_img'])?$uploadDir.$params[$i]['href_img']:'/web/img/no-ava.png';
    ?>
    <tr>
      <td><?php print($params[$i]['id']); ?></td>
      <td><?php print($params[$i]['datetime_update']); ?></td>
      <td><?php print($params[$i]['description']); ?></td>
      <td><?php print($params[$i]['text']); ?></td>
      <td><img src="<?php print($imgSrc); ?>" class="img-mini"> </td>
      <td>
        <a href="/blog/editNote?id=<?php print($params[$i]['id']); ?>" class="btn btn-primary" role="button" aria-pressed="true">Изменить</a>
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
    <th>Текст</th>
    <th>Ава</th>
    <th>Действия</th>
  </tr>
  </tfoot>
</table>
