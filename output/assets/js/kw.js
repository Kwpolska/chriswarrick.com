// Kw’s JS
// Copyright © 2014-2018, Chris Warrick.

function updateCsText() {
    csText = csDict[colorScheme][document.querySelector('html').lang];
}

var csDict={'dark':{'pl':'Tryb Jasny','en':'Light Mode'},'light':{'pl':'Tryb Ciemny','en':'Dark Mode'},'loading':{'pl':'Zmieniam schemat kolorów, proszę czekać…','en':'Changing color scheme, please wait…'}};
var csText;
updateCsText();

function changeColors() {
    var csOther = {'dark': 'light', 'light': 'dark'};
    colorScheme = csOther[colorScheme];
    var style = document.createElement('style');
    style.id = "color-changer-loading-style";
    style.innerText = "#color-changer-loading-box{font-family:'Source Sans Pro',sans-serif;font-size:16px;line-height:40px;background:#6c757d;color:#fff;position:fixed;left:50%;top:50%;margin-top:-20px;margin-left:-200px;height:40px;width:400px;border-radius:4px;border:1px solid #0ad;box-shadow:0px 0px 20px #0ad;transition:all .5s ease;text-align:center;opacity:1;}*{box-sizing:border-box;transition:background 1.5s ease,color 0.25s linear}@media(max-width:550px){#color-changer-loading-box{left:1%;margin-left:0;width:98%;}}</style>";
    document.head.append(style);

    var loadbox = document.createElement('div');
    loadbox.id = "color-changer-loading-box";
    loadbox.innerText = csDict['loading'][document.querySelector('html').lang];
    document.body.append(loadbox);

    setTimeout(function() {
        document.cookie = 'colorScheme=' + colorScheme + '; expires=17-Jan-2038 03:14:07 GMT; path=/';
        if (colorScheme === 'light') {
            document.querySelector("#kw-all-css").href = '/assets/css/all.css';
        } else {
            document.querySelector("#kw-all-css").href = '/assets/css/all-dark.css';
        }
        updateCsText();
        document.querySelector("#color-changer-text").innerText = csText;
        setTimeout(function() { loadbox.style = 'opacity:0'; }, 5000);
        setTimeout(function() { loadbox.remove(); style.remove(); }, 6000);
    }, 250);
    return false;
}

function onLoaded() {
    document.querySelector("#color-changer-text").innerText = csText;
    if (document.cookie.indexOf('kw_cookies=2') == -1) {
        if (document.querySelector('html').lang == 'pl') {
            document.querySelector("#cookiebox").innerHtml = '<div class="alert alert-primary fade show" role="alert" id="cookiealert">Ta strona używa ciasteczek. <a href="/pl/privacy/" class="alert-link">Dowiedz się więcej.</a> <button type="button" class="close" data-dismiss="alert">&times; Rozumiem</button></div>';
        } else {
            document.querySelector("#cookiebox").innerHtml = '<div class="alert alert-primary fade show" role="alert" id="cookiealert">This site uses cookies. <a href="/privacy/" class="alert-link">Read more.</a> <button type="button" class="close" data-dismiss="alert">&times; Got it</button></div>';
        }
    }

    $('#cookiealert').on('closed.bs.alert', function () {
        // One second before the year 2038 bug.
        document.cookie = 'kw_cookies=2; expires=17-Jan-2038 03:14:07 GMT; path=/';
    });

    document.querySelector("#color-changer-btn").addEventListener('click', changeColors);
    document.querySelector("#color-changer-mobile").addEventListener('click', changeColors);

    var sc = document.querySelector("#snackbar-container");
    if (sc !== null) {
        setTimeout(function() { sc.classList.add("snackbar-container-hidden"); }, 5000);
        setTimeout(function() { sc.remove() }, 5500);
    }
}

document.addEventListener('DOMContentLoaded', onLoaded, false);
