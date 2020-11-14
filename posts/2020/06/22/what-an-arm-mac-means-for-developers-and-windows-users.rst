.. title: What an ARM Mac means for developers and Windows users
.. slug: what-an-arm-mac-means-for-developers-and-windows-users
.. date: 2020-06-22 21:00:00+02:00
.. updated: 2020-11-14 12:40:00+01:00
.. tags: Apple, ARM, Mac, devel
.. category: Apple
.. description: ARM Macs might be good for Apple, but developers should be concerned about interoperability.
.. type: text

The rumor mill was right this time, and Apple has just announced they will
transition Macs to ARM processors. These news have some side effects for
software developers, particularly those not working with the Apple ecosystem.
And they also affect people who depend on both macOS and Windows.

.. TEASER_END

.. class:: alert alert-primary
.. contents::

In this post, I am not going to focus in the differences between x86_64 and ARM,
RISC and CISC, and all the benchmarks. Let’s assume that Apple manages to offer
ARM-based CPUs that can match performance of most Intel processors in Apple’s
lineup, and let’s even assume they can make an ARM Mac Pro. (A note on naming:
Apple Silicon is the official name, but it sounds ugly. I’ll just call it ARM.
For Intel, I’ll use either Intel or x86(_64).)

For many users, the transition will be more-or-less transparent. Sure, they’ll
lose some apps, just like they probably did with Catalina (which dropped
support for 32-bit Intel apps), or some apps will not be available/will be
buggy in the first few months of the transition (though it will be easier than
the PowerPC transition, because Apple uses little-endian byte order on ARM).

How will it work out in Apple land?
-----------------------------------

For developers who work only on iOS apps, the transition also won’t mean much.
Maybe a faster, more accurate Simulator. They’ll need to buy an ARM Mac sooner
or later (within the next 5 years), because Apple requires them to use the
latest Xcode version for App Store submissions, and Xcode supports at best the
previous version of macOS.  But that has been Apple’s policy forever, and the
Intel Macs will probably be within the usual deprecation range when that
happens.

The requirements for macOS-only developers are pretty obvious, they will need
to buy an ARM Mac on day one, so they can test their apps on the new platform.
They will also need to work on ARM compatibility — although updating your app
for the new OS is a yearly ritual in Apple land, so that’s also mostly
business-as-usual (unless you do a lot of unportable low-level stuff in your
code). There are some pro apps that tend to lag behind new Apple decrees (some
might have been hit by Catalina), and users of those apps might prefer to stay
with Intel for a little bit longer.

But then, we get to the requirements of developers who use Macs, but don’t work
exclusively with the Apple platforms. This is a fairly large group, since many
developers like Macs for the good hardware, Unix-based software, and the
integration of both. And for some part, non-developers are affected too.

Who needs non-Apple operating systems?
--------------------------------------

The first group are people tied to Windows, somehow. Some of them might be
using Boot Camp to play games. Others might be using Boot Camp or
virtualization software (Parallels Desktop, VMware Fusion, Oracle VM
VirtualBox) to run Windows and Windows-specific apps — perhaps they need the
Windows version of Office, or various Windows-onlypro apps, or they need
Windows to file their taxes, because their government does not care about
non-Windows OSes. Or perhaps they’re web developers, and they need to test
compatibility with the Windows versions of browsers, or the old Microsoft
browsers (IE and pre-Chromium Edge).

The second group is software developers who need Linux. While macOS provides a
very competent development environment, and many things can be run directly on
macOS, some use-cases may require a Linux VM.  Perhaps the most notable case is
Docker.

Docker is a solution for lightweight app containers, that can offer separation
between apps, and that can simplify and standardize deployment. Docker itself
is not a virtualization solution (at least in the traditional sense). Docker
must run on top of Linux (there’s also Docker-on-Windows, but that’s another
story). The Docker Desktop for Mac app runs a lightweight Linux VM, and runs
containers in that VM. The virtualization solution `Docker for Mac uses
<https://github.com/docker/for-mac>`_ is ``Hypervisor.framework``, which is
part of macOS itself.

Who else needs virtualization? Android developers. The Android Emulator is also
a virtual machine that runs the Android operating system. Android can run on
different architectures, and so, a x86 system image is typically used for the
Emulator.

Is virtualization possible on ARM?
----------------------------------

Yes, definitely. Apple has been testing it much earlier, since the
aforementioned ``Hypervisor.framework`` was found `on iOS in April
<https://twitter.com/never_released/status/1250533740557852674>`_.
And Apple announced virtualization support for ARM Macs during the keynote, and
showed an example of a Linux VM. That VM was, of course, running an ARM64
distribution of Linux.

