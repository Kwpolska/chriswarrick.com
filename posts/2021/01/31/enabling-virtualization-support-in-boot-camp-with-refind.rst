.. title: Enabling Virtualization Support in Boot Camp with rEFInd
.. slug: enabling-virtualization-support-in-boot-camp-with-refind
.. date: 2021-01-31 00:30:00+01:00
.. tags: Mac, Windows, Boot Camp, rEFInd, Virtualization
.. category: Apple
.. description: Getting virtuailzation working in Boot Camp thanks to rEFInd.
.. type: text
.. guide: yes
.. guide_effect: you now can use Windows in Boot Camp with hardware virtualization support
.. guide_platform: Intel Macs
.. guide_topic: virtualization in Boot Camp

You installed Windows on an Intel Mac via Boot Camp, and want to use
virtualization in it. But there’s an issue — hardware virtualization extensions
are not available. Luckily, this can be worked around easily with the help of
rEFInd, an alternate boot manager.

.. TEASER_END

Many software development workflows involve virtualization. WSL, Docker for
Windows, and the Android Emulator are some examples of common
virtualization-based tools. Then there are general virtualization
tools/hypervisors, such as VMware Workstation, Hyper-V or VirtualBox. All these
tools require hardware virtualization extensions (Intel VT-x, AMD-V) or at
least are very slow without them. Virtualization extensions are not enabled by
default in the CPU, they must be enabled by something. On typical PCs, this is
often a firmware-level setting (that might be disabled by default), or it might
be unconditionally enabled by the firmware. On a Mac, however, enabling VT-x is
done by macOS, as part of the boot process. This means that Windows running in
Boot Camp will start without virtualization, unless you want to boot into macOS
first and then reboot into Windows. That setup isn’t quite ergonomic (and what
if macOS refuses to shut down, as it often does for me?).

Instead, we’re going to use
`rEFInd <https://www.rodsbooks.com/refind/>`__, a boot manager for
EFI-based systems that can boot into various OSes and also handle other
parts of the boot process. But first, let’s prepare our system for this.

.. class:: lead

**DISCLAIMER:** Those steps may make your Mac fail to boot. I don’t take any
responsibility whatsoever if that happens. Prepare for the worst — make
backups, perhaps have install media ready, plan some downtime.

Step 1. Install Windows in Boot Camp the usual way
==================================================

The first thing you should do is install Windows 10 in Boot Camp, with
the help of the Boot Camp Assistant. The Assistant will take some time
to partition your drive and do other preparations (and show barely
informative progress bars, but `I ranted about that Apple design “feature”
already <https://chriswarrick.com/blog/2020/06/03/reinstalling-macos-what-to-try-when-all-else-fails/#an-open-letter-to-progress-bar-designers>`__).
There are no special preparations for this, the standard process will
work. If you already have Windows installed, you can go to the next
step.

Step 2. Ensure the setup is stable
==================================

We’ll be making changes to how the machine boots, and as such, it’s
good to have other things working correctly and in line with your
expected configuration. Make sure that:

-  Both macOS and Windows boot correctly

-  You can change the OS you boot into by holding the Option key after
   pressing Power (requires disabling the firmware password [1]_)

-  Disk encryption (FileVault, BitLocker) is enabled (if you want that, of
   course) and fully configured (initial encryption is complete)

-  Windows setup (including Boot Camp drivers) is complete

-  The ``OSXRESERVED`` partition that the Boot Camp Assistant created
   has been deleted (that should have happened when booting into macOS for the
   first time after installing Windows — complete with a slowly moving
   progress bar and no other information, as is usual for this OS — but
   if that didn’t happen, use Disk Utility in macOS or Recovery OS to do
   that — pick your drive, click *Partition* and delete the partition,
   this will grow the macOS partition)

-  System Integrity Protection is enabled (the procedure is a bit safer
   that way)

Step 3. Create a partition for rEFInd
=====================================

First, back up your data before making changes to your hard drive
layout. We’ll need to create a new partition for rEFInd to live on. This
is the safest option — you could install it to the EFI System Partition (ESP),
but macOS might want to put its own stuff there, and it’s safer not to
use it.

The rEFInd partition doesn’t need to be large (50 MB will be enough); it must use the HFS+ (Mac OS
Extended) file system. To create it, you have three options:

