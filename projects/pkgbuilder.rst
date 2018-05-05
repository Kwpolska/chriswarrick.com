.. title: PKGBUILDer
.. slug: pkgbuilder
.. date: 1970-01-01T00:00:00+00:00
.. description: An AUR helper (and library) in Python 3.
.. devstatus: 6
.. logo: /projects/_logos/pkgbuilder.png
.. previewimage: /projects/_banners/pkgbuilder.png
.. download: https://pypi.python.org/pypi/pkgbuilder
.. github: https://github.com/Kwpolska/pkgbuilder
.. bugtracker: https://github.com/Kwpolska/pkgbuilder/issues
.. role: Maintainer
.. license: 3-clause BSD
.. status: featured
.. language: Python
.. sort: 100

PKGBUILDer is an AUR helper.  It is used to build packages from the Arch User
Repository.  PKGBUILDer automates the process and provides a command-line user
interface.

There are two executables: ``pkgbuilder`` to run the main mode, which builds
AUR packages and also ABS packages (instead of downloading ready-made binaries)
and ``pb`` which builds AUR packages and downloads binary packages from the
repos (calls ``pacman``).  The user interface to both mimics ``pacman`` and
``makepkg`` as close as possible.

PKGBUILDer’s main guideline is “the user knows what they are doing” — no
unnecessary questions are asked.  By typing in a ``pkgbuilder`` command, the
user trusts the PKGBUILD file (we hope you read it).  This allows us to be very
fast.

PKGBUILDer also implements the most modern techniques available, including
handling AUR package upgrades in a **single HTTP request** instead of hundreds
or even thousands of them, depending on how many packages you have installed.

PKGBUILDer should be installed from the AUR itself, by installing the
``pkgbuilder`` package::

    wget https://aur.archlinux.org/packages/pk/pkgbuilder/pkgbuilder.tar.gz
    tar -xzvf pkgbuilder
    cd pkgbuilder
    makepkg -si