But what can we use this for? Turns out, it’s complicated. The easiest thing
from the few use-cases mentioned before is Android. Google just needs to get
the Emulator working on ARM Macs and ship that to the devs.

What about Linux in general? Many mainstream distributions
support ARM64, so that’s not a problem in general. The support for a particular
distro or software might be worse than on x86_64, but it’s generally not a
problem for users.

But for Docker, there’s a problem. One of the many advantages of Docker is
dev-prod parity. If you deploy your app with Docker to an x86_64 Linux server,
you can also install Docker on an x86_64 Linux developer machine (or a Linux VM on an
Intel Mac/Windows PC). Both the server and the dev machine can run **the same**
image, the same code, the same configuration. That won’t happen if they are a
different architecture. This means that you can end up with bugs happening
because of different environments, and it’s also possible that some images you
depend on are not available for both architectures.

And then we get to Windows. Windows also has an ARM version, but it’s currently
available only with a new ARM device (you can’t buy it standalone). If
Microsoft were to sell this, we’d have an issue with the software. Windows 10
on ARM supports 32/64-bit ARM software, and can run 32-bit Intel (x86) software
using emulation. It cannot, however, emulate apps that require 64-bit Intel
processors (x86_64).  This makes the software situation on that platform a bit
better. While many developers don’t care about ARM and might not have builds
for ARM available, most Windows software is available in both x86 and x86_64
versions, or is exclusively 32-bit. But certain pro apps are x86_64 only, so if
there is no ARM build of it, an ARM Windows PC currently cannot run it.
(*Update:* Microsoft announced `x86_64 emulation on ARM
<https://blogs.windows.com/windowsexperience/2020/09/30/now-more-essential-than-ever-the-role-of-the-windows-pc-has-changed/>`_,
which means more software will work.)

And note that Microsoft knows about the transition, but we haven’t heard
anything about Windows during the keynote…

Can we emulate x86(_64) and run x86(_64) Windows 10?
----------------------------------------------------

Theoretically? Yes. Practically? No.

The issue with emulation is speed. There are a few x86 emulators available, and
those emulators can be run on an ARM device just fine. You can find videos on
YouTube (not a very reliable source of information, I know) in which people try
to benchmark those, or try to run Windows using an emulator like that. And even
with an ancient Windows version, the emulation is painfully slow. Windows 10
would be basically unusable if you tried to emulate all of it.

How does the x86 emulation on Windows 10 for ARM work? You can watch `the
Channel 9 video about Windows 10 on ARM
<https://channel9.msdn.com/Events/Build/2017/P4171>`_ (around 6:00) for more
details. The trick is that system DLLs are using a hybrid x86/ARM64 library
format, which means x86 code can call those DLLs at native speeds. This means
that many apps run at near-native speed (depending on the ratio of custom code
to system DLL calls). This technique cannot work for emulating the entire
operating system. If Windows 10 on ARM was made available for ARM Macs, running
x86 Windows apps would become feasible.

Rosetta probably uses similar technique. Most apps will be translated at
install time, not at run time. But you can’t do that with an entire OS.

What’s next for people who rely on both macOS and Windows?
----------------------------------------------------------

For a few more years, Intel Macs will still be supported by Apple (with new
macOS versions) and by software vendors. But after that? Well, you’re stuck
with two machines, at least until Windows on ARM becomes viable and runnable on
Macs. Or you can start exploring alternatives to macOS software. If you’re one
of the macOS-as-UNIX-with-great-UX developers (hello!), perhaps you’ll have to
switch to Linux — or perhaps Windows with Windows Subsystem for Linux? (The
latter is becoming more usable with every Windows release, so keep an eye on
that… I wrote this post in NeoVim in WSL2, with Windows Terminal supporting
many advanced terminal features, and the transparent filesystem integration
letting me access Windows files directly).


Post-M1 announcement update (2020-11-14)
----------------------------------------

Parallels have confirmed `support for M1 Macs
<https://www.parallels.com/blogs/parallels-desktop-apple-silicon-mac/>`_ and
are offering a Technical Preview of their M1 virtualization product. This
announcement’s mention of Windows 10 ARM supporting x86_64 apps has caused
some tech writers to assume Parallels will support Windows 10 ARM on M1
Macs. This is **not** what the post says. Parallels is not, and cannot
announce support for that OS, because Windows 10 ARM is (still) available to
ARM OEMs only to install on their devices — making an official announcement
about this feature today would be admitting to doing something illegal/not
allowed by the EULA. I’m pretty sure they are not working on support for
Windows 10 ARM now and in the foreseeable future, until Microsoft opens up
Windows 10 ARM to the public — their own legal issues aside, who would they sell
the Windows support to?

In other news, `Docker is not ready yet
<https://github.com/docker/for-mac/issues/4733>`_.
