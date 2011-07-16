document.write('<div class="iesucks"><a href="/ie/">It looks like you\'re using Internet Explorer older than version 9. Click here.</a></div>');
function showMenu() {
    menu.innerHTML = '<button type="button" onclick="hideMenu()">Hide</button>'+content;
}
function hideMenu() {
    menu.innerHTML = '<button type="button" onclick="showMenu()">Show</button> Menu was hidden due to IE\'s suckiness. Use the Show button or change your browser.';
}

