.. title: Code writing code: Python and Vim as development aids
.. slug: code-writing-code-python-and-vim
.. date: 2016-05-27 10:46:35+02:00
.. tags: programming, Python, Vim, CSharp, Java
.. category: Programming
.. description: I built some programs. And programs to write them.
.. type: text

Recently I was working on some C# and Java code. And along the way, I used
Python and Vim to (re)write my code. A small Python script and a 6-keystroke
Vim macro did it faster and better than a human would.

Every programmer should learn a good scripting language and use a programmable
editor like Vim. Why? Here are two examples, after the break.

.. TEASER_END

Episode I: ``INotifyPropertyChanged``, or Python writing C#
===========================================================

I was building a private C# weekend project (that turned into a weeklong
project) — and by the way, WPF and C# are quite pleasant (Windows Forms is a
trainwreck, though). One of the things I used in that project was a DataGrid
bound to a list of custom objects (a DataGrid is a table, basically). And in
order to use it, you need to use the ``INotifyPropertyChanged`` interface `(MSDN)`_.
It involves doing something like this:

.. code:: csharp
   :linenos:

    private string name_ { get; set; }; // can also be a field

    [JsonProperty]
    public string name {
        get {
            return name_;
        }
        set {
            if (value != name_) {
                name_ = value;
                NotifyPropertyChanged("name");
            }
        }
    }

That’s 12 lines of code (excluding ``[JsonProperty]`` which comes from the
Json.NET library) for that pattern. Oh: and I need to do that for **every**
field/property of my class, because otherwise any changes to them would not be
reflected in the tables (and maybe one or two fields were *not* in the table).

Doing that by hand is really not feasible: you need to copy-paste this
large block 14 times and take care of 5 instances of the name (3 with
underscores, 2 without), 2 instances of the type, and the ``[JsonProperty]``
attribute (which does not appear on all properties).

So, I used one of those intelligent computer things to do it for me. I wrote a
really simple `Python`_ script and ran it. And I ended up with all 14 fields built
for me.

.. listing:: code-writing-code/write_properties.py python
   :linenos:

That script takes a list of properties and spits out a block of code, ready to
be pasted into the code. Visual Studio has a nice *Insert File as Text*
feature, so redirecting the output to a file and using that option is enough.

Episode II: Fixing argument order, or Vim (re)writing Java
==========================================================

Another project, `Number Namer`_, written in Java, and it does what it says on
the tin: takes a number and writes it out as words, while being multilingual and
extensible. I used Eclipse for this project, because it looks good, is really
helpful with its code linting, and does not run slowly on my aging system (I’m
looking at you, IntelliJ IDEA aka PyCharm aka Android Studio…)

And so, I was building a test suite, using `JUnit`_. It’s pretty
straightforward, and I remember the syntax from Python’s unittest (even though
I write tests with `pytest`_ nowadays). Or so I thought.

.. code:: java

    // (incorrect)
    assertEquals("Basic integers (7) failed", namer.name(7L), "seven");
    // (fixed)                              ^ cursor
    assertEquals("Basic integers (7) failed", "seven", namer.name(7L));

You see, the typical Python spelling is ``self.assertEquals(actual,
expected)``. Java adds a ``String`` message parameter and it also swaps
``actual`` and ``expected``. Which I didn’t notice at first, and I wrote my
assertions incorrectly. While it doesn’t *really* matter (it will still work),
the output looked a bit weird.

And I noticed only when I finished writing my tests (and I had a typo in my
expected output). I wanted to fix them all — not manually, of course. So, I
closed this file, brought up Vim, searched for the motion I need (it’s
``t{char}`` — see ``:help t``). And I ended up with this
(cursor placed on the comma after the first argument):

.. raw:: html

   <div style="text-align: center;">
   <kbd style="font-size: 2em;">dt,</kbd><kbd style="font-size: 2em;">t)</kbd><kbd style="font-size: 2em;">p</kbd>
   </div>

What does this do, you may ask? It’s actually pretty self-explanatory:

.. raw:: html

    <blockquote><p>
    <b>d</b>elete <b>t</b>ill comma, (go) <b>t</b>ill closing parenthesis, <b>p</b>aste.
    </p></blockquote>

This fixes one line. Automatically. Make it a macro (wrap in ``qq`` … ``q``,
use with ``@q``) and now you can run it on all lines, either by moving manually or by
searching for ``,`` and pressing ``n@q`` until you run out of lines.

Epilogue
========

Some of you might say “but VS/Eclipse/IDEA has an option for that somewhere” or
“[expensive tool] can do that” — and a Google search shows that there is an
Eclipse plugin to swap arguments and that I could also write a regex to solve
my second issue. Nevertheless, Python is a great tool in a programmer’s toolbox
— especially the interactive interpreter. And Vim is an awesome editor that can
accomplish magic in a few keystrokes — and there are many more things you can
do with it.

.. class:: lead

Go learn Python_ and Vim_ now.

Also: don’t even bother with VsVim or IdeaVim or any other Vim emulation
plugins, they work in unusual ways and often don’t give you everything — for
example, VsVim has a Vim visual mode (``v`` key) and Visual Studio selection
mode (mouse), and only one allows Vim keystrokes (the other will replace
selected text).

.. _(MSDN): https://msdn.microsoft.com/en-us/library/ms229614(v=vs.100).aspx
.. _Python: https://www.python.org/
.. _Number Namer: https://github.com/Kwpolska/numbernamer
.. _JUnit: http://junit.org/
.. _pytest: http://pytest.org/
.. _Vim: http://www.vim.org/
