.. title: Spawning subprocesses smartly and securely
.. slug: spawning-subprocesses-smartly-and-securely
.. date: 2017-09-02 20:40:00+02:00
.. tags: Python, C, Unix, Linux, subprocess, security, devel, best practices, guide
.. section: Programming
.. description: How to spawn subprocesses securely? Do I need a subprocess?
.. type: text
.. guide: yes
.. guide_topic: spawning subprocesses
.. guide_platform: Unix/Linux (and partially Windows)
.. guide_effect: you know when to spawn subprocesses and how to do it right
.. shortlink: subprocess

As part of your code, you may be inclined to call a command to do
something. But is it always a good idea? How to do it safely? What happens
behind the scenes?

.. TEASER_END

This article is written from a general perspective, with a Unix/C bias and a
very slight Python bias. The problems mentioned apply to all languages in most
environments, including Windows.

.. class:: alert alert-info

.. contents::

Use the right tool for the job
==============================

By calling another process, you introduce a third-party dependency.
That dependency isn’t controlled by your code, and your code becomes more fragile.
The problems include:

* the program is not installed, or even available, for the user’s OS of choice
* the program is not in the ``$PATH`` your process gets
* the hard-coded path is not correct on the end user’s system
* the program is in a different version (eg. GNU vs. BSD, updates/patches),
  which means different option names or other behaviors
* the program’s output is not what you expected due to user config (including
  locale)
* error reporting is based on numeric exit codes, and the meaning of those
  differs between programs (*if* they have meaning besides 0/1 in the first
  place)

On the other hand, if your code uses a lot of subprocesses, perhaps you should
stay with Bash. You can do the harder parts with Python, Ruby, or some other
language by calling them from within your Bash script.

Don’t spawn subprocesses if there’s an alternative
==================================================

Spawning a subprocess always incurs a (minor) [1]_ performance hit minor
compared to the alternatives. With that in mind, and the resiliency issues
listed above, you should always try to find an alternative for the
external command.

The simplest ones are the basic Unix utilities. Replace ``grep``, ``sed`` and
``awk`` with string operations and regular expressions. Filesystem utilities
will have equivalents — for Python, in ``os`` or ``shutil``. Your language of
choice can also handle things like networking (don’t call ``curl``), file
compression, working with date/time…

Similarly, you should check if there are packages available that already do
what you want — library bindings or re-implementations. And if there isn’t,
perhaps you could help the world by writing one of those and sharing it?

One more important thing: if the program uses the same language as your code,
then you should try to import the code and run it from the same process instead
of spawning a process, if this is feasible.

Security considerations: shells, spaces, and command injection
==============================================================

We come to the most important part of this article: how to spawn subprocesses
without compromising your system. When you spawn a subprocess on a typical Unix
system,  ``fork()`` is called, and your process is copied. Many modern Unix
systems have a copy-on-write implementation of that syscall, meaning that the
operation does not result in copying all the memory of the host process over.
Forking is (almost) immediately followed by calling ``execve()`` (or a helper
function from the exec family) [2]_ in the child process — that function
*transforms the calling process into a new process* [3]_. This technique is
called *fork-exec* and is the typical way to spawn a new process on Unix. [4]_

There are two ways to access this API, from the C perspective:

* directly, by calling ``fork()`` and ``exec*()`` (or ``posix_spawn()``), and providing an array of
  arguments passed to the process, or
* through the shell (``sh``), usually by calling ``system()``. As Linux’s
  manpage for ``system(3)`` puts it,

   The ``system()`` library function uses ``fork(2)`` to create a child process that executes the shell command specified in command using ``execl(3)`` as follows:

   .. code:: c

      execl("/bin/sh", "sh", "-c", command, (char *) 0);

If you go through the shell, you pass one string argument, whereas ``exec*()`` demands you to specify arguments separately. Let’s write a sample program to print all the arguments it receives. I’ll do it in Python to get a more readable output.

.. code:: python

   #!/usr/bin/env python3
   import sys
   print(sys.argv)

Let’s see what appears:

.. code:: text

    $ ./argv.py foo bar
    ['./argv.py', 'foo', 'bar']
    $ ./argv.py 'foo bar'
    ['./argv.py', 'foo bar']
    $ ./argv.py foo\ bar baz
    ['./argv.py', 'foo bar', 'baz']

    $ ./argv.py $(date)
    ['./argv.py', 'Sat', 'Sep', '2', '16:54:52', 'CEST', '2017']
    $ ./argv.py "$(date)"
    ['./argv.py', 'Sat Sep  2 16:54:52 CEST 2017']

    $ ./argv.py /usr/*
    ['./argv.py', '/usr/X11', '/usr/X11R6', '/usr/bin', '/usr/include', '/usr/lib', '/usr/libexec', '/usr/local', '/usr/sbin', '/usr/share', '/usr/standalone']
    $ ./argv.py "/usr/*"
    ['./argv.py', '/usr/*']

    $ ./argv.py $EDITOR
    ['./argv.py', 'nvim']

    $ $PWD/argv.py foo bar
    ['/Users/kwpolska/Desktop/blog/subprocess/argv.py', 'foo', 'bar']
    $ ./argv.py a{b,c}d
    ['./argv.py', 'abd', 'acd']

    $ python argv.py foo bar | cat
    ['argv.py', 'foo', 'bar']
    $ python argv.py foo bar > foo.txt
    $ cat foo.txt
    ['argv.py', 'foo', 'bar']

    $ ./argv.py foo; ls /usr
    ['./argv.py', 'foo']
    X11@        X11R6@      bin/        include/    lib/        libexec/    local/      sbin/       share/      standalone/

As you can see, the following things are handled by the shell (the process is unaware of this occurring):

* quotes and escapes
* expanding expressions in braces
* expanding variables
* wildcards (glob, ``*``)
* redirections and pipes (``> >> |``)
* command substitution (backticks or ``$(…)``)
* running multiple commands on the same line (``; && || &``)

The list is full of potential vulnerabilities. If end users are in control of
the arguments passed, and you go through the shell, they can
**execute arbitrary commands** or even **get full shell access**. Even in other
cases, you’ll have to *depend on the shell’s parsing*, which introduces an
unnecessary indirection.

TL;DR: How to do this properly in your language of choice
=========================================================

To ensure spawning subprocess is done securely, **do not use the shell in between**. If you need any of the operations I listed above as part of your command — wildcards, pipes, etc. — you will need to take care of them in your code; most languages have those features built-in.

.. class:: dl-horizontal

In C (Unix)
  Perform fork-exec by yourself, or use ``posix_spawn()``. This also lets you communicate with the process if you open a pipe and make it stdout of the child process. Never use ``system()``.

In Python
  Use the subprocess module. Always pass ``shell=False`` and give it a *list* of arguments. With asyncio, use ``asyncio.create_subprocess_exec`` (and not ``_shell``), but note it takes ``*args`` and not a list. Never use ``os.system`` and ``os.popen``.

In Ruby
  Pass arrays to ``IO.popen``. Pass multiple arguments to ``system()`` (``system(["ls", "ls"])`` or ``system("ls", "-l")``). Never use ``%x{command}`` or backticks.

In Java
  Pass arrays to ``Runtime.exec``. Pass multiple arguments or list to ``ProcessBuilder``.

In PHP
  All the standard methods go through the shell. Try ``escapeshellcmd()``, ``escapeshellarg()`` — or better, switch to Python. Or anything, really.

In Go
  ``os/exec`` and ``os.StartProcess`` are safe.

In Node.js
  Use ``child_process.execFile`` or ``child_process.spawn`` with ``shell`` set to false.

Elsewhere
  You should be able to specify multiple strings (using variadic arguments,
  arrays, or otherwise standard data structures of your language of choice) as
  the command line. Otherwise, you might be running into something
  shell-related.

The part where I pretend I know something about Windows
=======================================================

On Windows, argument lists are always passed to processes as strings (Python
joins them semi-intelligently if it gets a list). Redirections and variables
work in shell mode, but globs (asterisks) are always left for the called
process to handle.

Some useful functions are implemented as shell built-ins — in that case, you
need to call it via the shell.

Internals: There is no ``fork()`` on Windows. Instead, ``CreateProcess()``,
``ShellExecute()``, or lower-level ``spawn*()`` functions are used. ``cmd.exe
/c`` is called in shell calls.

.. [1] Unless your operating system does not implement copy-on-write forking — in that case, you might even run out of memory if you use too much of it.
.. [2] The function that does the real work is ``execve()``, which takes an exact path, an array of arguments, and takes environment variables as input. Other variants can also perform a ``$PATH`` search, take argv as variadic arguments, and inherit environment from the current process. ``execl()`` does the last two.
.. [3] Quoted from ``execve(2)`` `man page <https://www.freebsd.org/cgi/man.cgi?query=execve&sektion=2>`_ from FreeBSD.
.. [4] An alternative is ``posix_spawn()``, but it usually does fork-exec, unless your platform does not support forking.
