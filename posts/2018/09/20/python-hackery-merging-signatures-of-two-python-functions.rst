.. title: Python Hackery: merging signatures of two Python functions
.. slug: python-hackery-merging-signatures-of-two-python-functions
.. date: 2018-09-20 15:52:20+02:00
.. tags: hacking, Python, Python hackery, Python internals
.. category: Python
.. description: Merging signatures of two functions in Python.
.. type: text

Today’s blog post is going to contain fairly advanced Python hackery. We’ll
take two functions — one is a wrapper for the other, but also adds some
positional arguments.  And we’ll change the signature displayed everywhere from
the uninformative ``f(new_arg, *args, **kwargs)`` to something more
appropriate.

.. TEASER_END

This blog post was inspired by F4D3C0D3 on #python (freenode IRC). I also took
some inspiration from
Gynvael Coldwind’s classic `Python 101 <https://www.youtube.com/watch?v=7VJaprmuHcw>`_ (April Fools) video. (Audio and some comments are in Polish, but even if you don’t speak the language, it’s still worth it to click through the time bar and see some (fairly unusual) magic happen.)

Starting point
==============

.. code:: python

    def old(foo, bar):
        """This is old's docstring."""
        print(foo, bar)
        return foo + bar


    def new(prefix, foo, *args, **kwargs):
        return old(prefix + foo, *args, **kwargs)

Let’s test it.

.. code:: pycon

    >>> o = old('a', 'b')
    a b
    >>> n = new('!', 'a', 'b')
    !a b
    >>> print(o, n, sep=' - ')
    ab - !ab
    >>> help(old)
    Help on function old in module __main__:

    old(foo, bar)
        This is old's docstring.

    >>> help(new)
    Help on function new in module __main__:

    new(prefix, foo, *args, **kwargs)

The last line is not exactly informative — it doesn’t tell us that we need to
pass ``bar`` as an argument.  Sure, you could define ``new`` as just ``(prefix, foo,
bar)`` — but that means every change to ``old`` requires editing ``new`` as
well. So, not ideal. Let’s try to fix this.

The existing infrastructure: functools.wraps
============================================

First, let’s start with the basic facility Python already has.  The standard
library already comes with ``functools.wraps`` and
``functools.update_wrapper``.

If you’ve never heard of those two functions, here’s a crash course:

.. code:: python

    def decorator(f):
        @functools.wraps(f)
        def wrapper(*args, **kwargs):
            print("Inside wrapper")
            f(*args, **kwargs)
        return wrapper

    @decorator
    def square(n: float) -> float:
        """Square a number."""
        return n * n

If we try to inspect the ``square`` function, we’ll see the original name, arguments,
annotations, and the docstring.  If we ran this code again, but with the
``@functools.wraps(f)`` line commented out, we would only see ``wrapper(*args,
**kwargs)``.

This approach gives us a hint of what we need to do.  However, if we apply
``wraps`` (or ``update_wrapper``, which is what ``wraps`` ends up calling)
to our function, it will only have ``foo`` and ``bar`` as arguments, and its
name will be displayed as ``old``.

So, let’s take a look at `functools.update\_wrapper
<https://github.com/python/cpython/blob/4fe8dc68577f9e22aaf24db08fb6647277c42d4c/Lib/functools.py#L27-L79>`_. What does it do? Two things:

* copy some attributes from the old function to the new one
  (``__module__``, ``__name__``, ``__qualname__``, ``__doc__``, ``__annotations__``)
* update ``__dict__`` of the new function
* set ``wrapper.__wrapped__``

If we try to experiment with it — by changing the list of things to copy, for
example — we’ll find out that the annotations, the docstring, and the displayed name come from
the copied attributes, but the signature itself is apparently taken from ``__wrapped__``.

Further investigation reveals this fact about ``inspect.signature``:

 ``inspect.signature(callable, *, follow_wrapped=True)``

 *New in version 3.5:* ``follow_wrapped`` parameter. Pass ``False`` to get a signature of callable specifically (``callable.__wrapped__`` will not be used to unwrap decorated callables.)

And so, this is our **end goal:**

.. class:: lead

Craft a function with a specific signature (that merges ``old`` and ``new``) and set it as ``new.__wrapped__``.

But first, we need to talk about parallel universes.

Or actually, code objects.

Defining a function programmatically
====================================

Let’s try an experiment.

.. code:: pycon

   >>> def foo(bar): pass
   >>> foo.__wrapped__ = lambda x, y: None
   >>> help(foo)
   foo(x, y)

