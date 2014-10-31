// Kw’s JS
// Copyright © 2014, Chris Warrick.

$(document).ready(function() {
    if (document.cookie.indexOf('kw_cookies=1') == -1) {
        if ($('html').attr('lang') == 'en') {
            $("#cookiecontainer").html('<div class="alert alert-info fade in" role="alert" id="cookiealert">This site uses <strong>cookies</strong>.  <a href="/cookies/" class="alert-link">Read more.</a> <button type="button" class="close" data-dismiss="alert">&times; Got it</button></div>');
        } else if ($('html').attr('lang') == 'pl') {
            $("#cookiecontainer").html('<div class="alert alert-info fade in" role="alert" id="cookiealert">Ta strona używa <strong>ciasteczek</strong>.  <a href="/pl/cookies/" class="alert-link">Dowiedz się więcej.</a> <button type="button" class="close" data-dismiss="alert">&times; Rozumiem</button></div>');
        }
    }

    $('#cookiealert').on('closed.bs.alert', function () {
        // One second before the year 2038 bug.
        document.cookie = 'kw_cookies=1; expires=17-Jan-2038 03:14:07 GMT; path=/';
    });
});
