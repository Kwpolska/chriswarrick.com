.. title: Pipenv: promises a lot, delivers very little
.. slug: pipenv-promises-a-lot-delivers-very-little
.. date: 2018-07-17 19:40:00+02:00
.. updated: 2018-07-22 21:20:00+02:00
.. tags: Python, Pipenv, packaging
.. category: Python
.. description: Pipenv is a Python packaging tool that does one thing reasonably well. It tries to promote itself as much more than it is.
.. type: text
.. shortlink: pipenv

Pipenv is a Python packaging tool that does one thing reasonably well — application dependency management. However, it is also plagued by issues, limitations and a break-neck development process. In the past, Pipenv’s promotional material was highly misleading as to its purpose and backers.

In this post, I will explore the problems with Pipenv. Was it really
recommended by Python.org? Can everyone — or at least, the vast majority
of people — benefit from it?

.. TEASER_END

.. class:: alert alert-primary

.. contents::

“Officially recommended tool”, or how we got here
=================================================

 “Pipenv — the officially recommended Python packaging tool from Python.org, free (as in freedom).”

Pipenv’s README used to have a version of the above line in their README for
many months: it was added on  `2017-08-31`_ and eventually disappeared on `2018-05-19`_. For a short while (2018-05-16), it was clarified (*managing application dependencies*, and *PyPA* instead of *Python.org*), and for about 15 minutes, the tagline called Pipenv *the world’s worst* or something_ along_ these_ lines_ (this coming from the maintainer).

The README tagline claimed that Pipenv is the be-all, end-all of Python
packaging. The problem is: it isn’t that. There are some use cases that benefit
from Pipenv, but for many others, trying to use that tool will only lead to
frustration. We will explore this issue later.

Another issue with this tagline was the *Python.org* and *official* parts. The
thing that made it “official” was a `short tutorial`_ [1]_ on packaging.python.org,
which is the PyPA’s packaging user guide. Also of note is the *Python.org*
domain used. It makes it sound as if Pipenv was endorsed by the Python core
team. PyPA (Python Packaging Authority) is a separate organization — they are
responsible for the packaging parts (including pypi.org, setuptools, pip,
wheel, virtualenv, etc.) of Python. This made the endorsement misleading. Of
course, PyPA is a valued part of the Python world; an endorsement by the core
team — say, `inclusion in official Python distributions`_ — is something far more
important.

This tagline has led to many discussions and flamewars, perhaps with `this
Reddit thread from May`__ being the most heated and most important. The change
was the direct result of this Reddit thread. I recommend reading this thread in
full.

.. _2017-08-31: https://github.com/pypa/pipenv/commit/6e06fc451767a57e6fccb828c74a1412f6cef687
.. _2018-05-19: https://github.com/pypa/pipenv/commit/47debed9a1c2a3649bef4d59a3f1cf01bf059522

.. _something: https://github.com/pypa/pipenv/commit/6d77e4a0551528d5d72d81e8a15da4722ad82f26
.. _along: https://github.com/pypa/pipenv/commit/1c956d37e6ad20babdb5021610b2ed2c9c4203f2
.. _these: https://github.com/pypa/pipenv/commit/e3c72e167d21b921bd3bd89d4217b04628919bb2
.. _lines: https://github.com/pypa/pipenv/commit/fe78628903948013e8687d1a3be9fd4da2b6bd3d

.. _short tutorial: https://packaging.python.org/tutorials/managing-dependencies/
.. _inclusion in official Python distributions: https://docs.python.org/3/library/ensurepip.html
__ https://np.reddit.com/r/Python/comments/8jd6aq/why_is_pipenv_the_recommended_packaging_tool_by/

What pipenv does
================

We’ve already learned that Pipenv is used to *manage application dependencies*.
Let’s learn what that term really means.

Application dependencies
------------------------

Here is an example use case for Pipenv:
I’m working on a website based on Django.  I create ``~/git/website`` and run
``pipenv install Django`` in that directory.  Pipenv:

