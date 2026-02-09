---
title: "nap"
published: "2026-02-01T00:00:00+00:00"
description: "sleep with a progressbar."
devstatus: 5
download: "https://github.com/Kwpolska/nap/releases"
github: "https://github.com/Kwpolska/nap"
bugtracker: "https://github.com/Kwpolska/nap/issues"
role: "Maintainer"
license: "3-clause BSD"
language: "C"
sort: 80
---
`nap` is a special implementation of `sleep`.  The main difference is the
presence of a progressbar: `nap` prints out a progressbar and shows how much
time has already elapsed.  It is useful for impromptu “scheduling”, so you know
when will the process/wait finish.

`nap` takes the best features from both the GNU and BSD variants of
`sleep`: it values BSD-style simplicity, but also supports GNU-style input
with units (`s|m|h|d`).

See also: [think — Terminal Think Music](../think/)
