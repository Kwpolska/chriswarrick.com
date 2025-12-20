.. title: New project: upass — console UI for pass
.. slug: upass
.. date: 2015-07-06 14:30:00+02:00
.. tags: Python, projects, Linux, CLI, upass, app, password
.. category: Python
.. link: https://github.com/Kwpolska/upass
.. description: Because pass is too hard.
.. type: text
.. nocomments: true

`pass <http://www.passwordstore.org/>`_ is the standard Unix password manager.
And I just wrote a slightly friendlier, clickier interface with urwid and
Python.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/projects/upass/" class="btn btn-primary" style="width: 250px;">
   <i class="fa fa-info-circle"></i>
   Project page
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/galleries/upass/" class="btn btn-secondary" style="width: 250px;">
   <i class="far fa-image"></i>
   Screenshots
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/upass" class="btn btn-secondary" style="width: 250px;">
   <i class="fab fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://pypi.python.org/pypi/upass" class="btn btn-secondary" style="width: 250px;">
   <i class="fa fa-download"></i>
   Downloads (PyPI)
   </a>
   </p>

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
