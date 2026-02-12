// Kw’s JS
// Copyright © 2014-2026, Chris Warrick.

function onLoaded() {
    if (document.cookie.indexOf('kw_cookies=2') == -1) {
        if (document.querySelector('html').lang == 'pl') {
            document.querySelector("#cookiebox").innerHTML = '<div class="alert alert-primary alert-dismissible fade show" role="alert" id="cookiealert">Ta strona używa ciasteczek. <a href="/pl/privacy/" class="alert-link">Dowiedz się więcej.</a> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            document.querySelector("#cookiebox").innerHTML = '<div class="alert alert-primary alert-dismissible fade show" role="alert" id="cookiealert">This site uses cookies. <a href="/privacy/" class="alert-link">Read more.</a> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }

    document.querySelector("#cookiealert").addEventListener('closed.bs.alert', function () {
        // One second before the year 2038 bug.
        document.cookie = 'kw_cookies=2; expires=17-Jan-2038 03:14:07 GMT; path=/';
    });

    var sc = document.querySelector("#snackbar-container");
    if (sc !== null) {
        setTimeout(function() { sc.classList.add("snackbar-container-hidden"); }, 5000);
        setTimeout(function() { sc.remove() }, 5500);
    }
}

document.addEventListener('DOMContentLoaded', onLoaded, false);