* automatically creates a virtualenv somewhere in my home directory
* writes a Pipfile, which lists Django as my dependency
* installs Django using pip
* proceeds to write ``Pipfile.lock``, which stores the exact version and source file hash [2]_ of each package installed (including ``pytz``, Django’s dependency).

The last part of the process was the most time consuming. At one point, while
locking the dependency versions, Pipenv hangs for 46 seconds. This is one of
Pipenv’s notable issues: **it’s slow.** Of course, this isn’t the only one,
but it defintely doesn’t help. Losing 46 seconds isn’t much, but when we get to
the longer waits in the timing test section later, we’ll see something that
could easily discourage users from using a package.

Running scripts (badly)
-----------------------

But let’s continue with our workflow. ``pipenv run django-admin startproject
foobanizer`` is what I must use now, which is rather unwieldy to type, and
requires running pipenv even for the smallest things. (The ``manage.py`` script
has ``/usr/bin/env python`` in its shebang.) I can run ``pipenv shell`` to get
a new shell which runs the ``activate`` script by default, giving you the worst
of both worlds when it comes to virtualenv activation: the unwieldiness of a
new shell, and the activate script, which the proponents of the shell spawning
dislike.

Using ``pipenv shell`` means spawning a new subshell, executing the shell
startup scripts (eg. ``.bashrc``), and requiring you to exit with ``exit`` or
^D. If you type ``deactivate``, you are working with an extra shell, but now
outside of the virtualenv. Or you can use the ``--fancy`` mode that manipulates
``$PATH`` before launching the subshell, but it requires a specific shell
configuration, in which ``$PATH`` is not overridden in non-login shells — and
also often changing the config of your terminal emulator to run a login shell,
as many of the Linux terminals don’t do it.

Now, why does all this happen? Because a command cannot manipulate the
environment of the shell it spawns. This means that Pipenv must pretend what it
does is a reasonable thing instead of a workaround. This can be solved with
manual activation using ``source $(pipenv --venv)/bin/activate`` (can be made
into a neat alias), or shell wrappers (similar to what virtualenvwrapper does).

Finishing it all up
-------------------

Anyway, I want a blog on my site. I want to write them in Markdown syntax, so I
run ``pipenv install Markdown``, and a few long seconds later, it’s added to
both Pipfiles.  Another thing I can do is ``pipenv install --dev ipython`` and
get a handy shell for tinkering, but it will be marked as a development
dependency — so, not installed in production. That last part is an important
advantage of using Pipenv.

When I’m done working on my website, I commit both Pipfiles to my git
repository, and push it to the remote server. Then I can clone it to, say,
``/srv/website``. Now I can just ``pipenv install`` to get all the production
packages installed (but not the development ones — Django, pytz, Markdown will
be installed, but IPython and all its million dependencies won’t). There’s just
one caveat: by default, the virtualenv will still be created in the current
user’s home directory. This is a problem in this case, since it needs to be
accessible by `nginx and uWSGI`_, which do not have access to my (or root’s)
home directory, and don’t have a home directory of their own.  This can be
solved with ``export PIPENV_VENV_IN_PROJECT=1``. But note that I will now need
to export this environment variable every time I work with the app in ``/srv``
via Pipenv. The tool supports loading ``.env`` files, **but** only when
running ``pipenv shell`` and ``pipenv run``. You can’t use it to configure
Pipenv. And to run my app with nginx/uWSGI, I will need to know the exact virtualenv
path anyway, since I can’t use ``pipenv run`` as part of uWSGI configuration.

.. _nginx and uWSGI: https://chriswarrick.com/blog/2016/02/10/deploying-python-web-apps-with-nginx-and-uwsgi-emperor/

What pipenv doesn’t do
======================

The workflow I mentioned above looks pretty reasonable, right? There are some
deficiencies, but other than that, it seems to work well. The main issue with
Pipenv is: **it works with one workflow, and one workflow only.** Try to do
anything else, and you end up facing multiple obstacles.

