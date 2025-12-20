.. title: New project: think (Terminal Think Music)
.. slug: think
.. date: 2015-06-06 14:15:00+02:00
.. tags: Python, projects, Linux, think, app, CLI, game show, Jeopardy!
.. category: Python
.. link: https://github.com/Kwpolska/think
.. description: A new project of mine: Terminal Think Music.
.. type: text
.. nocomments: true

Are you executing a process that takes a long time?  Do you want to know that it’s
still working while you are in another terminal/making coffee?  Do you have a
favorite game show tune to play while doing something?

If yes: ``think`` is just for you.  For more details, read on or `hop onto the GitHub page <https://github.com/Kwpolska/think>`_.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <a href="https://chriswarrick.com/projects/think/" class="btn btn-primary" style="width: 250px;">
   <i class="fa fa-info-circle"></i>
   Project page
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/think" class="btn btn-secondary" style="width: 250px;">
   <i class="fab fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/think/releases" class="btn btn-secondary" style="width: 250px;">
   <i class="fa fa-download"></i>
   Downloads (GitHub)
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://pypi.python.org/pypi/think" class="btn btn-secondary" style="width: 250px;">
   <i class="fa fa-download"></i>
   Downloads (PyPI)
   </a>
   </p>

Installation
------------

You can install ``think`` from `PyPI <https://pypi.python.org/pypi/think>`_ (with ``pip install think``). Arch Linux
users can install the ``think`` package from the AUR.

Configuration
-------------

Create a file named ``~/.config/think.conf`` (or wherever your ``XDG_CONFIG_HOME`` is) with the following four lines:

.. code:: ini

    [Think]
    command = play
    file = /home/kwpolska/Dropbox/Media/Wielka Gra.mp3
    behavior = wait

* ``command`` is the command of the player that will be executed.  You can use
  ``play`` (from ``sox``) or any other fast CLI music player.
* ``file`` is the filename that will be given as the sole argument to the play command. No escaping is necessary. I’m using the `Wielka Gra <https://www.youtube.com/watch?v=Nnu7I3b7ZbY>`__ theme (BTW: I just published the MP3 file on the 1st anniversary of the upload!), Americans might want `Jeopardy! Think Music <https://www.youtube.com/watch?v=vXGhvoekY44>`__ (also the namesake for this command), Brits might want the `Countdown theme <https://www.youtube.com/watch?v=M2dhD9zR6hk>`__.
* ``behavior`` can be one of:

  * ``return`` — return control to the terminal as soon as the program finishes, without stopping the music
  * ``wait`` — wait until music stops before returning control
  * ``stop`` — stop the music and return control immediately

Usage
-----

Prepend ``think`` before the command that takes too long to execute:

.. code:: console

    think sleep 120
