.. title: Python Apps the Right Way: entry points and scripts
.. slug: python-apps-the-right-way-entry_points-and-scripts
.. date: 2014-09-15 18:00:00 UTC+02:00
.. tags: Python, howto, devel, best practices
.. section: Python

There are multiple ways to write an app in Python.  However, not all of them
provide your users with the best experience.

One of the problems some people encounter is *writing launch scripts*.  The
best way to handle this is the *Entry Points* mechanism of Setuptools, and a
``__main__.py`` file.  It’s quite easy to implement.  If you’re interested,
read on to learn more!

.. TEASER_END

Requirements and Desired Results
--------------------------------

You will need:

* a Python project
* a setup.py file using `setuptools`_
* the following directory structure:

  .. raw:: html

    <ul class="list-nobullets">
        <li>
        <a href="/listings/entry_points_project/"><i class="fa fa-folder-open"></i>
        entry_points_project/</a>
        <li>
            <ul class="list-nobullets">
            <li>
            <a href="/listings/entry_points_project/my_project/"><i class="fa fa-folder-open"></i> my_project/</a>
                <li>
                <ul class="list-nobullets">
                    <li>
                    <a href="/listings/entry_points_project/my_project/__init__.py.html"><i class="fa fa-file"></i>
                    __init__.py</a>
                    </li>
                    <li>
                    <a href="/listings/entry_points_project/my_project/__main__.py.html"><i class="fa fa-file"></i>
                    __main__.py</a>
                    </li>
                </ul>
                </li>
            <li>
            <a href="/listings/entry_points_project/setup.py.html"><i class="fa fa-file"></i> setup.py</a>
            </li>
            </ul>
        </li>
        </ul>


(``entry_points_project`` is also where the README and other auxiliary files
go, while ``my_project`` contains all the Python code.)

.. _setuptools: https://pypi.python.org/pypi/setuptools

When you’re done, you will have a project that can be executed by:

* ``python -m my_project``
* ``my_project``

Provided that you have your Python directory and its ``Scripts\`` subdirectory on
the %PATH%, this will **also work in Windows**.

Step 1: create a ``__main__.py`` file
-------------------------------------

In order to implement the first desired result, you need to create a
``__main__.py`` file in your package.  This file needs to contain a ``main()``
function that takes no arguments, and also a special passage to determine code
to run:

.. listing:: entry_points_project/my_project/__main__.py python
   :start-line: 6

1. The ``if __name__ == "__main__":`` idiom, as `documented here <https://docs.python.org/3/library/__main__.html>`_, is used to check whether
   this is executed as the top-level file, or if it has been imported by someone
   else (in this case, executing the ``main()`` function is not always intended).
2. The ``main()`` function must not take any arguments, because that’s how
   ``entry_points`` executes things.

Step 2: adjust ``setup.py`` accordingly
---------------------------------------

This is the real deal: create the entry points in your ``setup.py`` file.

.. listing:: entry_points_project/setup.py python
   :start-line: 4

1. You must use setuptools, otherwise this won’t work.
2. The most important piece of code is the ``entry_points`` declaration
   (unsurprisingly).
3. The declaration reads

.. code:: text

   "name_of_executable = module.with:function_to_execute"

4. If you are developing a GUI application (in Tkinter, PyQt/PySide,
   WxPython, PyGTK, PyGame…), you should change the declaration to
   ``gui_scripts``.
5. You can create multiple scripts this way.  You can also have multiple
   ``console_scripts`` *and* ``gui_scripts`` in one setup file.

.. class:: text-muted

All `code samples </listings/entry_points_project/>`_ are Copyright © 2014 Chris Warrick and licensed
under `CC-BY 3.0 <http://creativecommons.org/licenses/by-nc-nd/3.0/>`_.
