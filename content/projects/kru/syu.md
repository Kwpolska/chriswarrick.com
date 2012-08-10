**Name:** syu  
**License:** 3-clause BSD  
**[Download](https://github.com/downloads/Kwpolska/kru/syu.tar.gz)**

syu.  Maintaining your Arch Linux since 1901.
==============

PURPOSE
-------
This script can do much more than just an update:

    usage: syu [-h] [-t] [-l] [-o] [-r] [-c] [-a]

    default behavior: Perform an update (pacman -Syu + pkgbuilder -Syu).
    optional arguments:
      -h, --help      show this message and exit
      -t, --time      update time using NTP and exit
      -l, --lock      unlock the pacman databas and exit
      -o, --optimize  optimize the pacman database and exit
      -r, --reflect   reflect the mirrors
      -c, --cron      perform a cron-friendly update (NTP + pacman -Sy)
      -a, --noaur     don't use pkgbuilder while updating.

INSTALLATION
------------
Copy it to somewhere in your $PATH.

COPYRIGHT
---------
Copyright (C) 2011-2012, Kwpolska.
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
