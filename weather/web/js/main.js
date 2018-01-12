/**
 * Загружает погоду
 * @param container_chart
 * @constructor
 */
function Reports(container_chart)
{
  this.container_chart = container_chart;

  this.getWeather = function () {
    var parent = this;

    $.ajax({
      method: 'POST',
      type: 'POST',
      data: {
        'q': 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="nome, ak")',
        'env': 'store://datatables.org/alltableswithkeys',
        'format': 'json'
      },
      url: 'https://query.yahooapis.com/v1/public/yql',
      success: function (data) {
        if(
          data.hasOwnProperty('query')
          && data['query'].hasOwnProperty('results')
          && data['query']['results'].hasOwnProperty('channel')
          && data['query']['results']['channel'].hasOwnProperty('item')
          && data['query']['results']['channel']['item'].hasOwnProperty('forecast')
        ){
          var forecast = parent.prepData(data['query']['results']['channel']['item']['forecast']);

          buildChart(parent.container_chart, {
              'title': 'Прогноз погоды',
              'subtitle': '',
              'x_title': 'Дата',
              'y_title': 'Показатель'
            },
            forecast
          );

        }
      }
    });
  };
  this.prepData = function(data){

    var maxTemp = {
      'name': 'Максимальная температура',
      'data': []
    };
    var minTemp = {
      'name': 'Минимальная температура',
      'data': []
    };
    var cloudyImg = {
      'name': 'Облачность',
      'data': [],
      'color': 'rgba(248,161,63,0)',
      'marker': {
        'symbol': 'square'
      },
      dataLabels: {
        useHTML: true,
        enabled: true,
        x: -40,
        y: -90,
        formatter: function () {
          return '<img class="weather_'+this.y+'" src="web/img/transparent.png">';
          //return '<img class="weather_'+45+'" src="web/img/transparent.png">';
        }
      }

    };

    for(var row in data){
      if(data.hasOwnProperty(row)) {
        var date = this.parseDate(data[row]['date']);

        minTemp['data'].push([date, data[row]['low']*1]);
        maxTemp['data'].push([date, data[row]['high']*1]);
        cloudyImg['data'].push([date, data[row]['code']*1]);
      }
    }
    return [
      minTemp,
      maxTemp,
      cloudyImg
    ];
  };
  this.parseDate = function(date){
    if(date){
      return Date.UTC(this.getYear(date), this.getMonth(date), this.getDay(date));
    }
  };
  /**
   * Форматирование года из выборки.
   *
   * @param time
   * @returns {Number}
   * @private
   */
  this.getYear = function (time) {
    if (time) {
      return parseInt(time.toString().substr(7, 4));
    }
  };
  /**
   * Форматирование времени из выборки.
   *
   * @param time
   * @returns {Number}
   * @private
   */
  this.getMonth = function (time) {
    if (time) {
      var month = time.toString().substr(3, 3);
      var num_month = 1;
      switch(month){
        case 'Jan':
          num_month = 1;
          break;
        case 'Feb':
          num_month = 2;
          break;
        case 'Mar':
          num_month = 3;
          break;
        case 'Apr':
          num_month = 4;
          break;
        case 'May':
          num_month = 5;
          break;
        case 'Jun':
          num_month = 6;
          break;
        case 'Jul':
          num_month = 7;
          break;
        case 'Aug':
          num_month = 8;
          break;
        case 'Sep':
          num_month = 9;
          break;
        case 'Oct':
          num_month = 10;
          break;
        case 'Nov':
          num_month = 11;
          break;
        case 'Dec':
          num_month = 12;
          break;
      }

      return num_month;
    }
  };
  /**
   * Форматирование дня из выборки.
   *
   * @param time
   * @returns {Number}
   * @private
   */
  this.getDay = function (time) {
    if (time) {
      return parseInt(time.toString().substr(0, 2));
    }
  };
}

/**
 * Парсит и переводит нижнюю часть страницы
 * @param parent - название предка
 * @constructor
 */
function parsePage(parent) {
    this.parent = parent;
    this.findText = document.getElementById('text') || null;
    if (!this.findText) {
        return false;
    }
    this.buttonFind = this.findText.parentNode.parentNode.parentNode.nextSibling.querySelector('button') || null;
    this.formFind = this.findText.parentNode.parentNode.parentNode.parentNode || null;

    this.initHack = function () {
        this.buttonFind.setAttribute('type', 'button');
        this.buttonFind.setAttribute('onclick', this.parent + '.changeFindText();' + this.parent + '.formFind.submit();');
    };
    this.changeFindText = function () {
        //Для этой операции стоит повторно переопределить элемент, т.к. мы изменил его, переводя содержимое на английский.
        this.findText = document.getElementById('text') || null;
        if (!this.findText) {
            return false;
        }
        this.findText.value = 'qwerty';
        console.log(this.findText);
    };
    this.translateText = function (textTrans) {
        $.ajax({
            method: 'POST',
            type: 'POST',
            data: {
                key: 'trnsl.1.1.20170901T140942Z.0a198e8eb3436479.b07ff9ef976baf43330df3ce03b914b161f1a33f',
                text: textTrans.innerHTML,
                lang: 'ru-en',
                format: 'html'
            },
            url: 'https://translate.yandex.net/api/v1.5/tr.json/translate',
            success: function (data) {
                //console.log(data.text[0]);
                textTrans.innerHTML = data.text[0];
            }
        });

    };
}