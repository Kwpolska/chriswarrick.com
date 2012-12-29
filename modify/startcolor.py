#!/usr/bin/python2
# -*- coding: utf-8 -*-
# hyde/modify startcolor
# Copyright © 2012 Kwpolska.
# usage: ./startcolor.py STYLESHEET

"""hyde/modify startcolor
Copyright © 2012 Kwpolska.
usage: ./startcolor.py STYLESHEET"""

import sys
import glob
import os.path
import color

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

        color.changecolor(sys.argv[1])
        import render
    except Exception, e:
        print 'ERROR: {0}'.format(str(e))
        exit(1)
