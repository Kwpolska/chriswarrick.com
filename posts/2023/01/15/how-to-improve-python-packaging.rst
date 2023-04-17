.. title: How to improve Python packaging, or why fourteen tools are at least twelve too many
.. slug: how-to-improve-python-packaging
.. date: 2023-01-15 14:45:00+01:00
.. updated: 2023-04-18 00:45:00+02:00
.. tags: Python, pip, virtual environments, PyPA, packaging, PDM, CSharp, .NET, JavaScript, npm, Node.js
.. category: Python
.. description: A journey to the world of Python packaging, a visit to the competition, a hopeful look at the future, and highlights from a disappointing discussion.
.. type: text

There is an area of Python that many developers have problems with. This is an area that has seen many different solutions pop up over the years, with many different opinions, wars, and attempts to solve it. Many have complained about the packaging ecosystem and tools making their lives harder. Many beginners are confused about virtual environments. But does it have to be this way? Are the current solutions to packaging problems any good? And is the organization behind most of the packaging tools and standards part of the problem itself?

Join me on a journey through packaging in Python and elsewhere. We’ll start by describing the classic packaging stack (involving setuptools and friends), the scientific stack (with conda), and some of the modern/alternate tools, such as Pipenv, Poetry, Hatch, or PDM. We’ll also look at some examples of packaging and dependency-related workflows seen elsewhere (Node.js and .NET). We’ll also take a glimpse at a possible future (with a venv-less workflow with PDM), and see if the PyPA agrees with the vision and insights of eight thousand users.

.. TEASER_END

.. class:: alert alert-primary

.. contents::

The plethora of tools
=====================

There are many packaging-related tools in Python. All of them with different authors, lineages, and often different opinions, although most of them are now unified under the Python Packaging Authority (PyPA) umbrella. Let’s take a look at them.

The classic stack
-----------------

The classic Python packaging stack consists of many semi-related tools. Setuptools, probably the oldest tool of the group, and itself based on ``distutils``, which is part of the standard library (although it will be removed in Python 3.12), is responsible for installing a single package. It previously used ``setup.py`` files to do its job, which required arbitrary code execution. It then added support for non-executable metadata specification formats: ``setup.cfg``, and also ``pyproject.toml`` (partially still in beta). However, you aren’t supposed to use ``setup.py`` files directly these days, you’re supposed to be using pip. Pip installs packages, usually from the PyPI, but it can also support other sources (such as git repositories or the local filesystem). But where does pip install things? The default used to be to install globally and system-wide, which meant you could introduce conflicts between packages installed by pip and apt (or whatever the system package manager is). Even with a user-wide install (which pip is likely to attempt these days), you can still end up with conflicts, and you can also have conflicts in which package A requests X version 1.0.0, but package B expects X version 2.0.0—but A and B are not at all related and could live separately with their preferred version of X. Enter ``venv``, a standard library descendant of ``virtualenv``, which can create a lightweight virtual environment for packages to live in. This virtual environment gives you the separation from system packages and from different environments, but it is still tied to the system Python in some ways (and if the system Python disappears, the virtual environment stops working).

A few extra tools would be used in a typical packaging workflow. The ``wheel`` package enhances Setuptools with the ability to generate wheels, which are ready-to-install (without running ``setup.py``). Wheels can either be pure-Python and be installed anywhere, or they can contain pre-compiled extension modules (things written in C) for a given OS and Python (and there’s even a standard that allows building and distributing one wheel for all typical Linux distros). The ``wheel`` package should be an implementation detail, something existing inside Setuptools and/or pip, but users need to be aware of it if they want to make wheels on their system, because virtual environments produced by ``venv`` do not have ``wheel`` installed. Regular users who do not maintain their own packages may sometimes be told that pip is using something legacy because ``wheel`` is not installed, which is not a good user experience. Package authors also need ``twine``, whose sole task is uploading source distributions or wheels, created with other tools, to PyPI (and there’s not much more to say about that tool).

…and a few extensions
---------------------

Over the years, there have been a few tools that are based on things from the classic stack. For example, ``pip-tools`` can simplify dependency management. While ``pip freeze`` lets you produce a file with everything installed in your environment, there is no way to specify the dependencies you need, and get a lock file with specific versions and transitive dependencies (without installing and freezing everything), there is no easy way to skip development dependencies (e.g. IPython) when you ``pip freeze``, and there is no workflow to update all your dependencies with just pip. ``pip-tools`` adds two tools, ``pip-compile`` which takes in ``requirements.in`` files with the packages you care about, and produces a ``requrirements.txt`` with pinned versions of them and all transitive dependencies; and also ``pip-sync``, which can install ``requirements.txt`` and removes things not listed in it.

Another tool that might come in useful is ``virtualenvwrapper``, which can help you manage (create and activate) virtual environments in a central location. It has a few bells and whistles (such as custom hooks to do actions on every virtualenv creation), although for basic usage, you could replace it with a single-line shell function.

Yet another tool that works alongside the classic toolset is ``pipx``, which creates and manages virtual environments for apps written in Python. You tell it to ``pipx install Nikola``, and it will create a virtual environment somewhere, install Nikola into it, and put a script for launching it in ``~/.local/bin``. While you could do it all yourself with venv and some symlinks, pipx can take care of this, and you don’t need to remember where the virtual environment is.

The scientific stack and conda
------------------------------

The scientific Python community have had their own tools for many years. The conda tool can manage environments and packages. It doesn’t use PyPI and wheels, but rather packages from conda channels (which are prebuilt, and expect an Anaconda-distributed Python). Back in the day, when there were no wheels, this was the easiest way to get things installed on Windows; this is not as much of a problem now with binary wheels on PyPI—but the Anaconda stack is still popular in the scientific world. Conda packages can be built with ``conda-build``, which is separate, but closely related to ``conda`` itself. Conda packages are not compatible with ``pip`` in any way, they do not follow the packaging standards used by other tools. Is this good? No, because it makes integrating the two worlds harder, but also yes, because many problems that apply to scientific packages (and their C/C++ extension modules, and their high-performance numeric libraries, and other things) do not apply to other uses of Python, so having a separate tool lets people focusing the other uses simplify their workflows.

The new tools
-------------

A few years ago, new packaging tools appeared. Now, there were lots of “new fancy tools” introduced in the past, with setuptools extending distutils, then distribute forking setuptools, then distribute being merged back…

The earliest “new tool” was Pipenv. Pipenv had really terrible and misleading marketing, and it merged pip and venv, in that Pipenv would create a venv and install packages in it (from ``Pipfile`` or ``Pipfile.lock``). Pipenv can place the venv in the project folder, or hide it somewhere in the project folder (the latter is the default). However, Pipenv does not handle any packages related to packaging your code, so it’s useful only for developing non-installable applications (Django sites, for example). If you’re a library developer, you need setuptools anyway.

