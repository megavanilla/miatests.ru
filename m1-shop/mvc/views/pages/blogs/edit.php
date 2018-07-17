<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 23:22
 */

$id =  0;
$description = $text = $href_img = '';
if (!empty($params) && array_key_exists(0, $params)) {
  $id = array_key_exists('id', $params[0]) ? $params[0]['id'] : $id;
  $description = array_key_exists('description', $params[0]) ? $params[0]['description'] : $description;
  $text = array_key_exists('text', $params[0]) ? $params[0]['text'] : $text;
  $href_img = array_key_exists('href_img', $params[0]) ? $params[0]['href_img'] : $href_img;
}
?>
<a href="/" class="btn btn-success" role="button" aria-pressed="true">На главную</a>
<hr />
<form id="formEditNote" enctype="multipart/form-data">
  <div class="form-group">
    <label for="description">Описание</label>
    <input type="text" class="form-control" id="description" name="description" placeholder="Введите краткое описание" value="<?php print($description); ?>">
  </div>
  <div class="form-group">
    <label for="text">Текст</label>
    <textarea class="form-control" id="text" name="text" rows="3" placeholder="Введите текст"
              aria-describedby="textHelp"><?php print($text); ?></textarea>
    <small id="textHelp" class="form-text text-muted">Здесь можно вводить много букв.</small>
  </div>
  <div class="form-check">
    <label class="form-check-label" for="ava">Изображение</label>
    <input type="file" class="form-check-input" id="ava" name="ava">
  </div>
  <input type="hidden" class="form-check-input" id="id" name="id" value="<?php print($id); ?>">
  <hr/>
  <button type="button" class="btn btn-success" onclick="editNote(<?php print($id); ?>)">Изменить</button>
</form>