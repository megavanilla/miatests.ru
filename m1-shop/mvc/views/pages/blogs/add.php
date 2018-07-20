<a href="/m1-shop/" class="btn btn-success" role="button" aria-pressed="true">На главную</a>
<hr />
<form id="formAddNote" enctype="multipart/form-data">
  <div class="form-group">
    <label for="description">Описание</label>
    <input type="text" class="form-control" id="description" name="description" placeholder="Введите краткое описание">
  </div>
  <div class="form-group">
    <label for="text">Текст</label>
    <textarea class="form-control" id="text" name="text" rows="3" placeholder="Введите текст"aria-describedby="textHelp"></textarea>
    <small id="textHelp" class="form-text text-muted">Здесь можно вводить много букв.</small>
  </div>
  <div class="form-check">
    <label class="form-check-label" for="ava">Изображение</label>
    <input type="file" class="form-check-input" id="ava" name="ava">
  </div>
  <hr />
  <button type="button" class="btn btn-success" onclick="addNote();">Добавить</button>
</form>