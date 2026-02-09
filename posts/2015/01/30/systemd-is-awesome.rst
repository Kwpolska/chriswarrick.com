.. title: systemd is awesome.
.. slug: systemd-is-awesome
.. date: 2015-01-30 16:00:00+01:00
.. tags: Linux, systemd, Arch Linux, Debian, Fedora
.. category: Linux
.. description: Praise for systemd.
.. type: text

I recently switched distros on my server, from Debian to Fedora, to use
``systemd`` and keep it in line with my home Arch Linux system (which was
not reinstalled since 2010, by the way!)  Why is systemd so awesome?  Read
on to find out.

.. TEASER_END


*(I changed the hardware for my home system along the way.  And just copied
everything over with ``dd``.  That’s Linux at its finest.)*

User friendly
=============

The most important thing in systemd is its user friendliness.  systemd offers
the ``systemctl`` tool, which can be used to control all the services.  You
can see what exactly is going on: what is running, what failed to start,
and you can also see why if you ask ``systemctl status $SERVICE``.

Services
========

Writing services is fun
-----------------------

If you want your own services, you just need to write some simple INI files.  No
need for bash, distro-specific frameworks and whatnot.  If I have a functioning
service written on Arch, I can just copy the file over to Fedora and blindly
enable it — assuming I have the executables installed, it’s guaranteed to work.

And the units are tiny::

    fedora$ wc -l /usr/lib/systemd/system/nginx.service
    15 /usr/lib/systemd/system/nginx.service
    debian$ wc -l /etc/init.d/nginx
    101 /etc/init.d/nginx

And you can write a bare-minimum systemd daemon in less than that – not so easy
with ``sysvinit`` (writing everything on one line doesn’t count!)

Managing personal services
--------------------------

systemd also features user-specific services.  You can run any service as your
user.  I use this to run KwBot_, which was previously under control of
``supervisord`` — that’s one less dependency to care about!

.. _KwBot: /kwbot/

Runlevels do not exist
----------------------

systemd does away with the standard convention of runlevels.  They are replaced
by human-friendly *targets*.  Each unit defines its target: most use
``multi-user.target``.  It is much easier to manage.

No symlink mess
---------------

Just like old sysvinit-esque systems, systemd uses symlinks to manage
enabled/disabled services.  There is just one difference: you get just **one**
symlink in the appropriate ``.wants`` directory.  You do not have to look into
all the different runlevels.  ``graphical.target`` depends on
``multi-user.target``, which in turn depends on ``basic.target``, which depends
on a few more targets required to get the system up and running.

Units can depend on each other
------------------------------

Do you have some units that require the network to be up?  Set it to be run
after and require ``network.target`` and call it a day.

The Journal
===========

If I want to know what is going on in my system, I can just ask ``journalctl``
to show me the most recent messages.  I don’t need to read a thousand different
log files — most things appear in the journal.  Sure, some things aren’t there
(yet), but what I *can* see is very useful.

Did I really change my distro for all that?
===========================================

Yes.  I got too annoyed with Debian’s idiocy.  Also, DigitalOcean doesn’t
*really* support Debian testing, and I cannot survive with outdated software.
I feel much better and, more importantly, **safer** with Fedora.

(also, the “Veteran Unix Admins” of Devuan are a bunch of idiots.)
