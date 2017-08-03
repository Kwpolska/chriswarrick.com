.. title: Gynvael’s Mission 11 (en): Python bytecode reverse-engineering
.. slug: gynvaels-mission-11-en-python-bytecode-reverse-engineering
.. date: 2017-08-03 12:45:40+02:00
.. tags: hacking, Python, reverse engineering, Gynvael Coldwind, Paint, BMP, writeup
.. section: Python
.. link:
.. description: I solve a mission, reverse-engineer Python bytecode, and write code in Paint.
.. type: text

Gynvael Coldwind is a security researcher at Google, who hosts weekly livestreams about security and programming in `Polish <https://gaming.youtube.com/user/GynvaelColdwind/live>`_) and `English <https://gaming.youtube.com/user/GynvaelEN/live>`_). As part of the streams, he gives out missions — basically, CTF-style reverse engineering tasks. Yesterday’s mission was about Elvish — I mean Paint — I mean Python programming and bytecode.

.. TEASER_END

.. code:: text

   MISSION 011               goo.gl/13Bia9             DIFFICULTY: ██████░░░░ [6╱10]
   ┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅┅

   Finally some real work!

   One of our field agents managed to infiltrate suspects hideout and steal a
   pendrive possibly containing important information. However, the pendrive
   actually requires one to authenticate themselves before accessing the stored
   files.

   We gave the pendrive to our laboratory and they managed to dump the firmware. We
   looked at the deadlisting they sent and for our best knowledge it's some form of
   Elvish. We can't read it.

   Here is the firmware: goo.gl/axsAHt

   And off you go. Bring us back the password.

   Good luck!

   ---------------------------------------------------------------------------------

   If you decode the answer, put it in the comments under this video! If you write
   a blogpost / post your solution online, please add a link in the comments too!

   P.S. I'll show/explain the solution on the stream in ~two weeks.
   P.S.2. Bonus points for recreating the original high-level code.


Here’s the firmware:

.. code:: text

   co_argcount 1
   co_consts (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50)
   co_flags 67
   co_name check_password
   co_names ('decode', 'len', 'False', 'all', 'zip', 'ord')
   co_nlocals 4
   co_stacksize 6
   co_varnames ('s', 'good', 'cs', 'cg')
                 0 LOAD_CONST               1
                 3 LOAD_ATTR                0
                 6 LOAD_CONST               2
                 9 CALL_FUNCTION            1
                12 STORE_FAST               1
                15 LOAD_GLOBAL              1
                18 LOAD_FAST                0
                21 CALL_FUNCTION            1
                24 LOAD_GLOBAL              1
                27 LOAD_FAST                1
                30 CALL_FUNCTION            1
                33 COMPARE_OP               3 (!=)
                36 POP_JUMP_IF_FALSE       43
                39 LOAD_GLOBAL              2
                42 RETURN_VALUE
           >>   43 LOAD_GLOBAL              3
                46 BUILD_LIST               0
                49 LOAD_GLOBAL              4
                52 LOAD_FAST                0
                55 LOAD_FAST                1
                58 CALL_FUNCTION            2
                61 GET_ITER
           >>   62 FOR_ITER                52 (to 117)
                65 UNPACK_SEQUENCE          2
                68 STORE_FAST               2
                71 STORE_FAST               3
                74 LOAD_GLOBAL              5
                77 LOAD_FAST                2
                80 CALL_FUNCTION            1
                83 LOAD_CONST               3
                86 BINARY_SUBTRACT
                87 LOAD_CONST               4
                90 BINARY_AND
                91 LOAD_CONST               5
                94 BINARY_XOR
                95 LOAD_CONST               6
                98 BINARY_XOR
                99 LOAD_GLOBAL              5
               102 LOAD_FAST                3
               105 CALL_FUNCTION            1
               108 COMPARE_OP               2 (==)
               111 LIST_APPEND              2
               114 JUMP_ABSOLUTE           62
           >>  117 CALL_FUNCTION            1
               120 RETURN_VALUE

