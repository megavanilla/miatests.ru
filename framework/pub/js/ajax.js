//AJAX лоадер

// создает экземпляр XMLHttpRequest
function createXmlHttpRequestObject()
{
    // переменная для хранения ссылки на объект XMLHttpRequest
    var xmlHttp;
    // эта часть кода должна работать во всех броузерах, за исключением
    // IE6 и более старых его версий
    try
    {
        // попытаться создать объект XMLHttpRequest
        xmlHttp = new XMLHttpRequest();
    }
    catch(e)
    {
        // предполагается, что в качестве броузера используется
        // IE6 или более старая его версия
        var XmlHttpVersions = new Array(
            'MSXML2.XMLHTTP.6.0',
            'MSXML2.XMLHTTP.5.0',
            'MSXML2.XMLHTTP.4.0',
            'MSXML2.XMLHTTP.3.0',
            'MSXML2.XMLHTTP',
            'Microsoft.XMLHTTP');
        // пытаться создавать объект наиболее свежей версии
        // пока одна из попыток не увенчается успехом
        for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
        {
            try
            {
                // попытаться создать объект XMLHttpRequest
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            }
            catch (e)
            {
            }// игнорировать возможные ошибки
        }
    }
    // вернуть созданный объект или вывести сообщение об ошибке
    if (!xmlHttp)
    {
        //alert("Ошибка создания объекта XMLHttpRequest.");
        console.error("Ошибка создания объекта XMLHttpRequest.");
        return null;
    }
    else
    {
        return xmlHttp;
    }

}//End createXmlHttpRequestObject

