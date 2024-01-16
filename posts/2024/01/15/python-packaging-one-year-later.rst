.. title: Python Packaging, One Year Later: A Look Back at 2023 in Python Packaging
.. slug: python-packaging-one-year-later
.. date: 2024-01-15 19:50:00+01:00
.. tags: Python, pip, virtual environments, PyPA, packaging, PDM
.. category: Python
.. description: Are there still fourteen tools, or are there even more? Has Python packaging improved in a year?
.. type: text

A year ago, I wrote about the sad state of Python packaging. The large number of tools in the space, the emphasis on writing vague standards instead of rallying around the One True Tool, and the complicated ``venv``-based ecosystem instead of a solution similar to ``node_modules``. What has changed in the past year? Has anything improved, is everything the same, or are things worse than they were before?

.. TEASER_END

The tools
=========

`The original post`__ listed a bunch of packaging tools, calling *fourteen tools at least twelve too many*. My idea with that was that most people would be happy with one tool that does everything, but the scientific-Python folks might have special requirements that would work best as a second tool.

__ https://chriswarrick.com/blog/2023/01/15/how-to-improve-python-packaging/

Out of the tools named in last year’s post, all of them still seem to be maintained. Except for Flit (zero new commits in the past 30 days) and virtualenv (only automated and semi-automated version bumps), the tools have recent commits, pull requests, and issues.

All of those tools are still in use. `Françoise Conil analysed all PyPI packages`__ and checked their PEP 517 build backends: setuptools is the most popular (at 50k packages), Poetry is second at 41k, Hatchling is third at 8.1k. Other tools to cross 500 users include Flit (4.4k), PDM (1.3k), Maturin (1.3k, build backend for Rust-based packages).

__ https://framapiaf.org/@fcodvpt/111540079686191842

There are some new tools, of course. Those that crossed my radar are Posy__ and Rye__. Posy is a project of Nathaniel J. Smith (of trio fame), Rye is a project of Armin Ronacher (of Flask fame). The vision for both of them is to manage Python interpreters and projects, but not have a custom build backend (instead using something like hatchling). Posy is built on top of PyBI (a format for distributing binaries of Python interpreters, proposed by Smith in draft `PEP 711`__), Rye uses Gregory Szorc’s pre-built Pythons. Rye seems to be fairly complete and usable, Posy is right now a PoC of the PyBI format, and only offers a REPL with pre-installed packages.

__ https://github.com/njsmith/posy
__ https://github.com/mitsuhiko/rye
__ https://peps.python.org/pep-0711/

Both Posy and Rye are written in Rust. On the one hand, it makes sense that the part that manages Python interpreters is not written in Python, because that would require a separate Python, not managed by Posy/Rye, to run those tools. But Rye also has its own pyproject.toml parser in Rust, and many of its commands are implemented mostly or largely using Rust (sometimes also calling one-off Python scripts; although the main tasks of creating venvs, installing packages, and working with lockfiles are handed off to ``venv``, ``pip``, and ``pip-tools`` respectively).

Speaking of Rust and Python, there’s been another project in that vein that has grown a lot `(and gathered a lot of funding)`__ in the past year. That project is `Ruff`__, which is a linter and code formatter. Ruff formats Python code, and is written in Rust. This means it’s 10–100× faster than existing tools written in Python (according to Ruff’s own benchmarks). Fast is good, I guess, but what does this say about Python? Is the fact that package tools (which aren’t rocket science, maybe except for fast dependency solvers, and which often need access to Python internals to do their job) and code formatters (which require a deep understanding of Python syntax, and parsing Python sources to ASTs, something easy by the ``ast`` Python module) are written in another language? Does this trend make Python a toy language (as it is also often considered *a glue language* for NumPy and friends)? Also, why should contributing to a tool important to many Python developers require learning Rust?

__ https://astral.sh/blog/announcing-astral-the-company-behind-ruff
__ https://github.com/astral-sh/ruff

The standards
=============

Last time we looked at packaging standards, we focused on `PEP 582`__. It proposed the introduction of ``__pypackages__``, which would be a place for third-party packages to be installed to locally, on a per-project basis, without involving virtual environments, similarly to what ``node_modules`` is for node. The PEP was ultimately rejected__ in March 2023. The PEP wasn’t perfect, and some of its choices were questionable or insufficient (such as not recursively searching for ``__pypackages__`` in parent directories, or focusing on simple use-cases only). No new standards for something in that vein (with a better design) were proposed to this day.

__ https://peps.python.org/pep-0582/
__ https://discuss.python.org/t/pep-582-python-local-packages-directory/963/430