Setup.py, source distributions, and wheels
------------------------------------------

Pipenv only concerns itself with managing dependencies. **It isn’t a packaging
tool.** If you want your thing up on PyPI, Pipenv won’t help you with anything.
You still need to write a ``setup.py`` with ``install_requires``, because the
Pipfile format only specifies the dependencies and runtime requirements (Python
version), there is no place in it for the package name, and Pipenv does not
mandate/expect you to install your project. It can come in handy to manage the
development environment (as a ``requirements.txt`` replacement, or something
used to write said file), but if your project has a ``setup.py``, you still
need to manually manage ``install_requires``. Pipenv can’t create wheels on its
own either. And ``pip freeze`` is going to be a lot faster than Pipenv ever
will be.

Working outside of the project root
-----------------------------------

Another issue with Pipenv is the use of the working directory to select
the virtual environment. [3]_ Let’s say I’m a library author.  A user of my ``foobar``
library has just reported a bug and attached a ``repro.py`` file that lets me
reproduce the issue. I download that file to ``~/Downloads`` on my filesystem.
With plain old virtualenv, I can easily confirm the reproduction in a spare
shell with:

.. code:: shell

   $ ~/virtualenvs/foobar/bin/python ~/Downloads/repro.py

And then I can launch my fancy IDE to fix the bug.  I don’t have to ``cd`` into
the project. But with Pipenv, I can’t really do that.  If I put the virtualenv
in ``.venv`` with the command line option, I can type
``~/git/foobar/.venv/bin/python ~/Downloads/repro.py``. If I use the
centralized directory + hashes thing, Tab completion becomes mandatory, if I
haven’t memorized the hash.

.. code:: shell

   $ cd ~/git/foobar
   $ pipenv run python ~/Downloads/repro.py

What if I had two ``.py`` files, or ``repro.py`` otherwise depended on being in
the current working directory?

.. code:: shell

   $ cd ~/git/foobar
   $ pipenv shell
   (foobar-Mwd1l2m9)$ cd ~/Downloads
   (foobar-Mwd1l2m9)$ python repro.py
   (foobar-Mwd1l2m9)$ exit  # (not deactivate!)

**This is becoming ugly fairly quickly.** Also, with virtualenvwrapper, I can
do this:

.. code:: shell

   $ cd ~/Downloads
   $ workon foobar
   (foobar)$ python repro.py
   (foobar)$ deactivate

And let’s not forget that Pipenv doesn’t help me to write a ``setup.py``,
distribute code, or manage releases.  It just manages dependencies.  And it
does it pretty badly.

Nikola
------

I’m a co-maintainer of a static site generator, `Nikola
<https://getnikola.com>`_.  As part of this, I have the following places where
I need to run Nikola:

* ``~/git/nikola``
* ``~/git/nikola-site``
* ``~/git/nikola-plugins``
* ``~/git/nikola-themes``
* ``~/website`` (this blog)
* ``/Volumes/RAMDisk/n`` (demo site, used for testing and created when needed, on a `RAM disk`_)

That list is long.  End users of Nikola probably don’t have a list that long,
but they might just have more than one Nikola site.  For me, and for the
aforementioned users, Pipenv does not work.  To use Pipenv, all those
repositories would need to live in one directory. I would also need to have a
*separate* Pipenv environment for ``nikola-users``, because that needs Django.
Moreover, the Pipfile would have to be symlinked from ``~/git/nikola`` if we
were to make use of those in the project.  So, I would have a ``~/nikola``
directory just to make Pipenv happy, do testing/bug reproduction on a SSD (and
wear it out faster), and so on… Well, I could also use the virtualenv directly.
But in that case, Pipenv loses its usefulness, and makes my workflow more
complicated. I can’t use ``virtualenvwrapper``, because I would need to hack a
fuzzy matching system onto it, or memorize the random string appended to my
virtualenv name.  All because Pipenv relies on the current directory too much.

