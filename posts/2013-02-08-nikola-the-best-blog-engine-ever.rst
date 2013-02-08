.. title: Nikola — The Best Blog Engine Ever!
.. slug: 2013-02-08-nikola-the-best-blog-engine-ever
.. date: 2013/02/08 14:01:51
.. tags: Nikola, blog, Python
.. link:
.. description: Nikola is the best blog engine ever!

I recently found out about Nikola_ (through `Planet Python`_).  It is awesome,
even better than Hyde.  Why?  Right after the break.


.. TEASER_END

Why?
====

1. A lively community and an awesome lead developer, `Roberto Alsina`_.
2. Actively developed (last commit to Hyde was **11 months ago**).
3. Easily extensible.
4. Ships with the Bootstrap style, to which I planned to migrate (and I did)
5. reStructuredText, Markdown, Textile, … input.

How?
====

.. raw:: html

    <blockqoute>

After you have Nikola installed:

Create a site:
    ``nikola init mysite``
Create a post:
    ``nikola new_post``
Edit the post:
    The filename should be in the output of the previous command.
Build the site:
    ``nikola build``
Start the test server:
    ``nikola serve``
See the site:
    http://127.0.0.1:8000

That should get you going. If you want to know more, this manual will always be
here for you.

.. raw:: html

    <small><cite><a href="http://nikola.ralsina.com.ar/handbook.html">The Nikola
    Handbook</a> by Roberto Alsina</cite></blockquote>


That’s how easy it is.   Nikola has many more useful features.

And sure, I had to manually fix up all the posts because I decided to switch to
RST, but that’s not a problem.

Plans for the future
====================

I did transition the blog, but not everything is done yet.  I need to create a
**Contact form** and a **Project page**.  Both should be done by Sunday.

.. _Nikola: http://nikola.ralsina.com.ar/
.. _Planet Python: http://planet.python.org/
.. _Roberto Alsina: http://ralsina.com.ar/
