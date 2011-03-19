---
layout: ajax
title: syu
---
**Name:** syu  
**License:** GPLv3  
**[Download](https://github.com/downloads/Kwpolska/kru/syu.tar.gz)**

## Description
Syu is an Arch Linux updater. It can also update time.

### README.md

Kw's Maintenance Script (syu). Updating your Arch Linux since 1901.
==============

PURPOSE
-------
This script can do much more than just updating your system. Here is everything what it can do:

    Syntax: syu [argument]
    no   args    Perform an update (NTP + pacman -Syu + clyde -Syu --aur).
    -a --noaur   Disable clyde from the update process.
    -c --cron    Perform a cron-friendly update (NTP + pacman -Sy).
    -h --help    Show this message.
    -l --lock    Unlock pacman database.
    -r --reflect Run reflector.
    -t --time    Update time.

INSTALLATION
------------
Copy it to somewhere in your $PATH.

COPYRIGHT
---------
Copyright (C) 2010 Kwpolska.

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