Another contentious topic is lock files. Lock files for packaging systems are useful for reproducible dependency installations. The lock file records all installed packages (i.e. includes transitive dependencies) and their versions. Lock files often include checksums (like sha512) of the installed packages, and they often support telling apart packages installed via different groups of dependencies (runtime, buildtime, optional, development, etc.).

The classic way of achieving this goal are ``requirements.txt`` files. They are specific to pip, and they only contain a list of packages, versions, and possibly checksums. Those files can be generated by ``pip freeze``, or the third-party ``pip-compile`` from ``pip-tools``. ``pip freeze`` is very basic, ``pip-compile`` can’t handle different groups of dependencies other than making multiple ``requirements.in`` files, compiling them, and hoping there are no conflicts.

Pipenv, Poetry, and PDM have their own lockfile implementations, incompatible with one another. Rye piggybacks on top of ``pip-tools``. Hatch doesn’t have anything in core; they’re waiting for a standard implementation (there are some plugins though). `PEP 665`__ was rejected__ in January 2022. Its author, Brett Cannon, `is working on a PoC`__ of something that *might* become a standard (named mousebender__).

__ https://peps.python.org/pep-0665/
__ https://discuss.python.org/t/pep-665-take-2-a-file-format-to-list-python-dependencies-for-reproducibility-of-an-application/11736/140
__ https://snarky.ca/state-of-standardized-lock-files-for-python-august-2023/
__ https://github.com/brettcannon/mousebender

This is the danger of the working model adopted by the Python packaging world. Even for something as simple as lock files, there are at least four incompatible standards. An attempt at a specification was rejected due to “lukewarm reception”, even though there exist at least four implementations which are achieving roughly the same goals, and other ecosystems also went through this before.

Another thing important to Python are extension modules. Extension modules are written in C, and they are usually used to interact with libraries written in other languages (and also sometimes for performance). Poetry, PDM, and Hatchling don’t really support building extension modules. Setuptools does; `SciPy and NumPy migrated from their custom numpy.distutils to Meson`__. The team behind the PyO3 Rust bindings for Python develops Maturin__, which allows for building Rust-based extension modules — but it’s not useful if you’re working with C.

__ https://numpy.org/doc/stable/reference/distutils_status_migration.html
__ https://github.com/PyO3/maturin

There weren’t many packaging-related standards that were accepted in 2023. A standard worth mentioning is `PEP 668`__, which allows distributors to prevent `pip` from working (to avoid breaking distro-owned site packages) by adding an ``EXTERNALLY-MANAGED`` file. It was accepted in June 2022, but pip only implemented support for it in January 2023, and many distros already have enabled this feature in 2023. Preventing broken systems is a good thing.

__ https://peps.python.org/pep-0668/

But some standards did make it through. Minor and small ones aside, the most prominent 2023 standard would be `PEP 723`__: inline script metadata. It allows to add a comment block at the top of the file, that specifies the dependencies and the minimum Python version in a way that can be consumed by tools. Is it super useful? I don’t think so; setting up a project with pyproject.toml would easily allow things to grow. If you’re sending something via a GitHub gist, just make a repo. If you’re sending something by e-mail, just tar the folder. That approach promotes messy programming without source control.

__ https://peps.python.org/pep-0723/

Learning curves and the deception of “simple”
---------------------------------------------

Microsoft Word is simple, and a great beginner’s writing tool. You can make text bold with a single click. You can also make it blue in two clicks. But it’s easy to make an inconsistent mess. To make section headers, many users may just make the text bold and a bit bigger, without any consistency or semantics [1]_. Making a consistent document with semantic formatting is hard in Word. Adding `section numbering`__ requires you to select a heading and turn it into a list. There’s also supposedly some magic involved, that magic doesn’t work for me, and I have to tell Word to update the heading style. Even if you try doing things nicely, Word will randomly break, mess up the styles, mix up styles and inline ad-hoc formatting, and your document may look differently on different computers.

__ https://www.techrepublic.com/article/how-to-create-multilevel-numbered-headings-in-word-2016/

LaTeX is very confusing to a beginner, and has a massive learning curve. And you can certainly write ``\textbf{hello}`` everywhere. But with some learning, you’ll be producing beautiful documents. You’ll define a ``\code{}`` command that makes code monospace and adds a border today, but it might change the background and typeset in Comic Sans tomorrow if you so desire. You’ll use packages that can render code from external files with syntax highlighting. Heading numbering is on by default, but it can easily be disabled for a section. LaTeX can also automatically put new sections on new pages, for example. LaTeX was built for scientific publishing, so it has stellar support for maths and bibliographies, among other things.

