.. title: Python Virtual Environments in Five Minutes
.. slug: python-virtual-environments
.. date: 2018-09-04 20:15:00+02:00
.. updated: 2019-07-22 23:00:00+02:00
.. description: A short yet descriptive guide on Python virtual environments.
.. tags: Python, guide, devel, best practices, virtual environments, venv, virtualenv
.. category: Python
.. guide: yes
.. guide_effect: you now know how to use virtual environments
.. guide_platform: any platform
.. guide_topic: Python virtual environments
.. shortlink: venv


In Python, virtual environments are used to isolate projects from each other
(if they require different versions of the same library, for example). They let
you install and manage packages without administrative privileges, and without
conflicting with the system package manager.  They also allow to quickly create
an environment somewhere else with the same dependencies.

Virtual environments are a crucial tool for any Python developer. And at that,
a very simple tool to work with.

.. TEASER_END

Let’s get started!

Install
=======

There are two main tools used to create virtual environments:

* `virtualenv <https://virtualenv.pypa.io/>`_ has
  been the de facto standard tool for many years. It can be used with both
  Python 2 and 3, including very old versions of Python.
* `venv <https://docs.python.org/3/library/venv.html>`_ (aka pyvenv) was added to the
  standard library in Python 3.3, and with the addition of ``ensurepip`` in 3.4,
  it’s an even easier way to get a virtual environment created.

virtualenv can be installed with your system package manager, or ``pip
install --user virtualenv``.

venv comes built-in with Python 3, although
Debian/Ubuntu users will need to run ``sudo apt-get install python3-venv`` to
make it work. [1]_

Which one to use? It’s up to you. Both tools achieve the same goal in similar
ways. And if one of them does not work, you can try the other and it might just
work better.

*(Terminology note: most of the time, the names of both tools are used
interchargeably, “venv” was often used as an abbreviation for “virtualenv”
before the stdlib tool was created)*

Create
======

To create a virtual environment named ``env``, use (depending on your tool of
choice):

.. code:: text

    $ python3 -m virtualenv env

or

.. code:: text

    $ python3 -m venv env

Afterwards, you will end up with a folder named ``env`` that contains folders
named ``bin`` (``Scripts`` on Windows — contains executables, including
``python``), ``lib`` (contains code), and ``include`` (contains C headers).

Both tools install ``pip`` and ``setuptools``, but ``venv`` does not ship with
``wheel``. In addition, the default versions tend to be more-or-less outdated.
Let’s upgrade them real quick (first command is Unix, second is Windows): [2]_

.. code:: text

    $ env/bin/python -m pip install --upgrade pip setuptools wheel
    > env\Scripts\python -m pip install --upgrade pip setuptools wheel

Where to store virtual environments?
------------------------------------

While the tools allow you to put your virtual environments anywhere in the
system, it is not a desirable thing to do. There are two options:

1. Have one global place for them, like ``~/virtualenvs``.
2. Store them in each project’s directory, like ``~/git/foobar/.venv``.

The first option comes with tools that make it easier, such as
`virtualenvwrapper <https://virtualenvwrapper.readthedocs.io/>`_.
The second option is equally easy to work with, but comes with one caveat —
you must add the venv directory to your ``.gitignore`` file, since you don’t
want it in your repository (it’s binary bloat, and works only on your machine).

And if you don’t want to install virtualenvwrapper but want to put virtualenvs
in one global place, all you need is a short function in your shell
configuration file:

.. code:: bash

    export WORKON_HOME=~/virtualenvs

    function workon {
        source "$WORKON_HOME/$1/bin/activate"
    }

Use
===

There are three ways of working with virtual environments interactively (in a
shell):

* activation (run ``source env/bin/activate`` on \*nix;
  ``env\Scripts\activate`` on Windows) — it simplifies work and requires less
  typing, although it can sometimes fail to work properly.
* executing ``env/bin/python`` (``env\Scripts\python``) and other scripts directly, as
  activation only changes ``$PATH`` and some helper variables — those variables
  are not mandatory for operation, running the correct ``python`` is, and that
  method is failsafe.
* `in subshells <https://gist.github.com/datagrok/2199506>`_ (IMO, it’s bad UX)

