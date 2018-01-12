////Скрипты и основные параметры ядра

var logging = false;//Если истина - то ведется логирование функций в консоль
//console.log(message);

var href = document.location.href;

//Путь к изображению прелоадера
var preloadImgHref = './pub/img/loaders/cub.gif';

//Метод возвращает GET параметры
function $_GET(key)
{
    var s = window.location.search;
    s = s.match(new RegExp(key + '=([^&=]+)'));
    return s ? s[1] : false;
}

//Открывает стандатное модальное окно с содержимым по адресу href
function open_modal_window(href, get_params, desc, data_obj)
{
    return window.open(href + get_params, desc,'resizable=yes,width=750,height=550,left=500,top='+(screen.availHeight/2-365)+',scrollbars=1');
    //return false;
}

//Функция удаляет все пробелы из строки
function clear_all_space(text)
{
    var res = "";
    for(var i= 0, l = text.length; i<l; i++)
    {
        if(text[i] !== " ")
        {
            res += text[i];
        }
    }
    return res;
}

//
//Функция преобразует uri строку в нормальную
function urldecode(str)
{
    return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

/**
Метод является анаологом функции trim для PHP
**/
function trim( str, charlist )
{
    // Strip whitespace (or other characters) from the beginning and end of a string
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: mdsjack (http://www.mdsjack.bo.it)
    // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}

//Проверки

//Функция проверяет наличие элемента в массиве
function in_array(needle, haystack, argStrict)
{
    //  discuss at: http://phpjs.org/functions/in_array/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: vlado houba
    // improved by: Jonas Sciangula Street (Joni2Back)
    //    input by: Billy
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    //   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    //   returns 1: true
    //   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    //   returns 2: false
    //   example 3: in_array(1, ['1', '2', '3']);
    //   example 3: in_array(1, ['1', '2', '3'], false);
    //   returns 3: true
    //   returns 3: true
    //   example 4: in_array(1, ['1', '2', '3'], true);
    //   returns 4: false

    var key = '',
    strict = !! argStrict;

    //we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
    //in just one for, in order to improve the performance
    //deciding wich type of comparation will do before walk array
    if (strict)
    {
        for (key in haystack)
        {
            if (haystack[key] === needle)
            {
                return true;
            }
        }
    } else
    {
        for (key in haystack)
        {
            if (haystack[key] == needle)
            {
                return true;
            }
        }
    }

    return false;
}


//Функция вернёт true, если передана корректная JSON строка
function isValidJSON(src)
{
    var filtered = src;
    filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
    filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
    filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');

    return (/^[\],:{}\s]*$/.test(filtered));
}

//Функция возвращает true, если значение является массивом
function isArray(val)
{
    if (val instanceof Array) return true;
    else return false;
}

//Функция возвращает true, если значение является объектом
function isObject(val)
{
    if (val === null)
    {
        return false;
    }
    return ( (typeof val === 'function') || (typeof val === 'object') );
}

//Функция возвращает true, если массив или объект пуст
function isEmptyObject(obj)
{
    var name;
    for ( name in obj )
    {
        return false;
    }
    return true;
}

/**
 * Проверяет значение переменной на пустоту
 * Аналог PHP empty
 *
 * @param value - Переменная которую нужно проверить
 *
 * @return TRUE если значение не пусто
 */
  
function empty(value) {
    if(typeof value === "undefined"){
        return true;
    }
    switch(value) {
        case "":
        case 0:
        case "0":
        case null:
        case false:
            return true;
        default : return false;
    }
}

/**
 * Проверяет наличие элемента в массиве
 * Аналог PHP in_array
 *
 * @param needle - Искомый элемент
 * @param {array} arr массив в котором будет производиться поиск
 *
 * @return TRUE если элемент есть в массиве
 */
function in_array(needle, arr) {
    return (arr.indexOf(needle) !== -1);
}

//Функция генерирует HTML таблицу из переданного ей массива
function array_to_html_table_row(array, caption, obj_fields_name)
{
    var table = '';
    var str = '<table  id="table_creditors" class="bordered pagination" style="width: 99%; margin: 0 auto;">';
    var str_data ='';
    var str_column ='';

    if(caption != '')
    {
        str += '<caption>'+caption+'<?caption>';
    }
    //Перебираем все строки
    for(var array_rows in array)
    {
        //Строки массива

        str_data +='<tr>';
        str_column ='<tr>';
        for(array_field in array[array_rows])
        {
            //Колонки массива

            if(obj_fields_name && obj_fields_name[array_field])
            {
                str_column += '<th>'+obj_fields_name[array_field]+'</th>';
            }else
            {
                str_column += '<th>'+array_field+'</th>';
            }
            str_data += '<td>'+array[array_rows][array_field]+'</td>';
        }
        str_column +='</tr>';
        str_data += '</tr>';
    }
    str += '<thead>'+str_column+'</thead><tbody>'+str_data+'</tbody></table>';
    return str;
}

/**
Метод возвращает нерекурсивное количество элементов в переданном эелементе.
Если передан объект или массив, то будет возвращено количество элементов;
Если передана строка, то будет возвращена длина строки;
Если передано число, то будет возвращено его значение;
Если передан null, или неопределенное значение, то будет возвращено -1;
**/
function count_el(obj)
{
    //Если пришёл объект
    if(typeof(obj) == "object")
    {
        var count = 0;
        for(var prs in obj)
        {
            if(obj.hasOwnProperty(prs)) count++;
        }
        return count;
    }
    //Если пришёл массив
    if(typeof(obj) == "array" || typeof(obj) == "string")
    {
        return obj.length;
    }
    //Если пришло число
    if(typeof(obj) == "number")
    {
        return obj;
    }
    //Если пришёл undefined
    if(typeof(obj) == "undefined")
    {
        return -1;
    }
    //Если пришёл null
    if(typeof(obj) == "null")
    {
        return -1;
    }
}
 //Функция возвращает строковое значение размера файла
function humanFileSize(size)
{
    size = (size) ? size : 0;
    //size = str_replace(",", ".", size);
    //size.replace(/,/g, ".");
    minus= false; //Признак отрицательности значения
    if (size < 0)
    {
        minus = true;
    }
    //Тут мы получаем целые размеры файла, например: в файле всего 12456789 байт
    _B     = Math.floor(size / (Math.pow(1024, 0)));
    KB     = Math.floor(size / (Math.pow(1024, 1)));
    MB     = Math.floor(size / (Math.pow(1024, 2)));
    GB     = Math.floor(size / (Math.pow(1024, 3)));
    TB     = Math.floor(size / (Math.pow(1024, 4)));
    PB     = Math.floor(size / (Math.pow(1024, 5)));
    EB     = Math.floor(size / (Math.pow(1024, 6)));
    ZB     = Math.floor(size / (Math.pow(1024, 7)));
    YB     = Math.floor(size / (Math.pow(1024, 8)));
    //Тут получаем общий размер файла, например: в файле всего 15 МБ 230 КБ 140 Б
    n_YB   = YB;
    n_ZB   = ZB - YB * 1024;
    n_EB   = EB - ZB * 1024;
    n_PB   = PB - EB * 1024;
    n_TB   = TB - PB * 1024;
    n_GB   = GB - TB * 1024;
    n_MB   = MB - GB * 1024;
    n_KB   = KB - MB * 1024;
    n__B   = _B - KB * 1024;
    //Собираем результирующую строку, если в каком либо разряде нет значения,
    //т.е. 0, то этот разряд не выводим
    Result = (n_YB ? n_YB + "ЙБ. " : ''); //Йотабайт
    Result = Result + (n_ZB ? n_ZB + " ЗБ. " : ''); //Зеттабайт
    Result = Result + (n_EB ? n_EB + " ЭБ. " : ''); //Эксабайт
    Result = Result + (n_PB ? n_PB + " ПБ. " : ''); //Петабайт
    Result = Result + (n_TB ? n_TB + " ТБ. " : ''); //Терабайт
    Result = Result + (n_GB ? n_GB + " ГБ. " : ''); //Гигабайт
    Result = Result + (n_MB ? n_MB + " МБ. " : ''); //Мегабайт
    Result = Result + (n_KB ? n_KB + " КБ. " : ''); //Килобайт
    Result = Result + (n__B ? n__B + " Байт" : ''); //Байт
    if (size)
    {
        //Если передан размер
        if (minus == true)
        {
            //Если передано отрицательной значение
            return "-" . Result;
        }
        else
        {
            //Если передано положительное значение
            return Result;
        }
    }
    else
    {
        //Если размер не передан
        return '0 Байт';
    }
} //End humanFileSize
/**
Когда разбираешься с чужой библиотекой на JavaScript
очень часто нужно посмотреть все допустимые свойства
и методы объекта. Это может сделать простая функция:
Например, чтоб посмотреть все свойства и методы
ui.panel из jQuery нужно выполнить команду:
alert(dump(ui.panel,'ui.panel'));
**/
function dump(obj, prefix)
{
	if(!prefix)
    {
        prefix = '-';
    }
    var result = ""
    //Если obj является объектом, то парсим его в строку
    if(typeof(obj) == "object")
    {
        for (var i in obj)
        {
            result += "\r\n" + prefix + i + " = " + obj[i];
        }
        return result;
    }else
    {
        //Если obj не является объектом, то выводим его как строку
        return obj;
    }
}

/**
Когда разбираешься с чужой библиотекой на JavaScript
очень часто нужно посмотреть все допустимые свойства
и методы объекта. Это может сделать простая функция:
Например, чтоб посмотреть все свойства и методы
ui.panel из jQuery нужно выполнить команду:
alert(dump(ui.panel,'ui.panel'));
Эта функция выполняет рекурсивный дамп пообъекту
**/
function dump_recurs(obj, objname, prefix, postfix)
{

    if(!prefix)
    {
        prefix = '-';
    }
    if(!postfix)
    {
        postfix = '>';
    }

    var result = ""
    //Если obj является объектом, то парсим его в строку
    if(typeof(obj) == "object" || typeof(obj) == "array")
    {
        try
        {
            for (var i in obj)
            {
                if(obj[i] && (typeof(obj[i]) == "object" || typeof(obj[i]) == "array"))
                {
                    result += "\r\n" + prefix + postfix + i + dump_recurs(obj[i], objname, prefix + prefix, postfix);
                }else
                {
                    result += "\r\n" + prefix + postfix + i + " = " + obj[i];
                }
            }
        } catch (e)
        {
            //alert(e.lineNumber);
        }
        return result;
    }else
    {
        //Если obj не является объектом, то выводим его как строку
        return obj;
    }
}

//Функция упрощает получение тега по id
function ge(id)
{
    return document.getElementById(id);
}

//Функция упрощает получение массива тегов по указанному имени
function geN(name)
{
    return document.getElementsByTagName(name);
}//End geN


//Функция скрывает всплывающее окно,
// путем перехода на основную страницу
function action_cancel()
{
    //ge('popup').innerHTML = "";//Чистим содержимое модального окна
    document.location.href = href;
    return true;
}//action_cancel

////////////////////////////////////////////////////////////////////
///////////////Сведения от браузере///////////////
////////////////////////////////////////////////////////////////////

//Ширина окна
function getClientWidth()
{
    return document.compatMode=='CSS1Compat' && document.documentElement.clientWidth;
}

//Высота окна
function getClientHeight()
{
    return document.compatMode=='CSS1Compat' && document.documentElement.clientHeight;
}

//Размер документа по вертикали
function getDocumentHeight()
{
	if(document.body && document.body.scrollHeight){
    	return (document.body.scrollHeight > document.body.offsetHeight)?document.body.scrollHeight:document.body.offsetHeight;
    }else{
		return 0;
	}
}

//Размер документа по горизонтали
function getDocumentWidth()
{
	if(document.body && document.body.scrollWidth){
    	return (document.body.scrollWidth > document.body.offsetWidth)?document.body.scrollWidth:document.body.offsetWidth;
    }else{
		return 0;
	}
}


function sizeContent(id)
{
    var sizeWindow = getClientHeight()-270;
    var obj = document.getElementById(id);
    if(obj)
    {
        if(getUserBrowser(false) != 'ie5' && getUserBrowser() != 'ie6' && getUserBrowser(false) != 'ie7')
        {
            if ('style' in obj)
            {
                obj.style.height = sizeWindow + 'px';
            }
        } else
        {
            if ('style' in obj)
            {
                obj.style.height = sizeWindow;
            }
        }
    }
}

function alignCenter(id)
{
    var sizeHeight = document.compatMode=='CSS1Compat' && document.documentElement.clientHeight;
    var sizeWidth = document.compatMode=='CSS1Compat' && document.documentElement.clientWidth;
    var centerObj = document.getElementById(id);
    var LeftObj = sizeWidth/2 - centerObj.style.width/2 - 125;
    var TophObj = sizeHeight/2 - centerObj.style.height/2;

    if(getUserBrowser(false) != 'ie5' && getUserBrowser() != 'ie6' && getUserBrowser(false) != 'ie7')
    {
        centerObj.style.left = LeftObj + 'px';
        centerObj.style.top = TophObj + 'px';

    } else
    {
        centerObj.style.left = LeftObj;
        centerObj.style.top = TophObj;
    }

    /*console.log('Ширина окна: ' + sizeHeight + '; Высота окна:' + sizeWidth +
    '\nШирина элемента: ' + centerObj.style.width + '; Высота элемента: ' + centerObj.style.height +
    '\nСередина слева: ' + LeftObj + '; Середина сверху: ' + TophObj);*/
}

//Функция возвращает строку, содержащую название клиентского браузера
function getUserBrowser(ieCommon)
{
    ieCommon = ieCommon || false;

    var agent = navigator.userAgent;
    var agentStr;

    //Получаем поле выбора файла
    if (-1 < agent.indexOf('Firefox'))
    {
        agentStr = 'firefox';
    }
    if (-1 < agent.indexOf('Chrome'))
    {
        agentStr = 'chrome';
    }
    if (-1 < agent.indexOf('Safari'))
    {
        agentStr = 'safari';
    }
    if (-1 < agent.indexOf('Opera'))
    {
        agentStr = 'opera';
    }
    if (-1 < agent.indexOf('MSIE 5.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie5';
        }
    };
    if (-1 < agent.indexOf('MSIE 6.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie6';
        }
    };
    if (-1 < agent.indexOf('MSIE 7.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie7';
        }
    };
    if (-1 < agent.indexOf('MSIE 8.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie8';
        }
    };
    if (-1 < agent.indexOf('MSIE 9.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie9';
        }
    };
    if (-1 < agent.indexOf('MSIE 10.0'))
    {
        if(ieCommon == true)
        {
            agentStr = 'ie';
        }else
        {
            agentStr = 'ie10';
        }
    };

    return agentStr;
}

/////////////////////////////////////////////////////////////
///////////////Вспомогательные функции///////////////
/////////////////////////////////////////////////////////////

// Функция генерации случайных чисел от min до max
function rand(min, max)
{
    max = max || false;
    min = min || 255;

    if (max)
    {
        return Math.floor(Math.random()*(max-min+1))+min;
    }
    else
    {
        return Math.floor(Math.random()*(min+1));
    }
}
//Функция выполняет подключение файлов в код документа
function include_js(url) {
	if (navigator.appName=="Microsoft Internet Explorer") {
		window.execScript(url);
	} else {
		var obj = document.createElement('script');
		var textScr = document.createTextNode(url);
		document.body.appendChild(obj);
		obj.appendChild(textScr);
	};
}
/**
*
* Напишем вспомогательную функцию bind(func, context),
* которая будет жёстко фиксировать контекст для func:
*
**/
function bind_new(func, context) {
  return function() { // (*)
    return func.apply(context, arguments);
  };
}

//Это для браузеров меньше ie8, и других стареньких
function bind(func, context , args) {
  var bindArgs = [].slice.call(arguments, 2); // (1)
  function wrapper() {                        // (2)
    var args = [].slice.call(arguments);
    var unshiftArgs = bindArgs.concat(args);  // (3)
    return func.apply(context, unshiftArgs);  // (4)
  }
  return wrapper;
}
/////////////////////////////////////////////////////////////
///////////////Функции работы с DOM елементами///////////////
/////////////////////////////////////////////////////////////
/**
Например, создаём абзац текста:
var el = create( «p», { }, «Farewell, Love!» );
**/
// Функция возвращает созданный елемент с атрибутами
function _createElement(name, attributes)
{
    name = name || null;
    attributes = attributes || null;

    if(name != null)
    {
        var el = document.createElement( name );
        if ( typeof attributes == 'object' )
        {
            for ( var i in attributes )
            {
                el.setAttribute( i, attributes[i] );

                if ( i.toLowerCase() == 'class' )
                {
                    el.className = attributes[i];  // for IE compatibility

                } else if ( i.toLowerCase() == 'style' )
                {
                    el.style.cssText = attributes[i]; // for IE compatibility
                }
            }
        }
        for ( var i = 2; i<arguments.length; i++ )
        {
            var val = arguments[i];
            if ( typeof val == 'string' )
            {
                val = document.createTextNode( val )
            };
            el.appendChild( val );
        }
        return el;
    } else
    {
        return null;
    }
}

//Функция удалаяет элемент по идентификатору
function _delELement(id)
{
    id = id || null;
    if(id !=null)
    {
        delElem=document.getElementById(id);
        var par = delElem.parentNode;
        par.removeChild(delElem);
    }
}

//Функция удаляет дочерние элементы
function _delChildElements(id, element)
{
    id = id || null;
    element = element || null;

    if( id != null)
    {
        //Находим элемент, потомков которого необходимо удалить
        delElem=document.getElementById(id);

        if(element != null)
        {
            //Если у элемента есть потомки
            if (delElem.hasChildNodes())
            {
                //Перебираем всех потомков
                for (i=0; i<delElem.childNodes.length; i++)
                {
                    var currentNode = delElem.childNodes[i];

                    //Если был передан тип элемента, для удаляемых потомков,
                    //то удаляем только, соответствующие типу, элементы
                    if (currentNode.nodeName.toLowerCase() == element)
                    {
                        delElem.removeChild(currentNode);
                    }
                }
            }
        } else
        {
            //Если не был передан тип удаляемого потомка, то удаляем всех потомков
            delElem.innerHTML='';
        }
    }
}

//Функция добавляет элементы выбора файла
function add_Select_files()
{
    var rndAddBtn = 'addFile' + rand(0, 9999999);    //Создаем идентификатор для поля выбора файла
    var rndDelBtn = 'delFile' + rand(0, 9999999);    //Создаем идентификатор для кнопки удаления поля выбора
    var rndBR = 'brFile' + rand(0, 9999999);        //Создаем идентификатор для переноса строки
    var rndText = 'textFile' + rand(0, 9999999);        //Создаем идентификатор для текстового поля выбранного файла

    //Создаем элементы выбора файла
    var inpFile = _createElement( 'input', {'type': 'file', 'id':rndAddBtn, 'name': 'userfile[]', 'size':'45', 'multiple':true});
    var delButton = _createElement( 'input', {'type': 'button', 'id':rndDelBtn, 'value':'-', 'onclick':'_delELement(\'' + rndAddBtn + '\'); _delELement(\'' + rndDelBtn + '\'); _delELement(\'' + rndBR + '\');'});
    var inBR = _createElement( 'br', {'id':rndBR});

    //Вставляем созданные элементы
    document.getElementById('add_files').appendChild(inpFile);
    document.getElementById('add_files').appendChild(delButton);
    document.getElementById('add_files').appendChild(inBR);

}


//Подготовка объектов формы к отправке
//Иными словами функция возвращает объект из данных выбранной формы по ее идентификатору
function prepForm(id)
{

    if(id)
    {
        // получаем форму, допустим, она имеет идентификатор myForm
        var form = document.getElementById(id);
    }

    if(form)
    {

        // получаем все элементы формы
        var formElements = form.elements;

        var arrData = new Object();//Объявляем массив для данных формы

        //var strTest='';

        // в цикле перебираем элементы формы
        for (var j=0; j<formElements.length; j++)
        {

            // тип j-го элемента формы
            var type = formElements[j].type;

            // имя элемента
            var name = formElements[j].name

            // значение элемента
            var value = formElements[j].value

            //alert(name + " = " + value);

            //strTest = strTest+"Тип: "+type+"; Имя: "+name+": Значение: "+value+"\n";
            //alert(formElements[j].type + ':' + formElements[j].value);
            if(formElements[j].type != "button" && formElements[j].type != "submit")
            {
                //Если элемент формы не кнопка, то добавляем в объект данных
                if(formElements[j].type == "select-multiple")
                {
                    var indexList = [];
                    var cntVal=0;
                    arrData[name] = "";
                    for (var i = 0; i < formElements[j].length; i++)
                    {
                        if (formElements[j][i].selected)
                        {
                            if(cntVal>0)
                            {
                                arrData[name] = arrData[name] + "," + formElements[j][i].getAttribute('value');
                            }else
                            {
                                //Если это первое значение, то запятую ставить не надо
                                arrData[name] = formElements[j][i].getAttribute('value');
                            }
                            cntVal = cntVal+1;
                        };
                    };

                    //Если так и не получили значение то устанавливаем первый элемент
                    if(!arrData[name])
                    {
                        arrData[name] = formElements[j][0];//"false";
                    }

                }else if(formElements[j].type == "select-one")
                {
                    var indexList = [];
                    for (var i = 0; i < formElements[j].length; i++)
                    {
                        if (formElements[j][i].selected)
                        {
                            arrData[name] = value;
                        };
                    };

                    //Если так и не получили значение то устанавливаем первый элемент
                    if(!arrData[name])
                    {
                        arrData[name] = formElements[j][i];//"false";
                    }

                }else if(formElements[j].type == "radio")
                {
                    var indexList = [];

                    var inputs = document.getElementsByName(formElements[j].name);
                    var selectedValue;
                    for (var i = 0; i < inputs.length; i++)
                    {
                        if (inputs[i].checked)
                        {
                            //indexList.push(value);
                            arrData[name] = inputs[i].value;
                            break;
                        }
                    }

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "false";
                    }

                }else if(formElements[j].type == "checkbox")
                {
                    var indexList = [];
                    if (formElements[j].checked)
                    {
                        arrData[name] = "true";
                    } else
                    {
                        arrData[name] = "false";
                    };

                }else if(formElements[j].type == "file")
                {
                    arrData[name] = value;

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "false";
                    }
                }else if(formElements[j].type == "text" || formElements[j].type == "hidden" || formElements[j].type == "textarea" || formElements[j].type == "password" || formElements[j].type == "email" || formElements[j].type == "url" || formElements[j].type == "tel" || formElements[j].type == "search" || formElements[j].type == "pattern" || formElements[j].type == "color" || formElements[j].type == "month" || formElements[j].type == "week" || formElements[j].type == "time")
                {

                    arrData[name] = value;

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "";
                    }
                }else if(formElements[j].type == "number" || formElements[j].type == "range")
                {

                    arrData[name] = value;

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "0";
                    }
                }else if(formElements[j].type == "date" || formElements[j].type == "datetime" || formElements[j].type == "datetime-local")
                {

                    arrData[name] = value;

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "0000-00-00 00:00:00";
                    }

                }else if(formElements[j].value != 0 && formElements[j].value != "undefined" && formElements[j].value != undefined && formElements[j].value != null)
                {
                    arrData[name] = value;

                    //Если так и не получили значение то возвращаем false
                    if(!arrData[name])
                    {
                        arrData[name] = "0";
                    }
                }
            }
            //alert(formElements[j].type+':'+formElements[j].name+':'+formElements[j].value)
        }

        /*
        var str='';

        // в цикле перебираем элементы формы
        for(var p in arrData) {

        str = str+"Ключ: "+p+"; Значение: "+arrData[p]+"\n";
        }

        alert(strTest);

        alert(str);

        */

        return arrData;
    } else
    {
        return null
    }
}//End prepForm

