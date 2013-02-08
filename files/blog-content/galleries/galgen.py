#!/usr/bin/env python2
# -*- encoding: utf8 -*-
# Generate Gallery for Kw’s Blog (GALGEN)
# Copyright 2012 Kwpolska.
import csv
import sys

print "GALGEN"
print "Copyright 2012 Kwpolska.\n"

settingsreader = csv.reader(open(sys.argv[1], 'rb'), delimiter=';',
                        quotechar='"')
datareader = csv.reader(open(sys.argv[1], 'rb'), delimiter=';',
                        quotechar='"')
# get settings.
ggfile = False
galname = 'UNKNOWN_GALLERY'
entryname = 'UNKNOWN_ENTRY'

for row in settingsreader:
    if row[0] == 'GALGEN':
        ggfile = True
    if row[0] == 'SETGALNAME':
        galname = row[1]
    elif row[0] == 'SETENTRYNAME':
        entryname = row[1]

if ggfile != True:
    print "ERROR: not a GALGEN file."
    exit()
print "Generating gallery: '{0}'/'{1}'.".format(galname, entryname)

contents = [""".. title: {entryname}
.. slug: {galname}
.. date: 2011-01-01 00:00:00

<ul class="gallery">
""".format(entryname=entryname, galname=galname)]

for row in datareader:
    if row[0] != 'GALGEN' and row[0] != 'SETGALNAME' and row[0] != 'SETENTRYNAME':
        contents.append("""<li><figure>
<a href="http://kwcdn.tk/blog-content/galleries/{galname}/img/{filename}" title="{desc}" class="fancybox" rel="{galname}"><img src="http://kwcdn.tk/blog-content/galleries/{galname}/img/t/{filename}" alt="{desc}" title="{desc}"></a>
<figcaption>{desc}</figcaption></figure></li>""".format(galname=galname, filename=row[0], desc=row[1]))

contents.append('\n</ul>')

open(galname+'.rst', 'w').write(''.join(contents))
print "Saved as {0}.rst.".format(galname)
