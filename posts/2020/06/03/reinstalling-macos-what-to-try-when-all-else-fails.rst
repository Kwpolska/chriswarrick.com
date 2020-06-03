.. title: Reinstalling macOS: What To Try When All Else Fails
.. slug: reinstalling-macos-what-to-try-when-all-else-fails
.. date: 2020-06-03 23:00:00+02:00
.. tags: Apple, Mac, macOS, install
.. category: Apple
.. description: A collection of tricks to convince macOS installers to work.
.. type: text

Recently, I reinstalled macOS on my device. Throughout the process, many
attempts failed miserably. But I now have some experience and assorted hints on
what to try.

.. TEASER_END

**DISCLAIMER:** All information in this post is provided as-is, and some of it may
void your warranty. Neither Chris Warrick nor Apple will be responsible for any
damage to your devices caused as a result of using information in this post.

.. class:: alert alert-primary

.. contents::

Making install media with El Capitan (with access only to Recovery)
===================================================================

The best, safest, least error-prone way to do an install is with a USB stick.
Unfortunately, making a USB stick with the macOS installer on it is a nuisance.
The expected way to produce macOS install media is to download the installer
from App Store/Software Update, and run the ``createinstallmedia`` command-line
program included with that installer app. All is well, as long as macOS works.
If it doesn’t, and Recovery can’t install it for you, that can be difficult to
solve.

Apple does not make macOS images publicly available. That’s probably to make
Hackintoshing this little bit harder, but this also affects legitimate users.
The only thing you can download from Apple is El Capitan. Apple offers
`InstallMacOSX.dmg <https://support.apple.com/en-us/HT206886>`_ on their
website. If you take a look at the instructions, you will see that this is
*not* a bootable OS X image. This image has a ``.pkg`` package. This package is
expected to install ``/Applications/Install OS X El Capitan.app``. Well, we’re
in recovery, we can’t install stuff. So, let’s do this the manual way.

Manually installing .pkg files
==============================

Turns out the ``.pkg`` format is just an archives all the way down, with all
archives being different formats (at least three).

.. class:: float-md-right mt-md-0

.. sidebar:: macOS flat package format

    You can find more resources about the flat package format, `one
    <http://s.sudre.free.fr/Stuff/Ivanhoe/FLAT.html>`_ or `two
    <https://matthew-brett.github.io/docosx/flat_packages.html#payload_>`_, although
    this was deduted from the ``file`` command and The Unarchiver.

The first archive is the ``.pkg`` file itself. Those files are in `XAR format
<https://en.wikipedia.org/wiki/Xar_(archiver)>`_, which was invented by the
OpenDarwin community. You can either extract it with ``pkgutil --expand
foo.pkg foo_files`` (the last argument is the destination directory, can be
anything, will be created by ``pkgutil``) if you have access to that command (it’s
available in Recovery OS), or you can try the ``xar`` utility as ``xar -xf
foo.pkg``. The structure produced by both tools is a bit different, but we can
work with both.

The second archive-in-archive is the ``Payload``. It’s a gzipped cpio archive
that contains the files installed by this package.  If you have BSD tar
(default on macOS, easily installable on Linux), you can just do ``tar -xvf Payload``.
Otherwise, you can use ``gunzip -c Payload | cpio -i`` (or ``gzcat``). That
will extract all the files the package has.

Another nested archive is the ``Scripts`` archive, although note that
``pkgutil`` will extract it automatically. If it’s not extracted, it’s actually
``.cpio.gz`` again, with the same way to extract it.

(PS.  If you have ``7z`` around (on Windows/Linux as well), you can just point
it at all the compressed files mentioned in this paragraph.)

Making install media out of partial macOS installers (“not a valid OS installer application”)
=============================================================================================

a. From the El Capitan download
-------------------------------

Let’s expand the El Capitan package.

.. code:: console

    $ (mount the DMG in Disk Utility, copy the .pkg to a read-write volume)
    $ pkgutil --expand InstallMacOSX.pkg elcapitan
    $ ls -F elcapitan
    Distribution*       InstallMacOSX.pkg/ Resources/
    $ cd elcapitan/InstallMacOSX.pkg/
    $ tar -xvf Payload
    x .
    x ./Install OS X El Capitan.app
    x ./Install OS X El Capitan.app/Contents
    …

