#!/usr/bin/python2
# -*- coding: utf-8 -*-
# hyde/modify color
# Copyright © 2012 Kwpolska.
# usage: ./color.py STYLESHEET

"""hyde/modify color
Copyright © 2012 Kwpolska.
usage: ./color.py STYLESHEET"""

import sys
import glob
import os.path


def findcolor(color):
    matches = glob.glob('/home/kwpolska/hyde/content/media/css/{0}*'.format(
        color))  # searching for the color in the CSS directory
    if len(matches) == 1:
        colorf = matches[0]
    elif len(matches) == 0:
        raise Exception('Cannot find any matching stylesheets!')
    else:
        sresults = []
        ask = False
        for i in matches:
            if i.endswith('.min.css'):
                sresults.append(i)

        if len(sresults) == 1:
            colorf = sresults[0]
        else:
            ask = True

        if ask:
            print 'There is more than one stylesheet matching.  \
Type the name of the stylesheet you want.'
            print 'Matches:'
            for i in matches:
                print os.path.basename(i)
            testfor = raw_input('> ')
            colorf = findcolor(testfor)

    colorf = os.path.basename(colorf)

    return ['<link rel="stylesheet" href="{{ media_url(\'css/%s\')\
}}">' % colorf,
            '<link rel="stylesheet" href="{{ media_url(\'css/lite/%s\')\
}}">' % colorf]


def changecolor(color):
    ncolor = findcolor(color)
    open('/home/kwpolska/hyde/layout/css.j2', 'w').write(ncolor[0])
    open('/home/kwpolska/hyde/layout/css-lite.j2', 'w').write(ncolor[1])

if __name__ == '__main__':
    try:
        if '-h' in sys.argv:
            print __doc__
            exit(0)
        elif '--help' in sys.argv:
            print __doc__
            exit(0)
        elif len(sys.argv) == 1:
            raise Exception('Need an argument with the stylesheet name.')
        elif len(sys.argv) != 2:
            raise Exception('More than one argument found.')

        changecolor(sys.argv[1])
    except Exception, e:
        print 'ERROR: {0}'.format(str(e))
        exit(1)
