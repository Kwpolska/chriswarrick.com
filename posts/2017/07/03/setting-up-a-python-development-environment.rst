.. title: Setting up a Python development environment
.. slug: setting-up-a-python-development-environment
.. date: 2017-07-03 12:40:00+02:00
.. tags: Python, guide, devel, best practices, guide
.. category: Python
.. guide: yes
.. guide_effect: you can use Python and create something awesome
.. guide_platform: Windows, macOS, Linux
.. guide_topic: Python
.. shortlink: pysetup
.. updated: 2018-09-21 16:20:00+02:00

Setting up Python is usually simple, but there are some places where newcomers
(and experienced users) need to be careful. What versions are there? What’s the
difference between Python, CPython, Anaconda, PyPy? Those and many other
questions may stump new developers, or people wanting to use Python.

.. TEASER_END

Note: this guide is opinionated.

.. class:: right-toc

.. contents::

Glossary and questions
======================

Python versions: 2 vs 3
-----------------------

The Python community has undergone sort of a *schism* in recent years. Python
3, released in 2008, broke backwards compatibility: deprecated some bad
constructs and libraries (eg. ``raw_input()`` became ``input()`` and the
original Python 2 function that ran code input by users is gone; ``print()``
became a function; many things that returned lists now are iterators — ``zip``,
``range``), and completely remodelled strings (which are now Unicode by
default, and the interpreter behavior is stricter when the wrong type is used)

For new code, you should use Python 3. `Most popular packages support Python 3
<https://python3wos.appspot.com/>`_, and many of them support both Pythons at
the same time. The early bugs were ironed out in the first few point releases,
some features that made porting easier were added (back).

But what if you end up needing Python 2 later? No problem: you can learn the
differences in a short time, and with the help of a few libraries (eg. ``six``)
you can easily write code that is compatible with Python 2 and 3 at the same
time, using the same codebase (most libraries out there do that).

Python 2 will go EOL and lose official support and updates in 2020.

Read more: `Python 2 or Python 3 on Python Wiki <https://wiki.python.org/moin/Python2orPython3>`_

Can I run multiple Pythons on the same machine?
-----------------------------------------------

Yes. Note that multiple Python interpreters are completely separate: they have
their own pip and packages, and you can’t run Python 2 code in a Python 3
interpreter. You need to specify which interpreter to use when installing
packages and running some scripts (eg. ``pip2``, ``pip3`` or ``python3 -m pip``).

It’s best to limit yourself to the latest Python 2 and 3 versions. Python is
backwards-compatible within the major release, so Python 2.7 runs code
written with older 2.x versions in mind.

Implementations
---------------

A programming language is an abstract construct. To run code written in that
language, an interpreter or compiler needs to be written. In Python’s case,
there’s a plethora of implementations. Some of them are:

* **CPython** is the reference implementation. This is the implementation
  distributed on https://python.org/ and as part of many operating systems.
  Most Python features are first implemented in CPython, and then they are
  ported to other implementations.  If you don’t know what to choose, use
  CPython.
* **PyPy** is a fast implementation, written in a subset of Python. It’s compatible with
  Python 2.7 and 3.5 (beta support). It can run all pure Python code, and many
  extension libraries that use CFFI.
* **IronPython** is a .NET CLR implementation. It can integrate with .NET code.
* **Jython** is a Java JVM implementation. It can integrate with Java code, as
  well as other JVM languages.

Read more: `Python Implementations on Python Wiki <https://wiki.python.org/moin/PythonImplementations>`_

Distributions
-------------

There are also Python (CPython) distributions. They ship the CPython
interpreter and add some extra packages/features.  They are maintained by other
communities or corporate entities.

The most popular third-party distribution is `Anaconda
<https://www.continuum.io/downloads>`_ from Continuum Analytics. It’s popular
for data scientists, and includes over 100 packages, with extra pre-built
binaries available from the ``conda`` package manager.

I personally recommend to avoid Anaconda:

* Most packages have binary wheels for Windows, macOS and Linux (yes, Linux!)
  making the installation as simple as ``pip install numpy``.
* You waste disk space for packages Anaconda installs that you won’t ever need.
* It’s provided by some random for-profit company.
* I’ve seen bugs that were not reproducible outside of Anaconda.
* You can still do data science using the official distribution. There’s
  nothing special about Anaconda.

Read more: `Python distributions on Python Wiki <https://wiki.python.org/moin/PythonDistributions>`_

Can I make .exe files from Python programs?
-------------------------------------------

Yes, you can. There are tools for this — `PyInstaller
<http://www.pyinstaller.org/>`_ is the best one. Note that you usually need to
run it on the destination operating system. And remember that “compiling” to
exe files like that **is not** a security measure — your source code is still
easily recoverable. (It’s not a security measure in other languages either,
even if getting source code back might be more expensive/tricky in those.)

