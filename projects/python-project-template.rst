.. title: Python Project Template
.. slug: python-project-template
.. date: 1970-01-01T00:00:00+00:00
.. description: INSERT TAGLINE HERE.™
.. previewimage: /projects/_banners/pypt.png
.. status: 5
.. github: https://github.com/Kwpolska/python-project-template
.. bugtracker: https://github.com/Kwpolska/python-project-template/issues
.. role: Maintainer
.. license: 3-clause BSD
.. language: Python
.. sort: 80
.. featured: True

A template for Python projects.  It makes it easy to create new Python
projects, with just a few replacements required.  Many things are already
there, already prepared for you.

(Just don’t get scared when you see how massive the README is.  It’d be much
shorter in code and not in prose.  Besides, a significant portion of it is not
text)

Contents
========

The template contains the following files to get you started:

* pre-configured Sphinx with:

  * ``CONTRIBUTING.rst`` guide (used by GitHub when sending a pull request or an issue)
  * ``LICENSE.rst``
  * an empty ``CHANGELOG.rst``
  * this (worthless for most people) ``README.rst`` and a bare-bones ``index.rst`` page

* The exact same files in ``/``, which are fragile and **MAY NOT** be modified
  as they are replaced with copies in ``/docs`` by the ``release``
  script
* ``__init__.py`` and ``template.py`` files in the Python package directory
* A good-enough ``setup.py`` file
* ``tests.py`` containing some *Is My Python Sane?*-style tests
* A sample ``/usr/bin/`` script
* Addons for Qt users
* PKGBUILDs for the Arch Linux User Repository (AUR)
* A state-of-the-art ``release`` script, the operations of which are:

  * querying the current branch for version number
  * updating ``/docs/CHANGELOG.rst``
  * bumping the version number in all the files, changing dates where necessary
  * copying over ``/docs/README.rst``,  ``/docs/CHANGELOG.rst`` and ``/docs/CONTRIBUTING.rst`` to ``/``
  * locale generation (via the ``.pypt/localegen`` script)
  * running ``import $project`` and the testsuite
  * creating and uploading AUR packages
  * committing into git, finishing the ``git flow`` release
