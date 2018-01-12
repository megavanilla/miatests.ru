/*
Общее функции
 */

function ge(id) {
    var el = document.getElementById(id) || null;
    return (el !== null) ? el : null;
}

function geVal(id) {
    var el = document.getElementById(id) || null;
    return (el !== null && 'value' in el) ? el.value : null;
}

/**
 *
 * @param str
 * @returns {boolean}
 * @constructor
 */
function ValidURL(str) {
    return /^([a-z0-9_\.-]+)\.([a-z\.]{2,6})$|^localhost$/i.test(str);
}

/**
 * Пингователь
 * @constructor
 */
function Pinger(id_console) {
    var parent = this;
    this.maxCountQuery = 5;
    this.countQuery = 0;
    this.host = 0;
    this.img = new Image();
    this.startTime = 0;
    this.endTime = 0;
    var btn_submit_ping = document.getElementById('btn_submit_ping') || null;

    this.setConsoleEl = function (id_console) {
        var elConsole = document.getElementById(id_console) || null;
        this.console = (elConsole && 'value' in elConsole) ? elConsole : null;
    };

    this.setConsoleEl(id_console);

    this.img.onload = function () {
        parent.log();
    };

    this.img.onerror = function () {
        parent.log();
    };

    this.ping = function (host) {
        if (btn_submit_ping !== null) {
            btn_submit_ping.setAttribute('disabled', 'disabled');
        }
        parent.clearConsole();
        var intervalID = setInterval(function () {
            parent.startTime = new Date().getTime();
            parent.host = host;

            parent.img.src = parent.host;

            if (parent.countQuery >= parent.maxCountQuery) {
                clearInterval(intervalID);
                if (btn_submit_ping !== null) {
                    btn_submit_ping.removeAttribute('disabled');
                }
            }
        }, 1000);
    };

    this.log = function () {
        if (parent.countQuery === 0) {
            parent.countQuery++;
            return;
        }
        parent.endTime = new Date().getTime();
        var time = parent.endTime - parent.startTime;
        var minTime = (parent.host === 'http://localhost:80')?1:10;

        if (time >= minTime && time < 2500) {
            parent.logConsole('#Запрос ' + ((++parent.countQuery)-1) + '; Ответ от ' + parent.host + ': Время = ' + time + 'мс.');
        } else {
            parent.logConsole('#Запрос ' + ((++parent.countQuery)-1) + '; Нет ответа от ' + parent.host + ': Время = ' + time + 'мс.');
        }
    };

    this.clearConsole = function () {
        if (!parent.console) {
            console.clear();
        } else {
            parent.console.value = '';
        }
    };

    this.logConsole = function (str) {
        if (!parent.console) {
            console.log(str);
        } else {
            parent.console.value += str + "\r";
        }
    }
}

/**
 * Калькулятор
 * @constructor
 */
function Calculater() {
    var parent = this;
    var statusEl = document.getElementById('status') || null;
    var btnSbmtEl = document.getElementById('btn_submit') || null;

    //
    //Отправка зпароса
    //

    this.calculate = function () {
        var xhr = $.ajax({
            url: "calculate.php",
            method: "POST",
            dataType: "JSON",
            type: "json",
            data: {
                "min_value": geVal('min_value'),
                "max_value": geVal('max_value'),
                "count_numbers": geVal('count_numbers')
            },
            beforeSend: function () {
                parent.displayStatus('Отправка запроса');
                if (btnSbmtEl !== null) {
                    btnSbmtEl.setAttribute('disabled', 'disabled');
                }
            }
        });
        xhr.done(function (dataResp) {
            parent.displayStatus('Обработка результата.');
            parent.parseRes(dataResp);
        });
        xhr.fail(function () {
            parent.displayStatus('Не удалось выполнить запрос.', true);
        });
        xhr.always(function () {
            if (btnSbmtEl !== null) {
                btnSbmtEl.removeAttribute('disabled');
            }
        });
    };

    //
    //Парсинг результатов
    //

    this.parseRes = function (data) {
        if (data !== null && 'res' in data) {
            if (!('status' in data['res']) || 'status' in data['res'] && data['res']['status'] === 'error') {
                parent.resetResult();
                parent.displayStatus('Неудалось расчитать данные', true);
                return false;
            }
            var ranksText = ('ranks' in data['res']) ? parent.prepareResRanks(data['res']['ranks']) : 'n/A';
            var averageText = ('average' in data['res']) ? data['res']['average'] : 'n/A';
            var deviationText = ('deviation' in data['res']) ? data['res']['deviation'] : 'n/A';
            var modeText = ('mode' in data['res']) ? data['res']['mode'] : 'n/A';
            var medianText = ('median' in data['res']) ? data['res']['median'] : 'n/A';

            parent.setResultToSpan('ranks', ranksText);
            parent.setResultToSpan('average', averageText);
            parent.setResultToSpan('deviation', deviationText);
            parent.setResultToSpan('mode', (modeText) ? modeText : 'n/A');
            parent.setResultToSpan('median', medianText);

            parent.hideStatus();
        }
    };

    this.resetResult = function () {
        parent.setResultToSpan('ranks', 'n/A');
        parent.setResultToSpan('average', 'n/A');
        parent.setResultToSpan('deviation', 'n/A');
        parent.setResultToSpan('mode', 'n/A');
        parent.setResultToSpan('median', 'n/A');
    };

    this.prepareResRanks = function (ranks) {
        if (ranks instanceof Array) {
            if (ranks.length > 10) {
                var cnt = ranks.length;
                var str = '';
                for (var i = 0; i < cnt; i++) {
                    if (i < 7) {
                        str += ranks[i] + ', ';
                    }
                    else if (i === cnt - 1) {
                        str += '..., ' + ranks[i];
                    }
                }
                return str;
            } else {
                return ranks;
            }
        } else {
            return 'n/A';
        }
    };

    //
    //Вывод результатов
    //

    this.setResultToSpan = function (id, val) {
        var el = document.getElementById(id) || null;
        if (el !== null && 'innerText' in el) {
            el.innerText = val;
        }
    };

    //
    //Вывод статусов работы класса
    //

    this.displayStatus = function (msg, hideLoaderImage) {
        parent.setResultToSpan('status', msg);
        if (!hideLoaderImage) {
            parent.displayLoaderStatus();
        } else {
            parent.hideLoaderStatus();
        }
        if (statusEl !== null) {
            statusEl.setAttribute('style', 'display: inline-block;');
        }
    };

    this.hideStatus = function () {
        parent.setResultToSpan('status', '');
        if (statusEl !== null) {
            statusEl.setAttribute('style', 'display: none');
        }
    };

    this.displayLoaderStatus = function () {
        if (statusEl !== null) {
            statusEl.setAttribute('class', 'img-status');
        }
    };

    this.hideLoaderStatus = function () {
        if (statusEl !== null) {
            statusEl.removeAttribute('class');
        }
    };

}