//Ajax роутер - обёртка для ajax лоадера с колбеком и прелоадером

//Синхронный запрос
function route_sync(url, method, objParam, sessToken, callback, stopReloadPage, PreloadPage, preloadImgHref)
{
	stopReloadPage = stopReloadPage || false;
	/**
    Если переданы параметры
    **/
    if(objParam){
        objParam = JSON.stringify(objParam);
    }else{
        objParam = null;
    }
    PreloadPage = PreloadPage||true;
	ajax(
        {
            url: url, //путь к скрипту, который обрабатывает задачу
            type: 'json',
            async: false,
            use_preload: PreloadPage,
            use_preload_img_href:preloadImgHref,
            data:  {controller: method, data_send: objParam, token: sessToken},
            success:function(data_in)
            {
            	//Если есть ключ sc_resp, то возвращаем значение этого ключа
                if(data_in && data_in.sc_resp){
                    data_in = data_in.sc_resp;
		}
				
		//Если были сообщения о неактивности сессии, или неактуальности токена
                if(data_in == 'no sess'){
                    if(stopReloadPage == false){
                        alert('Время актуальности страницы истекло, страница будет обновлена.');
                        location.reload();
                    }
                }else if(data_in == 'sess_token_error'){
                    if(stopReloadPage == false){
                        alert('Ошибка получения токена, страница будет обновлена.');
                        location.reload();
                    }
                }
                if(callback){
                    callback(data_in);
                }
                return data_in;
            }
        });
}//End route_sync

//Асинхронный запрос
function route_async(url, method, objParam, sessToken, callback, stopReloadPage, PreloadPage, preloadImgHref)
{
	stopReloadPage = stopReloadPage||false;
	/**
    Если переданы параметры
    **/
    if(objParam){
        objParam = JSON.stringify(objParam);
    }else{
        objParam = null;
    }
    PreloadPage = PreloadPage||true;
	ajax(
        {
            url: url, //путь к скрипту, который обрабатывает задачу
            type: 'json',
            async: true,
            use_preload: PreloadPage,
            use_preload_img_href:preloadImgHref,
            data:  {controller: method, data_send: objParam, token: sessToken},
            success:function(data_in)
            {
            	//Если есть ключ sc_resp, то возвращаем значение этого ключа
                if(data_in && data_in.sc_resp){
                    data_in = data_in.sc_resp;
		}

                //Если были сообщения о неактивности сессии, или неактуальности токена
                if(data_in == 'no sess'){
                    if(stopReloadPage == false){
                        alert('Время актуальности страницы истекло, страница будет обновлена.');
                        location.reload();
                    }
                }else if(data_in == 'sess_token_error'){
                    if(stopReloadPage == false){
                        alert('Ошибка получения токена, страница будет обновлена.');
                        location.reload();
                    }
		}
                if(callback){
                    callback(data_in);
                }
                return data_in;
            }
        });
}//End route_async