function ajax(param)
{
    if (window.XMLHttpRequest) xmlHttp = new createXmlHttpRequestObject();
    //if (window.XMLHttpRequest) xmlHttp = new XmlHttp();


    var formData = new FormData();//Объект FormData
    var oFromData;//Переменная, содержит переданные параметры POST
    oFromData = param.data;
    var size_upload_file = 0;//Размер загружаемых файлов
    var msg_error = '';//Сообщение пользователю

    //formData.append('', JSON.stringify(param.data));

    //Для каждого значения создаем отдельную переменную
    for(var data_el in param.data)
    {
        //alert(dump_recurs(data_el,'data_el'));
        formData.append(data_el, param.data[data_el]);
    }

    x = (param.file)?param.file:null;
    async = (param.async)?param.async:true;
    //Возможно авторизацию стоит проверить
    login = (param.login)?param.login:null;
    password = (param.password)?param.password:null;
	
    // продолжать только если xmlHttp не содержит пустую ссылку
    if (xmlHttp)
    {
        // попытаться соединиться с сервером
        try
        {
            xmlHttp.open("POST", param.url, async, login, password);
			
			//alert(dump_recurs(x,'x'));
			
            if (x != null && 'files' in x)
            {
            	/**
            	* Здесь проверка файлов, выполняется до отправки их на сервер
            	**/
            	if(x.files && !x.files.length){
					//alert(123);
				}
                else if (x.files.length == 0)
                {
                    //alert("Выберите один или несколько файлов для загрузки");
                } else if(x.files.length == 1)
                {
                    var file = x.files[0];
                    
                    if(param.upload_max_filesize && !isNaN(param.upload_max_filesize) && file.size > param.upload_max_filesize){
                    	msg_error = 'Размер загружаемого файла не должен превышать ' + humanFileSize(param.upload_max_filesize);
                    	(param.alert_file_error)?alert(msg_error):console.info(msg_error);
					}else{
						formData.append('file', file);
						size_upload_file = file.size;
                    	//alert(humanFileSize(file.size));
					}
                }else
                {
                	var count_files = x.files.length;
                	if(param.max_file_uploads && !isNaN(param.max_file_uploads) && count_files > param.max_file_uploads){
						count_files = param.max_file_uploads;
						msg_error = 'На сервер разрешено загружать не больше ' + param.max_file_uploads + ' файлов, количество загружаемых файлов будет уменьшено';
						(param.alert_file_error)?alert(msg_error):console.info(msg_error);
					}
                    for (var i = 0; i < count_files; i++)
                    {
                        var file = x.files[i];
                        
	                	if(param.upload_max_filesize && !isNaN(param.upload_max_filesize) && file.size > param.upload_max_filesize){
							msg_error = 'Размер загружаемого файла не должен превышать ' + humanFileSize(param.upload_max_filesize);
							(param.alert_file_error)?alert(msg_error):console.info(msg_error);
						}else{
							formData.append('file_'+i, file);
							size_upload_file = size_upload_file + file.size;
                        	//alert(humanFileSize(file.size));
						}
                    }
                }
            }

			/**
			* Если размер запроса больше положенного,
			* то в консоль будет выведено сообщение об этом,
			* а сам запрос выполнен не будет
			**/
			//alert(size_upload_file);
			//alert(humanFileSize(size_upload_file));
			var size_post = size_upload_file;
			//alert('post_max_size = ' + param.post_max_size + '; size_post = ' + size_post);
			if(param.post_max_size && !isNaN(param.post_max_size) && size_post > param.post_max_size){
				msg_error = 'Размер отправляемых данных не должен превышать ' + humanFileSize(param.post_max_size);
				(param.alert_file_error)?alert(msg_error):console.info(msg_error);
			}else{
				//xmlHttp.setRequestHeader("Content-length", size_post);
				xmlHttp.send(formData);
				// Таймаут 10 секунд
				//var timeout = setTimeout( function(){ if(xmlhttp){xmlhttp.abort(); alert('Запрос отклонен по таймауту');} }, 10000);
				//Включаем прелоадер, если он применяется
	            if(document.body && param.use_preload && param.use_preload == true){
	            	var doc_height = getDocumentHeight();
					var doc_width = getDocumentWidth();
					var preloader_page = _createElement('div',{'id':'preloader_page','style':'position:absolute; left:0; top:0; min-height: '+doc_height+'px; width:'+doc_width+'px; margin:0 auto; z-index:999; opacity: .5; background-color: #fff;'});
		            
		            if(param.use_preload_img_href){
						var img = _createElement('img', {'src':param.use_preload_img_href, 'style':'display: block; margin:0 auto; margin-top:'+doc_height/2+'px;'});
						preloader_page.appendChild(img);
					}
		            
	            	document.body.insertBefore(preloader_page,document.body.firstChild);
				}
			}
			
			
            
            xmlHttp.onreadystatechange = function()
            {
            	
                
            	if (xmlHttp.readyState==0)
			    {//О - состояние не инициализировано. Метод open ( ) еще не вызывался.
			        if(param.not_initialized)param.not_initialized(xmlHttp);
			    }
			    else if (xmlHttp.readyState==1)
			    {//1 - запрос создан. Метод open ( ) был вызван, а метод send ( ) - нет.
			        if(param.sending)param.sending(xmlHttp);
			    }
			    else if(xmlHttp.readyState==2)
			    {//2 - запрос отправлен. Метод send ( ) был вызван, но ответ еще не получен.
			        if(param.send)param.send(xmlHttp);
			    }
			    else if(xmlHttp.readyState==3)
			    {//3 - идет получение ответа. Некоторые данные ответа получены.
			    	if(param.exchange)param.exchange(xmlHttp);
			        
			    }
                // не пытаться выполнить запрос к серверу,
                // если XMLHttpObject занят обработкой предыдущего запроса
                // Ограничение запросов производится в случае ручного указания параметра subquery
                if (param.subquery && param.subquery == true && !(xmlHttp.readyState == 0 || xmlHttp.readyState == 4))
                {
                	//Отключаем прелоадер, если он применялся
		            if(param.use_preload && param.use_preload == true){
						_delELement('preloader_page');
					}
                    //alert("Невозможно соединиться с сервером, повторите попытку чуть позже.");
                    console.error("Невозможно соединиться с сервером, повторите попытку чуть позже.");
                }else{

                    // продолжить, если процесс завершен
                    // продолжить только если статус HTTP равен "OK"
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) //если ответ положительный
                    {//4 - запрос выполнен. Все данные ответа получены и доступны.
						//Отключаем прелоадер, если он применялся
			            if(document.body && ge('preloader_page') && param.use_preload && param.use_preload == true){
							_delELement('preloader_page');
						}
                        try
                        {
                        	var resp_data = {};
                        		if(param.type == 'text'){
                        			resp_data = String(xmlHttp.responseText);
								}else if(param.type == 'json'){
									
									try{
										resp_data = JSON.parse(xmlHttp.responseText);
									} catch (e) {
										console.error("Ошибка преобразования ответа из формата JSON в объект\n");
										resp_data = null;
									}
								}else if(param.type == 'xml'){
									try{
										var xmlResponse = xmlHttp.responseXML;
										// перехватить потенциально возможные ошибки в IE и Opera
										if (!xmlResponse || !xmlResponse.documentElement)
											throw("Неверная структура документа XML:\n" + xmlHttp.responseText);
										// перехватить потенциально возможные ошибки в Firefox
										var rootNodeName = xmlResponse.documentElement.nodeName;
										if (rootNodeName == "parsererror")
											throw("Неверная структура документа XML:\n" + xmlHttp.responseText);
											// получить ссылку на корневой элемент XML
											resp_data = xmlResponse.documentElement;
									} catch (e) {
										console.error("Неверная структура документа XML:\n" + xmlHttp.responseText + "\n" + e.toString());
										resp_data = null;
									}
								}else{
									resp_data = xmlHttp.responseText;
								}
			                if(param.success)param.success(resp_data);
                        }
                        catch(e)
                        {
                            // вывести сообщение об ошибке
                            alert("Ошибка чтения ответа сервера: " + e.toString());
                            console.error("Ошибка чтения ответа сервера: " + e.toString());
                        }
		            }//если ответ положительный
                }//если XMLHttpObject не занят обработкой предыдущего запроса
                
                //Если статус не 200 и не 0
				if(xmlHttp.status != 0 && xmlHttp.status != 200){
					console.info('status: '+xmlHttp.status);
					if(!(param.load_error && param.load_error == true)){
						var status = xmlHttp.status;
						var data = JSON.stringify(param.data);
						ajax({
							url: "./system/json_objs/http_status.json",
							type: 'json',
							async: false,
							load_error : true,
							success : function(data_status){									
								//Получаем описание статуса ответа
								var status_message = '';
								var status_message_russ = '';
								var status_desc = '';
								var status_when_used = '';
								
								//alert(dump_recurs(data_status,'data_status'));
								//alert('Статус: ' + status);
								
								if(data_status && data_status[status]
									 && data_status[status]['message']
									 && data_status[status]['message_russ']
									 && data_status[status]['desc']
									 && data_status[status]['when_used']
								){
									status_message =  data_status[status]['message'];
									status_message_russ =  data_status[status]['message_russ'];
									status_desc =  data_status[status]['desc'];
									status_when_used =  data_status[status]['when_used'];
								}
								
								if(status < 200){
									if(console.group){console.group("Статус: " + status_message);}
									console.info("Ресурс: " + param.url);
									if(data){console.info("Данные: " + data);}
									console.info("Информация: " + status_message_russ);
									console.info("Описание: " + status_desc);
									console.info("Когда применяется статус: " + status_when_used);
									if(console.groupEnd){console.groupEnd();}
								}else if(status > 100 && status < 300){
									if(console.group){console.group("Статус: " + status_message);}
									console.info("Ресурс: " + param.url);
									if(data){console.info("Данные: " + data);}
									console.info("Информация: " + status_message_russ);
									console.info("Описание: " + status_desc);
									console.info("Когда применяется статус: " + status_when_used);
									if(console.groupEnd){console.groupEnd();}
								}else if(status > 200 && status < 400){
									if(console.group){console.group("Статус: " + status_message);}
									console.warn("Ресурс: " + param.url);
									if(data){console.warn("Данные: " + data);}
									console.warn("Информация: " + status_message_russ);
									console.warn("Описание: " + status_desc);
									console.warn("Когда применяется статус: " + status_when_used);
									if(console.groupEnd){console.groupEnd();}
								}else if(status > 300 && status < 500){
									if(console.group){console.group("Статус: " + status_message);}
									console.error("Ресурс: " + param.url);
									if(data){console.error("Данные: " + data);}
									console.error("Информация: " + status_message_russ);
									console.error("Описание: " + status_desc);
									console.error("Когда применяется статус: " + status_when_used);
									if(console.groupEnd){console.groupEnd();}
								}else if(status > 400 && status < 600){
									if(console.group){console.group("Статус: " + status_message);}
									console.error("Ресурс: " + param.url);
									if(data){console.error("Данные: " + data);}
									console.error("Информация: " + status_message_russ);
									console.error("Описание: " + status_desc);
									console.error("Когда применяется статус: " + status_when_used);
									if(console.groupEnd){console.groupEnd();}
								}
							}
						});
					}
				}//Обработка ошибок
            }
        }
        // вывести сообщение об ошибке в случае неудачи
        catch (e)
        {
            //alert("Невозможно соединиться с сервером:\n" + e.toString());
            console.error("Невозможно соединиться с сервером:\n" + "Запрашиваемый адрес: " + param.url + "\nОписание ошибки: " + e.toString());
        }
    }
}//End ajax

