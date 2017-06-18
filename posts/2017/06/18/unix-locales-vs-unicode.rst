.. title: Unix locales vs Unicode (‘ascii’ codec can’t encode character…)
.. slug: unix-locales-vs-unicode
.. date: 2017-06-18 20:40:00+02:00
.. tags: Unix, Unicode, locale, Python, guide
.. section: Programming
.. description: How to configure locales for Unicode support.
.. type: text
.. guide: yes
.. guide_topic: Locale support/Unicode
.. guide_platform: Unix (Linux, macOS, etc.)
.. guide_effect: you can now use Unicode in many more places
.. shortlink: locale

You might get unusual errors about Unicode and inability to convert
to ASCII. Programs might just crash at random. Those are often simple to fix —
all you need is correct locale configuration.

.. TEASER_END

.. class:: lead

Has this ever happened to you?

.. code:: pytb

    Traceback (most recent call last):
      File "aogonek.py", line 1, in <module>
        print(u'\u0105')
    UnicodeEncodeError: 'ascii' codec can't encode character '\u0105' in position 0: ordinal not in range(128)

.. code:: text

    Nikola: Could not guess locale for language en, using locale C

.. code:: text

    Input: ą
    Desired ascii(): '\u0105'
    Real ascii(): '\udcc4\udc85'

.. code:: text

    perl: warning: Setting locale failed.
    perl: warning: Please check that your locale settings:
        [...]
        are supported and installed on your system.
    perl: warning: Falling back to the standard locale ("C").

.. class:: lead

All those errors have the same root cause: incorrect locale configuration.
To fix them all, you need to generate the missing locales and set them.

Check currently used locale
---------------------------

The ``locale`` command (without arguments) should tell you which locales you’re
currently using.  (The list might be shorter on your end)

.. code:: sh

    $ locale
    LANG="en_US.UTF-8"
    LC_CTYPE="en_US.UTF-8"
    LC_NUMERIC="en_US.UTF-8"
    LC_TIME="en_US.UTF-8"
    LC_COLLATE="en_US.UTF-8"
    LC_MONETARY="en_US.UTF-8"
    LC_MESSAGES="en_US.UTF-8"
    LC_PAPER="en_US.UTF-8"
    LC_NAME="en_US.UTF-8"
    LC_ADDRESS="en_US.UTF-8"
    LC_TELEPHONE="en_US.UTF-8"
    LC_MEASUREMENT="en_US.UTF-8"
    LC_IDENTIFICATION="en_US.UTF-8"
    LC_ALL=

If any of those is set to ``C`` or ``POSIX``, has a different encoding than
``UTF-8`` (sometimes spelled ``utf8``) is empty (with the exception of
``LC_ALL``), or if you see any errors, you need to reconfigure your locale.

Check locale availability and install missing locales
-----------------------------------------------------

The first thing you need to do is check locale availability. To do this, run
``locale -a``. This will produce a list of all installed locales.  You can use
``grep`` to get a more reasonable list.

.. code:: text

    $ locale -a | grep -i utf
    <lists all UTF-8 locales>
    $ locale -a | grep -i utf | grep -i en_US
    en_US.UTF-8

The best locale to use is the one for your language, with the UTF-8 encoding.
The locale will be used by some console apps for output. I’m going to use
``en_US.UTF-8`` in this guide.

If you can’t see any UTF-8 locales, or no appropriate locale setting for your
language of choice, you might need to generate those. The required actions
depend on your distro/OS.

* Debian, Ubuntu, and derivatives: install ``language-pack-en-base``, run ``sudo dpkg-reconfigure locales``
* RHEL, CentOS, Fedora: install ``glibc-langpack-en``
* Arch Linux: uncomment relevant entries in ``/etc/locale.gen`` and run ``sudo locale-gen`` `(wiki) <https://wiki.archlinux.org/index.php/Locale>`_
* For other OSes, refer to the documentation.

You need a UTF-8 locale to ensure compatibility with software. Avoid the ``C``
and ``POSIX`` locales (it’s ASCII) and locales with other encodings (those
aren’t used by ~anyone these days)

Configure system-wide
---------------------

On some systems, you may be able to configure locale system-wide.  Check your
system documentation for details. If your system has systemd, run 

.. code:: text

    sudo localectl set-locale LANG=en_US.UTF-8

Configure for a single user
---------------------------

If your environment does not allow system-wide locale configuration (macOS,
shared server with generated but unconfigured locales), or if you want to
ensure it’s always configured independently of system settings.

To do this, you need to edit the configuration file for your shell. If you’re
using bash, it’s ``.bashrc`` (or ``.bash_profile`` on macOS). For zsh users,
``.zshrc``.  Add this line (or equivalent in your shell):

.. code:: sh

    export LANG=en_US.UTF-8 LC_ALL=en_US.UTF-8

That should be enough. Note that those settings don’t apply to programs
not launched through a shell.

----

**Python/Windows corner:** Python 3.7 will fix this on Unix by assuming UTF-8
if it encounters the C locale.  On Windows, Python 3.6 is using UTF-8
interactively, but not when using shell redirections to files or pipes.

*This post was brought to you by ą — U+0105 LATIN SMALL LETTER A WITH OGONEK.*