The second new tool was Poetry. It manages environments and dependencies in a similar way to Pipenv, but it can also build ``.whl`` files with your code, and it can upload wheels and source distributions to PyPI. This means it has pretty much all the features the other tools have, except you need just one tool. However, Poetry is opinionated, and its opinions are sometimes incompatible with the rest of the packaging scene. Poetry uses the ``pyproject.toml`` standard, but it does not follow the standard specifying how metadata should be represented in a ``pyproject.toml`` file (PEP 621), instead using a custom ``[tool.poetry]`` table. This is partly because Poetry came out before PEP 621, but the PEP was accepted over 2 years ago—the biggest compatibility problem is Poetry’s node-inspired ``~`` and ``^`` dependency version markers, which are not compatible with PEP 508 (the dependency specification standard). Poetry can package C extension modules, although it uses setuptools’ infrastructure for this (and requires a custom ``build.py`` script).

Another similar tool is Hatch. This tool can also manage environments (it allows multiple environments per project, but it does not allow to put them in the project directory), and it can manage packages (but without lockfile support). Hatch can also be used to package a project (with PEP 621-compliant ``pyproject.toml`` files) and upload it to PyPI. It does not support C extension modules.

A tool that tries to be a simpler re-imagining of Setuptools is Flit. It can build and install a package using a ``pyproject.toml`` file. It also supports uploads to PyPI. It lacks support for C extension modules, and it expects you to manage environments on your own.

There’s one more interesting (albeit not popular or well-known) tool. This tool is PDM. It can manage venvs (but it defaults to the saner ``.venv`` location), manage dependencies, and it uses a standards-compliant ``pyproject.toml``. There’s also a curious little feature called PEP 582 support, which we’ll talk about later.

Tooling proliferation and the Python Package Authority
======================================================

The previous sections mentioned 14 (fourteen!) distinct tools. As we’ll discover soon, that’s at least 12 too many. Let’s try to compare them.

First, let’s define nine things that we would expect packaging tools to do:

1. Manage environments
2. Install packages
3. Package/develop apps
4. Package libraries
5. Package C extension modules
6. Install in editable mode
7. Lock dependencies
8. Support pyproject.toml files
9. Upload to PyPI

.. raw:: html

   <div style="font-size: 90%">

.. class:: table table-hover

==================== =========================== ========================================= ==================================================== ================================================================================== =========================================
 Tool                 Maintainer                  Use-case                                  # of supported features                              # of partially supported features                                                  # of unsupported features
==================== =========================== ========================================= ==================================================== ================================================================================== =========================================
 setuptools           PyPA                        Making things installable                 4                                                    2 (pyproject.toml partially in beta, installing—only setuptools-based sdists)      3
 pip                  PyPA                        Installing packages                       2                                                    1 (Locking dependencies only manually)                                             6
 venv                 PyPA                        Creating virtual environments             1 (Creating environments)                            0                                                                                  8
 wheel                PyPA                        Building wheels in setuptools             0                                                    1 (Building wheels in setuptools)                                                  8
 Twine                PyPA                        Uploading to PyPI                         1 (Uploading to PyPI)                                0                                                                                  8
 pip-tools            Jazzband                    Managing requirements files               2 (Locking dependencies, installing packages)        0                                                                                  7
 virtualenvwrapper    Doug Hellmann               Managing virtual environments             1 (Managing environments)                            0                                                                                  8
 pipx                 PyPA                        Installing Python command-line tools      2 (Installing packages, editable installs)           1 (Managing environments)                                                          6
 conda                Anaconda, Inc.              Managing environments and dependencies    3 (Managing environments, installing things)         4 (Manual locking, packaging requires conda-build)                                 2 (pyproject.toml and PyPI)
 Pipenv               PyPA                        Managing dependencies for apps            3 (Managing environments, installing and locking)    1 (Developing apps)                                                                5
 Poetry               Sébastien Eustace et al.    Packaging and managing dependencies       7                                                    2 (pyproject.toml, C extensions)                                                   0
 Flit                 PyPA                        Packaging pure-Python projects            5                                                    1 (Installing only flit packages)                                                  3
 Hatch                PyPA                        Packaging and managing dependencies       7                                                    0                                                                                  2 (C extensions, locking dependencies)
 PDM                  Frost Ming                  Packaging and managing dependencies       8                                                    0                                                                                  1 (C extensions)
==================== =========================== ========================================= ==================================================== ================================================================================== =========================================

.. raw:: html

   </div>
   <details style="margin-bottom: 1rem">
   <summary style="background: rgba(0, 170, 221, 10%); padding: .25rem; border-radius: .25rem">Expand table with more details about support for each feature</summary>
   <div style="font-size: 90%; margin-top: .5rem">

.. class:: table table-hover

==================== ============================= ============================================================ ============================================ =================== =================================== =============== =========== =============================== =======================
 Tool                 F1 (Envs)                     F2 (Install)                                                 F3 (Apps)                                    F4 (Libraries)      F5 (Extensions)                     F6 (Editable)   F7 (Lock)   F8 (pyproject.toml)             F9 (Upload)
==================== ============================= ============================================================ ============================================ =================== =================================== =============== =========== =============================== =======================
 setuptools           No                            Only if authoring the package, direct use not recommended    Yes                                          Yes                 Yes                                 Yes             No          Beta                            No (can build sdist)
 pip                  No                            Yes                                                          No                                           No                  No                                  Yes             Manually    N/A                             No
 venv                 Only creating environments    No                                                           No                                           No                  No                                  No              No          No                              No
 wheel                No                            No                                                           No                                           No                  No                                  No              No          No                              No (can build wheels)
 Twine                No                            No                                                           No                                           No                  No                                  No              No          No                              Yes
 pip-tools            No                            Yes                                                          No                                           No                  No                                  No              Yes         No                              No
 virtualenvwrapper    Yes                           No                                                           No                                           No                  No                                  No              No          No                              No
 pipx                 Sort of                       Yes                                                          No                                           No                  No                                  Yes             No          No                              No
 conda                Yes                           Yes (from conda channels)                                    Develop (conda-build is a separate tool)     With conda-build    With conda-build                    Yes             Manually    No                              No
 Pipenv               Yes                           Yes                                                          Only develop                                 No                  No                                  No              Yes         No                              No
 Poetry               Yes                           Yes                                                          Yes                                          Yes                 Sort of (custom build.py script)    Yes             Yes         Yes, but using custom fields    Yes
 Flit                 No                            Only if authoring the package                                Yes                                          Yes                 No                                  Yes             No          Yes                             Yes
 Hatch                Yes                           Yes                                                          Yes                                          Yes                 No                                  Yes             No          Yes                             Yes
 PDM                  Yes                           Yes                                                          Yes                                          Yes                 No                                  Yes             Yes         Yes                             Yes
==================== ============================= ============================================================ ============================================ =================== =================================== =============== =========== =============================== =======================

.. raw:: html

   </div>
   </details>