Nikola end users who want to use Pipenv will also have a specific directory
structure forced on them. What if the site serves as docs for a project, and
lives inside another project’s repo? Two virtualenvs, 100 megabytes wasted.
Or worse, Nikola ends up in the other project’s Pipfile, which is technically
good for our download stats, but not really good for the other project’s
contributors.

.. _RAM disk: https://en.wikipedia.org/wiki/RAM_drive

The part where I try to measure times
=====================================

Pipenv is famous for being slow.  But how slow is it really?
I put it to the test.  I used two test environments:

* Remote: a DigitalOcean VPS, the cheapest option (1 vCPU), Python 3.6/Fedora
  28, in Frankfurt
* Local: my 2015 13” MacBook Pro (base model), Python 3.7, on a rather slow
  Internet connection (10 Mbps on a good day, and the test was not performed on
  one of them)

Both were runninng Pipenv 2018.7.1, installed from pip.

And with the following cache setups:

* Removed: ``~/.cache/pipenv`` removed
* Partial: ``rm -rf ~/.cache/pipenv/depcache-py*.json ~/.cache/pipenv/hash-cache/``
* Kept: no changes done from previous run

Well, turns out Pipenv likes doing strange things with caching and locking.  A
look at the Activity Monitor hinted that there is network activity going on
when Pipenv displays its *Locking [packages] dependencies...* line and
hangs. Now, the docs don’t tell you that. The most atrocious example was a
local Nikola install that was done in two runs: the first ``pipenv install
Nikola`` run was interrupted [4]_ right after it was done installing packages,
so the cache had all the necessary wheels in it. The install took 10 minutes
and 7 seconds, 9:50 of which were taken by locking dependencies and installing
the locked dependencies — so, roughly nine and a half minutes were spent
staring at a static screen, with the tool doing *something* in the background —
and Pipenv doesn’t tell you what happens in this phase.

(Updated 2018-07-22: In the pipenv measurements: the first entry is the total
time of pipenv executon. The second is the long wait for pipenv to do its
“main” job: locking dependencies and installing them. The timing starts when
pipenv starts locking dependencies and ends when the prompt appears. The third
item is pipenv’s reported installation time.  So, pipenv install ⊇ locking/installing ⊇ Pipfile.lock install.)

.. class:: table table-striped table-bordered

