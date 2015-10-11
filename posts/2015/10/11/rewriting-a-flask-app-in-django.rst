.. title: Rewriting a Flask app in Django
.. slug: rewriting-a-flask-app-in-django
.. date: 2015-10-11 17:24:43+02:00
.. tags: Python, Internet, Django, Flask, Nikola
.. section: Python
.. link: 
.. description: I spent Saturday on rewriting a Flask app in Django.
.. type: text

I spent Saturday on rewriting a Flask app in Django.  The app in question was
`Nikola Users <https://users.getnikola.com/>`_, which is a very simple CRUD
app.  And yet, the Flask code was a mess, full of bugs and vulnerabilities.
Eight hours later, I had a fully functional Django app that did more and fixed
all problems.

.. TEASER_END

Original Flask app
==================

The original Flask app had a ton of problems.  In order to make it anywhere
near useful, I would need to spend hours.  Here’s just a few of
them:

* 357 lines of spaghetti code (295 SLOC), all in one file
* No form data validation, no CSRF [#]_ protection (it did have XSS protection
  though)
* Login using Mozilla Persona, which requries JavaScript, is a bit kludgey, and
  feels desolate (and also had me store the admin e-mail list in code)
* Geopolitics issues: using country flags for languages
* A lot of things were implemented by hand
* SQLAlchemy is very verbose
* no DB migrations (makes enhancements harder)
* Languages implemented as a PostgreSQL integer array
* Adding a language required running a command-line script and **restarting the
  app** (languages were cached in Python dicts with no way to reload them from
  the database; that would require talking through uWSGI anyway because there
  were multiple processes involved)
* The templates were slightly hacky (the page title was set in each individual
  template and not in the view code); menus hacked together in HTML with no
  highlighting
* Python 2.7

The rewrite
===========

I started the process by opening `Django documentation
<https://docs.djangoproject.com/en/>`_, with its wonderful
`tutorial <https://docs.djangoproject.com/en/1.8/intro/tutorial01/>`_.  Now, I have written a couple basic Django apps before, but
the majority of them didn’t do much.  In other words, I didn’t have a lot of experience.  Especially with taking user input and relationships.  It took me about 8 hours to get feature parity, and more.

Getting all the features was really simple.  For example, to get a many-to-many
relationship for languages, I had to write just one line.

.. code:: python

    languages = models.ManyToManyField(Language)

That’s it.  I didn’t have to run through complicated SQLAlchemy documentation,
which provides a `13-line solution <http://docs.sqlalchemy.org/en/rel_1_0/orm/basic_relationships.html#many-to-many>`_ to the same problem.

Django also simplified New Relic integration, as the browser JS can be implemented
using Django template tags.

Django is not without its problems, though.  I got a very cryptic traceback
when I did this:

.. code:: python

    publish_email = forms.BooleanField("Publish e-mail", required=False)
    TypeError: "BooleanField() got multiple values for argument 'required'"

The real problem with this code?  I forgot the ``label=`` keyword.  The
problem is, the model API accepts this syntax — ``verbose_name`` is the first
argument.  (I am not actually using the labels though, I write my own form
HTML)

Still, the Django version is much cleaner.  And the best part of all?  There
are no magic global objects (``g``, ``session``, ``request``) and
decorator-based views (which are a bit of syntax abuse IMO).

In the end, I have:

* 382 lines of code (297 SLOC) over 6 files — much cleaner, and with less long lines
* form data validation (via Django), CSRF and XSS protection
* Login using Django built-in authentication, without JavaScript
* Language codes (granted, I could’ve done that really easily back in Flask)
* Tried-and-true implementations of common patterns
* Django models are much more readable and friendly
* Django-provided DB migrations (generated automatically!)
* Languages implemented using Django many-to-many relationships
* Adding a language is possible from the Django built-in admin panel and is
  reflected immediately (no caching)
* Titles and menus in code
* Python 3
* New features: featured sites; show only a specified language — were really easy to add

.. [#] I had some ``CSRF_ENABLED`` variable, but it did not seem to be actually
       used by anything.
