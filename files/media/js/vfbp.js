/*! Copyright (C) 2011, Kwpolska.  Licensed under the 3-clause BSD License. !*/

/* Copyright (C) 2011, Kwpolska
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

function vfbpauth() {
    $.ajaxSetup ({
        cache: false
    });
    $.post("http://kwpolska.tk/contact/antibot.php",
            {'json': '{"vfbpjs": "auth", "token": "'+$('[name="vfbpjstoken"]').val()+'"}'},
            function(data) {
                if(data == '{"vfbpjs": "results", "auth": "true"}') {
                    $('#vfbpjsresults').html = 'Authenticated as a human being! (or a bot with JS support)';
                    $('[name="vfbpq"]').val('[ignored]');
                    document.write('WORKS, now make those other two jerks above me do the same');
                } else {
                    $('#vfbpjsresults').html = 'JS authentication failed, retrying...';
                    vfbpauth();
                }
            });
}

$(document).ready(function() {
    $('#vfbpjsresults').html = 'JavaScript detected: attempting anti-bot authentication...';
    vfbpauth();
});