To the uninitiated, this might look like *Elvish*. In reality, this is Python bytecode — the instruction set understood by Python’s (CPython 2.7) virtual machine. Python, like many other languages, uses a compiler to translate human-readable source code into something more appropriate for computers. Python code compiles to bytecode, which is then executed by CPython’s virtual machine. CPython bytecode can be ported between different hardware, while machine code cannot. However, machine code can often be faster than languages based on virtual machines and bytecode. (Java and C# work the same way as Python, C compiles directly to machine code)

This is the internal representation of a Python function. The first few lines are the member variables of the ``f.__code__`` object of our function. We know that:

* it takes 1 argument
* it has 7 constants: None, a long string of hex digits, the string ``'hex'``, and numbers: 89, 255, 115, 50.
* its `flags <https://docs.python.org/2.7/library/inspect.html#code-objects-bit-flags>`_ are set to 67 (CO_NOFREE, CO_NEWLOCALS, CO_OPTIMIZED). This is the “standard” value that most uncomplicated functions take.
* its name is ``check_password``
* it uses the following globals or attribute names: ``decode``, ``len``, ``False``, ``all``, ``zip``, ``ord``
* it has 4 local variables
* it uses a stack of size 6
* its variables are named ``s``, ``good``, ``cs``, ``cg``

There are two ways to solve this task: you can re-assemble the ``dis`` output, or try to re-create the function by hand, using the bytecode and the ``opcode`` module. I chose the latter method.

Reverse-engineering Python bytecode: re-creating the function by hand
=====================================================================

I started by recreating the original firmware file. I created an empty function and wrote some code to print out ``__code__`` contents and ``dis.dis`` output. I also added color-coding to help me read it:

.. code:: python

   #!/usr/bin/env python2
   import dis
   import sys

   # Write code here
   def check_password(s):
       pass

   # Reverse engineering the code
   cnames = ('co_argcount', 'co_consts', 'co_flags', 'co_name', 'co_names', 'co_nlocals', 'co_stacksize', 'co_varnames')
   cvalues = (1, (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50), 67, 'check_password', ('decode', 'len', 'False', 'all', 'zip', 'ord'), 4, 6, ('s', 'good', 'cs', 'cg'))

   for n, ov in zip(cnames, cvalues):
       v = getattr(check_password.__code__, n)
       if v == ov:
           sys.stderr.write('\033[1;32m')
       else:
           sys.stderr.write('\033[1;31m')
       sys.stderr.flush()

       sys.stdout.write(str(n) + " " + str(v) + "\n")
       sys.stdout.flush()

       sys.stderr.write('\033[0m')
       sys.stderr.flush()

   dis.dis(check_password)


If we run this solver, we get the following output (text in brackets added by me):

.. code:: text

   co_argcount 1            [OK]
   co_consts (None,)        [1/7 match]
   co_flags 67              [OK]
   co_name check_password   [OK]
   co_names ()              [0/6 match]
   co_nlocals 1             [should be 4]
   co_stacksize 1           [should be 6]
   co_varnames ('s',)       [1/4 match]
     7           0 LOAD_CONST               0 (None)
                 3 RETURN_VALUE

We can see (with the help of colors, not reproduced here), that we’ve got ``co_argcount``, ``co_flags``, ``co_name`` correctly. We also have one constant (``None``, in every function) and one variable name (``s``, the argument name). We can also see ``dis.dis()`` output. While it looks similar to the assignment, there are a few noticeable differences: there is no ``7`` at the start, and ``LOAD_CONST`` instructions in the original code did not have anything in parentheses (only comparisions and loops did).  This makes reading byte-code harder, but still possible. (I originally thought about using ``diff`` for help, but it’s not hard to do it by hand. I did use ``diff`` for the final checking after a manual conversion)

Let’s stop to look at the constants and names for a second. The long string is followed by ``hex``, and one of the constants is ``decode``. This means that we need to use ``str.decode('hex')`` to create a (byte)string of some information. Puzzle answers tend to be human-readable, and this string isn’t — so we need to do some more work.

So, let’s try reproducing the start of the original mission code using what we’ve just discussed. Python’s VM is based on a stack. In the bytecode above, you can see that instructions take 0 or 1 arguments. Some of them put things on the stack, others do actions and remove them. Most instruction names are self-explanatory, but the full list can be found in the `dis module documentation <https://docs.python.org/2/library/dis.html#python-bytecode-instructions>`_.

Instructions like ``LOAD`` and ``STORE`` refer to indices in the constants/names/varnames tuples. To make it easier, here’s a “table” of them:

.. code:: text

   constants
    0     1                                                       2      3   4    5    6
   (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50)

   names (globals, attributes)
    0         1      2        3      4      5
   ('decode', 'len', 'False', 'all', 'zip', 'ord')

   varnames (locals, _fast)
    0    1       2     3
   ('s', 'good', 'cs', 'cg')

In order to improve readability, I will use “new” ``dis`` output with names in parentheses below:

.. code:: text

    0 LOAD_CONST               1 ('4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89')
    3 LOAD_ATTR                0 (decode)
    6 LOAD_CONST               2 ('hex')
    9 CALL_FUNCTION            1 # function takes 1 argument from stack
   12 STORE_FAST               1 (good)

As I guessed before, the first line of our function is as follows:

.. code:: python

    def check_password(s):
        good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')  # new

If we run the solver again, we’ll see that the first 12 bytes of our bytecode match the mission text. We can also see that ``varnames`` is filled in half, we’ve added two constants, and one name.  The next few lines are as follows:

.. code:: text

   15 LOAD_GLOBAL              1
   18 LOAD_FAST                0
   21 CALL_FUNCTION            1
   24 LOAD_GLOBAL              1
   27 LOAD_FAST                1
   30 CALL_FUNCTION            1
   33 COMPARE_OP               3 (!=)
   36 POP_JUMP_IF_FALSE       43
   39 LOAD_GLOBAL              2
   42 RETURN_VALUE

We can see that we’re putting a global name on stack and calling it with one argument. In both cases, the global has the index 1, that’s ``len``. The two arguments are ``s`` and ``good``. We put both lengths on stack, then compare them. If the comparison fails (they’re equal), we jump to the instruction starting at byte 43, otherwise we continue execution to load the second global (False) and return it.  This wall of text translates to the following simple code:

.. code:: python

    def check_password(s):
        good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
        if len(s) != len(good):  # new
            return False         # new

Let’s take another look at our names. We can see we’re missing ``all``, ``zip``, ``ord``. You can already see a common pattern here: we will iterate over both strings at once (using ``zip``), do some math based on the character’s codes (``ord``), and then check if all the results are truthy.

Here’s the bytecode with value annotations and comments, which explain what happens where:

.. code:: text

   >>   43 LOAD_GLOBAL              3 (all)
        46 BUILD_LIST               0
        49 LOAD_GLOBAL              4 (zip)
        52 LOAD_FAST                0 (s)
        55 LOAD_FAST                1 (good)
        58 CALL_FUNCTION            2           # zip(s, good)
        61 GET_ITER                             # Start iterating: iter()
   >>   62 FOR_ITER                52 (to 117)  # for loop iteration start (if iterator exhausted, jump +52 bytes to position 117)
        65 UNPACK_SEQUENCE          2           # unpack a sequence (a, b = sequence)
        68 STORE_FAST               2 (cs)      # cs = item from s
        71 STORE_FAST               3 (cg)      # cg = item from good
        74 LOAD_GLOBAL              5 (ord)
        77 LOAD_FAST                2 (cs)
        80 CALL_FUNCTION            1           # put ord(cs) on stack
        83 LOAD_CONST               3 (89)
        86 BINARY_SUBTRACT                      # - 89   [subtract 89 from topmost value]
        87 LOAD_CONST               4 (255)
        90 BINARY_AND                           # & 255  [bitwise AND with topmost value]
        91 LOAD_CONST               5 (115)
        94 BINARY_XOR                           # ^ 115  [bitwise XOR with topmost value]
        95 LOAD_CONST               6 (50)
        98 BINARY_XOR                           # ^ 50   [bitwise XOR with topmost value]
        99 LOAD_GLOBAL              5 (ord)
       102 LOAD_FAST                3 (cg)
       105 CALL_FUNCTION            1           # put ord(cs) on stack
       108 COMPARE_OP               2 (==)      # compare the two values on stack
       111 LIST_APPEND              2           # append topmost value to the list in topmost-1; pop topmost (append to list created in comprehension)
       114 JUMP_ABSOLUTE           62           # jump back to start of loop
   >>  117 CALL_FUNCTION            1           # after loop: call all([list comprehension result])
       120 RETURN_VALUE                         # return value returned by all()

We can now write the full answer.

.. listing:: listings/gynvaels-mission-11-en/mission11.py python

In the end, our ``dis.dis()`` output matches the mission text (except the removed values, but their IDs do match), our ``co_*`` variables are all green, and we can get to work on solving the puzzle itself!

**Side note:** this task uses a list comprehension. You might want to optimize it, remove the brackets, and end up with a generator expression. This would make the task harder, since would require working with the internal generator code object as well:

.. code:: text

  co_consts (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', <code object <genexpr> at 0x104a86c30, file "mission11-genexpr.py", line 11>)

  46 LOAD_CONST               3 (<code object <genexpr> at 0x104a86c30, file "mission11-genexpr.py", line 11>)

``BINARY_*`` and ``ord`` disappeared from the new listing. You can see the `modified code </listings/gynvaels-mission-11-en/mission11-genexpr.py.html>`_ (which differs by two bytes) and `solver output </listings/gynvaels-mission-11-en/mission11-genexpr.txt.html>`_.

Solving the real puzzle
=======================

I solved the extra credit part of the puzzle. The *real* aim of the puzzle was to recover the password — the text for which ``check_password()`` will return True.

This part is pretty boring. I built a dictionary, where I mapped every byte (0…256) to the result of the calculation done in the ``check_password()`` function’s loop. Then I used that to recover the original text.

.. code:: python

   pass_values = {}
   for i in range(256):
       result = i - 89 & 255 ^ 115 ^ 50
       pass_values[result] = i

   good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
   password = ''
   for c in good:
       password += chr(pass_values[ord(c)])

   print(password)
   print(check_password(password))

**The password is:** ``huh, that actually worked!``.

What was that Paint thing about?
================================

.. raw:: html

  <blockquote>Yesterday’s mission was about Elvish — <strong>I mean Paint</strong> — I mean Python programming.<footer>yours truly in this post’s teaser</footer></blockquote>

Most of my readers were probably puzzled by the mention of Paint. Long-time viewers of Gynvael’s streams in Polish remember the Python 101 video he posted on April Fools last year. See `original video <https://www.youtube.com/watch?v=7VJaprmuHcw>`_, `explanation <http://gynvael.coldwind.pl/?id=599>`_, `code <https://github.com/gynvael/stream/tree/master/007-python-101>`_ (video and explanation are both Polish; you can get the gist of the video without hearing the audio commentary though.) **Spoilers ahead.**

In that prank, Gynvael taught Python basics. The first part concerned itself with writing bytecode by hand. The second part was about drawing custom Python modules. In Paint. Yes, Paint, the simple graphics program included with Microsoft Windows. He drew a custom Python module in Paint, and saved it using the BMP format. It looked like this (zoomed PNG below; `download gynmod.bmp </pub/gynvaels-mission-11-en/gynmod.bmp>`_):

.. image:: /images/gynvaels-mission-11-en/gynmod-zoom.png
   :align: center

How was this done? There are three things that come into play:

* Python can import modules from a ZIP file (if it’s appended to sys.path). Some tools that produce ``.exe`` files of Python code use this technique; the old egg file format also used ZIPs this way.
* BMP files have their header at the start of a file.
* ZIP files have their header at the end of a file.
* Thus, one file can be a valid BMP and ZIP at the same time

I took the code of ``check_password`` and put it in ``mission11.py`` (which I already cited above). Then I compiled to ``.pyc`` and created a ``.zip`` out of it.

.. listing:: listings/gynvaels-mission-11-en/mission11.py python

Since I’m not an expert in any of the formats, I booted my Windows virtual machine and blindly copied the `parameters used by Gynvael <http://gynvael.coldwind.pl/img/secapr16_3.png>`_ to open the ZIP file (renamed ``.raw``) in IrfanView and save as ``.bmp``. I changed the size to 83×2, because my ZIP file was 498 bytes long (3 BPP * 83 px * 2 px = 498 bytes) — by doing that, and through sheer luck with the size, I could avoid adding comments and editing the ``zip``. I ended up with this (PNG again; `download mission11.bmp </pub/gynvaels-mission-11-en/mission11.bmp>`_):

.. image:: /images/gynvaels-mission-11-en/mission11-zoom.png
   :align: center

The ``.bmp`` file is runnable! We can use this code:

.. listing:: listings/gynvaels-mission-11-en/ziprunner.py python

And we get this:

.. image:: /images/gynvaels-mission-11-en/running-bmp.png
   :align: center

Resources
=========

* `mission11-solver.py (full solver code) </listings/gynvaels-mission-11-en/mission11-solver.py.html>`_
* `mission11-genexpr.py </listings/gynvaels-mission-11-en/mission11-genexpr.py.html>`_, `mission11-genexpr.txt </listings/gynvaels-mission-11-en/mission11-genexpr.txt.html>`_ (used for side note regarding generator expressions vs list comprehensions)
* `mission11.py code, used in BMP file </listings/gynvaels-mission-11-en/mission11.py.html>`_
* `ziprunner.py, file that runs the BMP/ZIP module </listings/gynvaels-mission-11-en/ziprunner.py.html>`_ (adapted from Gynvael’s)
* `gynmod.bmp </pub/gynvaels-mission-11-en/gynmod.bmp>`_
* `mission11.bmp </pub/gynvaels-mission-11-en/mission11.bmp>`_

Thanks for the mission (and BMP idea), Gynvael!
