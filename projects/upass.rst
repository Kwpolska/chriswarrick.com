.. title: upass
.. slug: upass
.. date: 1970-01-01T00:00:00+00:00
.. description: upass: because pass is too hard.
.. status: 3
.. download: https://pypi.python.org/pypi/upass
.. github: https://github.com/Kwpolska/upass
.. bugtracker: https://github.com/Kwpolska/upass/issues
.. role: Maintainer
.. license: 3-clause BSD
.. language: Python
.. sort: 90
.. featured: true
.. previewimage: /projects/_banners/upass.png
.. gallery: /galleries/upass/

``upass`` is an interface to `pass <http://www.passwordstore.org/>`_, the standard Unix password manager.

``upass`` is using urwid, which means it has a friendly full-screen console
interface.  It shows your directory structure (with flattened subdirectories)
and calls ``pass`` when requested.  (It does not use ``pass -c`` due to
subprocessing issues, instead opting for a manual copy — note that the
clipboard **will not be cleared**).

If you want to see how it looks, check out `the screenshots gallery </galleries/upass/>`_.

``upass`` is under development (and was initially written in one evening).  If you have
ideas, bugs, or want to help, hop over to the `GitHub page <https://github.com/Kwpolska/upass>`_.

You can install ``upass`` from `PyPI <https://pypi.python.org/pypi/upass>`_ (with ``pip install upass``). Arch Linux
users can install the ``upass`` package from the AUR.