We’ve got the installer app, which is what we need to create an install image.
Great, let’s try it!

.. code:: console

    # "Install OS X El Capitan.app/Contents/Resources/createinstallmedia" --volume /Volumes/MyBlankUSBDrive --applicationpath "Install OS X El Capitan.app"
    Install OS X El Capitan.app does not appear to be a valid OS installer application.

Oh, we’ve got a problem. Turns out there’s one more thing we need to take care
of, and it’s the scripts. MacOS packages have scripts, typically shell scripts,
that are run at various stages in the install process. We can look at the
``PackageInfo`` file, or just look in the ``Scripts`` folder, to see that
there’s an ``link_package`` script we need to run. This script creates a
``Contents/SharedSupport`` directory inside the installer app, and
copies/hardlinks the ``InstallESD.dmg`` file (which is the install formerly-DVD
image) to that directory. Let’s try doing this on our own:

.. code:: console

    $ mkdir "Install OS X El Capitan.app/Contents/SharedSupport"
    $ mv InstallESD.dmg "Install OS X El Capitan.app/Contents/SharedSupport"
    # "Install OS X El Capitan.app/Contents/Resources/createinstallmedia" --volume /Volumes/MyBlankUSBDrive --applicationpath "Install OS X El Capitan.app"
    Ready to start.
    To continue we need to erase the disk at /Volumes/MyBlankUSBDrive.
    If you wish to continue type (Y) then press return:

And it works! ``createinstallmedia`` will now produce valid install media.

b. Installer from Recovery/App Store (any macOS version)
--------------------------------------------------------

If you are in Recovery, you can find an Install app on the filesystem. If you
try to run it, you will get the same error as in the previous paragraph:

.. code:: text

    Install macOS Catalina.app does not appear to be a valid OS installer application.

This also happens with some older macOS versions, where you get a small
``.app`` from the App Store, and that app does the actual download.

Whatever the issue was, we need to download the install files with the
installer. Open the installer and let it run until the download finishes. If
the app asks you to reboot, quit it at this point. If it never asks, you can
still find a way to get files out (after a failed install, they should not be
removed).

The install files can be found in ``/macOS Install Data`` on the destination
volume. For older versions, you will just have ``InstallESD.dmg``, newer
versions add more and more files, some of which are hardware-specific (and
Catalina has ``InstallESDDmg.pkg``, because Apple loves nesting archives for no
reason!). However many files you find, you can just:

1. Copy ``Install macOS Catalina.app`` to a read-write volume.
2. Copy the contents of ``/Volumes/TARGET/macOS Install Data`` to ``Install
   macOS Catalina.app/Content/SharedSupport``. Make sure you account for hidden
   files, if any (copy the entire directory). If you did this correctly,
   ``InstallESDDmg.pkg`` (or ``InstallESD.dmg`` on older verisons) is in the ``SharedSupport``
   directory (not in a subdirectory).
3. Run ``createinstallmedia``. It should now consider the installer valid. The
   available options differ slightly depending on the OS version.

El Capitan installer can’t be verified
======================================

If you get this error, it might be because Apple’s signing keys expired, or
because of other date/time weirdness. Regardless, you can force an install if
you are sure the installer is not damaged with this command `(source)
<https://apple.stackexchange.com/questions/216730/this-copy-of-the-install-os-x-el-capitan-application-cant-be-verified-it-may-h>`__:

.. code:: console

    # installer -pkg  /Volumes/Mac\ OS\ X\ Install\ DVD/Packages/OSInstall.mpkg -target /Volumes/"XXX"

Bonus tidbit 1: how the download works
======================================

While messing with all the installer stuff, I found out a few
interesting/worrying things about the download process.

The first one is that the macOS installer uses plain HTTP without encryption to
download files. That opens you to all the standard issues — an attacker can
replace files you download, and the protocol doesn’t do anything to detect
errors (the installer will verify files, but where do the checksums come
from?).

The second one is how the download happens. You might have noticed it to be a
bit slower than usual traffic. The download happens in 10 MB chunks, using the
``Range`` HTTP header. The installer asks for 10 MB, gets it, saves, asks for
another chunk. Repeat that over 800 times, and the overhead of the entire HTTP
dance becomes noticeable. (I haven’t checked, but I hope the installer at least
uses Keep-Alive. I wouldn’t be particularly surprised if it didn’t, though.)