You should pay close attention to the Maintainer column in the table. The vast majority of them are maintained by PyPA, the Python Packaging Authority. Even more curiously, the two tools that have the most “Yes” values (Poetry and PDM) are not maintained by the PyPA, but instead other people completely independent of them and not participating in the working group. So, is the working group successful, if it cannot produce one fully-featured tool? Is the group successful if it has multiple projects with overlapping responsibilities? Should the group focus their efforts on standards like `PEP 517`_, which is a common API for packaging tools, and which also encourages the creation of even more incompatible and competing tools?

Most importantly: which tool should a beginner use? The PyPA has a few guides and tutorials, one is `using pip + venv`__, another is `using pipenv`__ (why would you still do that?), and `another tutorial`__ that lets you pick between Hatchling (hatch’s build backend), setuptools, Flit, and PDM, without explaining the differences between them—and without using any environment tools, and without using Hatch’s/PDM’s build and PyPI upload features (instead opting to use ``python -m build`` and ``twine``). The concept of virtual environments can be very confusing for beginners, and managing virtual environments is difficult if everyone has incompatible opinions about it.

It is also notable that `PEP 20`_, the Zen of Python, states this:

    *There should be one-- and preferably only one --obvious way to do it.*

Python packaging definitely does not follow it [1]_. There are 14 ways, and none of them is obvious or the only good one. All in all, this is an unsalvageable mess. Why can’t Python pick one tool? What does the competition do? We’ll look at this in a minute. But first, let’s talk about the elephant in the room: Python virtual environments.

__ https://packaging.python.org/en/latest/tutorials/installing-packages/
__ https://packaging.python.org/en/latest/tutorials/managing-dependencies/
__ https://packaging.python.org/en/latest/tutorials/packaging-projects/

.. _PEP 20: https://peps.python.org/pep-0020/
.. _PEP 517: https://peps.python.org/pep-0517/

Does Python really need virtual environments?
=============================================

Python relies on virtual environments for separation between projects. Virtual environments (aka virtualenvs or venvs) are folders with symlinks to a system-installed Python, and their own set of site-packages. There are a few problems with them:

How to use Python from a virtual environment?
---------------------------------------------

There are two ways to do this. The first one is to activate it, by running the activate shell script installed in the environment’s bin directory. Another is to run the python executable (or any other script in the bin directory) directly from the venv. [2]_

Activating venvs directly is more convenient for developers, but it also has some problems. Sometimes, activation fails to work, due to the shell caching the location of things in ``$PATH``. Also, beginners are taught to ``activate`` and run ``python``, which means they might be confused and try to use activate in scripts or cronjobs (but in those environments, you should not activate venvs, and instead use the Python executable directly). Virtual environment activation is more state you need to be aware of, and if you forget about it, or if it breaks, you might end up messing up your user-wide (or worse, system-wide) Python packages.

How are (system) Pythons and virtual environments related?
----------------------------------------------------------

The virtual environment depends very tightly on the (system/global/pyenv-installed) Python used to create it. This is good for disk-space reasons (clean virtual environments don’t take up very much space), but this also makes the environment more fragile. If the Python used to create the environment is removed, the virtual environment stops working. If you fully manage your own Python, then it’s probably not going to happen, but if you depend on a system Python, upgrading packages on your OS might end up replacing Python 3.10 with Python 3.11. Some distributions (e.g. Ubuntu) would only make a jump like this on a new distribution release (so you can plan ahead), some of them (e.g. Arch) are rolling-release and a regular system upgrade may include a new Python, whereas some (e.g. Homebrew) make it even worse by using paths that include the patch Python version (3.x.y), which cause virtual environments to break much more often.

How to manage virtual environments?
-----------------------------------

The original virtualenv tool, and its simplified standard library rewrite venv, allow you to put a virtual environment anywhere in the file system, as long as you have write privileges there. This has led to people and tools inventing their own standards. Virtualenvwrapper stores environments in a central location, and does not care about their contents. Pipenv and poetry allow you to choose (either a central location or the .venv directory in the project), and environments are tied to a project (they will use the project-specific environment if you’re in the project directory). Hatch stores environments in a central location, and it allows you to have multiple environments per project (but there is no option to share environments between projects).

Brett Cannon has recently done `a survey`__, and it has shown the community is split on their workflows: some people use a central location, some put them in the project directory, some people have multiple environments with different Python versions, some people reuse virtualenvs between projects… Everyone has different needs, and different opinions. For example, I use a central directory (~/virtualenvs) and reuse environments when working on Nikola (sharing the same environment between development and 4 Nikola sites). But on the other hand, when deploying web apps, the venv lives in the project folder, because this venv needs to be used by processes running as different users (me, root, or the service account for the web server, which might have interactive login disabled, or whose home directory may be set to something ephemeral).

__ https://snarky.ca/classifying-python-virtual-environment-workflows/

So: **does Python need virtual environments?** Perhaps looking how other languages handle this problem can help us figure this out for Python?

How everyone else is doing it
=============================

We’ll look at two ecosystems. We’ll start with `JavaScript/Node.js (with npm)`_, and then we’ll look at the `C#/.NET (with dotnet CLI/MSBuild)`_ ecosystem for comparison. We’ll demonstrate a sample flow of making a project, installing dependencies in it, and running things. If you’re familiar with those ecosystems and want to skip the examples, continue with `How is Node better than Python?`_ and `Are those ecosystems’ tools perfect?`_. Otherwise, read on.

JavaScript/Node.js (with npm)
-----------------------------

There are two tools for dealing with packages in the Node world, namely npm and Yarn. The npm CLI tool is shipped with Node, so we’ll focus on it.

Let’s create a project:


.. code:: text

    $ mkdir mynpmproject
    $ cd mynpmproject
    $ npm init
    …answer a few questions…
    $ ls
    package.json

We’ve got a package.json file, which has some metadata about our project (name, version, description, license). Let’s install a dependency:

.. code:: text

    $ npm install --save is-even

    added 5 packages, and audited 6 packages in 2s

    found 0 vulnerabilities


The mere existence of an ``is-even`` package is questionable; the fact that it includes four dependencies is yet another, and the fact that it depends on ``is-odd`` is even worse. But this post isn’t about ``is-even`` or the Node ecosystem’s tendency to use tiny packages for everything (but I wrote one about this topic `before`__). Let’s look at what we have in the filesystem:

__ https://chriswarrick.com/blog/2019/02/15/modern-web-development-where-you-need-500-packages-to-build-bootstrap/

.. code:: text

    $ ls
    node_modules/  package.json  package-lock.json
    $ ls node_modules
    is-buffer/  is-even/  is-number/  is-odd/  kind-of/

Let’s also take a peek at the ``package.json`` file:

.. code:: json

    {
      "name": "mynpmproject",
      "version": "1.0.0",
      "description": "",
      "main": "index.js",
      "scripts": {
        "test": "echo \"Error: no test specified\" && exit 1"
      },
      "author": "",
      "license": "ISC",
      "dependencies": {
        "is-even": "^1.0.0"
      }
    }