//Функция выполняет проверку браузера, если это ie<8
//То выдаем сообщение об этом
function errorBrowser()
{
    var helpurl = 'http://windows.microsoft.com/ru-ru/internet-explorer/download-ie';
    var message = 'Ваш браузер устарел. Сайт будет работать неправильно. Чтобы исправить проблему нажмите здесь.';
    if(getUserBrowser(false) == 'ie5' || getUserBrowser(false) == 'ie6' || getUserBrowser(false) == 'ie7' || getUserBrowser(false) == 'ie8' || getUserBrowser(false) == 'ie9')
    {

        /*alert('Внимание! Ваш браузер устарел, необходимо его обновить браузер!');*/
        console.log('Внимание! Ваш браузер устарел, необходимо его обновить! http://windows.microsoft.com/ru-ru/internet-explorer/download-ie');
        document.write('<!DOCTYPE HTML>'+"\n");
        document.write('    <html>'+"\n");
        document.write('    <head>'+"\n");
        document.write('        <style>'+"\n");
        document.write('            #old'+"\n");
        document.write('            {'+"\n");
        document.write('                background: transparent url("./system/img/oldBrowser/bkg-body.jpg") no-repeat right top;'+"\n");
        document.write('                height: 100%;'+"\n");
        document.write('                /*width: 100%;*/'+"\n");
        document.write('                font-family: Tahoma, Verdana, Helvetica, Arial, sans-serif; /* Follows MSCOM Typography Guidelines */'+"\n");
        document.write('                color: #4b4b4b;'+"\n");
        document.write('                font-size: 0.78em;'+"\n");
        document.write('                width:783px;'+"\n");
        document.write('                margin: auto;'+"\n");
        document.write('            }'+"\n");
        document.write('            .descriptiontext .file'+"\n");
        document.write('            {'+"\n");
        document.write('                text-indent: 1.5em; /* Отступ первой строки */'+"\n");
        document.write('            }'+"\n");
        document.write('            .delim'+"\n");
        document.write('            {'+"\n");
        document.write('                background-color: silver;'+"\n");
        document.write('                height: 1px;'+"\n");
        document.write('                border-width:0px; /* Убрать рамки вокруг элемента */'+"\n");
        document.write('            }'+"\n");
        document.write('            #modern-browser'+"\n");
        document.write('            {'+"\n");
        document.write('                margin:0;'+"\n");
        document.write('                padding:10px 20px 20px;'+"\n");
        document.write('                list-style:none;'+"\n");
        document.write('            }'+"\n");
        document.write('            #modern-browser li'+"\n");
        document.write('            {'+"\n");
        document.write('                padding-left:130px;'+"\n");
        document.write('                margin-top:20px;'+"\n");
        document.write('            }'+"\n");
        document.write('            #modern-browser li.ie8{background:url("./system/img/oldBrowser/big-ie8.gif") no-repeat 5px top;}'+"\n");
        document.write('            #modern-browser li.chrome{background:url("./system/img/oldBrowser/big-chrome.gif") no-repeat 5px top;}'+"\n");
        document.write('            #modern-browser li.opera{background:url("./system/img/oldBrowser/big-opera.gif") no-repeat 5px top;}'+"\n");
        document.write('            #modern-browser li.firefox{background:url("./system/img/oldBrowser/big-firefox.gif") no-repeat 5px top;}'+"\n");
        document.write('        </style>'+"\n");
        document.write('        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'+"\n");
        document.write('    </head>'+"\n");
        document.write('    <body>'+"\n");
        document.write('        <div id="oldies-bar" style="z-index: 65535; background: #ffffe1 url(./system/img/oldBrowser/exclaim.gif) no-repeat 7px 2px; border-bottom: 1px solid #716f64; border-top: 1px solid #e0dfd0; padding: 0; margin: 0; position: fixed; width:100%; height: 21px; left:0; top:0; _position: absolute; _top: expression(eval(document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)); _left: expression(eval(document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft)); _width: expression(eval(document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth));"><span style="display: block; float: right; padding: 2px 7px 2px 7px; margin: 0; cursor: pointer; font: 12px Verdana; color: #536482;" onclick="document.getElementById(\'oldies-shadow\').style.display=\'none\'; document.getElementById(\'oldies-bar\').style.display=\'none\';">×</span><a target="_blank" href="'+helpurl+'" style="display: block; text-decoration: none; cursor: pointer; padding: 3px 0 2px 26px; margin: 0 30px 0 0; font: 11px Verdana; color: #536482;">'+message+'</a></div><div id="oldies-shadow" style="height: 22px; padding: 0; margin: 0;"></div>'+"\n");
        document.write('        <div id="old">'+"\n");
        document.write('            <div id="header">'+"\n");
        document.write('            <h1>Ваш браузер устарел!</h1>'+"\n");
        document.write('            <font>Вы пользуетесь устаревшей версией браузера Internet Explorer. Данная версия браузера не поддерживает многие современные технологии, из-за чего многие страницы отображаются некорректно, а главное — на сайтах могут работать не все функции. В связи с этим на Ваш суд представляются более современные браузеры. Все они бесплатны, легко устанавливаются и просты в использовании. При переходе на любой нижеуказанный браузер все ваши закладки и пароли будут перенесены из текущего браузера, вы ничего не потеряете.</font>'+"\n");
        document.write('        </div>'+"\n");
        document.write('        <ul id="modern-browser">'+"\n");
        document.write('            <li class="firefox">'+"\n");
        document.write('                <h2>Mozilla Firefox</h2>'+"\n");
        document.write('                    <div class="descriptiontext">'+"\n");
        document.write('                        Один из самых распространенных и гибких браузеров. Браузер может быть настроен под себя на любой вкус при помощи огромного числа дополнений на все случаи жизни и тем оформления, которые вы найдете на официальном сайте дополнений.'+"\n");
        document.write('                        <ul class="file">'+"\n");
        document.write('                            <li style="padding-left: 0px;"><a href="http://www.mozilla.org/firefox/">Перейти к загрузке Mozilla Firefox</a></li>'+"\n");
        document.write('                        </ul>'+"\n");
        document.write('                    </div>'+"\n");
        document.write('                </li>'+"\n");
        document.write('                <hr class="delim" />'+"\n");
        document.write('                    <li class="chrome">'+"\n");
        document.write('                        <h2>Google Chrome</h2>'+"\n");
        document.write('                        <div class="descriptiontext">'+"\n");
        document.write('                            Новый, но уже достаточно популярный браузер от гиганта поисковой индустрии, компании Google. Обладает очень простым и удобным интерфейсом. Если вам нужен просто браузер без специальных функций — для вас Google Chrome станет лучшим выбором.'+"\n");
        document.write('                            <ul class="file">'+"\n");
        document.write('                                <li style="padding-left: 0px;"><a href="http://www.google.com/chrome/">Перейти к загрузке Google Chrome</a></li>'+"\n");
        document.write('                            </ul>'+"\n");
        document.write('                        </div>'+"\n");
        document.write('                    </li>'+"\n");
        document.write('                <hr class="delim" />'+"\n");
        document.write('                    <li class="opera">'+"\n");
        document.write('                        <h2>Opera</h2>'+"\n");
        document.write('                        <div class="descriptiontext">'+"\n");
        document.write('                            Браузер Opera всегда позиционировался, как очень удобный и быстрый. Имеет внутренние утилиты для ускорения загрузки страниц, особенно актуально для пользователей с медленным интернетом. Хотя отлично подойдет и любым другим пользователям.'+"\n");
        document.write('                            <ul class="file">'+"\n");
        document.write('                                <li class="v9" style="padding-left: 0px;"><a href="http://www.opera.com/">Перейти к загрузке Opera</a></li>'+"\n");
        document.write('                            </ul>'+"\n");
        document.write('                        </div>'+"\n");
        document.write('                </li>'+"\n");
        document.write('                <hr class="delim" />'+"\n");
        document.write('                    <li class="ie8">'+"\n");
        document.write('                        <h2>Новый Internet Explorer</h2>'+"\n");
        document.write('                        <div class="descriptiontext">'+"\n");
        document.write('                            <span>Современная версия браузера от компании Microsoft. Бесплатно предоставляется всем желающим и свободен для распространения. Если слова «браузер» и «Internet Explorer» для вас незнакомы, установите эту программу.</span>'+"\n");
        document.write('                            <ul class="file">'+"\n");
        document.write('                                <li class="microsoft" style="padding-left: 0px;"><a href="http://www.microsoft.com/rus/windows/internet-explorer/">Перейти к загрузке Internet Explorer</a></li>'+"\n");
        document.write('                            </ul>'+"\n");
        document.write('                        </div>'+"\n");
        document.write('                    </li>'+"\n");
        document.write('                <hr class="delim" />'+"\n");
        document.write('            </ul>'+"\n");
        document.write('        </div>'+"\n");
        document.write('    </body>'+"\n");
        document.write('</html>'+"\n");

        //Узнаем имя хоста
        //var url =  location.protocol + "//" + location.host;
        //document.location.href = url + '/old_browser.html';

    }
}