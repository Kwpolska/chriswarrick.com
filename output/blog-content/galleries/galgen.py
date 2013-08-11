#!/usr/bin/python
# -*- encoding: utf8 -*-
# GALlery GENerator v0.1
# Copyright © 2012-2013, Kwpolska.
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

__version__ = '0.1'

print('GALGEN v{} — Copyright 2012-2013 Kwpolska.\n'.format(__version__))

import csv
import sys

settingsreader = csv.reader(open(sys.argv[1], 'rb'), delimiter=';',
                        quotechar='"')
datareader = csv.reader(open(sys.argv[1], 'rb'), delimiter=';',
                        quotechar='"')
# get settings.
ggfile = False
galversion = '0.0'
internalname = 'unknown-gallery'
galleryname = 'Unknown Gallery'

for row in settingsreader:
    if row[0] == 'GALGEN':
        ggfile = True
        galversion = row[1]
    if row[0] == 'INTERNAL':
        internalname = row[1]
    elif row[0] == 'NAME':
        galleryname = row[1]

if not ggfile:
    print('This is not a GALGEN file.')
    sys.exit(1)

if galversion != __version__:
    print('ERROR: this file is incompatible with this version of GALGEN.')
    sys.exit(2)

print('Generating gallery: "{0}"/"{1}".'.format(internalname, galleryname))

contents = ["""<!--
.. title: {gn}
.. slug: {in}
.. date: {date}
.. description: Gallery: {gn}
-->

<ul class="gallery">""".format(gn=galleryname, in=internalname,
    date=datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S'))]

for row in datareader:
    if row[0] not in ('GALGEN', 'INTERNAL', 'NAME'):
        contents.append('<li><figure> <a href="./img/{filename}" '
        'title="{desc}" class="fancybox" rel="{internalname}"><img '
        'src="./img/t/{filename}" alt="{desc}" title="{desc}"></a>'
        '<figcaption>{desc}</figcaption></figure></li>'.format(
            internalname=internalname, filename=row[0], desc=row[1]))

contents.append('</ul>')

with open(internalname+'.html', 'w') as fh:
    fh.write('\n'.join(contents))

print('Saved as {0}.html.'.format(internalname))
