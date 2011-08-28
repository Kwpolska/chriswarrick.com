---
title: PKGBUILDer
layout: projects
---
**Name:** PKGBUILDer  
**License:** GPLv3  
**[Download](https://github.com/downloads/Kwpolska/kru/pkgbuilder.tar.gz)**

PKGBUILDer.  Easy AUR helper-like script.
==============

PURPOSE
-------
This tool is a basic AUR helper.  Version 2 in Py3K.

INSTALLATION
------------
Copy pkgbuilder.py to /usr/bin or ~/bin.  If you want to use pkgman,
the included pacman wrapper, you need to:
 *  rename pkgbuilder.py to pkgbuilder
 *  copy pkgman AND pkgbuilder to /usr/bin, ~/bin or any other directory
    from $PATH.

NOTES
-----
This is Version 2 of PKGBUILDer.  The original Perl version is included,
but it shall not be used.

Version 2 works in two ways:

 *  regular: search outputs category/package, building in current
    working directory;
 *  pacman wrapper-friendly: search outputs 'aur' instead of categories,
    building in /tmp/pkgbuilder-UID

Notice: this script is not finished yet.  Please be careful.

COPYRIGHT
---------
Copyright (C) 2011 Kwpolska.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
