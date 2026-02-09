---
title: "upass"
published: "2026-02-01T00:00:00+00:00"
description: "upass: because pass is too hard."
devstatus: 7
download: "https://pypi.python.org/pypi/upass"
github: "https://github.com/Kwpolska/upass"
bugtracker: "https://github.com/Kwpolska/upass/issues"
role: "Maintainer"
license: "3-clause BSD"
language: "Python"
sort: 92
featured: true
thumbnail: "/projects/_banners/upass.png"
gallery: "/galleries/upass/"
---
<div class="alert alert-danger" role="alert">
This project is no longer maintained. I have switched to [KeePassXC](https://keepassxc.org/) many years ago (turns out a single specific file is much more portable than git + gpg).
</div>

`upass` is an interface to [pass](http://www.passwordstore.org/), the standard Unix password manager.

`upass` is using urwid, which means it has a friendly full-screen console
interface.  It shows your directory structure (with flattened subdirectories)
and calls `pass` when requested.  (It does not use `pass -c` due to
subprocessing issues, instead opting for a manual copy — note that the
clipboard **will not be cleared**).

If you want to see how it looks, check out [the screenshots gallery](/galleries/upass/).
