.. title: Zsh — A Shell For The Power Users.
.. slug: zsh-a-shell-for-the-power-users
.. date: 2011-03-05 00:00:00
.. tags: Linux, shell, zsh
.. category: Linux
.. description: A word or two about Zsh.

What’s the most important non-kernel piece of software in UNIX-like OSes? The shell. A good shell.

.. TEASER_END

I’m working with UNIX-like OSes a long time. I wasn’t using a shell all the time, but the black window with a monospace font was seen more often on my screen with time. A black window with `bash` in it. Are you wondering why? They set it as the default shell. And some users might not bother with changing their settings or testing other shells.

``user@localhost:~$ zsh``
=========================

Are you still using bash? Switch over to zsh and learn why it’s better. Let’s begin with a reason list.

Reason #1. Intelligent Completion
=================================

The zsh’s bulit-in completion is the best one ever made. For example, here is how regular bash responds to ``pacman <Tab><Tab>``:

.. code-block:: text

    [kwpolska@kwpolska-lin ~]$ pacman
    Display all XXX possibilities? (y or n)

(The *possibilities* are all the files and directories in the current working directory.)

And here’s another bash response, this time with bash-completion:

.. code-block:: text

    [kwpolska@kwpolska-lin ~]$ pacman -
    -D          -h          -Q          -R          -S          -U          -V
    --database  --help      --query     --remove    --sync      --upgrade   --version

That’s much more helpful, but a new user still wouldn’t know what to do.

Are you wondering what zsh did after adding a ``-`` before the first ``<Tab>``?

.. code-block:: text

    [kwpolska@kwpolska-lin ~]$ pacman -Q
    -Q  -- Query the package database
    -R  -- Remove a package from the system
    -S  -- Synchronize packages
    -U  -- Upgrade a package
    -V  -- Display version and exit
    -h  -- Display usage

(If you will press tab once, it will just show the possibilities. If you will press it once again, it will change to -R.)

Do you want to start the GNOME’s Preferred Applications dialog box **from the shell, without using the Tab key**? Good luck! The name is ``gnome-default-applications-properties``. That’s **38** characters. **THIRTY EIGHT** characters. If you will make a typo in bash, you’ll see “command not found”, swear a few times and find the typo yourself. With zsh, instead of searching for typo, you can press ``<Tab>``. In many cases, you’ll see the proper command.

Reason #2. No ``cd`` required
=============================

If you will add one line to your zshrc, you’ll be able to skip cd if you want to go to a directory (doesn’t work if there’s something in the ``$PATH`` with the same name)::
    setopt autocd

Reason #3. Bulit-in commands
============================

Do you want to use the basic ``more``/``less``/``$PAGER`` to read a file? Just say ``<filename`` and you’re done. Do you need to use FTP? You can use ``zftp``.

Reason #4. ``bindkeys``
=======================

Wish to use some keys for special text operations? You can use bindkeys. I’m binding these keystrokes:

.. code-block:: bash

    bindkey "\e[1~" beginning-of-line       # Home
    bindkey "\e[4~" end-of-line             # End
    bindkey "\e[5~" beginning-of-history    # PageUp
    bindkey "\e[6~" end-of-history          # PageDown
    bindkey "\e[2~" quoted-insert           # Ins
    bindkey "\e[3~" delete-char             # Del
    bindkey "^[OH"  beginning-of-line       # Home
    bindkey "^[OF"  end-of-line             # End
    bindkey "^[[5~" beginning-of-history    # PageUp
    bindkey "^[[6~" end-of-history          # PageDown
    bindkey "^[[2~" quoted-insert           # Ins
    bindkey "^[[3~" delete-char             # Del
    bindkey "^[[1;5D" backward-word         # ^Left
    bindkey "^[[1;5C" forward-word          # ^Right


Getting Help
============

Do you need help? Choose one of the sources.

#zsh @ freenode
----------------
Do you love IRC, like me? Visit #zsh at freenode.

ZSHWiki
-------
The Z Shell has its very own wiki at <http://zshwiki.org>.

Mailing Lists
-------------
Subscribe to a mailing list: <http://zsh.sourceforge.net/Arc/mlist.html>.

Website/Web Documentation
-------------------------
You can find some information at <http://zsh.sourceforge.net/>.

The Man Page aka *Because zsh contains many features, the zsh manual has been split into a number of sections*
--------------------------------------------------------------------------------------------------------------

The `zsh` man page just tells you the most important things and informs you about other sections. If you aren’t sure where to search, try ``man zshall``.

.. code-block:: text

           zsh          Zsh overview
           zshroadmap   Informal introduction to the manual
           zshmisc      Anything not fitting into the other sections
           zshexpn      Zsh command and parameter expansion
           zshparam     Zsh parameters
           zshoptions   Zsh options
           zshbuiltins  Zsh built-in functions
           zshzle       Zsh command line editing
           zshcompwid   Zsh completion widgets
           zshcompsys   Zsh completion system
           zshcompctl   Zsh completion control
           zshmodules   Zsh loadable modules
           zshcalsys    Zsh built-in calendar functions
           zshtcpsys    Zsh built-in TCP functions
           zshzftpsys   Zsh built-in FTP client
           zshcontrib   Additional zsh functions and utilities
           zshall       Meta-man page containing all of the above
