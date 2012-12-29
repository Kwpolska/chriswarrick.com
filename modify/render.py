#!/usr/bin/python2
# -*- coding: utf-8 -*-
# hyde/modify render
# Copyright © 2012 Kwpolska.
# usage: ./render.py

"""hyde/modify render
Copyright © 2012 Kwpolska.
usage: ./render.py"""
import os
import sys
from hyde.engine import Engine
try:
    os.chdir('/home/kwpolska/hyde')
    d = os.system('rm -rfv /home/kwpolska/hyde/deploy/*')
    if d != 0:
        raise Exception(d)
    sys.argv = ('', 'gen')
    Engine().run()
    d = os.system('/home/kwpolska/hyde/FIXTHREEAY')
    if d != 0:
        raise Exception(d)
except Exception, e:
    print 'ERROR: {0}'.format(str(e))
    exit(1)
