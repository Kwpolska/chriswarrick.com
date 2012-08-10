/*! Copyright (C) 2011-2012, Kwpolska.  Licensed under the 3-clause BSD License. !*/

/* Copyright (C) 2011-2012, Kwpolska.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions, and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions, and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the author of this software nor the names of
 *    contributors to this software may be used to endorse or promote
 *    products derived from this software without specific prior written
 *    consent.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

projectlist = {
    '/projects/': ['Projects Home', 'projectshome'],
    '/projects/kwpastebin/': ['KwPastebin', 'kwpastebin'],
    '/projects/kwportal/': ['KwPortal', 'kwportal'],
    '/projects/pkgbuilder/': ['PKGBUILDer', 'pkgbuilder'],
    '/projects/trashman/': ['Trashman', 'trashman'],
    '/projects/kru/': ['KRU', 'kru'],
    '/projects/kru/kwinstaller/': ['KRU/KwInstaller', 'kru/kwinstaller'],
    '/projects/kru/kwd/': ['KRU/KWD', 'kru/kwd'],
    '/projects/kru/multigit/': ['KRU/MultiGit', 'kru/multigit'],
    '/projects/kru/unpacker/': ['KRU/unpacker', 'kru/unpacker'],
    '/projects/kru/sha512sum/': ['KRU/sha512sum', 'kru/sha512sum'],
    '/projects/kru/syu/': ['KRU/syu', 'kru/syu'],
    '/projects/kru/kcaptcha/': ['KRU/kcaptcha', 'kru/kcaptcha'],
    '/projects/kru/lanyon/': ['KRU/lanyon', 'kru/lanyon'],
};

function loadProject(title, id, pushstate) {
    $.ajaxSetup ({
        cache: false
    });
    var ajax_load = "<img src='http://kwcdn.tk/images/spinner.gif' alt='Loading...'>";
    var urlid = id.replace('-', '/')
    if (id == 'projectshome') {
        $('#projectinfo').html(ajax_load).load('/media/projects-nil/index.html');
        $(document).attr('title', 'Projects — Kw’s Home');
        $('#sitetitle').html('Projects');
        if (pushstate != false) {
            history.pushState({ path: 'http://kwpolska.tk/projects/' }, '', 'http://kwpolska.tk/projects/');
        }

    } else {
        $('#projectinfo').html(ajax_load).load('/media/projects-nil/'+urlid+'.html');
        $(document).attr('title', 'Projects/'+title+' — Kw’s Home');
        $('#sitetitle').html('Projects/'+title);
        if (pushstate != false) {
            history.pushState({ path: 'http://kwpolska.tk/projects/'+urlid+'/' }, '', 'http://kwpolska.tk/projects/'+urlid+'/');
        }
    }

    pl = $('#projectlistcontents').children('li').children('a');
    kru = $('#krulist').children('li').children('a');
    pl = jQuery.merge(pl, kru);

    pl.each(function() {
        $('#'+this.id).removeClass('active');
    });
    $('#'+id).addClass('active')

}

$(document).ready(function() {
    pl = $('#projectlistcontents').children('li').children('a');
    kru = $('#krulist').children('li').children('a');
    pl = jQuery.merge(pl, kru);
    pl.each(function() {
        $('#'+this.id).click(function() {
            loadProject(this.title, this.id, true);
            return false;
        });
    });
});

$(window).bind('popstate', function() {
    loadProject(projectlist[location.pathname][0], projectlist[location.pathname][1], false);
});
