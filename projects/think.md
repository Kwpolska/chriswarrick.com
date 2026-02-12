---
title: "Terminal Think Music"
published: "2026-02-01T00:00:00+00:00"
description: "Terminal Think Music: for when it takes too long to do something."
devstatus: 6
download: "https://pypi.org/project/think/"
github: "https://github.com/Kwpolska/think"
bugtracker: "https://github.com/Kwpolska/think/issues"
role: "Maintainer"
license: "3-clause BSD"
language: "Python"
sort: 78
---
Are you executing a process that takes a long time? Do you want to know that it’s
still working while you are in another terminal/making coffee? Do you have a
favorite game show tune to play while doing something?

If yes: `think` is just for you.

Installation
------------

You can install `think` from [PyPI](https://pypi.python.org/pypi/think) (with `pip install think`).

Configuration
-------------

Create a file named `~/.config/think.conf` (or wherever your `XDG_CONFIG_HOME` is) with the following four lines:

```ini
[Think]
command = play
file = /home/kwpolska/Dropbox/Media/Wielka Gra.mp3
behavior = wait
```

* `command` is the command of the player that will be executed.  You can use
  `play` (from `sox`) or any other fast CLI music player.
* `file` is the filename that will be given as the sole argument to the play command. No escaping is necessary. I’m using the [Wielka Gra](https://www.youtube.com/watch?v=gafGRpmwDTo) theme, Americans might want [Jeopardy! Think Music](https://www.youtube.com/watch?v=vXGhvoekY44) (also the namesake for this command), Brits might want the [Countdown theme](https://www.youtube.com/watch?v=M2dhD9zR6hk).
* `behavior` can be one of:

  * `return` — return control to the terminal as soon as the program finishes, without stopping the music
  * `wait` — wait until music stops before returning control
  * `stop` — stop the music and return control immediately

Usage
-----

Prepend `think` before the command that takes too long to execute:

```text
think sleep 120
```

See also: [nap — sleep with a progressbar](../nap/)