So, there are two ways to do this.  The first one would be to generate a string
with the signature and just use ``eval`` to get a ``__wrapped__`` function. But
that would be cheating, and honestly, quite boring. (The inspect module could
help us with preparing the string.)  The second one? Create code objects
manually.

Code objects
------------

To create a function, we’ll need the ``types`` module. ``types.FunctionType``
gives us a function, but it asks us for a code object. As the `docs
<https://docs.python.org/3/reference/datamodel.html>`_ state,
*Code objects represent byte-compiled executable Python code, or bytecode.*

To create one by
hand, we’ll need ``types.CodeType``. Well, not exactly by hand — we’ll end up doing a three-way merge between
``source`` (``old``), ``dest`` (``new``) and ``def _blank(): pass`` (a function
that does nothing).

Let’s look at the docstring for ``CodeType``:

.. code:: text

    code(argcount, kwonlyargcount, nlocals, stacksize, flags, codestring,
        constants, names, varnames, filename, name, firstlineno,
        lnotab[, freevars[, cellvars]])

    Create a code object.  Not for the faint of heart.

All of the arguments end up being fields of a code objects (name starts with
``co_``).  For each
function ``f``, its code object is ``f.__code__``. You can find the filename in
``f.__code__.co_filename``, for example. The meaning of all fields can be
found in docs for the `inspect module
<https://docs.python.org/3/library/inspect.html#types-and-members>`_. We’ll be
interested in the following three fields:

* ``argcount`` — number of arguments (not including keyword only arguments, \* or \*\* args)
* ``kwonlyargcount`` — number of keyword only arguments (not including \*\* arg)
* ``varnames`` — tuple of names of arguments and local variables

For all the other fields, we’ll copy them from the appropriate function (one of
the three).  We don’t expect anyone to call the wrapped function directly; as
long as ``help`` and ``inspect`` members don’t crash when they look into it,
we’re fine.

Everything you need to know about function arguments
----------------------------------------------------

.. code:: pycon

   >>> def f(a, b=1, c=2, *, d=3): pass
   >>> inspect.getfullargspec(f)
   FullArgSpec(args=['a', 'b', 'c'], varargs=None, varkw=None, defaults=(1, 2), kwonlyargs=['d'], kwonlydefaults={'d': 3}, annotations={})

A function signature has the following syntax:

1. Any positional (non-optional) arguments
2. Variable positional arguments (``*x``, name stored in ``varargs``)
3. Arguments with defaults (keyword-maybe arguments); their value is stored in ``__defaults__`` left-to-right
4. Keyword-only arguments (after an asterisk); their values are stored in a dictionary.  Cannot be used if ``varargs`` are defined.
5. Variable keyword arguments (``**y``, name stored in ``varkw``)

We’re going to make one assumption: we aren’t going to support a ``source``
function that uses variable arguments of any kind.  So, our final signature
will be composed like this:

1. ``dest`` positional arguments
2. ``source`` positional arguments
3. ``dest`` keyword-maybe arguments
4. ``source`` keyword-maybe arguments
5. ``dest`` keyword-only arguments
6. ``source`` keyword-only arguments

That will be saved into ``co_names``.  The first two arguments are counts —
the first one is ``len(1+2+3+4)`` and the other is ``len(5+6)``. The remaining
arguments to ``CodeType`` will be either safe minimal defaults, or things taken from
one of the three functions.

We’ll also need to do one more thing: we must ensure ``__defaults__``,
``__kwdefaults__``, and ``__annotations__`` are all in the right places.
That’s also a fairly simple thing to do (it requires more tuple/dict merging).
And with that, we’re done.

Final results
=============

Before I show you the code, let’s test it out:

.. code:: python

    # old defined as before
    @merge_args(old)
    def new(prefix, foo, *args, **kwargs):
        return old(prefix + foo, *args, **kwargs)

And the end result — ``help(new)`` says:

.. code:: text

    new(prefix, foo, bar)
        This is old's docstring.

We did it!

.. class:: lead

The code is available on GitHub_ and on PyPI_ (``pip install merge_args``).
There’s also an extensive test suite.

PS. you might be interested in another related post of mine, in which I
reverse-engineer the compilation of a function: `Gynvael’s Mission 11 (en): Python bytecode reverse-engineering
<https://chriswarrick.com/blog/2017/08/03/gynvaels-mission-11-en-python-bytecode-reverse-engineering/>`_

.. _GitHub: https://github.com/Kwpolska/merge_args