Our ``package.json`` file now lists the dependency, and we’ve also got a lock file (``package-lock.json``), which records all the dependency versions used for this install. If this file is kept in the repository, any future attempts to ``npm install`` will use the dependency versions listed in this file, ensuring everything will work the same as it did originally (unless one of those packages were to get removed from the registry).

Let’s try writing a trivial program using the module and try running it:

.. code:: text

    $ cat index.js
    var isEven = require('is-even');
    console.log(isEven(0));

    $ node index.js
    true

Let’s try removing ``is-odd`` to demonstrate how badly designed this package is:

.. code:: text

    $ rm -rf node_modules/is-odd
    $ node index.js
    node:internal/modules/cjs/loader:998
      throw err;
      ^

    Error: Cannot find module 'is-odd'
    Require stack:
    - /tmp/mynpmproject/node_modules/is-even/index.js
    - /tmp/mynpmproject/index.js
        at Module._resolveFilename (node:internal/modules/cjs/loader:995:15)
        at Module._load (node:internal/modules/cjs/loader:841:27)
        at Module.require (node:internal/modules/cjs/loader:1061:19)
        at require (node:internal/modules/cjs/helpers:103:18)
        at Object.<anonymous> (/tmp/mynpmproject/node_modules/is-even/index.js:10:13)
        at Module._compile (node:internal/modules/cjs/loader:1159:14)
        at Module._extensions..js (node:internal/modules/cjs/loader:1213:10)
        at Module.load (node:internal/modules/cjs/loader:1037:32)
        at Module._load (node:internal/modules/cjs/loader:878:12)
        at Module.require (node:internal/modules/cjs/loader:1061:19) {
      code: 'MODULE_NOT_FOUND',
      requireStack: [
        '/tmp/mynpmproject/node_modules/is-even/index.js',
        '/tmp/mynpmproject/index.js'
      ]
    }

    Node.js v18.12.1

How is Node better than Python?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Badly designed packages aside, we can see an important difference from Python in that there is **no virtual environment**, and all the packages live in the project directory. If we fix the ``node_modules`` directory by running ``npm install``, we can see that I can run the script from somewhere else on the file system:

.. code:: text

    $ pwd
    /tmp/mynpmproject
    $ npm install

    added 1 package, and audited 6 packages in 436ms

    found 0 vulnerabilities
    $ node /tmp/mynpmproject/index.js
    true
    $ cd ~
    $ node /tmp/mynpmproject/index.js
    true

**If you try to do that with a Python tool…**

* If you’re using a manually managed venv, you need to remember to activate it, or to use the appropriate Python.
* If you’re using something fancier, it might be tied to the current working directory, and it may expect you to change into that directory, or to pass an argument pointing at that directory.

I can also run my code as ``root``, and as an unprivileged ``nginx`` user, without any special preparation (like telling pipenv/poetry to put their venv in the project directory, or running them as the other users):

.. code:: text

    $ su -
    # node /tmp/mynpmproject/index.js
    true
    # sudo -u nginx node /tmp/mynpmproject/index.js
    true

**If you try to do that with a Python tool…**

* If you’re using a manually managed venv, you can use its Python as another user (assuming it has the right permissions).
* If your tool puts the venv in the project directory, this will work too.
* If your tool puts the venv in some weird place in your home folder, the other users will get their own venvs. The ``uwsgi`` user on Fedora uses ``/run/uwsgi`` as its home directory, and ``/run`` is ephemeral (tmpfs), so a reboot forces you to reinstall things.

We can even try to change the name of our project:

.. code:: text

    $ cd /tmp
    $ mv mynpmproject mynodeproject
    $ node /tmp/mynodeproject/index.js
    true

**If you try to do that with a Python tool…**

* If you’re using a manually managed venv, and it lives in a central directory, all is well.
* If you or your tool places the venv in the project directory, the venv is now broken, and you need to recreate it (hope you have a recent ``requirements.txt``!)
* If your tool puts the venv in some weird place in your home folder, it may decide that this is a different project, which means it will recreate it, and you’ll have an unused virtual environment somewhere on your filesystem.

Other packaging topics
~~~~~~~~~~~~~~~~~~~~~~

Some packages may expose executable scripts (with the ``bin`` property). Those can be run in three ways:

1. Installed globally using ``npm install -g``, which would put the script in a global location that’s likely in ``$PATH`` (e.g. ``/usr/local/bin``).
2. Installed locally using ``npm install``, and executed with the ``npx`` tool or manually by running the script in ``node_packages/.bin``.
3. Not installed at all, but executed using the ``npx`` tool, which will install it into a cache and run it.

Also, if we wanted to publish our thing, we can just run ``npm publish`` (after logging in with ``npm login``).

C#/.NET (with dotnet CLI/MSBuild)
---------------------------------

In modern .NET, the One True Tool is the dotnet CLI, which uses MSBuild for most of the heavy lifting. (In the classic .NET Framework, the duties were split between MSBuild and NuGet.exe, but let’s focus on the modern workflow.)

Let’s create a project:


.. code:: text

    $ mkdir mydotnetproject
    $ cd mydotnetproject
    $ dotnet new console
    The template "Console App" was created successfully.

    Processing post-creation actions...
    Running 'dotnet restore' on /tmp/mydotnetproject/mydotnetproject.csproj...
      Determining projects to restore...
      Restored /tmp/mydotnetproject/mydotnetproject.csproj (in 92 ms).
    Restore succeeded.
    $ ls
    mydotnetproject.csproj  obj/  Program.cs

We get three things: a ``mydotnetproject.csproj`` file, which defines a few properties of our project; ``Program.cs``, which is a hello world program, and ``obj/``, which contains a few files you don’t need to care about.

Let’s try adding a dependency. For a pointless example, but slightly more reasonable than the JS one, we’ll use ``AutoFixture``, which brings in a dependency on ``Fare``. If we run ``dotnet add package AutoFixture``, we get some console output, and our ``mydotnetproject.csproj`` now looks like this:

.. code:: xml

    <Project Sdk="Microsoft.NET.Sdk">

      <PropertyGroup>
        <OutputType>Exe</OutputType>
        <TargetFramework>net6.0</TargetFramework>
        <ImplicitUsings>enable</ImplicitUsings>
        <Nullable>enable</Nullable>
      </PropertyGroup>

      <ItemGroup>
        <PackageReference Include="AutoFixture" Version="4.17.0" />
      </ItemGroup>

    </Project>

The first ``<PropertyGroup>`` specifies what our project is (Exe = something you can run), specifies the target framework (.NET 6.0 [3]_), and enables a few opt-in features of C#. The second ``<ItemGroup>`` was inserted when we installed AutoFixture.

We can now write a pointless program in C#. Here’s our new ``Program.cs``:

.. code:: csharp

    using AutoFixture;
    var fixture = new Fixture();
    var a = fixture.Create<int>();
    var b = fixture.Create<int>();
    var result = a + b == b + a;
    Console.WriteLine(result ? "Math is working": "Math is broken");

(We could just use C#’s/.NET’s built-in random number generator, AutoFixture is complete overkill here—it’s meant for auto-generating test data, with support for arbitrary classes and other data structures, and we’re just getting two random ints here. I’m using AutoFixture for this example, because it’s simple to use and demonstrate, and because it gets us a transitive dependency.)

And now, we can run it:

.. code:: text

    $ dotnet run
    Math is working

If we want something that can be run outside of the project, and possibly without .NET installed on the system, we can use dotnet publish. The most basic scenario:

..  code:: text

    $ dotnet publish
    $ ls bin/Debug/net6.0/publish
    AutoFixture.dll*  Fare.dll*  mydotnetproject*  mydotnetproject.deps.json  mydotnetproject.dll  mydotnetproject.pdb  mydotnetproject.runtimeconfig.json
    $ du -h bin/Debug/net6.0/publish
    424K    bin/Debug/net6.0/publish
    $ bin/Debug/net6.0/publish/mydotnetproject
    Math is working

You can see that we’ve got a few files related to our project, as well as ``AutoFixture.dll`` and ``Fare.dll``, which are our dependencies (``Fare.dll`` is a dependency of ``AutoFixture.dll``). Now, let’s try to remove ``AutoFixture.dll`` from the published distribution:

.. code:: text

    $ rm bin/Debug/net6.0/publish/AutoFixture.dll
    $ bin/Debug/net6.0/publish/mydotnetproject
    Unhandled exception. System.IO.FileNotFoundException: Could not load file or assembly 'AutoFixture, Version=4.17.0.0, Culture=neutral, PublicKeyToken=b24654c590009d4f'. The system cannot find the file specified.

    File name: 'AutoFixture, Version=4.17.0.0, Culture=neutral, PublicKeyToken=b24654c590009d4f'
    [1]    45060 IOT instruction (core dumped)  bin/Debug/net6.0/publish/mydotnetproject

We can also try a more advanced scenario:

.. code:: text

    $ rm -rf bin obj  # clean up, just in case
    $ dotnet publish --sc -r linux-x64 -p:PublishSingleFile=true -o myoutput
    Microsoft (R) Build Engine version 17.0.1+b177f8fa7 for .NET
    Copyright (C) Microsoft Corporation. All rights reserved.

      Determining projects to restore...
      Restored /tmp/mydotnetproject/mydotnetproject.csproj (in 4.09 sec).
      mydotnetproject -> /tmp/mydotnetproject/bin/Debug/net6.0/linux-x64/mydotnetproject.dll
      mydotnetproject -> /tmp/mydotnetproject/myoutput/
    $ ls myoutput
    mydotnetproject*  mydotnetproject.pdb
    $ myoutput/mydotnetproject
    Math is working
    $ du -h myoutput/*
    62M     myoutput/mydotnetproject
    12K     myoutput/mydotnetproject.pdb
    $ file -k myoutput/mydotnetproject
    myoutput/mydotnetproject: ELF 64-bit LSB pie executable, x86-64, version 1 (GNU/Linux), dynamically linked, interpreter /lib64/ld-linux-x86-64.so.2, BuildID[sha1]=47637c667797007d777f4322729d89e7fa53a870, for GNU/Linux 2.6.32, stripped, too many notes (256)\012- data
    $ file -k myoutput/mydotnetproject.pdb
    myoutput/mydotnetproject.pdb: Microsoft Roslyn C# debugging symbols version 1.0\012- data

We have a single output file that contains our program, its dependencies, and parts of the .NET runtime. We also get debugging symbols if we want to run our binary with a .NET debugger and see the associated source code. (There are ways to make the binary file smaller, and we can move most arguments of ``dotnet publish`` to the .csproj file, but this post is about Python, not .NET, so I’m not going to focus on them too much.)

How is .NET better than Python?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

I’m not going to bore you with the same demonstrations I’ve already shown when discussing `How is Node better than Python?`_, but:

* You can run built .NET projects as any user, from anywhere in the filesystem.
* All you need to run your code is the output directory (publishing is optional, but useful to have a cleaner output, to simplify deployment, and to possibly enable compilation to native code).
* If you do publish in single-executable mode, you can just distribute the single executable, and your users don’t even need to have .NET installed.
* You do not need to manage environments, you do not need special tools to run your code, you do not need to think about the current working directory when running code.

Other packaging topics
~~~~~~~~~~~~~~~~~~~~~~

Locking dependencies is disabled by default, but if you add ``<RestorePackagesWithLockFile>true</RestorePackagesWithLockFile>`` to the ``<PropertyGroup>`` in your ``.csproj`` file, you can enable it (and get a ``packages.lock.json`` file in output).

Regarding `command line tools`__, .NET has support for those as well. They can be installed globally or locally, and may be accessed via $PATH or via the ``dotnet`` command.

__ https://learn.microsoft.com/en-us/dotnet/core/tools/global-tools

As for publishing your package to NuGet.org or to another repository, you might want to look at the `full docs`__ for more details, but the short version is:

1. Add some metadata to the ``.csproj`` file (e.g. ``PackageId`` and ``Version``)
2. Run ``dotnet pack`` to get a ``.nupkg`` file
3. Run ``dotnet nuget push`` to upload the ``.nupkg`` file (passing the file name and an API key)

__ https://learn.microsoft.com/en-us/nuget/quickstart/create-and-publish-a-package-using-the-dotnet-cli

Once again, everything is done with a single ``dotnet`` tool. The .NET IDEs (in particular, Visual Studio and Rider) do offer friendly GUI versions of many features. Some of those GUIs might be doings things slightly differently behind the scenes, but this is transparent to the user (and the backend is still MSBuild or a close derivative of it). I can take a CLI-created project, add a dependency from Rider, and publish an executable from VS, and everything will work the same. And perhaps XML files aren’t as cool as TOML, but they’re still easy to work with in this case.

Other languages and ecosystems
------------------------------

While we have explored two tools for two languages in depth, there are also other languages that deserve at least a mention. In the **Java** world, the two most commonly used tools are Maven and Gradle. Both tools can be used to manage dependencies and build artifacts that can be executed or distributed further (things like JAR files). Other tools with support for building Java projects exist, but most people just pick one of the two. The community of **Scala**, which is another JVM-based language, prefers sbt (which can be used for plain Java as well), but there are also Maven or Gradle users in that community. Finally, two new-ish languages which are quite popular in the recent times, **Go** and **Rust**, have first-party tooling integrated with the rest of the toolchain. The ``go`` command-line tool can accomplish many build/dependency/packaging tasks. Rust’s ``cargo``, which ships with the standard distribution of Rust, handles dependencies, builds, running code and tests, as well as publishing your stuff to a registry.

Are those ecosystems’ tools perfect?
------------------------------------

.. |bitcoin-sucks| raw:: html

   <a href="https://arstechnica.com/information-technology/2018/11/hacker-backdoors-widely-used-open-source-software-to-steal-bitcoin/">stealing <s>imaginary Internet money</s> Bitcoin</a>

Not always, they have their deficiencies as well. In the Node ecosystem, packages may execute arbitrary code on install, which can be a security risk (there are some known examples, like a npm package `wiping hard drives in Russia and Belarus`__, or another one |bitcoin-sucks|). Binary packages are not distributed on the npm registry directly, they’re either built with ``node-gyp``, or have prebuilt packages downloaded via ``node-pre-gyp`` (which is a third-party tool).

In the .NET ecosystem, the tools also create an ``obj`` directory with temporary files. Those temporary files are tied to the environment they’re running in, and while the tooling will usually re-create them if something changes, it can sometimes fail and leave you with confusing errors (which can generally be solved by removing the ``bin`` and ``obj`` directories). If a package depends on native code (which is not already available on the target OS as part of a shared library), it must include binary builds in the NuGet package for all the platforms it supports, as there is `no standard way`__ to allow building something from source.

You can also find deficiencies in the tools for the other languages mentioned. Some people think Maven is terrible because it uses XML and Gradle is the way to go, and others think Gradle’s use of a Groovy-based DSL makes things much harder than they need to be and prefer Maven instead.

__ https://arstechnica.com/information-technology/2022/03/sabotage-code-added-to-popular-npm-package-wiped-files-in-russia-and-belarus/
__ https://github.com/NuGet/Home/issues/9631

PEP 582: the future of Python packaging?
========================================

Recall that when introducing PDM, I mentioned `PEP 582`_. This PEP defines a ``__pypackages__`` directory. This directory would be taken into consideration by Python when looking for imports. It would behave similarly to ``node_modules``. Since there will be no symlinks to the system Python, it will resolve the issues with moving the virtual environment. Because the packages live in the project, there is no problem with sharing a project directory between multiple system users. It might even be possible for different computers (but with the same Python version and OS) to share the ``__pypackages__`` directory (in some specific cases). The proposed ``__pypackages__`` directory structure has ``lib/python3.10/site-packages/`` subfolders, which still makes the “reinstall on Python upgrade” step mandatory, but it doesn’t apply to minor version upgrades, and if you’re dealing with a pure-Python dependency tree, ``mv __pypackages__/lib/python3.10 __pypackages__/lib/python3.11`` might just work. This structure does make sense for binary dependencies, or for dependencies necessary only on older Python versions, as it allows you to use multiple Python versions with the same project directory. The PEP does not say anything about sharing ``__pypackages__`` between projects, but you could probably solve that problem with symlinks (assuming the tooling doesn’t care if the directory is a symlink, and it shouldn’t care IMO).

While PEP 582 is a great vision, and it would simplify many package-related workflows, it hasn’t seen much care from the powers-that-be. The PEP was proposed in May 2018, and there’s even `a usable implementation`__ that’s less than 50 lines of code, there `hasn’t been much progress`__ on having it accepted and implemented in Python proper. However, PDM does not care, and it allows you to enable the future on your own machine.

.. _PEP 582: https://peps.python.org/pep-0582/
__ https://github.com/kushaldas/pep582/blob/main/pep582.py
__ https://discuss.python.org/t/pep-582-python-local-packages-directory/963/


Enabling the future on your own machine
---------------------------------------

Let’s enable the future on my own machine. That will require one simple command:

.. code:: text

    $ eval "$(pdm --pep582)"

After that, we can initialize our project and install requests into it. Let’s try:

.. code:: text

    $ mkdir mypdmproject
    $ cd mypdmproject
    $ pdm init
    Creating a pyproject.toml for PDM...
    Please enter the Python interpreter to use
    0. /usr/bin/python (3.11)
    1. /usr/bin/python3.11 (3.11)
    2. /usr/bin/python2.7 (2.7)
    Please select (0): 1
    Using Python interpreter: /usr/bin/python3.11 (3.11)
    Would you like to create a virtualenv with /usr/bin/python3.11? [y/n] (y): n
    You are using the PEP 582 mode, no virtualenv is created.
    For more info, please visit https://peps.python.org/pep-0582/
    Is the project a library that will be uploaded to PyPI [y/n] (n): n
    License(SPDX name) (MIT):
    Author name (Chris Warrick):
    Author email (…):
    Python requires('*' to allow any) (>=3.11):
    Changes are written to pyproject.toml.
    $ ls
    pyproject.toml
    $ pdm add requests
    Adding packages to default dependencies: requests
    🔒 Lock successful
    Changes are written to pdm.lock.
    Changes are written to pyproject.toml.
    Synchronizing working set with lock file: 5 to add, 0 to update, 0 to remove

      ✔ Install charset-normalizer 2.1.1 successful
      ✔ Install certifi 2022.12.7 successful
      ✔ Install idna 3.4 successful
      ✔ Install requests 2.28.1 successful
      ✔ Install urllib3 1.26.13 successful

    🎉 All complete!


So far, so good (I’m not a fan of emoji in terminals, but that’s my only real complaint here.) Our ``pyproject.toml`` looks like this:

.. code:: toml

    [tool.pdm]

    [project]
    name = ""
    version = ""
    description = ""
    authors = [
        {name = "Chris Warrick", email = "…"},
    ]
    dependencies = [
        "requests>=2.28.1",
    ]
    requires-python = ">=3.11"
    license = {text = "MIT"}

If we try to look into our file structure, we have this:

.. code:: text

    $ ls
    pdm.lock  __pypackages__/  pyproject.toml
    $ ls __pypackages__
    3.11/
    $ ls __pypackages__/3.11
    bin/  include/  lib/
    $ ls __pypackages__/3.11/lib
    certifi/             certifi-2022.12.7.dist-info/
    idna/                idna-3.4.dist-info/
    charset_normalizer/  charset_normalizer-2.1.1.dist-info/
    requests/            requests-2.28.1.dist-info/
    urllib3/             urllib3-1.26.13.dist-info/

We’ll write a simple Python program (let’s call it ``mypdmproject.py``) that makes a HTTP request using ``requests``. It will also print ``requests.__file__`` so we’re sure it isn’t using some random system copy: [4]_

.. code:: python

    import requests
    print(requests.__file__)
    r = requests.get("https://chriswarrick.com/")
    print(r.text[:15])

.. code:: text

    $ python mypdmproject.py
    /tmp/mypdmproject/__pypackages__/3.11/lib/requests/__init__.py
    <!DOCTYPE html>

Let’s finally try the tests we’ve done in the other languages. Requests is useless without urllib3, so let’s remove it [5]_ and see how well it works.

.. code:: text

    $ rm -rf __pypackages__/3.11/lib/urllib3*
    $ python mypdmproject.py
    Traceback (most recent call last):
      File "/tmp/mypdmproject/mypdmproject.py", line 1, in <module>
        import requests
      File "/tmp/mypdmproject/__pypackages__/3.11/lib/requests/__init__.py", line 43, in <module>
        import urllib3
    ModuleNotFoundError: No module named 'urllib3'


Finally, can we try with a different directory? How about a different user?

.. code:: text

    $ pdm install
    Synchronizing working set with lock file: 1 to add, 0 to update, 0 to remove

      ✔ Install urllib3 1.26.13 successful

    🎉 All complete!
    $ pwd
    /tmp/mypdmproject
    $ cd ~
    $ python /tmp/mypdmproject/mypdmproject.py
    /tmp/mypdmproject/__pypackages__/3.11/lib/requests/__init__.py
    <!DOCTYPE html>
    # su -s /bin/bash -c 'eval "$(/tmp/pdmvenv/bin/pdm --pep582 bash)"; python /tmp/mypdmproject/mypdmproject.py' - nobody
    su: warning: cannot change directory to /nonexistent: No such file or directory
    /tmp/mypdmproject/__pypackages__/3.11/lib/requests/__init__.py
    <!DOCTYPE html>

This is looking pretty good. An independent project manages to do what the big Authority failed to do over so many years.

Is this the perfect thing?
--------------------------

Well, almost. There are two things that I have complaints about. The first one is the ``pdm --pep582`` hack, but hopefully, the PyPA gets its act together and gets it into Python core soon. However, another important problem is the lack of separation from system site-packages. Avid readers of footnotes [6]_ might have noticed I had to use a Docker container in my PDM experiments, because requests is very commonly found in system ``site-packages`` (especially when using system Pythons, which have requests because of some random package, or because it was unbundled from pip). [7]_ This can break things in ways you don’t expect, because you might end up importing and depending on system-wide things, or mixing system-wide and local packages (if you don’t install an extra requirement, but those packages are present system-wide, then you might end up using an extra you haven’t asked for). This is an important problem—a good solution would be to disable system site-packages if a ``__pypackages__`` directory is in use.

The part where the Steering Council kills it
--------------------------------------------

In late March 2023, the Python Steering Council has announced `the rejection of PEP 582`__. The reasons cited in the SC decision cited the limitations of the PEP (the ``__pypackages__`` directory not always being enough, and the lack of specification on how it would behave in edge cases). Another argument is that it is possible to get ``__pypackages__`` via “one of the many existing customization mechanisms for ``sys.path``, like ``.pth`` files or a ``sitecustomize`` module” — things commonly considered hacks, not real solutions. While users certainly can do anything they want to the ``sys.path`` (often with tragic consequences), the point of having a common standard is to encourage tools to add support for it — if you use the aforementioned hacks, your IDE might end up not noticing the packages or considering them part of your code (trying to index them and search for things in them). Another reason cited for the rejection is the disagreement among the packaging community, which should not be surprising, especially in light of the next section.

The PEP 582/``__pypackages__`` mechanism may become official one day, and finally make Python packaging approachable. That would probably require someone to step up and write a new PEP that would make more people happy. Or Python might be stuck with all these incompatible tools, and invent 10 more in the next few years. (PDM is still there, and it still supports ``__pypackages__``, even though its implementation isn’t exactly the same as suggested by the now-rejected PEP.) Python’s current trajectory, as demonstrated by this decision, and by many people still being forced to struggle with the needlessly complicated virtual environments, sounds an awful lot like `the classic Onion headline`__: ‘No Way to Prevent This,’ Says Only Programming Community Where This Regularly Happens.

__ https://discuss.python.org/t/pep-582-python-local-packages-directory/963/430
__ https://en.wikipedia.org/wiki/%27No_Way_to_Prevent_This,%27_Says_Only_Nation_Where_This_Regularly_Happens

PyPA versus reality: packaging survey results and PyPA reaction
===============================================================

Some time ago, the PSF ran a survey on packaging. Over 8000 people responded. `The users have spoken:`__

* Most people think packaging is too complex.
* An overwhelming majority prefers using just a single tool.
* Most people also think the existence of multiple tools is not beneficial for the Python packaging ecosystem.
* Virtually everyone would prefer a clearly defined official workflow.
* Over 50% of responses think tools for other ecosystems are better at managing dependencies and installing packages.

The next step after this survey was for the packaging community to `discuss its results`__ and try to come up with a new packaging strategy. The first post from Shamika Mohanan (the Packaging Project Manager at PSF) that triggered the discussion also focused heavily on the users’ vision to unify packaging tools and to have One True Tool. This discussion was open to people involved with the packaging world; many participants of the discussion are involved with PyPA, and I don’t think I’ve seen a single comment from the people behind Poetry or PDM.

__ https://drive.google.com/file/d/1U5d5SiXLVkzDpS0i1dJIA4Hu5Qg704T9/view
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420

Most of the thread ended up being discussion of binary extensions, including discussions of how to help tool proliferation by making it possible for tools that aren’t setuptools to build binary extensions. There was also a lot of focus on the scientific community’s issues with `libraries with native code`__, heavily rooted in C/C++, and with attempts to replace Conda with new PyPA-approved tools. The “unified tool” for everyone else was mentioned in some posts, but they were certainly the minority.

__ https://pypackaging-native.github.io/

Some PyPA members talked about a UX analysis, and that they expect the unified tool to be re-exporting functionality from existing tools—which immediately raises the question: which tools should it export functionality from and why? Is ``pip install unified-packaging-tool`` going to bring in all fourteen? Is the fact that users are unhappy with what they have, and many of them would be happy with something lke npm/dotnet/cargo, not enough to determine the UX direction of the unified tool?

Some of them are also against breaking existing workflows. Is a unified packaging tool going to work for every single user? Definitely not. But are there that many distinct basic workflows? If we ignore things that border on bikeshedding, such as src vs no-src, or venv locations, are there that many workflows to consider? Someone making a library and someone making an application do have different needs (e.g. with regard to publishing the package or acceptable dependency versions). Someone working with C extensions (or extensions using something like Cython) may have different needs, but their needs would usually be a superset of the needs of someone working on a pure-Python project. The scientific community might have more specialized needs, related to complex non-Python parts, but I am positive many of their points could be solved by the unified tool as well, even if it’s not by the time this tool reaches v1.0. It is also possible that the scientific community might prefer to stay with Conda, or with some evolution of it that brings it closer in line with the Unified Packaging Tool but also solves the scientists’ needs better than a tool also solving the non-scientists’ needs can.

Then there’s a discussion about the existing tools and which one is the tool for the future. The maintainer of Hatch (Ofek Lev) says that `Hatch can provide the “unified UX”`__. But do the maintainers of Poetry or PDM agree? Poetry seems to be far more active than Hatch, going by GitHub issues, and it’s also worth noting that Hatch’s bus factor is 1 (with Ofek Lev responsible for 542 out of 576 commits to the master branch). `Russell Keith-Magee from BeeWare`__ has highlighted the fact that tooling aside, the PyPA does a bad job at communicating things. Russell mentioned that one of PyPA tutorials now uses Hatch, but there is no way to know if the PyPA considers Hatch to be the future, are people supposed to migrate onto Hatch, and is Flit, another recent PyPA tool, now useless? Russell also makes good points about focusing efforts: should people focus on helping Hatch support extension modules (which, according to the Hatch maintainer, is the last scenario requiring setuptools; other participants note that you can already build native code without setuptools), or should people focus on improving setuptools compatibility with PEP 517?

__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/4
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/46

There were also some people stating their opinions on unifying things in various ways—and many of them are against__ unifying__ things. There were some voices of reason, like that of Russell Keith-Magee, or of `Simon Notley`__, who correctly noticed the thread fails to resolve problems of developers, who are confused about packaging, and don’t understand the different choices available and how they interoperate. Simon does agree that native dependencies are important and happen often in Python projects (and so do I), but the users who responded to the survey had something else in mind — as exemplified by the discussion opening post, mentioning the user expecting the simplicity of Rust’s cargo, and by the survey results. 70% of the survey respondents also use ``npm``, so many Python users have already seen the simpler workflows. The survey respondents were also asked to rank a few focus areas based on importance. “Making Python packaging better serve common use cases and workflows” was ranked first out of the provided options [8]_ by 3248 participants. “Supporting a wider range of use cases (e.g. edge cases, etc.)” was ranked first by 379 people, and it was the least important in the minds of 2989 people.

__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/136
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/137
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/140

.. |macosx-name| raw:: html

   <s>Mac OS X</s> <s>OS X</s> macOS

One more point that highlights the detachment of packaging folk from reality was mentioned by Anderson Bravalheri. To Anderson, a new unified tool would be `disrespectful of the work`__ the maintainers of the existing tools put into maintaining them, and disrespectful of users who had to adapt to the packaging mess. This point is completely absurd. Was the replacement of MS-DOS/Windows 9x and Classic Mac OS with Windows NT and |macosx-name| disrespectful to their respective designers, and the users who had to adapt to manually configuring minutiae, figuring out how to get all your software and hardware to run with weird limitations that were necessary in the 1980s, and the system crashing every once in a while? Was the replacement of horses with cars disrespectful to horses, and the people who were removing horse manure from the streets? Was the replacement of the Ford Model T with faster, safer, more efficient, and easier to use cars disrespectful to Henry Ford? Technology comes and goes, and sometimes, getting an improvement means we need to get rid of the old stuff. This applies outside of technology, too—you could come up with many examples of change in the world, which might have put some people out of power, but has greatly improved the lives of millions of people (the fall of communism in Europe, for example). Also, going back to the technology world of today, this sentiment suggests Anderson is far too attached to the software they write—is this a healthy approach?

__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420/133

Nobody raised PEP 582 or the complexity of virtual environments. It might not be visible from the ivory towers of packaging tool maintainers, who have years of experience dealing with them, but it certainly does exist for regular people, for people who think the Python provided by their Linux distro is good enough, and especially for people for whom Python is their introduction to programming.

I would like to once again highlight: that’s not just the opinion of one random rambling Chris. The opinion that Python packaging needs to be simplified and unified is held by about half of the 8774 people who took the survey.

But here’s one more interesting thing: Discourse, the platform that the discussion was held on, shows the number of times a link was clicked. Granted, this count might not be always accurate, but if we assume it is, the link to the results summary was clicked only 14 times (as of 2023-01-14 21:20 UTC). The discussion has 28 participants and 2.2k views. If we believe the link click counter, **half of the discussion participants did not even bother reading what the people think**.

.. image:: /images/python-packaging/discourse-link-clicks.png
   :align: center

Summary
=======

Python packaging is a mess, and it always has been. There are tons of tools, mostly incompatible with each other, and no tool can solve *all* problems (especially no tool from the PyPA). PDM is really close to the ideal, since it can do away with the overhead of managing virtual environments—which is hopefully the future of Python packaging, or the 2010s of Node.js packaging (although it is not going to be the 2023 of Python packaging, considering the Steering Council rejection). Perhaps in a few years, Python developers (and more importantly, Python learners!) will be able to just ``pip install`` (or ``pdm install``?) what they need, without worrying about some “virtual environment” thing, that is separate but not quite from a system Python, and that is not a virtual machine. Python needs less tools, not more.

`Furthermore, I consider that the PyPA must be destroyed.`__ The strategy discussion highlights the fact that they are unable to make Python packaging work the way the users expect. The PyPA should focus on producing one good tool, and on getting PEP 582 into Python. A good way to achieve this would be to put its resources behind PDM. The issues with native code and binary wheels are important, but plain-Python workflows, or workflows with straightforward binary dependencies, are much more common, and need to be improved. This improvement needs to happen now.

Discuss in the comments below, on `Hacker News`__, or on `Reddit`__.

__ https://en.wikipedia.org/wiki/Carthago_delenda_est
__ https://news.ycombinator.com/item?id=34390585
__ https://www.reddit.com/r/Python/comments/10cnx5i/how_to_improve_python_packaging_or_why_fourteen/

Footnotes
=========

.. [1] Funnily enough, the aphorism itself fails at “one obvious way to do it”. It is with dashes set in two different ways (with spaces after but not before, and with spaces before but not after), and none of them is the correct one (most English style guides prefer no spaces, but some allow spaces on both sides).
.. [2] Apologies for the slight Linux focus of this post; all the points I make apply on Windows as well, but perhaps with some slightly different names and commands.
.. [3] There’s a new major version of .NET every year, with the even-numbered versions being LTS. Those are far less revolutionary than the Python 2 → 3 transition, and after you jump on the modern .NET train, upgrading a project to the new major version is fairly simple (possibly even just bumping the version number).
.. [4] And to be extra sure, I used a clean ``python:latest`` Docker container, since requests is so commonly found in system site packages.
.. [5] A little caveat here, I also had to remove the ``dist-info`` folder, so that PDM would know it needs to be reinstalled.
.. [6] Yes, that’s you!
.. [7] Also, why is there no good HTTP client library in Python’s standard library? Is the “standard library is where packages go to die” argument still relevant, if requests had four releases in 2022, and urllib3 had six, and most of the changes were minor?
.. [8] I have removed the “Other” option, and shifted all options ranked below it by one place, since we don’t know what the other thing was and how it related to the options presented (the free-form responses were removed from the public results spreadsheet to preserve the users’ anonymity). In the event a respondent left some of the options without a number, the blank options were not considered neither first nor last.

Revision History
================

This post got amended in April 2023 with an update about the SC rejection of PEP 582 (in a new subsection and in the Summary section).