+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
| Task | Action                                 | Measurement   | Environment   | Cache                         | Times in seconds                              |
|      |                                        | method        |               |                               +-----------+-----------+-----------+-----------+
|      |                                        |               |               |                               | Attempt 1 | Attempt 2 | Attempt 3 | Average   |
+======+========================================+===============+===============+===============================+===========+===========+===========+===========+
|    1 | virtualenv                             | ``time``      | Remote        | (not applicable)              | 3.911     | 4.052     | 3.914     | 3.959     |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    2 | pip install Nikola                     | ``time``      | Remote        | Removed                       | 11.562    | 11.943    | 11.773    | 11.759    |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    3 | pip install Nikola                     | ``time``      | Remote        | Kept                          |  7.404    |  7.681    |  7.569    | 7.551     |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    4 | pipenv install Nikola                  | ``time``      | Remote        | Removed                       | 67.536    | 62.973    | 71.305    | 67.271    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 42.6      | 40.5      | 39.6      | 40.9      |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 14        | 14        | 13        | 13.667    |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    5 | adding Django to an environment        | ``time``      | Remote        | Kept (only Nikola in cache)   | 39.576    | —         | —         | 39.576    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 32        | —         | —         | 32        |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 14        | —         | —         | 14        |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    6 | adding Django to another environment   | ``time``      | Remote        | Kept (both in cache)          | 37.978    | —         | —         | 37.978    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 30.2      | —         | —         | 30.2      |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 14        | —         | —         | 14        |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    7 | pipenv install Django                  | ``time``      | Remote        | Removed                       | 20.612    | 20.666    | 20.665    | 20.648    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 6.6       | 6.4       | 6         | 6.333     |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 1         | 1         | 1         | 1         |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    8 | pipenv install Django (new env)        | ``time``      | Remote        | Kept                          | 17.615    | —         | —         | 17.615    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 3.5       | —         | —         | 3.5       |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 1         | —         | —         | 1         |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|    9 | pipenv install Nikola                  | ``time``      | Remote        | Partial                       | 61.507    | —         | —         | 61.507    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 38.40     | —         | —         | 38.40     |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 14        | —         | —         | 14        |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|   10 | pipenv install Django                  | ``time``      | Local         | Removed                       | 73.933    | —         | —         | 73.933    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 46        | —         | —         | 46        |
|      +----------------------------------------+---------------+               |                               +-----------+-----------+-----------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 0         | —         | —         | 0         |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|   11 | virtualenv                             | ``time``      | Local         | (not applicable)              | 5.864     | —         | —         | 5.864     |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|   12 | pip install Nikola (cached)            | ``time``      | Local         | Kept                          | 10.951    | —         | —         | 10.951    |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+
|   13 | pipenv install Nikola                  | ``time``      | Local         | Partial, after interruption   | 607.647   | (10m 7s)              | 607.647   |
|      +----------------------------------------+---------------+               |                               +-----------+-----------------------+-----------+
|      | ├─ locking/installing from lockfile    | stopwatch     |               |                               | 590.85    | (9m 50s)              | 590.85    |
|      +----------------------------------------+---------------+               |                               +-----------+-----------------------+-----------+
|      | └─ Pipfile.lock install                | pipenv        |               |                               | 6         |                       | 6         |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------------------+-----------+
|   14 | pipenv install                         | ``time``      | Local         | Kept                          | 31.399    | (L/I: 10.51 s)        | 31.399    |
+------+----------------------------------------+---------------+---------------+-------------------------------+-----------+-----------+-----------+-----------+

Alternatives and new tools
==========================

Python packaging is something with the state of which nobody seems to be
satisfied. As such, there are many new contenders for the role of “best new
packaging tool”. Apart from Pipenv, there are Hatch_ (by Ofek Lev) and Poetry_
(by Sébastien Eustace). Both are listed in the “official” tutorial as alternate
options.

Hatch
-----

**Hatch** tries to take care of *everything* in the packaging process. This is
mostly an asset, as it helps replace other tools. However, it can also be
argued that it adds a single point of failure. Hatch works on already standard
files, such as requirements.txt and setup.py, so it can be replaced with
something else quite easily. It doesn’t use as much magic as Pipenv and is more
configurable.  Some choices made by
Hatch are questionable (such as manually parsing ``pkg/__init__.py`` for a
version number, `installing test suites to site-packages
<https://github.com/ofek/hatch/pull/60>`_ (a rather common oversight), or its shell feature which is as ugly as Pipenv’s), and it does
not do anything to manage dependencies.  It doesn’t necessarily work for the
Django use case I mentioned earlier, or for end-users of software.

Poetry
------

**Poetry** is somewhere in between. Its main aim is close to Pipenv, but it
also makes it possible to distribute things to PyPI. It tries really hard to
hide that it uses Pip behind the scenes. Its README comes with an extensive
`“What about Pipenv?” <https://github.com/sdispater/poetry#what-about-pipenv>`_
section, which I recommend reading — it has a few more examples of bad Pipenv
features.  Poetry claims to use the standardized (PEP 518) ``pyproject.toml``
file to replace the usual lot of files. Unfortunately, the only thing that is
standardized is the file name and syntax. Poetry uses custom ``[tool.poetry]``
sections, which means that one needs Poetry to fully use the packages created
with it, leading to vendor lock-in. (The aforementioned Hatch tool also
produces a ``pyproject.tmpl``, which contains a ``metadata`` section…) There is
a ``build`` feature to produce a sdist with setup.py and friends.

