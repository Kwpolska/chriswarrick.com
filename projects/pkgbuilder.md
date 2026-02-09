---
title: "PKGBUILDer"
published: "2026-02-01T00:00:00+00:00"
description: "An AUR helper (and library) in Python 3."
devstatus: 7
logo: "/projects/_logos/pkgbuilder.png"
thumbnail: "/projects/_banners/pkgbuilder.png"
download: "https://pypi.org/project/pkgbuilder/"
github: "https://github.com/Kwpolska/pkgbuilder"
bugtracker: "https://github.com/Kwpolska/pkgbuilder/issues"
role: "Maintainer"
license: "3-clause BSD"
featured: true
language: "Python"
sort: 100
---
<div class="alert alert-danger" role="alert">
This project is no longer maintained. I have switched to macOS and then to Windows many years ago, so I don’t have an Arch instance to test with.
</div>

PKGBUILDer is an AUR helper.  It is used to build packages from the Arch User
Repository.  PKGBUILDer automates the process and provides a command-line user
interface.

There are two executables: `pkgbuilder` to run the main mode, which builds
AUR packages and also ABS packages (instead of downloading ready-made binaries)
and `pb` which builds AUR packages and downloads binary packages from the
repos (calls `pacman`).  The user interface to both mimics `pacman` and
`makepkg` as close as possible.

PKGBUILDer’s main guideline is “the user knows what they are doing” — no
unnecessary questions are asked.  By typing in a ``pkgbuilder`` command, the
user trusts the PKGBUILD file (we hope you read it).  This allows us to be very
fast.

PKGBUILDer also implements the most modern techniques available, including
handling AUR package upgrades in a **single HTTP request** instead of hundreds
or even thousands of them, depending on how many packages you have installed.
