.. title: Python Project Template
.. slug: python-project-template
.. date: 1970-01-01T00:00:00+00:00
.. description: INSERT TAGLINE HERE.™
.. previewimage: /projects/_banners/pypt.png
.. devstatus: 5
.. github: https://github.com/Kwpolska/python-project-template
.. bugtracker: https://github.com/Kwpolska/python-project-template/issues
.. role: Maintainer
.. license: 3-clause BSD
.. language: Python
.. sort: 86
.. status: featured

A template for Python projects.  It makes it easy to create new Python
projects, with just a few replacements required.  Many things are already
there, already prepared for you.

Contents
========

The template contains the following files to get you started:

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
