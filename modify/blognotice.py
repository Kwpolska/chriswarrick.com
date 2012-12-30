#!/usr/bin/python2
# -*- coding: utf-8 -*-
# hyde/modify blognotice
# Copyright © 2012 Kwpolska.
# usage: ./blognotice.py

"""hyde/moficy blognotce
Copyright © 2012 Kwpolska.
usage: ./blognotice.py"""

import sys


def changenotice(notice):
    if notice == '':
        open('/home/kwpolska/hyde/layout/blognotice.j2', 'w').write('')
    else:
        open('/home/kwpolska/hyde/layout/blognotice.j2', 'w').write(
            '<div class="blognotice">Notice: {0}</div>'.format(notice))

if __name__ == "__main__":
    try:
        if '-h' in sys.argv or '--help' in sys.argv:
            print __doc__
            exit(0)

        print "Notice? (leavy empty to remove)"
        newnotice = raw_input('> ')
        changenotice(newnotice)
    except KeyboardInterrupt:
        print "Cancelled."
        exit(0)