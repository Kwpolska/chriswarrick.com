.. title: PKGBUILDer Ready for Prime Time!
.. slug: pkgbuilder-ready-for-prime-time
.. date: 2012-07-19 16:30:00
.. tags: projects, Python, Linux
.. tags: Python
.. description: My AUR helper, PKGBUILDer, is ready for prime time.  A word about it is posted here.
.. nocomments: true

.. class:: pull-right
.. image:: /blog-content/logos/pkgbuilder.png

Yesterday, I officially decided to get PKGBUILDer a life.

.. TEASER_END

Have some copypaste from reddit_ (/r/archlinux):

I decided to share my AUR helper with the /r/archlinux community.  There is a
bazillion of them, but none of them fit my needs in terms of speed and
usability.

The actual development of PKGBUILDer started at the end of August 2011, and it
has improved greatly since then.  I just released 2.1.4.4.  Although the
numbering started at 2.0, because 1.0 is the version number for the original
Perl “script” (rewritten in 10 lines of Bash).  Since then, nearly every line
changed in some way.  I did not make too many public announcements, though,
since I believed that it was not quite ready for that.  2.1.4.0 was a release
which was neatly ready for prime time.  With help of fosskers (the guy behind
aura, another AUR helper, that was also announced here lately), I fixed the
last bugs, added downgrade functionalities and I think I got it right this
time.

PKGBUILDer is an AUR helper with many useful features.  They include:

 * ``pb`` wrapper for pacman and PKGBUILDer (``-Shv`` are taken and worked with,
   everything else is dropped down to pacman)
 * pacman-style output (`-S` flag activates)
 * translations support (looking for translators, the docs contain a guide)
 * user-friendly
 * no useless questions, it b
 * very, VERY fast (AUR) `-[Sy]u`
 * AUR downgrade warnings with `-[Sy]u`
 * Python 3 + pyalpm, meaning everything is using a nice library rather than
   cheap pacman calls. (I do call `makepkg` though, but I cannot do anything
   else.  I also ask the shell to take the dependencies out, because (ba)sh
   is too complex to be parsed by other things)

I’d love to see some comments, hints and contributions.  Thanks in advance!

GitHub_ — Docs_ – AUR_

.. _reddit: http://www.reddit.com/r/archlinux/comments/10339m/pkgbuilder_an_aur_helper_for_humans/
.. _GitHub: https://github.com/Kwpolska/pkgbuilder
.. _Docs: http://pkgbuilder.rtfd.org/
.. _AUR: https://aur.archlinux.org/packages.php?ID=52542

Now, let’s get further.

Comparison with other helpers
=============================
(requested by untitaker on reddit)

.. class:: table table-striped

============================= ====== ====== ===== ============== =======
Feature                       yaourt packer cower PKGBUILDer     aura
============================= ====== ====== ===== ============== =======
Written in                    bash   bash   C     Python 3       Haskell
Repository support            yes    yes    no    yes (`pb`)     yes
Dependency support            yes    yes    yes   yes            yes
Questions asked (total)       many   many   none  one            some
Speed                         slow   slow   fast  fast           medium
-Syu speed                    slow   slow   fast  ultra fast     slow
Philosophy                    1      2      3     4              5
User must think?              no     no     yes   twice          yes
Additional features           none?  none?  none  downgrade      6
Chances to break tomorrow[^7] high   high   low   low            high
Human friendliness            high   high   low   ultra high     medium
Output prettiness             high   high   low   high           high
============================= ====== ====== ===== ============== =======

1. pacman with AUR support.
2. pacman with AUR support.
3. AUR support, just hinting at stuff, don’t do everything for the user.
4. AUR support, but with doing stuff for the user., `pb` = pacman with AUR support.
5. pacman + AUR support, but the AUR is separate from everything else.
6. logs, orphans, downgrades, unicorns.
7. …if pacman devs change something in the ouptut.

How do I get it?
================

Easy.

    wget http://kwpolska.github.com/pb.py
    python3 pb.py

Or, using your favourite AUR helper, install pkgbuilder (and the deps, python-requests and python-certify)


