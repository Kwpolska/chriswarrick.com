// Kw’s JS
// Copyright © 2014-2018, Chris Warrick.

function changeColors() {
    var csOther = {'dark': 'light', 'light': 'dark'};
    colorScheme = csOther[colorScheme];
    document.cookie = 'colorScheme=' + colorScheme + '; expires=17-Jan-2038 03:14:07 GMT; path=/';
    if (colorScheme === 'dark') {
        $("#kw-all-css").attr('href', 'https://chriswarrick.com/assets/css/all-dark.css');
    } else {
        $("#kw-all-css").attr('href', 'https://chriswarrick.com/assets/css/all.css');
    }
    csText=csDict[colorScheme][document.getElementsByTagName('html')[0].lang];
    $("#color-changer-text").text(csText);
    return false;
}

$(document).ready(function() {
    if (document.cookie.indexOf('kw_cookies=2') == -1) {
        if ($('html').attr('lang') == 'pl') {
            $("#cookiebox").html('<div class="alert alert-primary fade show" role="alert" id="cookiealert">Ta strona używa ciasteczek. <a href="/pl/privacy/" class="alert-link">Dowiedz się więcej.</a> <button type="button" class="close" data-dismiss="alert">&times; Rozumiem</button></div>');
        } else {
            $("#cookiebox").html('<div class="alert alert-primary fade show" role="alert" id="cookiealert">This site uses cookies. <a href="/privacy/" class="alert-link">Read more.</a> <button type="button" class="close" data-dismiss="alert">&times; Got it</button></div>');
        }
    }

    $('#cookiealert').on('closed.bs.alert', function () {
        // One second before the year 2038 bug.
        document.cookie = 'kw_cookies=2; expires=17-Jan-2038 03:14:07 GMT; path=/';
    });

    $("#color-changer-btn").click(changeColors);
    $("#color-changer-mobile").click(changeColors);
});