Let’s now talk about programming. Python is simple, and a great beginner’s programming language. You can write *hello world* in a single line of code. The syntax is simpler, there are no confusing leftovers from C (like the index-based ``for`` loop) or machine-level code (like ``break`` in ``switch``), no pointers in sight. You also don’t need to write classes at all; you don’t need to write a class only to put a ``public static void main(String[] args)`` method there [2]_. You don’t need an IDE, you can just write code using any editor (even notepad.exe will do for the first day or so), you can save it as a .py  file and run it using ``python whatever.py``.

Your code got more complicated? No worry, you can split it into multiple ``.py`` files, use ``import name_of_other_file_without_py`` and it will just work. Do you need more structure, grouping into folders perhaps? Well, forget about ``python whatever.py``, you must use ``python -m whatever``, and you must ``cd`` to where your code is, or mess with ``PYTHONPATH``, or install your thing with ``pip``. This simple yet common action (grouping things into folders) has massively increased complexity.

The standard library is not enough [3]_ and you need a third-party dependency? You find some tutorial that tells you to ``pip install``, but ``pip`` will now tell you to use ``apt``. And ``apt`` may work, but it may give you an ancient version that does not match the tutorial you’re reading. Or it may not have the package. Or the Internet will tell you not to use Python packages from ``apt``. So now you need to `learn about venvs`__ (which add more complexity, more things to remember; most tutorials teach activation, venvs are easy to mess up via basic operations like renaming a folder, and you may end up with a venv in git or your code in a venv). Or you need to pick one of the many one-stop-shop tools to manage things.

__ https://chriswarrick.com/blog/2018/09/04/python-virtual-environments/

In other ecosystems, an IDE is often a necessity, even for beginners. The IDE will force you into a project system (maybe not the best or most common one by default, but it will still be a coherent project system). Java will force you to make more than one file with the “1 public class = 1 file” rule, and it will be easy to do so, you won’t even need an ``import``.

Do you want folders? In Java or C#, you just create a folder in the IDE, and create a class there. The new file may have a different ``package``/``namespace``, but the IDE will help you to add the correct ``import``/``using`` to the codebase, and there is no risk of you using too many directories (including something like ``src``) or using too few (not making a top-level package for all your code) that will require correcting all imports. The disruption from adding a folder in Java or C# is minimal.

The project system will also handle third-party packages without you needing to think about where they’re downloaded or what a virtual environment is and how to activate it from different contexts. A few clicks and you’re done. And if you don’t like IDEs? Living in the CLI is certainly possible in many ecosystems, they have reasonable CLI tools for common management tasks, as well as building and running your project.

PEP 723 solves a very niche problem: dependency management for single-file programs. Improving life for one-off things and messy code was apparently more important to the packaging community than any other improvements for big projects.

By the way, you could adapt this lesson to static and dynamic typing. Dynamic typing is easier to get started with and requires less typing, but compile-type checking can prevent many bugs — bugs that require higher test coverage to catch with dynamic typing. That’s why the JS world has TypeScript, that’s why mypy/pyright/typing has gained a lot of mindshare in the Python world.

The future…
===========

Looking at the `Python Packaging Discourse`__, there were some discussions about ways to improve things.

__ https://discuss.python.org/c/packaging/14

For example, this `discussion about porting off setup.py`__ was started by Gregory Szorc, who had `a long list of complaints`__, pointing out the issues with the communication from the packaging world, and documentation mess (his post is worth a read, or at least a skim, because it’s long and full of packaging failures). There’s one page which recommends setuptools, another which has four options with Hatchling as a default, and another still promoting Pipenv. We’ve seen this a year ago, nothing changed in that regard. Some people tried finding solutions, some people shared their opinions… and then the Discourse moderator decided to protect his PyPA friends from having to read user feedback and locked the thread.

__ https://discuss.python.org/t/user-experience-with-porting-off-setup-py/37502
__ https://gregoryszorc.com/blog/2023/10/30/my-user-experience-porting-off-setup.py/

Many other threads about visions were had, like the one about `10-year views`__ or about `singular packaging tools`__. The strategy discussions, based on the user survey, had `a second part`__ (the `first one`__ concluded in January 2023), but it saw less posts than the first one, and discussions did not continue (and there were `discussions about how to hold the discussions`__). There are plans to `create a packaging council`__ — design-by-committee at its finest.

