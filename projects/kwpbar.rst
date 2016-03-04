.. title: Progress bar collection
.. slug: kwpbar
.. date: 1970-01-01T00:00:00+00:00
.. description: A collection of progress bars.
.. status: 5
.. role: Maintainer
.. license: 3-clause BSD
.. language: Python, C
.. sort: 86
.. featured: false

Languages
=========

* `C <https://github.com/Kwpolska/kwpbar.c>`_
* `Python <https://github.com/Kwpolska/kwpbar.py>`_ (``pip install kwpbar``)

API
===

* int get_termlength() → return terminal length (columns); helper function
* int/None pbar(double value, double max) → print a progress bar to the screen (stderr). Can raise ValueError in Python; returns 0 for success and 1 for failure in C.
* void/None erase_pbar() → erase the progress bar from the screen