In a simple ``poetry add Nikola`` test, it took 24.4s/15.1s/15.3s to resolve
dependencies (according to Poetry’s own count, Remote environment, caches
removed), complete with reassuring output and no quiet lockups.  Not as good as
pip, but it’s more reasonable than Pipenv.  Also, the codebase and its layout
are rather convoluted. Poetry produces packages instead of just managing
dependencies, so it’s generally more useful than Pipenv.

.. _Hatch: https://github.com/ofek/hatch
.. _Poetry: https://github.com/sdispater/poetry

Pip is here to stay!
--------------------

But in all the talk about new tools, we’re forgetting about the old ones, and
they do their job well — so well in fact, that the new tools still need them
under the covers.

Pip is fast. It does its job well enough. It lacks support for splitting
packages between production and development (as Pipenv and Poetry do). This
means that ``pip freeze`` and ``pip install`` are instant, at the cost of (a)
needing two separate environments, or (b) installing development dependencies
in production (which *should* only be a waste of HDD space and nothing more in
a well-architected system).

The virtualenv management features can be provided by virtualenvwrapper. That
tool’s main advantage is the shell script implementation, which means that
``workon foo`` activates the ``foo`` virtualenv without spawning a new
subshell (an issue with Pipenv, Hatch, and Poetry, that I already covered when
describing Pipenv’s operation in the `Running scripts (badly)`_ chapter.) An
argument often raised by Pipenv proponents is that one does not need to concern
itself with creating the virtualenv, and doesn’t need to care where it is.
Unfortuntately, many tools require this knowledge from their user, or force a
specific location, or require it to be different to the home directory.

And for a reasonable project template with release automation — well, I have my
own entry in that category, called (rather unoriginally) the `Python Project
Template (PyPT) <https://github.com/Kwpolska/python-project-template>`_.

Yes, setup.py files are not ideal, since they use ``.py`` code and a function
execution, making access to meta information hard (``./setup.py egg_info``
creates tool-accessible text files). Their main advantage is that they are the
*only* format that is widely supported — pip is the de-facto default
Python package manager (which is pre-installed on Windows and Mac), and other
tools would require installation/bootstrapping first.

The break-neck pace of Pipenv
=============================

A good packaging tool is stable. In other words, it doesn’t change often, and
it strives to support existing environments. It wouldn’t be fun to re-download
everything on your system, because someone decided that ``/usr`` is now called
``/stuff``, and all the files in ``/usr`` would become forgotten and not
removed. Well, this is what Pipenv did:


.. class:: table table-striped table-bordered

================    ========================================================================================================================================
Date/Time (UTC)     Event
================    ========================================================================================================================================
2017-01-31 22:01    v3.2.14 released. ``pipenv --three`` creates ``./.venv`` (eg. ``~/git/foo/.venv``). Last version with the original behavior of pipenv.
2017-02-01 05:36    v3.3.0 released. ``pipenv --three`` creates ``~/.local/share/virtualenvs/foo`` (to be precise, ``$WORKON_HOME/foo``).
2017-02-01 06:10    `Issue #178`_ is reported regarding the behavior change.
2017-02-01 06:18    Kenneth Reitz responds: “no plans for making it configurable.” and closes the issue.
2017-02-02 03:05    Kenneth Reitz responds: “added ``PIPENV_VENV_IN_PROJECT`` mode for classic operation. Not released yet.”
2017-02-02 04:29    v3.3.3 released. The default is still uses a “remote” location, but ``.venv`` can now be used.
2017-03-02 13:48    v3.5.0 released. The new default path is ``$WORKON_HOME/foo-HASH``, eg. ``~/.local/share/virtualenvs/foo-7pl2iuUI``.
================    ========================================================================================================================================

.. _Issue #178: https://github.com/pypa/pipenv/issues/178

Over the course of a month, the location of the virtualenv changed twice. If
the user didn’t read the changelog and didn’t manually intervene (also of note,
the option name was mentioned in the issue and in v3.3.4’s changelog), they
would have a stale ``.venv`` directory, since the new scheme was adopted for
them. And then, after switching to v3.5.0, they would have a stale virtualenv
hidden somewhere in their home directory, because pipenv decided to add hashes.

