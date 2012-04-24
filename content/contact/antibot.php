<?php
# VFBP v2
# A PHP bot protection tool.
# Copyright (C) 2011, Kwpolska
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# 1. Redistributions of source code must retain the above copyright
#    notice, this list of conditions, and the following disclaimer.
#
# 2. Redistributions in binary form must reproduce the above copyright
#    notice, this list of conditions, and the following disclaimer in the
#    documentation and/or other materials provided with the distribution.
#
# 3. Neither the name of the author of this software nor the names of
#    contributors to this software may be used to endorse or promote
#    products derived from this software without specific prior written
#    consent.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
# "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
# LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
# A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT
# OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
# LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
# DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
# THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

session_start(); # initialize a session
# session protection code
if (!isset($_SESSION['init'])) {
    session_regenerate_id();
    $_SESSION['init'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}

if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    session_start();
    session_regenerate_id();
    $_SESSION['init'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}

### AUTHENTICATION METHOD #1: numbers.

$_SESSION['num'] = rand(1,6);
$_SESSION['let'] = chr($_SESSION['num'] + 64);
$numbers = Array('first', 'second', 'third', 'fourth', 'fifth', 'sixth');
$_SESSION['vfbpfnum'] = $numbers[$_SESSION['num'] - 1];

### AUTHENTICATION METHOD #2: AJAX.

$token = uniqid('vfbp_');
$_SESSION['vfbpjstoken'] = $token;
$_SESSION['vfbpjsauth'] = false;

if(isset($_POST['json'])) {
    # JSON scheme:
    # {"vfbpjs": "auth", "token": "TOKEN"}
    $json = json_decode($_POST['json']);
    if($json['token'] = $_SESSION['vfbpjstoken']) {
        $_SESSION['vfbpjsauth'] = true;
        echo '{"vfbpjs": "results", "auth": "true"}';
    } else {
        echo '{"vfbpjs": "results", "auth": "false"}';
    }
}
?>