Where to learn Python? Where to get help?
-----------------------------------------

The choice of learning material is important. If you get a bad book, it might
discourage you from learning (because it’s boring), or may teach you
bad/outdated practices.

If you can already program in another language, I recommend the `official
Python tutorial <https://docs.python.org/3/tutorial/>`_. For newcomers to
programming, I recommend `Think Python
<http://greenteapress.com/wp/think-python-2e/>`_ or `Automate the Boring Stuff
with Python <https://automatetheboringstuff.com/>`_.  They teach Python 3, and
(mostly) best practices.

If you need help, try ``#python`` on freenode IRC, the `Tutor <https://mail.python.org/mailman/listinfo/tutor>`_ or `Python-list <https://mail.python.org/mailman/listinfo/python-list>`_ mailing lists, or a bunch of other communities. (I’m a regular on ``#python``)

Installing Python
=================

This guide will focus on installing CPython 2.7 and 3.x (latest), using the standard
distribution. This choice is satisfactory for most people. Third-party
distributions, while handy in some cases, are not needed for most. (See
`Distributions`_ for arguments)

Throughout this guide, I’ll refer to the Python interpreter executable as
``python``. The exact name depends on your system and desired version. On most
OSes, ``python`` is Python 2 and ``python3`` is 3; ``python2`` should also
exist.  On Arch Linux, ``python`` is Python 3. On Windows, use the ``py``
launcher.

Windows
-------

Download the installer(s): https://www.python.org/downloads/

Those installers come with ``pip``, and modern Python 3.x versions come with
the ``py`` launcher.  You can use that launcher to pick a specific Python
version, eg.:

* ``py -3 -m pip install <package>``
* ``py -2 somefile.py``
* ``py -2.7``
* ``py`` (default system version)

It’s recommended for most use, and mandatory for upgrading pip.

The 32-bit versions are more versatile. Most packages support both (the only
exception I’m aware of is Tensorflow, which only allows 64-bit Python 3.5 as of
now).

macOS
-----

macOS ships with Python 2.7.10 (as of macOS Sierra). It’s not the latest
version; it’s good enough for most people, but I still recommend installing
your own (the system Python doesn’t include ``pip``, for example). You can
install the latest 2.7 version, as well as Python 3, using a package manager. I
recommend Homebrew — it’s the most popular solution, and lets you install many
other packages.

**DO NOT** use the python.org installers: they do not have uninstallers, so you
will have outdated versions lying around after some time. There is no
auto-update as well.  **DO NOT** attempt to remove the system-installed Python,
this will only damage your system and you’ll need to reinstall.

If you already have a package manager installed (MacPorts, Fink), don’t install
a new one and just use the existing one.

1. Install `Homebrew <https://brew.sh/>`_.
2. Run ``brew install python python3``.
3. You should now have ``python``, ``python3``, ``pip`` and ``pip3``.

To update Homebrew and Python, run ``brew update``.

Linux (and other Unix-like OSes)
--------------------------------

On Linux, there usually are good enough packages in your OS repositories. You
should be able to install the appropriate package for Python (2 and/or 3).
Most (if not all) distributions require Python — **do not** remove the
pre-installed packages, and be careful not to overwrite them with something
newer.

If the version that ships with your distribution is too old, there are some
options. There might be some repositories with better versions, eg. the
`deadsnakes PPA <https://launchpad.net/~fkrull/+archive/ubuntu/deadsnakes>`_
for Ubuntu. Then there’s the other option of compiling Python. There
are some tools to help with this, like ``pyenv`` or ``pythonz`` (they can also
manage multiple Python versions), or you can do it manually.
The instructions depend on your exact requirements, but here’s a summary:

1. Download the `source distribution from Python.org <https://www.python.org/downloads/source/>`_ and unpack it. Go into the unpacked source directory.
2. Ensure you’ve got a functional C compiler and Python’s dependencies. You can
   usually use your system’s package manager to install the build dependencies
   of your system Python. Some dependencies are optional (eg. ``sqlite3``
   requires SQLite headers).
3. Run |conf3x| and then ``make``. (You may add other options to both. It will
   take a while.)
4. Run ``make altinstall`` as root. Avoid ``make install``, as it can override
   ``python`` executables.

Remember: compiling Python should be considered a **last resort**, unless you
have very specific Python version requirements.

Installing packages
===================

To install third-party packages, you should use pip, the Python package
manager. If you’re using Windows or macOS (from Homebrew), pip is included with
your copy of Python.  If you’re on Linux and installed Python from a system
repository, install the correct system package (``python-pip``,
``python3-pip``). If you compiled your own Python, pip is also included.

