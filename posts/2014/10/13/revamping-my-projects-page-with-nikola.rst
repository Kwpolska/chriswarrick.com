.. title: Revamping My Projects Page with Nikola
.. slug: revamping-my-projects-page-with-nikola
.. date: 2014-10-13 14:15:00+02:00
.. tags: Python, devel, Nikola
.. category: Python
.. description: A tale on how I revamped my projects page.
.. type: text
.. nocomments: true

A week ago, I was inspired to produce a new `projects page </projects/>`_ for
myself.  The previous one was a trainwreck with a lot of hacks.  Also hosted on
GitHub Pages for some reason.

.. TEASER_END

So, considering I’m so invested in `Nikola <https://getnikola.com/>`_ already,
I produced the `projectpages plugin <http://plugins.getnikola.com/#projectpages>`_
and also made it publicly available.  The plugin produces two files,
``projects/index.html`` and ``projects/projects.json``, and also enforces a
specific framework for the stories used for the individual projects, because
all the metadata are taken from special meta fields.

In Nikola, post metadata is completely arbitrary (in fact, that’s my fault; I
`contributed the feature <https://github.com/getnikola/nikola/pull/304>`_ back in February 2013).
You can put anything you want, and Nikola will let any plugin and template use the information in any way it likes.

And that is basically the gist of the projectpages plugin.  Using some specific
`meta fields <https://github.com/getnikola/plugins/tree/master/v7/projectpages#meta-fields>`_,
the plugin produces all the files.  It also provides ready-made templates for
the story pages (though the default templates are designed to fit my site
only).

This plugin is basically a special index page generator.  It takes all the
stories in the designated projects directory, looks at the metadata, and
lists them in a nice format (slider of featured projects + a list of everything
else that is not hidden).  Everything automated and done for you, as is always
with Nikola — which values simplicity and ease of use.

.. raw:: html

    <p><strong>The result:</strong> a pretty <strong><a href="/projects/">projects page</a></strong>.  And some good OSS work done.</p>

PS. I just underwent a move to `DigitalOcean <https://www.digitalocean.com/>`_
and I love them.  Moreover, this blog is proudly *HTTPS only* as of yesterday.