But this raises another question. The servers clearly support partial downloads.
And yet, if your network disconnects during the download, your download
progress for that file is reset, and in Catalina, you can go from 8 GB back to
500 MB if you’re particularly unlucky. The question is, why? This
infrastructure should make it trivial to continue the download, perhaps
discarding the most recent chunk if you’re concerned about that download of it
being unsuccessful.

Bonus tidbit 2: using Terminal from Setup Assistant
===================================================

The first time you boot a Mac after a clean install, it starts the Setup
Assistant. This app asks for basic OS settings (locale, date/time, user
accounts), and also lets you restore user data from backups.

Sometimes, you might want to access the Terminal or Console from that screen.
You can do that with Ctrl + Opt + Cmd + T and Ctrl + Opt + Cmd + C respectively `(source)
<https://chris-collins.io/2018/03/15/Using-Terminal-At-macOS-Setup-Assistant/>`__.

How could that come in handy? For example, if you want to check if the backup
drive still worked and if the process isn’t stuck (I wrote a test file and also
checked ``top``).

Also, Dear Progress Bar Designers: can you please make your progress bars
functional? The macOS progress bar might look sleek at just 7 px (non-Retina)/6
pt = 12 px (Retina) high, but at the same time, you’re looking at individual
pixels if you need to know if it works or if it’s stuck. I have had to point my
mouse cursor at the end of the filled-in part just to know if it’s working or
not. Or sometimes, put a piece of paper in front of my screen, because there is
no mouse cursor when macOS installs on the black screen. How to make
that progress bar easier to use and more informative? Just add numbers on top of
it. For long-running processes, I wouldn’t mind progress bars that said
“12.34%”. That specific Setup/Migration Assistant window should be changed (it
only has a remaining time estimate and transfer speed, it should also show
moved data/total size), but wouldn’t more things benefit from a clear
indication of the progress? Yes, perhaps it looks less sleek, perhaps it
requires more space for the bar.

Just compare: which is easier to parse? Which is more informative?

.. raw:: html

    <div class="mb-3">
    <div class="progress" style="height: 6.5px; border-radius: 6.5px;">
      <div class="progress-bar" role="progressbar" style="width: 42.42%;" aria-valuenow="42.42" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    </div><div class="mb-3">
    <div class="progress" style="height: 20px; border-radius: 20px;">
      <div class="progress-bar" role="progressbar" style="width: 42.42%;" aria-valuenow="42.42" aria-valuemin="0" aria-valuemax="100">42%</div>
    </div>
    </div><div class="mb-3">
    <div class="progress" style="height: 20px; border-radius: 20px;">
      <div class="progress-bar" role="progressbar" style="width: 42.42%;" aria-valuenow="42.42" aria-valuemin="0" aria-valuemax="100">42.4%</div>
    </div>
    </div><div class="mb-3">
    <div class="progress" style="height: 20px; border-radius: 20px;">
      <div class="progress-bar" role="progressbar" style="width: 64.64%;" aria-valuenow="64.64" aria-valuemin="0" aria-valuemax="100"></div>
      <div style="position: absolute; text-align: center; left: 0; right: 0;">64.64% (6.7 GB/10 GB copied)</div>
    </div>
    </div>

I’d honestly be happy enough with option 2, at least it can be read easily and
you can remember the number instead of a vague position.

In the end…
===========

After all this, I managed to get macOS Catalina installed. After various
failures in built-in El Capitan recovery and Catalina Internet Recovery, I first
installed El Capitan with this hack, then jumped to Mojave because I thought
the new Software Update would help (it didn’t, same installer, same
failed-to-extract-package issue), then made a Catalina USB stick, and it
finally clean-installed, but I was worried about the backup disk’s operation,
and I used a proxy on my local network to try and speed up Catalina downloads
without much improvement… but hey, at least it works. Apple should really make
it easier to install their OS and to make boot media even when stuff doesn’t
work, even from Windows. The Hackintosh folks can just find someone with a
working Mac and ask them to download from App Store and make install media, or
find less legitimate sources. But if your system crashes, I’d probably want to
get working install media immediately, myself, and from Apple.
