.. title: KwKeyboard
.. slug: kwkeyboard
.. date: 2017-03-11 20:44:45+01:00
.. tags:
.. category:
.. link:
.. description: An opinionated keyboard layout for Windows, Linux (X11) and macOS.
.. type: text
.. status: 5
.. download: https://chriswarrick.com/projects/kwkeyboard/#downloads
.. role: Maintainer
.. license: 3-clause BSD
.. language: multiple
.. sort: 10

An opinionated keyboard layout for Windows, Linux (X11) and macOS.  Perfect for Polish, good enough for some other European languages.

Downloads
=========

* `KwKeyboard-Windows-2017-03.zip`__
* `KwKeyboard-Linux-2017-03.zip`__
* `KwKeyboard-macOS-2017-03.dmg`__

__ /pub/KwKeyboard/KwKeyboard-Windows-2017-03.zip
__ /pub/KwKeyboard/KwKeyboard-Linux-2017-03.zip
__ /pub/KwKeyboard/KwKeyboard-macOS-2017-03.dmg

Usage
=====

* Windows: run ``setup.exe``
* Linux (X11): ``sudo mv xkb-symbols /usr/share/X11/xkb/symbols/pl && setxkbmap pl`` (use default Polish layout)
* macOS: mount disk image; drag-and-drop the bundle to ``Keyboard Layouts``

Version differences
===================

* macOS has some Apple additions: Apple logo on Shift-Option-F, Command on Option-G, Option on Shift-Option-G
* Windows and Linux instead feature a lower single quote on Alt-Shift-F, and the letter eng (Ŋ ŋ) on Alt-G, Alt-Shift-G.

Customization
=============

* Windows: install Microsoft Keyboard Layout Creator (MSKLC) — Windows 7 recommended
* Linux: edit the plain-text file (/usr/include/X11/keysymdef.h for reference)
* macOS: install Ukelele

License
=======

Copyright © 2012-2017, Chris Warrick.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

1. Redistributions of source code must retain the above copyright
   notice, this list of conditions, and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions, and the following disclaimer in the
   documentation and/or other materials provided with the distribution.

3. Neither the name of the author of this software nor the names of
   contributors to this software may be used to endorse or promote
   products derived from this software without specific prior written
   consent.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
