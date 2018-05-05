.. title: PESEL Reader
.. slug: peselreader
.. date: 1970-01-01T00:00:00+00:00
.. description: A small program for reading PESEL numbers.
.. devstatus: 6
.. download: https://github.com/Kwpolska/peselreader/releases
.. github: https://github.com/Kwpolska/peselreader
.. bugtracker: https://github.com/Kwpolska/peselreader/issues
.. role: Maintainer
.. license: 3-clause BSD
.. language: C
.. sort: 76

This very small piece of software decodes PESEL numbers (think SSNs for Polish
citizens) into C structs.  The following information is encoded in a PESEL
number (11 digits long; the positions are zero-indexed):

* Date of Birth (positions 0-5)
* Gender (position 9)
* Checksum (position 10)

The PESEL Reader is based on the `official specification <https://msw.gov.pl/pl/sprawy-obywatelskie/centralne-rejestry-pan/32,PESEL.html?sid=1b5a96b2ef9e1ba0b2d3fa56f590c29f#w jaki sposób>`_, as published by the Ministry of the Interior of the Republic of Poland.
