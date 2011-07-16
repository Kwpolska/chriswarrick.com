function getFile(fileName, elementToUpdate) {
    $.ajaxSetup ({
        cache: false
    });
    var ajax_load = "<img src='images/spinner.gif' alt='Loading...'>";

    var loadUrl = fileName;
    $('#projectinfo').html(ajax_load).load(loadUrl);
}


function closeAjaxBox() {
    document.childNodes[1].removeChild(document.getElementById('msgbox'));
}

function ajaxBox(url, title) {
    var newdiv = document.createElement('div');
    newdiv.setAttribute('id', 'msgbox');
    /*document.childNodes[1].appendChild(newdiv);
      var base = '<div id="header"><h1>'+title+'</h1><button onclick="closeAjaxBox();">Close</button></div><div id="msgboxcontents">Loading...</div>';*/
    newdiv.innerHTML='<div id="header"><h1>'+title+'</h1><button onclick="closeAjaxBox();">Close</button></div><div id="msgboxcontents">Loading...</div>';
    document.childNodes[1].appendChild(newdiv);
    getFile(url, '#msgboxcontents');
}