Whichever method you use, you must remember that without doing any of these
things, you will still be working with the system Python.

For non-interactive work (eg. crontab entries, system services, etc.),
activation and subshells are not viable solutions. In these cases, you must
always use the full path to Python.

Here are some usage examples (paths can be relative, of course):

.. code:: text

    ## *nix, activation ##
    $ source /path/to/env/bin/activate
    (env)$ pip install Django
    (env)$ deactivate

    ## *nix, manual execution ##
    $ /path/to/env/bin/pip install Django

    ## Windows, activation ##
    > C:\path\to\env\Scripts\activate
    (venv)> pip install Django
    (venv)> deactivate

    ## Windows, manual execution ##
    > C:\path\to\env\Scripts\pip install Django

The same principle applies to running Python itself, or any other script
installed by a package. (With Django’s ``manage.py``, calling it as
``./manage.py`` requires activation, or you can run
``venv/bin/python manage.py``.)

Moving/renaming/copying environments?
-------------------------------------

If you try to copy or rename a virtual environment, you will discover that the
copied environment does not work. This is because a virtual environment is
closely tied to both the Python it was created with, and the location it was
created in. (The “relocatable” option is deprecated and generally fails to
solve the problem.) [3]_

However, this is very easy to fix. Instead of moving/copying, just create a new
environment in the new location. Then, run ``pip freeze > requirements.txt`` in
the old environment to create a list of packages installed in it. With that,
you can just run ``pip install -r requirements.txt`` in the new environment to
install packages from the saved list. (Of course, you can copy ``requirements.txt``
between machines. In many cases, it will just work; sometimes, you might need a few
modifications to ``requirements.txt`` to remove OS-specific stuff.)

.. code:: text

    $ oldenv/bin/pip freeze > requirements.txt
    $ python3 -m venv newenv
    $ newenv/bin/pip install -r requirements.txt
    (You may rm -rf oldenv now if you desire)

Note that it might also be necessary to re-create your virtual environment
after a Python upgrade, [4]_ so it might be handy to keep an up-to-date
``requirements.txt`` for your virtual environments (for many projects, it makes
sense to put that in the repository).

Frequently Asked Questions
==========================

Do I need to install the virtualenv tool for each Python I want to use it with?
-------------------------------------------------------------------------------

In most cases, you can use ``virtualenv -p pythonX env`` to specify a different
Python version, but with some Python version combinations, that approach might
be unsuccessful.

I’m the only user on my system. Do I still need virtual environments?
---------------------------------------------------------------------

Yes, you do. First, you will still need separation between projects, sooner or
later.  Moreover, if you were to install packages system-wide with pip, you
might end up causing conflicts between packages installed by the system package
manager and by pip. Running ``sudo pip`` is never a good idea because of this.

I’m using Docker. Do I still need virtual environments?
-------------------------------------------------------

They are still a good idea in that case. They protect you against any bad
system-wide Python packages your OS image might have (and one popular base OS
is famous for those). They don’t introduce any extra overhead, while allowing
to have a clean environment and the ability to re-create it outside of Docker
(eg. for local development without Docker)

What about Pipenv?
------------------

Pipenv is a dependency management tool. It isn’t compatible with most workflows, and comes with many issues. In my opinion, it’s not worth using (Also, that thing about it being an officially recommended tool? Turns out it’s not true.)

I also wrote a blog post detailing concerns with that tool, titled `Pipenv: promises a lot, delivers very little <https://chriswarrick.com/blog/2018/07/17/pipenv-promises-a-lot-delivers-very-little/>`_.

Footnotes
=========

.. [1] The thing you’re actually installing is ``ensurepip``. In general, Debian isn’t exactly friendly with Python packaging.
.. [2] On Windows, you *must* run ``python -m pip`` instead of ``pip`` if you want to upgrade the package manager itself.
.. [3] All script shebangs contain the direct path to the environment’s Python executable.  Many things in the virtual environment are symlinks that point to the original Python.
.. [4] Definitely after a minor version (3.x → 3.y) upgrade, sometimes after a patch version upgrade (3.x.y → 3.x.z) as well.