To run pip, use ``py -m pip`` (Windows), ``python -m pip`` (other platforms),
or the short ``pip``/``pip3`` commands.

**NEVER use sudo pip.** This can cause numerous problems:

* conflicts between packages installed by pip and your system package
  manager
* pip modifying system packages, leading to issues when updating them, or
  breaking dependencies
* no isolation between package versions, which is sometimes needed to satisfy
  dependencies

Note that a package install is specific to the Python interpreter used to run
``pip``. Packages installed to a virtualenv are separate from system packages;
packages installed for “global” Python 2.7 are separate from |last3x| packages.
Virtual environments generally don’t use the system packages, unless
specifically enabled during creation.

Some distros have popular packages in their repositories. Sometimes they’re
good; in other cases they’re terribly outdated or they lack important
components, making package managers angry and sick of supporting a 2-year-old
version. (Especially since most bugs are closed with “we’ve fixed that long
ago”)

User installs
-------------

At a small scale, you can install packages with pip for a single user.  Use
``pip install --user PACKAGE`` to do this. If your package installs scripts_,
they will be installed to ``~/.local/bin`` on Linux, and
``~/Library/Python/X.Y/bin`` on macOS (X.Y is Python version), or you can use
``python -m`` if the package supports it.

For most people and projects, virtual environments are better. There are,
however, use cases for putting some packages user-wide — if you don’t work on
projects, but instead are doing one-off research projects, those are better
suited by user-wide installs.

.. _scripts: https://chriswarrick.com/blog/2014/09/15/python-apps-the-right-way-entry_points-and-scripts/

Virtual environments
--------------------

.. class:: lead

   I wrote a newer, more detailed post about virtualenvs: `Python Virtual
   Environments in Five Minutes
   <https://chriswarrick.com/blog/2018/09/04/python-virtual-environments/>`_

Virtual environments are the best way to install and manage Python packages.
Advantages include:

* Isolation of projects and their requirements: if one app/package requires
  library version X, but another requires version Y, they can live in separate
  virtual environments
* Independent from system-wide packages
* Lightweight (an empty virtualenv is about 10 MB)
* Simple to re-create in any place (``pip freeze > requirements.txt`` → ``pip install -r requirements.txt``)

Tools and management
~~~~~~~~~~~~~~~~~~~~

There are two tools to facilitate creation of virtual environments: the older
`virtualenv <https://virtualenv.pypa.io/en/stable/>`_ project, and the newer
``venv`` module. The ``venv`` module is shipped with Python 3.x; some
distributions may put it in a separate package or remove it altogether. Use
whichever works for you.  Virtualenv is compatible with more Python versions
and cannot be broken by incompetent OS package maintainers (``venv`` requires
an extra package on Debian).

There are multiple schools of thought regarding virtualenv placement and
content. Myself, I use `virtualenvwrapper
<https://virtualenvwrapper.readthedocs.io/en/latest/>`_ to manage virtualenvs
and put them in ``~/virtualenvs``. Other people put virtualenvs inside their
git repositories (but they *must* be in ``.gitignore``) Virtualenvs should only contain packages
installed with ``pip`` so they can be recreated quickly.

I also use the ``virtualenvwrapper`` plugin for Oh My Zsh, which also
activates virtualenvs with the same name as a git repo, or the environment
named by a ``.venv`` file.

Installation and usage
~~~~~~~~~~~~~~~~~~~~~~

To install virtualenv user-wide, use ``pip install --user virtualenv``. You can
then use it with ``python -m virtualenv DIRECTORY``. You may pass extra
options, eg. interpreter to use (``-p python3``). Sometimes you need to install
virtualenv for every Python version; usually, one copy is enough.

How to use them? This is a subject of heated debate in the Python community.

* Some people believe that activating (``source bin/activate`` on \*nix;
  ``Scripts\activate`` on Windows) is the right thing to do and simplifies work.
* Others think that you should use ``bin/python`` (or other scripts in that
  directory) directly, as activation only changes ``$PATH`` and some helper
  variables — those variables are not mandatory for operation, running
  the correct ``python`` is.
* Others still think `virtualenvs should be used in subshells
  <https://gist.github.com/datagrok/2199506>`_.

In my opinion, if activating virtualenvs works in your environment, you should
do it — it’s the most convenient option. There are, however, cases when
activation fails, or is otherwise impossible — calling ``bin/python`` directly
is your best bet in that case. If you are working inside shell scripts, do not
activate virtualenvs.  I’m not a fan of the subshell option, because it
complicates stuff if you work on multiple projects, and requires tracking usage
manually.

Upgrading and moving
~~~~~~~~~~~~~~~~~~~~