//Метод выполняет подгрузку скрипта в DOM документ
function addScript(url) {
	ajax({
		url: url,
		type: 'text',
		success:function(data_in)
        {
        	if (navigator.appName=="Microsoft Internet Explorer") {
				window.execScript(data_in);
			} else {
				var obj = document.createElement('script');
				var textScr = document.createTextNode(data_in);
				obj.appendChild(textScr);
				document.body.appendChild(obj);				
			};
        }
	});
};

//Объект MIME типов
var MIME_TYPES =
{
		'application':[
		'application/atom+xml',
		'application/EDI-X12',
		'application/EDIFACT',
		'application/json',
		'application/javascript',
		'application/octet-stream',
		'application/ogg',
		'application/pdf',
		'application/postscript',
		'application/soap+xml',
		'application/x-woff',
		'application/xhtml+xml',
		'application/xml-dtd',
		'application/xop+xml',
		'application/zip',
		'application/gzip',
		'application/x-bittorrent',
		'application/x-tex'
	],
	'audio':[
		'audio/basic',
		'audio/L24',
		'audio/mp4',
		'audio/aac',
		'audio/mpeg',
		'audio/ogg',
		'audio/vorbis',
		'audio/x-ms-wma',
		'audio/x-ms-wax',
		'audio/vnd.rn-realaudio',
		'audio/vnd.wave',
		'audio/webm'
	],
	'example':[],
	'image':[
		'image/gif',
		'image/jpeg',
		'image/pjpeg',
		'image/png',
		'image/svg+xml',
		'image/tiff',
		'image/vnd.microsoft.icon',
		'image/vnd.wap.wbmp'
	],
	'message':[
		'message/http',
		'message/imdn+xml',
		'message/partial',
		'message/rfc822'
	],
	'model':[
		'model/example',
		'model/iges',
		'model/mesh',
		'model/vrml',
		'model/x3d+binary',
		'model/x3d+vrml',
		'model/x3d+xml'
	],
	'multipart':[
		'multipart/mixed',
		'multipart/alternative',
		'multipart/related',
		'multipart/form-data',
		'multipart/signed',
		'multipart/encrypted'
	],
	'text':[
		'text/cmd',
		'text/css',
		'text/csv',
		'text/html',
		'text/javascript',
		'text/plain',
		'text/php',
		'text/xml'
	],
	'video':[
		'video/mpeg',
		'video/mp4',
		'video/ogg',
		'video/quicktime',
		'video/webm',
		'video/x-ms-wmv',
		'video/x-flv',
		'video/3gpp',,
		'video/3gp',
		'video/3gpp2',
		'video/3g2'
	],
	'vnd':[
		'application/vnd.oasis.opendocument.text',
		'application/vnd.oasis.opendocument.spreadsheet',
		'application/vnd.oasis.opendocument.presentation',
		'application/vnd.oasis.opendocument.graphics',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-powerpoint',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.mozilla.xul+xml',
		'application/vnd.google-earth.kml+xml'
	],
	'x':[
		'application/x-www-form-urlencoded',
		'application/x-dvi',
		'application/x-latex',
		'application/x-font-ttf',
		'application/x-shockwave-flash',
		'application/x-stuffit',
		'application/x-rar-compressed',
		'application/x-tar',
		'text/x-jquery-tmpl',
		'application/x-javascript'
	],
	'x-pkcs':[
		'application/x-pkcs12',
		'application/x-pkcs12',
		'application/x-pkcs7-certificates',
		'application/x-pkcs7-certreqresp',
		'application/x-pkcs7-mime',
		'application/x-pkcs7-signature'
	]
};
