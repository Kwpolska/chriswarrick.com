.. title: Structuring and automating a Python project with the Python Project Template
.. slug: python-project-template-structure-and-automation
.. date: 2017-04-09 11:00:00+02:00
.. tags: guide, Python, projects, PyPT, Nikola
.. section: Programming
.. description: How to structure a Python project and automate releases.
.. type: text
.. guide: yes
.. guide_topic: Python
.. guide_platform: any platform
.. guide_effect: your Python project is now well-structured and automated

To create a project that other people can use and contribute to, you need to
follow a specific directory structure. Moreover, releasing a new version should
be as simple and painless as possible. For my projects, I use a template that
has the structure already in place, and comes with automation for almost every
part of a release.

.. TEASER_END

The `Python Project Template`__ is my approach to this. It comes with a good (IMO) structure and automation.

What does it include? A whole lot of things. Documentation, Sphinx
configuration, a simple test suite, a ``setup.py`` file, some AUR stuff, and
perhaps the most important part — the ``release`` script. It can automate a lot
of tasks that are part of a release.

You see, releasing a package is error-prone. There are a lot of things that can go wrong:

* Version numbers. They may appear in code comments, Sphinx configuration,
  README files and documentation, and setup.py. Some people claim to have
  “solutions” for this. Most of those solutions don’t work right — either they
  import a file from the project (which may break if ``__init__.py`` is too
  magical), read a file from that place (which might not get included
  properly), or use some setuptools extension to get the version from VCS or
  whatever (which needs to be installed before the package). Using ``sed`` to
  fix the version numbers is much simpler.
* Forgetting about changelogs.
* Not updating translations or other important files.

We’ve had quite a few botched releases in the Nikola__ project. I wrote a
checklist__ to prevent things like those. You may notice that the most
prominent step is to run a ``release`` script. This step replaced 21 others —
now the checklist only talks about writing announcements, sending e-mails,
updating the website, and doing some GitHub stuff that is not yet automated.

The template promotes a *release early, release often* workflow: since making a
new release requires almost no human intervention, you might as well do it
every time you make a bunch of changes. In my projects, *everything* gets
automated, and it might as well be possible in yours.

The complete feature list (as of v2.1.5)
----------------------------------------

* pre-configured Sphinx with:

  * ``CONTRIBUTING.rst`` guide (used by GitHub when sending a pull request or an issue)
  * ``LICENSE.rst``
  * an empty ``CHANGELOG.rst``
  * ``README.rst``
  * a bare-bones ``index.rst`` page

* The exact same files in ``/``, which are fragile and **MAY NOT** be modified
  as they are replaced with copies in ``/docs`` by the ``release`` script
* ``__init__.py``, ``__main__.py`` and ``template.py`` files in the Python package directory
* A ``setup.py`` file that could be good enough for people, and that supports
  ``entry_points`` (see https://go.chriswarrick.com/entry_points).
* ``tests/`` containing some *Is My Python Sane?*-style tests (using ``pytest``)
* An automated global update script (``.pypt/PYPT-UPDATE``)
* Entry points configuration ready to be uncommented (and a matching
  ``__main__.py`` file)
* Add-ons for Qt users (in ``pypt-extras/Qt``)
* A sample hook for AUR updates (in ``pypt-extras/AUR``)
* PKGBUILDs for the Arch Linux User Repository (AUR)
* A state-of-the-art ``release`` script, the operations of which are:

  * querying the user for version number, commit message and changes
  * updating ``/docs/CHANGELOG.rst``
  * bumping the version number in all the files, changing dates where necessary
  * copying over ``/docs/README.rst``,  ``/docs/CHANGELOG.rst`` and ``/docs/CONTRIBUTING.rst`` to ``/``
  * locale generation (via the ``.pypt/localegen`` script)
  * running ``import $PROJECTLC`` and the test suite
  * uploading a source distribution and a wheel to PyPI
  * Making a Git commit and tagging the release
  * creating a GitHub Releases entry
  * updating the AUR packages (by using hooks)

.. raw:: html

    <div style="text-align: center;">
    <a href="https://github.com/Kwpolska/python-project-template" class="btn btn-lg btn-info"><i class="fa fa-github"></i> Check it out on GitHub</a>
    </div>


__ https://github.com/Kwpolska/python-project-template
__ https://getnikola.com/
__ http://getnikola.github.io/releng/checklist.html
