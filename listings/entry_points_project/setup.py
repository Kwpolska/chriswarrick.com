# Copyright (C) 2014 Chris Warrick.
# From: http://chriswarrick.com/
# License: CC BY http://creativecommons.org/licenses/by/3.0/

from setuptools import setup

setup(name='my_project',
      version='0.1.0',
      packages=['my_project'],
      entry_points={
          'console_scripts': [
              'my_project = my_project.__main__:main'
          ]
      },
      )
