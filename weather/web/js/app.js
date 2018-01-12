/**
 * Строит график на основе полученного массива данных
 * @param id (string) - идентификатор тега, куда будет загружен график
 * @param params (object) - массив основных параметров для графика
 * title - Заголовок графика
 * subtitle - Подзаголовок графика
 * x_title - Заголовок оси X графика
 * y_title - Заголовок оси Y графика
 * @param data_chart (array objects) - данные для графика
 * состоит из объектов вида:
 * name - название показателя
 * data - данные
 * @returns {boolean | chart}
 */
function buildChart(id, params, data_chart) {
  var container = document.getElementById(id) || null;
  if (container === null || !params) {
    return false;
  }

  var title = (typeof(params.title) !== 'undefined') ? params.title : 'Заголовок графика';
  var subtitle = (typeof(params.subtitle) !== 'undefined') ? params.subtitle : 'Подзаголовок графика';
  var x_title = (typeof(params.x_title) !== 'undefined') ? params.x_title : 'Заголовок оси X графика';
  var y_title = (typeof(params.y_title) !== 'undefined') ? params.y_title : 'Заголовок оси Y графика';
  //var xAxis_type = (typeof(params.xAxis_type) !== 'undefined') ? params.xAxis_type : 'Заголовок оси Y графика';

  Highcharts.setOptions({
    lang: {
      months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
      shortMonths: ['Янв.', 'Фев.', 'Март', 'Апр.', 'Май', 'Июнь', 'Июль', 'Авг.', 'Сент.', 'Окт.', 'Ноя.', 'Дек.'],
      weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
      shortWeekdays: ['Вс.', 'Пн.', 'Вт.', 'Ср.', 'Чт.', 'Пт.', 'Сб.']
    }
  });

  return Highcharts.chart(id, {
    chart: {
      type: 'column'
    },
    title: {
      text: title
    },
    subtitle: {
      text: subtitle
    },

    xAxis:
      {
        type: 'datetime',
        title: {
          text: x_title
        }
      },
    yAxis: {
      title: {
        text: y_title
      },
      min: 0
    },
    tooltip: {
      dateTimeLabelFormats: { // don't display the dummy year
        millisecond: "%A, %b %e, %H:%M:%S.%L",
        second: "%A, %b %e, %H:%M:%S",
        minute: "%A, %b %e, %H:%M",
        hour: "%A, %b %e, %H:%M",
        day: "%A, %b %e, %Y",
        week: "%A, %b %e, %Y",
        month: "%B %Y",
        year: "%Y"
      }
      //headerFormat: '<b>{series.name}</b><br>',
      //pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
    },

    plotOptions: {
      column: {
        pointPadding: 0.2,
        borderWidth: 0
      }
    },
    series: data_chart
  })/*.renderer.image('https://www.highcharts.com/samples/graphics/sun.png', 100, 100, 30, 30).add()*/;
}