Also, this is not configurable. One cannot disable the hashes in paths, even
though `users <https://github.com/pypa/pipenv/issues/589>`__ `wanted
<https://github.com/pypa/pipenv/issues/1049>`__ to. It would also help people
who want to mix Pipenv and virtualenvwrapper.

Pipenv is a very **opinionated** tool, and if the dev team changes their mind,
the old way is not supported.

Pipenv moves fast and doesn’t care if anything breaks. As an example, between
2018-03-13 13:21 and 2018-03-14 13:44 (a little over 24 hours), Pipenv had 10
releases, ranging from v11.6.2 to v11.7.3. The changelog_ is rather unhelpful
when it comes to informing users what happened in each of the releases.

.. _changelog: https://github.com/pypa/pipenv/blob/25df09c171a548fd71d4df735767bf763a653b83/HISTORY.txt

Extra reading:

* `Kenneth Reitz, A Letter to /r/python (with some notes about bipolar disorder) <https://journal.kennethreitz.org/entry/r-python>`_
* Reddit comment threads for the letter: `first <https://np.reddit.com/r/Python/comments/8kdfd6/kenneth_reitz_a_letter_to_rpython_with_some_notes/>`_ and `second <https://np.reddit.com/r/Python/comments/8kjv8x/a_letter_to_rpython_kenneth_reitzs_journal/>`_

Conclusion
==========

* Pipenv, contrary to popular belief and (now removed) propaganda, is not an
  officially recommended tool of Python.org. It merely has a tutorial written
  about it on packaging.python.org (page run by the PyPA).
* Pipenv solves one use case reasonably well, but fails at many others, because
  it forces a particular workflow on its users.
* Pipenv does not handle any parts of packaging (cannot produce sdists and
  wheels).  Users who want to upload to PyPI need to manage a ``setup.py`` file
  manually, alongside and independently of Pipenv.
* Pipenv produces lockfiles, which are useful for reproducibility, at the cost
  of installation speed. The speed is a noticeable issue with the tool. ``pip
  freeze`` is good enough for this, even if there are no dependency classes
  (production vs development) and no hashes (which
  have minor benefits) [2]_
* Hatch attempts to replace many packaging tools, but some of its practices and
  ideas can be questionable.
* Poetry supports the same niche Pipenv does, while also adding the ability to
  create packages and improving over many gripes of Pipenv. A notable issue is
  the use of a custom all-encompassing file format, which makes switching tools
  more difficult (vendor lock-in).
* Pip, setup.py, and virtualenv — the traditional, tried-and-true tools — are
  still available, undergoing constant development. Using them can lead to a
  simpler, better experience.  Also of note, tools like virtualenvwrapper
  can manage virtualenvs better than the aforementioned new Python tools,
  because it is based on shell scripts (which can modify the enivironment).

.. [1] On a side note, the tutorial explains nothing. A prospective user only learns it’s similar to npm or bundler (what does that mean?), installs one package, and runs a ``.py`` file through ``pipenv run``.
.. [2] Note that one can’t change the file on PyPI after uploading it, so this would only be protection against rogue PyPI admins or a MitM attack (in which case you’ve got bigger problems anyways). `Also, the feature is fairly broken. <https://github.com/nedbat/coveragepy/issues/679#issuecomment-406396761>`_
.. [3] Fortunately, it looks in the parent directories for Pipfiles as well. Otherwise, you might end up with one environment for ``foo`` and another for ``foo/foo`` and yet another for ``foo/docs`` and so on…
.. [4] The interruption happened by mistake due to the RAM disk running out of space, but it was actually a good thing to have happened.

-----

.. class:: alert alert-info

**Other discussion threads:** `r/Python <https://www.reddit.com/r/Python/comments/a3h81m/pipenv_promises_a_lot_delivers_very_little/>`_, `Hacker News <https://news.ycombinator.com/item?id=18612590>`_.