-  From macOS, by shrinking the macOS partition: open Disk Utility,
   choose your drive, select Partition, add a new partition, set its
   size and file system (in that order!). This will take a few minutes
   (10-15, or possibly more), and you won’t be able to use your Mac
   during the resize.

-  From Recovery OS, by shrinking the macOS partition: same steps apply,
   but it might be a bit safer than doing it from within macOS.

-  From Windows, by shrinking the Windows partition: open Disk
   Management (press the Windows key and type *partition*, or open
   Computer Management from Administrative Tools), right click your
   Windows partition, select Shrink Volume. Enter the desired size and
   click Shrink. Then, right click the unallocated space and create a
   New Simple Volume. For now, choose FAT32 or exFAT; you’ll need to
   reformat it as HFS+ from within macOS later (*Erase* in Disk Utility). This
   will take a few seconds — and even if you include the time to reboot, it’s
   faster.

After you create the new partition and make sure it’s HFS+ (Mac OS
Extended), you can proceed with the setup. Also, if you don’t want the
partition to be visible in the Finder, run the following command (insert
the correct volume path for your system):

.. code:: text

    sudo chflags hidden /Volumes/rEFInd

Step 4. Configure and install rEFInd
====================================

To set ue rEFInd, you’ll need to boot into macOS. `Download
rEFInd <https://www.rodsbooks.com/refind/getting.html>`__ from the
author’s website — you want the file named *A binary zip file*. Extract
this archive anywhere on your system (``~/Downloads`` is fine).

First, you’ll need to change the configuration file
``refind/refind.conf-sample``. Locate the setting named
``enable_and_lock_vmx``, uncomment it (remove the ``#`` at the start
of the line), and set its value to ``true``. You can also make other
configuration changes — the default ``timeout`` of 20 seconds is
likely to be too much for your needs.

When your configuration file is ready, you can install rEFInd. You can
use the ``refind-install`` tool, or perform a manual install (check
out the `installation
docs <https://www.rodsbooks.com/refind/installing.html>`__ for more
details).

Before installing, you’ll need to get the device name of your rEFInd
partition. Open Disk Utility, select the partition from the left pane,
and check the *Device* field (for example, ``disk9s9`` — it will be
**different** on your system, depending on your partition layout).

Open a Terminal, ``cd`` into the directory where rEFInd was extracted,
and run the following command (replace ``disk9s9`` with the device
name on your system):

.. code:: text

    ./refind-install --ownhfs disk9s9

This command will produce an error if you have SIP enabled — but this
error is not important for us, the install will work without the change
that SIP prevented. [2]_

You can now shut down your Mac and use the Option key while starting up
to choose the OS. You should see three options: Macintosh HD, EFI Boot,
and Boot Camp. The EFI Boot option is rEFInd — pick that, boot into
Windows (Microsoft EFI boot), *et voilà* — Windows can now run virtualization software.

There are a few more things that you can do now, depending on your OS
preferences.

-  You can make rEFInd the default boot loader. Hold *Control* on the
   Apple boot device selection screen and click the Power icon under the
   EFI Boot drive (`source for the
   tip <https://apple.stackexchange.com/a/73742>`__).

-  You can use rEFInd to boot into macOS, although this might not work
   with Big Sur according to the author (it seems to work for me, but
   YMMV). You can use the standard boot method for macOS (by defaulting
   to Macintosh HD, or by choosing it from the Power+Option picker) and
   rEFInd exclusively for Windows (and set your timeout to a low value).

-  You can modify rEFInd’s configuration — in this scenario, the config
   file is ``/Volumes/rEFInd/System/Library/CoreServices/refind.conf``.
   You can set a custom background image, for example (`rEFInd’s
   site <https://www.rodsbooks.com/refind/>`__ can help you figure out
   what options are available and what you can set them to).

.. [1]
   If the firmware password is important to you, you can restore it after
   the setup is done — this will mean using rEFInd to boot both Windows and
   macOS, although I decided to remove the firmware password and boot
   into macOS from the Power+Option boot menu.

.. [2]
   The failing operation is marking the rEFInd partition bootable in the Mac
   sense, using the ``bless`` command. However, the drive is considered
   bootable as an EFI-compliant boot volume (it has ``*.efi`` files in specific
   places), and this is the boot method we’re using here. SIP aside, the
   ``bless`` utility is a bit buggy, and we can use rEFInd without a blessed
   partition just fine.
