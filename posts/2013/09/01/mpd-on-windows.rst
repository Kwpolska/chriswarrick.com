.. title: Adventures in Windows: Music Player Daemon
.. slug: mpd-on-windows
.. date: 2013-09-01 19:15:00
.. description: A short how-to on installing MPD on Windows.
.. tags: Windows, mpd
.. section: Windows

Recently, I had to reinstall Windows.  One of the things I had to set up
was MPD, the `Music Player Daemon`_.

This is a short guide on how to do this.

.. TEASER_END

Step 1: get MPD and MPC
=======================

Download `MPD <http://musicpd.org/download/win32/>`_ (``mpd-x.y.z-win32.zip``, replacing ``x.y.z`` with whatever is the current available version of this file) and `MPC <http://musicpd.org/download/mpc/>`_ (``mpc-x.y-win32-zip``).  I suggest using
``C:\mpd`` as your base path.  This post assumes you actually used this
value.

Put everything from the ``/mpd-x.y.z-win32/bin/`` directory of the
``mpd-x.y.z-win32.zip`` archive into your base path (``C:\mpd``).  From
the ``mpc-x.y-win32.zip`` archive, take ``mpc.exe`` and
``libmpdclient-2.dll`` and put them there.

Step 1.5 (optional, but recommended): get GMPC
==============================================

If you want a nice, easy, graphical interface for MPD, you should install
GMPC, the `Gnome Music Player Client`_.  The installation is very
straightforward, so I’ll leave the details out.

Step 2: create a config file
============================

You need to create a configuration file.  A sample one is included in
``/mpd-x.y.z-win32/doc/mpdconf.sample``.  Use the ``winmm`` output type.

**Warning:** Windows Notepad will not work, because it does not understand
Unix-style line endings (``\n`` instead of ``\r\n``).

For ``db_file`` and ``log_file``, I recommend a file in ``C:\mpd\data\``.
I also recommend saving your config as ``C:\mpd\data\mpd.conf`` (in fact,
this is yet another assumption we make in this guide)

Multi Boot Corner: keep parts of the config the same
----------------------------------------------------

Because I have a dual-boot system, there are some directions for
multi-boot users.

Your ``music_directory``, ``playlist_directory`` and ``state_file`` *must* be the same on all the systems in your multi-boot environment.  It is recommended to use the same ``db_file``.

Your audio outputs must have the same names.

Step 3: test your configuration
===============================

Open up two Command Prompt windows.  In the first one, run::

    cd C:\mpd
    mpd data\mpd.conf

If you see this message::

    Failed to load database: Failed to open database file "<name>": No such file or directory

then you can ignore it.  If you see another message, fix whatever it
states.  You may also use ``--stdout`` or ``-v`` to get more output
messages.

Multi Boot path
---------------

If you followed the Multi Boot instructions correctly, you might be
hearing music already.  If you do not, use your other Command Prompt window and
run::

    cd C:\mpd
    mpc play

If it outputs the name of a song, a ``[playing]`` line, all is well (if you
cannot hear music, unmute your audio).

If you do not have a mpd database yet, use the *Single path*.

**If MPD works, press Ctrl+C in the window where you ran ``mpd
data\mpd.conf.``**

Single path
-----------

In the other Command Prompt window, run::

    cd C:\mpd
    mpc update
    mpc add "FILENAME"
    mpc play

(FILENAME should be a name of a file in your music library.  Make sure to
keep the quotes.)

And now you should hear music.  If you do not, try to unmute, then read
the output of ``mpc`` and try to fix it.

**If MPD works, press Ctrl+C in the window where you ran ``mpd
data\mpd.conf.``**

Step 3.5: test GMPC
===================

Start the Gnome Music Player Client.  Click *Forward*, *Connect*,
*Forward*, *Close* and all should be well.

Step 4: create a Windows service
================================

In order to run the daemon in the *daemon* mode, i.e. hidden, you need to
perform some extra steps.  You cannot use Startup in the Start Menu, you
also cannot use the Run registry keys (this results in an ugly Command Prompt
window running all the time that you cannot close or hide in the tray).

How to solve this problem?  Just run it as a Windows service.  That is
very easy to do.

Open a Command Prompt window as an administrator (right-click on *Command
Prompt* in the Start Menu/on the Start Screen and choose *Run as administrator*).

In that window, run one very easy command (make sure to copy-paste this
exactly!)::

    sc create mpd binPath= "c:\mpd\mpd.exe c:\mpd\data\mpd.conf"

If it said:

* ``[SC] CreateService SUCCESS``, congratulations — you are almost done!  Skip down to *Service configuration.*
* ``[SC] OpenSCManager FAILED 5: Access is denied.``, you need an
  **Administrator** Command Prompt.
* something else, check your spelling or Google the error.


Service configuration
---------------------

That is the easiest part of this guide.

Start the *Services* console.  You can get to it by typing
``services.msc`` into the Start Menu/Screen search (*Run* for XP or
older).

In the tool, find the ``mpd`` service.  Go to the *Log On* tab, choose
*This account:* and enter your credentials there.  Hit *Apply* and go to
the *General* tab, on which you should choose the *Startup type* to be
*Automatic (Delayed Start)*.  Finish by pressing *Start*.  MPD should be
running and configured properly.  You can now hit *OK* and close the
*Services* window, along with the command prompts you have open.

**Warning:** if you change your Windows password, you need to change your
password in the *Services* console as well!

Just in case: uninstalling the service
--------------------------------------

First off, stop the service (in the Services console or through ``sc stop
mpd`` in an administrator command prompt).

Then, run ``sc delete mpd``.


.. _Music Player Daemon: http://musicpd.org/
.. _Gnome Music Player Client: http://gmpclient.org/
