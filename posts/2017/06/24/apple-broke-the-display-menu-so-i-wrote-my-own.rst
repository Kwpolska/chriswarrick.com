.. title: Apple broke the display menu, so I wrote my own
.. slug: apple-broke-the-display-menu-so-i-wrote-my-own
.. date: 2017-06-24 21:02:47+02:00
.. tags: Apple, Swift, programming, projects, app, display
.. section: Programming
.. link: 
.. description: I wrote a menu bar extra to manage display settings.
.. type: text

A short time ago, the macOS display menu stopped working for me. It no longer had options to change mirroring settings, only supporting AirPlay. So I wrote my own, also solving some other issues.

.. TEASER_END

The first thing to consider is: what features do I really want? I’m using a MacBook Pro with an external display. I sometimes need to switch to only one display [1]_ — mirroring is useful here. Although I can afford to put the dock at the bottom in dual-screen mode, it works better on the right if I’m tight on space. [2]_ Also when working on the MacBook screen, I can switch to a higher resolution. So, I need:

* mirroring
* screen resolution
* dock position and icon size

But I don’t want to pick all those three independently — a preset, like *dual screen* or *MacBook only, 1440×900* works better.

So, I wrote **Display Menu**. It’s a simple menu bar extra — I haven’t yet built any friendly configuration GUI for it, so all you get for now is a JSON file. It’s roughly 600 lines of Swift. The app doesn’t do much, other than displaying an icon in the menu bar, and setting display preferences when asked to. But hey, it works for me.

Also, I must admit that Swift is a pretty nifty thing. Although the function to read JSON files needs to do a ton of type casting, and some of the low-level stuff looks as ugly as in C, but other than that, Swift is a modern, friendly language.

Display Menu is open-source, licensed under the 3-clause BSD license. `Fork it on GitHub <https://github.com/Kwpolska/DisplayMenu>`_ or `download binary releases <https://github.com/Kwpolska/DisplayMenu/releases>`_.

.. [1] Inkscape/Xorg don’t work well with multiple displays. I can also mirror and turn off the external screen or use it with some other device.
.. [2] also, in dual-screen mode, I can have the dock on one screen only, and the dock must live on the edge of the “extended” display — so either on the left side of the MacBook screen, or on the right side of the external display.