Upgrading the system Python may make your virtualenvs unusable.
For patch version upgrades, you can just update symlinks (see `fix-venvs.sh`__).
However, if the minor version changes, it’s best to re-create the virtualenv
(you need to create ``requirements.txt`` ahead of time).

You cannot move a virtualenv between directories/machines or rename
virtualenvs. You need to use ``pip freeze > requirements.txt``, create a new
virtualenv, and run ``pip install -r requirements.txt`` (you can then delete
the old environment with a simple ``rm -rf``)

__ https://github.com/Kwpolska/scripts/blob/master/fix-venvs.sh

Packages with C extensions (binary)
-----------------------------------

The situation improved drastically in the past year or so. Nowadays, almost
all packages have a pre-compiled package available in PyPI. Those packages work
for Windows, macOS, and Linux. There are packages for some of the most
common *offenders*, including Pillow, lxml, PyQt5, numpy… However, there might
still be packages without wheels on PyPI.

If there is no wheel for a package and you are on Windows, check out `Christoph
Gohlke’s unofficial binaries <http://www.lfd.uci.edu/~gohlke/pythonlibs/>`_.
If you can’t find any wheels online, you would have to resort to compiling it
manually — this requires installing Visual Studio (Visual C++) in a version
that matches your Python, and it’s kind of a pain to do.

If you are not on Windows, you must install a C compiler and toolchain.
If you get a warning about missing ``Python.h``, install the appropriate development
package — for example, ``python-dev`` or ``python3-dev``) on Debian/Ubuntu,
``python-devel`` or ``python3-devel`` on RHEL/Fedora. The package you’re trying
to install might have other dependencies that you need to install (the
``-dev(el)`` part is important, too)

Other stuff
-----------

If you’re working on a project, use ``pip install -e .`` inside the project
directory to install the package in your environment in development (editable)
mode. This loads code directly from your repository — you don’t need to
re-install on every change; you might need to re-install when your version
number changes.

Editors and IDEs
================

Another important thing a developer should take care of is the choice of an
editor. This is an important decision, and is the reason for many holy wars in
the programmer community.

A good editor should have syntax highlighting for all languages you need to
work with. It should also have features like visual block/multiple selections,
sophisticated find-and-replace, file finding, code completion, and many more minor
but helpful features.

Then there’s the difference between IDEs and text editors. Text editors are
simpler, whereas IDEs try to include many extra things not necessarily related
to writing code. IDEs often use more resources, but you won’t notice it with a
modern computer (especially with a SSD).

The best IDE out there is `PyCharm <https://www.jetbrains.com/pycharm/>`_ from
JetBrains. It has both a free Community and paid Professional edition. The
JetBrains folks are experts at IDEs — they have fully-fledged tools for many
languages. Their Python solution offers a plethora of options that aid
programmers in their work.  Also, if you work with Java, or otherwise more than
one IDEA-supported language, then install IntelliJ IDEA and the Python plugin
(which has the same features as PyCharm).  Students can get `free
Professional/Ultimate licenses for JetBrains products
<https://www.jetbrains.com/student/>`_.

I also spend a lot of time in `Vim <http://www.vim.org/>`_ (`neovim
<https://neovim.io/>`_/`VimR <http://vimr.org/>`_ to be precise). Vim is the
most powerful text editor out there, and with the right set of plugins it can
beat IDEs at speed and productivity. Vim has a steep learning curve, but it’s
worth it — you can do large changes with just a few keystrokes. Vim is
considered so good that many IDEs (Visual Studio, IntelliJ IDEA/PyCharm) have
Vim emulation plugins.

Another option is `Visual Studio Code <https://code.visualstudio.com/>`_ — it’s
a text editor, but can offer many IDE-like features with the right set of
plugins. It’s Electron-based architecture, or effectively being based on top of
Google’s Chromium, is unfortunate and can lead to terrible performance on
lower-end machines, and on higher-end ones in some cases. (In my experience,
it’s better than Atom.) You can also try `Sublime Text
<https://www.sublimetext.com/>`_ ($80).

But really, almost any editor will do. But please **avoid** IDLE, the editor
included with Python. It lacks some of the most basic things — it doesn’t even
have an option to show line numbers. Not to mention its ugliness. Also, don’t
use Notepad and TextEdit. Those are too simple, and Notepad has encoding
issues.


Update history
==============

2018-09-21
    Link to python-virtual-environments post.

2017-07-19
    Better description of problems caused by using sudo pip.

2017-07-10
    Added notes about not removing built-in Pythons.

2017-07-07
    Spelling fixes and updates to the virtualenv usage section.

.. |last3x| replace:: 3.6
.. |conf3x| replace:: ``./configure --prefix=/opt/python3.6``
