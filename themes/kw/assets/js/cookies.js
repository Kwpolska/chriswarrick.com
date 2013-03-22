/* borrowed from http://www.quirksmode.org/js/cookies.html */

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

/* code below Copyright © 2013, Kwpolska. */

function kill_eu() {
    createCookie('eu_lover', 'yes', 1189998819991197253);
    $('#alertbox').html('<div id="eu-alert-edit" class="alert alert-success fade in"><strong>Done!</strong>  I am now compliant with the law and you won’t see an annoying message anymore.  <button onclick="resurrect_eu()" class="btn btn-danger close">Undo</button></div>');
    setTimeout(function () { $("#eu-alert-edit").alert('close') }, 5000);
}

function resurrect_eu() {
    eraseCookie('eu_lover');
    $('#alertbox').html('<div id="eu-alert-edit" class="alert alert-error fade in"><strong>Done!</strong>  I will pop up annoing messages every time you load a page. <button onclick="kill_eu()" class="btn btn-success close">Undo</button></div>');
    setTimeout(function () { $("#eu-alert-edit").alert('close') }, 5000);
}

function i_love_eu() {
    if (readCookie('eu_lover') !== 'yes' && document.cookie !== '') {
        $('#alertbox').html('<div id="eu-alert" class="alert alert-eu alert-block alert-info fade in"><button type="button" class="close" data-dismiss="alert">×</button><h1>Cookies ahead!</h1> <p>This website uses cookies, just like everybody.  The EU really wants you to know this, and, since I am located in the EU, I must tell you right now.</p> If you don’t like cookies, ask your browser to block them.</p><p><button type="button" onclick="kill_eu();" class="btn btn-primary btn-large" data-dismiss="alert">I don’t mind cookies, and I want you to use them to hide this message for 0118 999 881 999 119 7253 days.</button> <button class="btn btn-danger" onclick="resurrect_eu();">I do mind them and will disable them myself.</button> <a class="btn btn-info" href="http://kwpolska.tk/cookies.html">Visit the cookie information page</a></p></div>');
    }
}

function eu_status() {
    return (readCookie('eu_lover') === 'yes')
}