__ https://discuss.python.org/t/the-10-year-view-on-python-packaging-whats-yours/31834
__ https://discuss.python.org/t/wanting-a-singular-packaging-tool-vision/21141
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-2/23442
__ https://discuss.python.org/t/python-packaging-strategy-discussion-part-1/22420
__ https://discuss.python.org/t/structure-of-the-packaging-strategy-discussions/23478
__ https://discuss.python.org/t/draft-update-to-python-packaging-governance/31608

But all those discussions, even when not locked by an overzealous moderator, haven’t had any meaningful effect. The packaging ecosystem is still severely fragmented and confusing. `The PyPA docs and tutorials`__ still contradict each other. The PyPA-affiliated tools still have less features than the unaffiliated competition (even the upstart Rye has some form of lockfiles, unlike Hatch or Flit), and going by the PEP 517 build backend usage statistics, they are more popular than the modern PyPA tools. The authors of similar yet competing tools have not joined forces to produce the One True Packaging Tool.

__ https://packaging.python.org/en/latest/tutorials/

…is looking pretty bleak
------------------------

On the other hand, if you look at the 2023 contribution graphs for most packaging tools, you might be worried about the state of the packaging ecosystem.

* Pip__ has had a healthy mix of contributors and a lot of commits going into it.
* Pipenv__ and setuptools__ have two lead committers, but still a healthy amount of commits.
* Hatch__, however, is a **one-man-show**: Ofek Lev (the project founder) made 184 commits, the second place belongs to Dependabot with 6 commits, and the third-place contributor (who is a human) has five commits.  The bus factor of Hatch and Hatchling is 1.

The non-PyPA tools aren’t doing much better:

* Poetry__ has two top contributors, but at least there are four human contributors with a double-digit number of commits.
* PDM__ is a one-man-show, like Hatch.
* Rye__ has one main contributor, and three with a double-digit number of commits; note it’s pretty new (started in late April 2023) and it’s not as popular as the others.

__ https://github.com/pypa/pip/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/pypa/pipenv/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/pypa/setuptools/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/pypa/hatch/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/python-poetry/poetry/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/pdm-project/pdm/graphs/contributors?from=2023-01-01&to=2023-12-31&type=c
__ https://github.com/mitsuhiko/rye/graphs/contributors?from=2023-04-23&to=2023-12-31&type=c

Conclusion
==========

I understand the PyPA is a loose association of volunteers. It is sometimes said the name *Python Packaging Authority* was `originally a joke`__. However, they are also the group that maintains all the packaging standards, so they *are* the authority when it comes to packaging. For example, `PEP 668`__ starts with a warning block saying it’s a historical document, and `the up-to-date version of the specification is on PyPA’s site`__ (as well as a bunch of other `packaging specs`__).

__ https://discuss.python.org/t/remove-the-authority-from-packaging/1993
__ https://peps.python.org/pep-0668/
__ https://packaging.python.org/en/latest/specifications/externally-managed-environments/
__ https://packaging.python.org/en/latest/specifications/

**The PyPA should shut down or merge some duplicate projects, and work with the community (including maintainers of non-PyPA projects) to build One True Packaging Tool.** To make things easier. To avoid writing code that does largely the same thing 5 times. To make sure thousands of projects don’t depend on tools with a bus factor of 1 or 2. To turn packaging from a problem and an insurmountable obstacle to something that *just works™*, something that an average developer doesn’t need to think about.

It’s not rocket science. Tons of languages, big and small, have a coherent packaging ecosystem (just read `last year’s post`__ for some examples of how simple it can be). Instead of focusing on specifications and governance, focus on producing one comprehensive, usable, user-friendly tool.

__ https://chriswarrick.com/blog/2023/01/15/how-to-improve-python-packaging/

Discuss below or `on Hacker News`__.

__ https://news.ycombinator.com/item?id=39004600

Footnotes
=========

.. [1] Modern Word at least makes this easier, because the heading styles get top billing on the ribbon; they were hidden behind a completely non-obvious combo box that said *Normal* in Word 2003 and older.
.. [2] C# 10 removed the requirement to make a class with a ``Main`` method, it can pick up one file with top-level statements and make it the entrypoint.
.. [3] The Python standard library gets a lot of praise. It *is* large compared to C, but nothing special compared to Java or C#. It is also full of low-quality libraries, like ``http.server`` or ``urllib.request``, yet some people insist on only using the standard library. The standard library is also less stable and dependable (with constant deprecations and removals, and with new features requiring upgrading all of Python). All the “serious” use-cases, like web development or ML/AI/data science are impossible with just the standard library.

