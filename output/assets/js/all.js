// Kw’s JS
// Copyright © 2014-2021, Chris Warrick.

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
            document.querySelector("#kw-all-css").href = 'https://chriswarrick.com/assets/css/all.css';
        } else {
            document.querySelector("#kw-all-css").href = 'https://chriswarrick.com/assets/css/all-dark.css';
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

/*!
 * baguetteBox.js
 * @author  feimosi
 * @version 1.11.1
 * @url https://github.com/feimosi/baguetteBox.js
 */
!function(e,t){"use strict";"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.baguetteBox=t()}(this,function(){"use strict";var e,t,n,o,i,a='<svg width="44" height="60"><polyline points="30 10 10 30 30 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"stroke-linecap="butt" fill="none" stroke-linejoin="round"/></svg>',s='<svg width="44" height="60"><polyline points="14 10 34 30 14 50" stroke="rgba(255,255,255,0.5)" stroke-width="4"stroke-linecap="butt" fill="none" stroke-linejoin="round"/></svg>',l='<svg width="30" height="30"><g stroke="rgb(160,160,160)" stroke-width="4"><line x1="5" y1="5" x2="25" y2="25"/><line x1="5" y1="25" x2="25" y2="5"/></g></svg>',r={},u={captions:!0,buttons:"auto",fullScreen:!1,noScrollbars:!1,bodyClass:"baguetteBox-open",titleTag:!1,async:!1,preload:2,animation:"slideIn",afterShow:null,afterHide:null,onChange:null,overlayBackgroundColor:"rgba(0,0,0,.8)"},c={},d=[],f=0,g=!1,p={},m=!1,b=/.+\.(gif|jpe?g|png|webp)/i,v={},h=[],y=null,w=function(e){-1!==e.target.id.indexOf("baguette-img")&&I()},k=function(e){e.stopPropagation?e.stopPropagation():e.cancelBubble=!0,q()},E=function(e){e.stopPropagation?e.stopPropagation():e.cancelBubble=!0,j()},x=function(e){e.stopPropagation?e.stopPropagation():e.cancelBubble=!0,I()},C=function(e){p.count++,p.count>1&&(p.multitouch=!0),p.startX=e.changedTouches[0].pageX,p.startY=e.changedTouches[0].pageY},B=function(e){if(!m&&!p.multitouch){e.preventDefault?e.preventDefault():e.returnValue=!1;var t=e.touches[0]||e.changedTouches[0];t.pageX-p.startX>40?(m=!0,q()):t.pageX-p.startX<-40?(m=!0,j()):p.startY-t.pageY>100&&I()}},T=function(){p.count--,p.count<=0&&(p.multitouch=!1),m=!1},N=function(){T()},L=function(t){"block"===e.style.display&&e.contains&&!e.contains(t.target)&&(t.stopPropagation(),H())};function A(e){if(v.hasOwnProperty(e)){var t=v[e].galleries;[].forEach.call(t,function(e){[].forEach.call(e,function(e){V(e.imageElement,"click",e.eventHandler)}),d===e&&(d=[])}),delete v[e]}}function P(e){switch(e.keyCode){case 37:q();break;case 39:j();break;case 27:I();break;case 36:!function(e){e&&e.preventDefault();X(0)}(e);break;case 35:!function(e){e&&e.preventDefault();X(d.length-1)}(e)}}function S(i,a){if(d!==i){for(d=i,function(i){i||(i={});for(var a in u)r[a]=u[a],"undefined"!=typeof i[a]&&(r[a]=i[a]);t.style.transition=t.style.webkitTransition="fadeIn"===r.animation?"opacity .4s ease":"slideIn"===r.animation?"":"none","auto"===r.buttons&&("ontouchstart"in window||1===d.length)&&(r.buttons=!1);n.style.display=o.style.display=r.buttons?"":"none";try{e.style.backgroundColor=r.overlayBackgroundColor}catch(e){}}(a);t.firstChild;)t.removeChild(t.firstChild);h.length=0;for(var s,l=[],c=[],f=0;f<i.length;f++)(s=W("div")).className="full-image",s.id="baguette-img-"+f,h.push(s),l.push("baguetteBox-figure-"+f),c.push("baguetteBox-figcaption-"+f),t.appendChild(h[f]);e.setAttribute("aria-labelledby",l.join(" ")),e.setAttribute("aria-describedby",c.join(" "))}}function F(t){r.noScrollbars&&(document.documentElement.style.overflowY="hidden",document.body.style.overflowY="scroll"),"block"!==e.style.display&&(z(document,"keydown",P),p={count:0,startX:null,startY:null},Y(f=t,function(){O(f),R(f)}),M(),e.style.display="block",r.fullScreen&&(e.requestFullscreen?e.requestFullscreen():e.webkitRequestFullscreen?e.webkitRequestFullscreen():e.mozRequestFullScreen&&e.mozRequestFullScreen()),setTimeout(function(){e.className="visible",r.bodyClass&&document.body.classList&&document.body.classList.add(r.bodyClass),r.afterShow&&r.afterShow()},50),r.onChange&&r.onChange(f,h.length),y=document.activeElement,H(),g=!0)}function H(){r.buttons?n.focus():i.focus()}function I(){r.noScrollbars&&(document.documentElement.style.overflowY="auto",document.body.style.overflowY="auto"),"none"!==e.style.display&&(V(document,"keydown",P),e.className="",setTimeout(function(){e.style.display="none",document.fullscreen&&(document.exitFullscreen?document.exitFullscreen():document.mozCancelFullScreen?document.mozCancelFullScreen():document.webkitExitFullscreen&&document.webkitExitFullscreen()),r.bodyClass&&document.body.classList&&document.body.classList.remove(r.bodyClass),r.afterHide&&r.afterHide(),y&&y.focus(),g=!1},500))}function Y(e,t){var n=h[e],o=d[e];if(void 0!==n&&void 0!==o)if(n.getElementsByTagName("img")[0])t&&t();else{var i=o.imageElement,a=i.getElementsByTagName("img")[0],s="function"==typeof r.captions?r.captions.call(d,i):i.getAttribute("data-caption")||i.title,l=function(e){var t=e.href;if(e.dataset){var n=[];for(var o in e.dataset)"at-"!==o.substring(0,3)||isNaN(o.substring(3))||(n[o.replace("at-","")]=e.dataset[o]);for(var i=Object.keys(n).sort(function(e,t){return parseInt(e,10)<parseInt(t,10)?-1:1}),a=window.innerWidth*window.devicePixelRatio,s=0;s<i.length-1&&i[s]<a;)s++;t=n[i[s]]||t}return t}(i),u=W("figure");if(u.id="baguetteBox-figure-"+e,u.innerHTML='<div class="baguetteBox-spinner"><div class="baguetteBox-double-bounce1"></div><div class="baguetteBox-double-bounce2"></div></div>',r.captions&&s){var c=W("figcaption");c.id="baguetteBox-figcaption-"+e,c.innerHTML=s,u.appendChild(c)}n.appendChild(u);var f=W("img");f.onload=function(){var n=document.querySelector("#baguette-img-"+e+" .baguetteBox-spinner");u.removeChild(n),!r.async&&t&&t()},f.setAttribute("src",l),f.alt=a&&a.alt||"",r.titleTag&&s&&(f.title=s),u.appendChild(f),r.async&&t&&t()}}function j(){return X(f+1)}function q(){return X(f-1)}function X(e,t){return!g&&e>=0&&e<t.length?(S(t,r),F(e),!0):e<0?(r.animation&&D("left"),!1):e>=h.length?(r.animation&&D("right"),!1):(Y(f=e,function(){O(f),R(f)}),M(),r.onChange&&r.onChange(f,h.length),!0)}function D(e){t.className="bounce-from-"+e,setTimeout(function(){t.className=""},400)}function M(){var e=100*-f+"%";"fadeIn"===r.animation?(t.style.opacity=0,setTimeout(function(){c.transforms?t.style.transform=t.style.webkitTransform="translate3d("+e+",0,0)":t.style.left=e,t.style.opacity=1},400)):c.transforms?t.style.transform=t.style.webkitTransform="translate3d("+e+",0,0)":t.style.left=e}function O(e){e-f>=r.preload||Y(e+1,function(){O(e+1)})}function R(e){f-e>=r.preload||Y(e-1,function(){R(e-1)})}function z(e,t,n,o){e.addEventListener?e.addEventListener(t,n,o):e.attachEvent("on"+t,function(e){(e=e||window.event).target=e.target||e.srcElement,n(e)})}function V(e,t,n,o){e.removeEventListener?e.removeEventListener(t,n,o):e.detachEvent("on"+t,n)}function U(e){return document.getElementById(e)}function W(e){return document.createElement(e)}return[].forEach||(Array.prototype.forEach=function(e,t){for(var n=0;n<this.length;n++)e.call(t,this[n],n,this)}),[].filter||(Array.prototype.filter=function(e,t,n,o,i){for(n=this,o=[],i=0;i<n.length;i++)e.call(t,n[i],i,n)&&o.push(n[i]);return o}),{run:function(r,u){var d;return c.transforms="undefined"!=typeof(d=W("div")).style.perspective||"undefined"!=typeof d.style.webkitPerspective,c.svg=function(){var e=W("div");return e.innerHTML="<svg/>","http://www.w3.org/2000/svg"===(e.firstChild&&e.firstChild.namespaceURI)}(),c.passiveEvents=function(){var e=!1;try{var t=Object.defineProperty({},"passive",{get:function(){e=!0}});window.addEventListener("test",null,t)}catch(e){}return e}(),function(){if(e=U("baguetteBox-overlay"))return t=U("baguetteBox-slider"),n=U("previous-button"),o=U("next-button"),void(i=U("close-button"));var r,u;(e=W("div")).setAttribute("role","dialog"),e.id="baguetteBox-overlay",document.getElementsByTagName("body")[0].appendChild(e),(t=W("div")).id="baguetteBox-slider",e.appendChild(t),(n=W("button")).setAttribute("type","button"),n.id="previous-button",n.setAttribute("aria-label","Previous"),n.innerHTML=c.svg?a:"&lt;",e.appendChild(n),(o=W("button")).setAttribute("type","button"),o.id="next-button",o.setAttribute("aria-label","Next"),o.innerHTML=c.svg?s:"&gt;",e.appendChild(o),(i=W("button")).setAttribute("type","button"),i.id="close-button",i.setAttribute("aria-label","Close"),i.innerHTML=c.svg?l:"&times;",e.appendChild(i),n.className=o.className=i.className="baguetteBox-button",r=c.passiveEvents?{passive:!1}:null,u=c.passiveEvents?{passive:!0}:null,z(e,"click",w),z(n,"click",k),z(o,"click",E),z(i,"click",x),z(t,"contextmenu",N),z(e,"touchstart",C,u),z(e,"touchmove",B,r),z(e,"touchend",T),z(document,"focus",L,!0)}(),A(r),function(e,t){var n=document.querySelectorAll(e),o={galleries:[],nodeList:n};return v[e]=o,[].forEach.call(n,function(e){t&&t.filter&&(b=t.filter);var n=[];if(n="A"===e.tagName?[e]:e.getElementsByTagName("a"),0!==(n=[].filter.call(n,function(e){if(-1===e.className.indexOf(t&&t.ignoreClass)&&e.getElementsByTagName("img").length>0)return b.test(e.href)})).length){var i=[];[].forEach.call(n,function(e,n){var o=function(e){e.preventDefault?e.preventDefault():e.returnValue=!1,S(i,t),F(n)},a={eventHandler:o,imageElement:e};z(e,"click",o),i.push(a)}),o.galleries.push(i)}}),o.galleries}(r,u)},show:X,showNext:j,showPrevious:q,hide:I,destroy:function(){var a,s;a=c.passiveEvents?{passive:!1}:null,s=c.passiveEvents?{passive:!0}:null,V(e,"click",w),V(n,"click",k),V(o,"click",E),V(i,"click",x),V(t,"contextmenu",N),V(e,"touchstart",C,s),V(e,"touchmove",B,a),V(e,"touchend",T),V(document,"focus",L,!0),function(){for(var e in v)v.hasOwnProperty(e)&&A(e)}(),V(document,"keydown",P),document.getElementsByTagName("body")[0].removeChild(document.getElementById("baguetteBox-overlay")),v={},d=[],f=0}}});
