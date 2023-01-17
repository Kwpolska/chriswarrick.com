.. title: Python Virtual Environments in Five Minutes
.. slug: python-virtual-environments
.. date: 2018-09-04 20:15:00+02:00
.. updated: 2021-04-03 13:00:00+02:00
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

The best tool that can be used to create virtual environments is the
`venv <https://docs.python.org/3/library/venv.html>`_ module, which is part of
the standard library since Python 3.3.

``venv`` is built into Python, and most users don’t need to install anything.
However, Debian/Ubuntu users will need to run ``sudo apt-get install python3-venv`` to make it work (due to Debian not installing some components
that ``venv`` needs by default). [1]_

The alternative (and original, and previously standard) virtual environment tool is `virtualenv
<https://virtualenv.pypa.io/>`_. It works with Python 2.7, and has a couple
extra fetures (that you generally won’t need). virtualenv can be installed with your system package manager, or ``pip install --user virtualenv``.

Which one to use? Probably ``venv``. Both tools achieve the same goal in similar
ways. And if one of them does not work, you can try the other and it might just
work better.

*(Terminology note: most of the time, the names of both tools are used
interchargeably, “venv” was often used as an abbreviation for “virtualenv”
before the stdlib tool was created)*

Create
======

To create a virtual environment named ``env``, you need to run the ``venv``
tool with the Python you want to use in that environment.

.. code:: text

    Linux:   $ python3 -m venv env
    Windows: > py -m venv env

or, if you’re using ``virtualenv``:

.. code:: text

    $ python3 -m virtualenv env
    > py -m virtualenv env

Afterwards, you will end up with a folder named ``env`` that contains folders
named ``bin`` (``Scripts`` on Windows — contains executables and scripts
installed by packages, including
``python``), ``lib`` (contains code), and ``include`` (contains C headers).

Both tools install ``pip`` and ``setuptools``, but ``venv`` does not ship with
``wheel``. In addition, the default versions tend to be more-or-less outdated.
Let’s upgrade them real quick: [2]_

.. code:: text

    $ env/bin/python -m pip install --upgrade pip setuptools wheel
    > env\Scripts\python -m pip install --upgrade pip setuptools wheel

Where to store virtual environments?
------------------------------------

While the tools allow you to put your virtual environments anywhere in the
system, it is not a desirable thing to do. There are two options:

1. Have one global place for them, like ``~/virtualenvs``.
2. Store them in each project’s directory, like ``~/git/foobar/.venv``.

The first option can be easier to manage, there are tools that can help manage
those (eg. ``virtualenvwrapper``, shell auto-activation scripts, or the
``workon`` functions described below).  The second option is equally easy to
work with, but comes with one caveat — you must add the venv directory to your
``.gitignore`` file (or ``.git/info/exclude`` if you don’t want to commit
changes to ``.gitignore``), since you don’t want it in your repository (it’s
binary bloat, and works only on your machine).

If you pick the global virtual environment store option, you can use the following short
function (put it in ``.bashrc`` / ``.zshrc`` / your shell configuration file)
to get a simple way to activate an environment (by running ``workon foo``).
``virtualenvwrapper`` also has a ``workon`` feature, although I don’t think
``virtualenvwrapper`` is really necessary and too helpful — the ``workon``
feature is handy though, and so here’s a way to do it without
``virtualenvwrapper``:

.. code:: bash
   :linenos:

    export WORKON_HOME=~/virtualenvs

    function workon {
        source "$WORKON_HOME/$1/bin/activate"
    }

And for PowerShell fans, here’s a ``workon.ps1`` script:

.. code:: powershell
   :linenos:

    $WORKON_HOME = "$home\virtualenvs"
    & "$WORKON_HOME\$($args[0])\Scripts\activate.ps1"

And for cmd.exe fans… you should switch to PowerShell, it’s a very nice and
friendly shell (though perhaps requiring some effort to learn how to be
productive with it).

Use
===

There are three ways of working with virtual environments interactively (in a
shell):

* activation (run ``source env/bin/activate`` on \*nix;
  ``env\Scripts\activate`` on Windows) — it simplifies work and requires less
  typing, although it can sometimes fail to work properly. (After installing
  scripts, ``hash -r`` may be necessary on \*nix to use them.)
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

    ## Windows, updating pip/setuptools/wheel ##
    > C:\path\to\env\Scripts\python -m pip install -U pip setuptools wheel

The same principle applies to running Python itself, or any other script
installed by a package. (With Django’s ``manage.py``, calling it as
``./manage.py`` requires activation, or you can run
``venv/bin/python manage.py``.)

Moving/renaming/copying environments?
-------------------------------------

If you try to copy or rename a virtual environment, you will discover that the
copied environment does not work. This is because a virtual environment is
closely tied to both the Python it was created with, and the location it was
created in. (The “relocatable” option of ``virtualenv`` does not work and is deprecated.) [3]_

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

To manage those ``requirements.txt`` files in a more orgnized yet still simple
way, you might be interested in `pip-tools <https://github.com/jazzband/pip-tools>`_.

Frequently Asked Questions
==========================

I’m using virtualenv. Do I need to install it for each Python I want to use it with?
------------------------------------------------------------------------------------

In most cases, you can use ``virtualenv -p pythonX env`` to specify a different
Python version, but with some Python version combinations, that approach might
be unsuccessful. (The ``venv`` module is tied to the Python version it’s
installed in.)

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

Consider using `pip-tools <https://github.com/jazzband/pip-tools>`_ instead.

Footnotes
=========

.. [1] The thing you’re actually installing is ``ensurepip``. In general, Debian isn’t exactly friendly with Python packaging.
.. [2] On Windows, you *must* run ``python -m pip`` instead of ``pip`` if you want to upgrade the package manager itself.
.. [3] All script shebangs contain the direct path to the environment’s Python executable.  Many things in the virtual environment are symlinks that point to the original Python.
.. [4] Definitely after a minor version (3.x → 3.y) upgrade, sometimes (I’m looking at you Homebrew) after a patch version upgrade (3.x.y → 3.x.z) as well.
