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