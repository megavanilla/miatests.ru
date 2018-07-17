function addNote(){
  let data = document.querySelector('#formAddNote');
  if(!data){
    alert('Не удалось получить данные с формы.');
    return;
  }

  var formElement = document.querySelector("form");
  var request = new XMLHttpRequest();
  request.open("POST", "submitform.php");
  request.send(new FormData(data));

  let description = data.querySelector('#description').value;
  let text = data.querySelector('#text').value;
  let ava = data.querySelector('#ava').files[0];

  ajax(
    {
      url: '/blog/add/', //путь к скрипту, который обрабатывает задачу
      type: 'json',
      async: true,
      data:  {
        description: description,
        text: text,
        ava: ava
      },
      success:function(data)
      {
        if(data.hasOwnProperty('status') && data['status'] === 'ok'){
          alert('Запись успешно добавлена.');
          location.assign('/');
        }else{
          alert('Не удалось добавить запись.');
        }
      }
    });
}

function editNote(id){
  let data = document.querySelector('#formEditNote');
  if(!data){
    alert('Не удалось получить данные с формы.');
    return;
  }

  let idEdit = data.querySelector('#id').value;
  let description = data.querySelector('#description').value;
  let text = data.querySelector('#text').value;
  let ava = data.querySelector('#ava').files[0];

  ajax(
    {
      url: '/blog/edit/', //путь к скрипту, который обрабатывает задачу
      type: 'json',
      async: true,
      data:  {
        id: idEdit,
        description: description,
        text: text,
        ava: ava
      },
      success:function(data)
      {
        if(data.hasOwnProperty('status') && data['status'] === 'ok'){
          alert('Запись успешно изменена.');
          location.assign('/');
        }else{
          alert('Не удалось изменить запись.');
        }
      }
    });
}

function delNote(id){
  //TODO - вместо обновления страницы запилить перерисовку таблицы.
  let isSure = confirm('Вы действительно желаете удалить запись?');
  if(!isSure){return;}
  ajax(
    {
      url: '/blog/del/', //путь к скрипту, который обрабатывает задачу
      type: 'json',
      async: true,
      data:  {id: id},
      success:function(data)
      {
        if(data.hasOwnProperty('status') && data['status'] === 'ok'){
          alert('Запись успешно удалена.');
          location.reload();
        }else{
          alert('Не удалось удалить запись.');
        }
      }
    });
}