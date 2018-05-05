.. title: Windows 10 November Upgrade: Windows as a (Dis-)service
.. slug: windows-10-november-upgrade-windows-as-a-dis-service
.. date: 2015-12-27 12:15:00+01:00
.. tags: Windows, review, rant
.. category: Windows
.. description: Windows 10 upgrades are painful.
.. type: text

.. class:: pull-right
.. thumbnail:: /images/Windows10-1511.png
   :figwidth: 20%

   The *About Windows* screen.

I upgraded Windows 10 from the RTM version (build 10240, July 2015) to the
*November Upgrade* version (1511/build 10560). It took me a good two hours,
multiple reboots, and a BSOD.

.. TEASER_END


I upgraded to Windows 10 (from Windows 10) back in August. The upgrade
experience was okay. The main issue I had was that the error messages were a
bit uninformative. There was one error, Windows 10 claimed it *couldn’t check
available disk space*, which is a really strange way to say *your active
partition is a Linux one and you have a Linux bootloader set up*. But after
switching to the Windows bootloader, it worked fine. I only had to fight a bit
with the display drivers, but other than that, it succeeded.

Windows 10 claims to be *Windows as a Service*: a fast update cycle with
new Windows builds released often. Now, Linux users are accustomed to this.
Depending on your distribution, you get new versions somewhere between “every
day” (rolling release: :doc:`Arch Linux <arch-linux-the-best-distro-ever>`,
Gentoo, Debian sid), “every 6 months” (Ubuntu, Fedora), or some other time
scale.
(Three months and two weeks passed between the two “general” builds of Windows 10.)
And most of the time, the upgrades are fast and relatively painless.

With Windows 10, this is not the case. I booted to Windows yesterday, to set up
some new hardware and mess around with the OS. It offered the upgrade in
Windows Update. After downloading it (3 GB, according to Microsoft), it started
installing, without success. There were no error messages, and “Error” or
“Failed to install” were all I could get.

I found the setup log files. The error log ended with “failed to
resurrect new system”. Which didn’t make a lot of sense to me, even though I’m
quite proficient with this sort of things.

This can be dangerous, so I backed up my drives with Clonezilla, which is
really useful — and it ships with the Arch Linux ISO, which helped a lot.

So, I tried my trusty friend, the Media Creation Tool. I needed it to get the
original install to work, and it came useful now. After yet another download,
I got a USB stick with Windows 10 on it. So, I rebooted to the installer, which
told me I can’t use it and I need to start it from within Windows.

I rebooted back into Windows, started the installer. It took it a while to
begin the installation process (including waiting forever to get updates,
so I restarted the process and disabled updating now, and another long wait to
make sure it will work on my machine), but then it went quite fast and was at 70%
after less than 10 minutes. Sadly, that’s too good to be true. It rebooted and
started counting from zero. 90 minutes passed since I started, and the login
screen came up. Username, password, log in.

*Hi, we’ve updated your PC.* Six more minutes, but at long last, I got my
desktop. There’s just one problem though: 1024×768 is not the screen resolution
I started the install with (oddly enough, the “Updating Windows” part was
running at 1080p for part of the process, and then went back to 768p after a
reboot). I tried installing NVIDIA drivers, and it failed — however, Windows
managed to install something and wanted a reboot (what is this, Windows 95?).
Fine, let’s reboot and get a fully functional Windows environment?

:( *Your PC ran into a problem and needs to restart.* A blue screen of death,
on the second boot, talking about *critical structure corruption in CI.dll*. Well, shit.
I rebooted, but I first took a little detour to the Arch Linux USB stick I
prepared before to get a sane bootloader back. I’ve done this before, and
usually requires three commands.

.. code:: console

   # mount /dev/sda2 /mnt
   Metadata kept in Windows cache, refused to mount.
   Failed to mount '/dev/sda2': Operation not permitted
   The NTFS partition is in an unsafe state. Please resume and shutdown
   Windows fully (no hibernation or fast restarting), or mount the volume
   read-only with the 'ro' mount option.

Wait, what?! I remember my hard drive layout, and ``/dev/sda2`` is my Linux
ext4 partition! I checked ``cfdisk``, and apparently I now have a *Hidden NTFS WinRE*, with Linux moved to ``/dev/sda3`` and my extended partition moved to ``/dev/sda4`` (what would happen if I already had four partitions?). This is typical Windows misbehaviour: **not caring about other OSes that might be installed**.

I accepted this defeat, mounted ``/dev/sda3``, ran ``arch-chroot /mnt`` and
``syslinux-install_update -i -a -m`` (I’m not a fan of GRUB 2, and I have a MBR
drive layout). I should test this out by rebooting into Linux.

.. code:: console

   Error getting authority: Error initializing authority: Could not connect: No such file or directory (g-io-error-quark, 1)
   Welcome to emergency mode!

systemd **really** values ``/etc/fstab``, and you can’t boot if any entry fails
to mount, which was the case with the Windows drive in ``/dev/sda1`` and my
other NTFS partition (note that the error message is very unhelpful)

I guess I have to get into Windows first. I tried booting Windows again, this
time it worked (in glorious 1080p, which was the case on the previous boot
too).

So, Windows re-enabled Fast Boot (a.k.a. hibernation instead of clean shutdown)
as part of the upgrade. I hunted down the setting and restored sanity.
But when I was doing that, I noticed things were spelt (or spelled) a bit
differently than two hours ago. You see, the original Windows 7 system was US
English. After the upgrade to Windows 10, I took the opportunity to install the
British and Polish language packs (it’s a multi-user machine). Both of which
were gone. I had to fix that, too.

And all I got out of it were colourful window borders, which are not even in my
desired colour (Windows has a limited colour palette, even though I explicitly
set it to `#00AADD </brand/>`_). What a great way to waste a Saturday!

This leaves me wondering, how does this work for Windows Insiders (Microsoft’s
community beta testers)? Is the process better if Windows Update manages to
perform the install? Or is it substantially faster if you’re running on a SSD?
Perhaps the testers have dedicated machines and don’t run experimental builds
on their daily drivers. But I doubt that, because that’s a significant
investment without any financial gain from helping out a corporation. So,
virtual machines? I have no idea how they cope.

By the way, ``cmd.exe`` and ``winver.exe`` claims it’s *Copyright © 2016*. Previously, Windows copyright
notices were outdated. Now, they’re in the future.
